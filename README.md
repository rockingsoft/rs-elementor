# RS Elementor Widgets for WooCommerce

A lightweight, free, and open‑source plugin that adds two high‑quality Elementor widgets for WooCommerce:

- Advanced Product Images
- Product Reviews

Widgets are designed to be fast, accessible, and easy to customize in Elementor.

## Features

- __Advanced Product Images__ (`widgets/advanced-product-images.php`)
  - Customizable thumbnail strip (left, right, top, bottom)
  - Adjustable thumbnail size and gap
  - Click‑to‑zoom modal/lightbox with next/prev navigation
  - Uses product main image + gallery images with de‑duplication
  - Responsive height controls for the main image
  - Styles: `assets/css/advanced-product-images.css`
  - Script: `assets/js/advanced-product-images.js`

- __Product Reviews__ (`widgets/product-reviews.php`)
  - Displays reviews for the current WooCommerce product
  - Initial “preview” list with configurable count and a Show All button
  - Full reviews modal with client‑side sorting (highest to lowest)
  - Optional avatar, verified badge, date, and rating display
  - No AJAX required; renders server‑side, sorts in the browser
  - Styles: `assets/css/product-reviews.css`
  - Script: `assets/js/product-reviews.js`

## Requirements

- WordPress: 5.0+
- PHP: 7.0+
- WooCommerce: 3.0+
- Elementor: 3.0.0+

These checks are enforced in the main plugin file `rs-elementor-widgets.php`.

## Installation

- __From WordPress Admin__
  1. Upload the plugin ZIP to Plugins → Add New → Upload Plugin.
  2. Activate “RS Elementor Widgets for WooCommerce”.

- __Manual (FTP/SFTP)__
  1. Copy this folder to `wp-content/plugins/rs-elementor-widgets/`.
  2. Activate the plugin in WordPress.

No build steps are required; CSS/JS are included and registered per widget.

## Using the Widgets (Elementor)

- __Category__: “RS WooCommerce”
- __Widgets__:
  - Advanced Product Images
  - Product Reviews

Add the widget to a single product template or product page:

- __Advanced Product Images__
  - Controls: Thumbnails Position, Thumbnail Size, Thumbnail Gap, Main Image Height/Max Height, etc.
  - Thumbnails are generated from the product’s main image + gallery.
  - Click the main image to open a fullscreen modal with navigation.

- __Product Reviews__
  - Controls: Title visibility/text, initial reviews count, Show All button text, modal title, visibility toggles (avatar, verified badge, date, rating), and typography/colors.
  - Shows a configurable number of reviews inline and the full list in a modal with sorting.

## Internationalization

- Text Domain: `rs-elementor-widgets`
- Ready for translation. Use standard WordPress translation methods.

## Accessibility

- Widgets include descriptive labels and ARIA attributes for interactive UI (e.g., modal controls, next/prev navigation, close buttons).
- Ensure your theme contrast and focus styles are adequate.

## Performance

- Assets are registered centrally in `RS_Elementor_Widgets::widget_styles()` and `RS_Elementor_Widgets::widget_scripts()` and loaded by each widget via `get_style_depends()` / `get_script_depends()` only when the widget is used on a page.

## Privacy

- This plugin does not collect or transmit any personal data.
- It relies only on WordPress, WooCommerce, and Elementor data available on your site.

## Compatibility & Known Limitations

- Designed for WooCommerce single product context. Reviews widget expects to be used on single product templates where a current product is available.
- The Advanced Product Images widget uses available product images; if none exist, it renders a friendly notice.

## Support

- Free community support via GitHub Issues (if this project is hosted on GitHub) or the WordPress.com marketplace listing.
- Please include: WordPress, PHP, WooCommerce, and Elementor versions; theme name; steps to reproduce; and screenshots.

## Contributing

Contributions are welcome!

- Fork the repository and create a topic branch.
- Follow WordPress coding standards where applicable.
- Keep changes focused and include before/after notes or screenshots.
- Open a pull request.

## License

- Licensed under the GNU General Public License v3.0 (GPL-3.0)
- See `LICENSE` for full text.

You are free to use, modify, and redistribute this plugin under the GPL, including for commercial sites, consistent with the license terms.

## Changelog

- 1.0.0
  - Initial public release with two widgets: Advanced Product Images and Product Reviews.
