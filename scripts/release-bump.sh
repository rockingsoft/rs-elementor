#!/usr/bin/env bash
set -euo pipefail

# Bump the MINOR version in the main plugin file, commit, tag, and optionally push.
# - Expects to be run from the repository root.
# - Targets: class-rs-elementor-widgets.php header `Version: X.Y.Z` and `const VERSION = 'X.Y.Z';`
#
# Usage:
#   scripts/release-bump.sh                 # bump, commit, tag, push
#   scripts/release-bump.sh --no-push       # bump, commit, tag, do NOT push
#   scripts/release-bump.sh --dry-run       # show what would change, do NOT modify
#   scripts/release-bump.sh --dry-run --no-push
#   scripts/release-bump.sh --no-bump       # do not change files; tag/push using current version
#
# Notes (macOS compatible): uses `sed -i ''` for in-place edits.

PLUGIN_FILE="class-rs-elementor-widgets.php"
PUSH=true
DRY_RUN=false
NO_BUMP=false

# Parse arguments
for arg in "$@"; do
  case "$arg" in
    --no-push)
      PUSH=false
      ;;
    --dry-run)
      DRY_RUN=true
      ;;
    --no-bump)
      NO_BUMP=true
      ;;
    *)
      echo "Error: unknown argument: $arg" >&2
      echo "Usage: scripts/release-bump.sh [--dry-run] [--no-push] [--no-bump]" >&2
      exit 2
      ;;
  esac
done

# Ensure git repo
if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  echo "Error: not inside a git repository" >&2
  exit 1
fi

# Ensure main plugin file exists
if [[ ! -f "$PLUGIN_FILE" ]]; then
  echo "Error: $PLUGIN_FILE not found in current directory $(pwd)" >&2
  exit 1
fi

# Ensure remote 'origin' exists when pushing (skip in dry-run)
if $PUSH && ! $DRY_RUN; then
  if ! git remote get-url origin >/dev/null 2>&1; then
    echo "Error: git remote 'origin' not configured; use --no-push to skip pushing" >&2
    exit 1
  fi
fi

# Extract current version from plugin header (line with "* Version:")
CURRENT_VERSION=$(grep -E "^[[:space:]]*\* Version:[[:space:]]*[0-9]+\.[0-9]+\.[0-9]+" "$PLUGIN_FILE" | sed -E 's/.*Version:[[:space:]]*([0-9]+\.[0-9]+\.[0-9]+).*/\1/' | head -n1)
if [[ -z "${CURRENT_VERSION}" ]]; then
  echo "Error: could not detect current version from $PLUGIN_FILE header" >&2
  exit 1
fi

IFS='.' read -r MAJOR MINOR PATCH <<<"${CURRENT_VERSION}"
if [[ -z "${MAJOR}" || -z "${MINOR}" || -z "${PATCH}" ]]; then
  echo "Error: invalid current version format: ${CURRENT_VERSION}" >&2
  exit 1
fi

NEW_MINOR=$((MINOR + 1))
NEW_VERSION="${MAJOR}.${NEW_MINOR}.0"

# If not bumping, keep current version as the target for tagging
if $NO_BUMP; then
  NEW_VERSION="${CURRENT_VERSION}"
fi

if $DRY_RUN; then
  echo "[dry-run] Current version: ${CURRENT_VERSION}"
  echo "[dry-run] New version:     ${NEW_VERSION}"
  if $NO_BUMP; then
    echo "[dry-run] --no-bump specified: would NOT modify ${PLUGIN_FILE}."
    echo "[dry-run] Would create tag v${NEW_VERSION} and push (unless --no-push)."
  else
    echo "[dry-run] Would update the following in ${PLUGIN_FILE}:"
    # Show the header line and how it would look
    HEADER_LINE=$(grep -nE "^[[:space:]]*\* Version:[[:space:]]*[0-9]+\.[0-9]+\.[0-9]+" "$PLUGIN_FILE" | head -n1 | cut -d: -f1)
    if [[ -n "$HEADER_LINE" ]]; then
      CURRENT_HEADER=$(sed -n "${HEADER_LINE}p" "$PLUGIN_FILE")
      echo "  - Line ${HEADER_LINE}:"
      echo "    ${CURRENT_HEADER}"
      echo "    -> (new) $(echo "$CURRENT_HEADER" | sed -E "s/([[:space:]]*\* Version:[[:space:]]*)[0-9]+\.[0-9]+\.[0-9]+/\\1${NEW_VERSION}/")"
    fi
    # Show the const VERSION line and how it would look
    CONST_LINE=$(grep -nE "const[[:space:]]+VERSION[[:space:]]*=[[:space:]]*'[0-9]+\.[0-9]+\.[0-9]+';" "$PLUGIN_FILE" | head -n1 | cut -d: -f1)
    if [[ -n "$CONST_LINE" ]]; then
      CURRENT_CONST=$(sed -n "${CONST_LINE}p" "$PLUGIN_FILE")
      echo "  - Line ${CONST_LINE}:"
      echo "    ${CURRENT_CONST}"
      echo "    -> (new) $(echo "$CURRENT_CONST" | sed -E "s/(const[[:space:]]+VERSION[[:space:]]*=[[:space:]]*')[0-9]+\.[0-9]+\.[0-9]+(';)$/\\1${NEW_VERSION}\\2/")"
    fi
  fi
  echo "[dry-run] No files were modified. No commits, tags, or pushes were performed."
  exit 0
fi

# Update files only when bumping
if ! $NO_BUMP; then
  # Update header Version line (keep leading spaces)
  sed -i '' -E "s/^([[:space:]]*\* Version:[[:space:]]*)[0-9]+\.[0-9]+\.[0-9]+/\\1${NEW_VERSION}/" "$PLUGIN_FILE"

  # Update const VERSION = 'X.Y.Z';
  sed -i '' -E "s/(const[[:space:]]+VERSION[[:space:]]*=[[:space:]]*')[0-9]+\.[0-9]+\.[0-9]+(';)$/\\1${NEW_VERSION}\\2/" "$PLUGIN_FILE"

  # Verify changes applied
  if ! grep -q "${NEW_VERSION}" "$PLUGIN_FILE"; then
    echo "Error: version update failed in $PLUGIN_FILE" >&2
    exit 1
  fi
fi

# Commit, tag, push
BRANCH=$(git rev-parse --abbrev-ref HEAD)
TAG="v${NEW_VERSION}"
MESSAGE="chore(release): bump version to ${TAG}"
if $NO_BUMP; then
  MESSAGE="chore(release): release ${TAG} (no version bump)"
fi

# Stage updated file when bumping
if ! $NO_BUMP; then
  git add "$PLUGIN_FILE"
fi

# If no changes and not in --no-bump mode, exit; if --no-bump, continue to tagging
if git diff --cached --quiet; then
  if $NO_BUMP; then
    echo "No file changes due to --no-bump; proceeding to tag and push."
  else
    echo "No changes to commit; $PLUGIN_FILE already at ${NEW_VERSION}?" >&2
    exit 0
  fi
fi

# Commit only if there are staged changes
if ! git diff --cached --quiet; then
  git commit -m "$MESSAGE"
fi

# Create annotated tag if not exists
if git rev-parse "$TAG" >/dev/null 2>&1; then
  echo "Tag $TAG already exists, skipping tag creation" >&2
else
  git tag -a "$TAG" -m "$MESSAGE"
fi

# Push branch and tags
if $PUSH; then
  echo "Pushing branch '$BRANCH' and tags to origin..."
  git push origin "$BRANCH"
  git push origin "$TAG" || true
else
  echo "--no-push specified; skipping push. Created commit and tag $TAG locally."
fi

echo "Done. New version: ${NEW_VERSION}"
