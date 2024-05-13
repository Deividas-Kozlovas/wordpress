=== Wholesale Order Table for WooCommerce ===
Tags: home delivery, takeaway, wholesale, b2b, woocommerce, bulk, order, form, table, ordering
Contributors: arosoft
Donate link: https://paypal.me/arosoftdonate
Requires at least: 6.0
Tested up to: 6.4
Stable tag: 2.2.0
Requires PHP: 7.4
License: GPL v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Wholesale Order Table for WooCommerce lets your customers make fast bulk orders. Perfect for B2B and wholesalers!

== Description ==

Wholesale Order Table for WooCommerce is the perfect solution for wholesale ordering because of its fast order process and its visualization of product data.

Recurring orders of large volumes of many products will be made fast and easy.

In wholesale, there will often be beneficial to display the product price per consumer unit even if you sell it in whole batches.

On the settings page, you will be able to add the column “Unit Price” which will automatically calculate the unit price if you have given the “Package size” quantity attribute.

To show the order table on any page, use the shortcode [ordertable] or [ordertable categories=”category A slug , category B slug”] or [ordertable tags=”tag A, tag B”] if you want to include only certain categories or tags.
To limit products per page using the shortcode use [ordertable limit=x].
To order products use [ordertable orderby=”title”].
To show products sorted by their category title use [ordertable show_categories=”true”].
To show the category description along with the title add show_category_description=”true” to the shortcode.


The plugin is also available in a [premium version](https://wholesaleorderplugin.com/) that lets you display selected product data from attributes, tags, images, SKU, tax class, unit price, package price etc.


== Frequently Asked Questions ==

= Can I show the order table with a shortcode? =

Yes, use shortcode [ordertable] or [ordertable categories="category A slug, category B slug"] or [ordertable tags="tag A, tag B"] if you want to include only certain categories or tags.

To limit products per page using the shortcode use [ordertable limit=x].

To show products sorted by their category title use [ordertable show_categories="true"].

To show the category description along with the title add show_category_description="true" to the shortcode.

To order products use [ordertable orderby="title"].

You can use the following order settings.

title – The product title. This is the default orderby mode.
date – The date the product was published.
id – The post ID of the product.
menu_order – The Menu Order, if set (lower numbers display first).
popularity – The number of purchases.
rand – Randomly order the products on page load
rating – The average product rating.
sku - The product sku.

= Can I limit products per page? =

If you are using the shop page the page limitation is set via Appearance -> Customize -> WooCommerce -> Product Catalog and then choose "Products per row" and "Products per column". The limit of one page will be  "Products per column" * "Products per row".

If you are showing the odertable with the shortcode [ordertable] just set [ordertable limit=15].

= Can the plugin handle unit prices =

Yes, unit prices will be shown in the order table if you have given the product a "Package Size".

= Is it possible to define the delivery area through a drawn zone? =

Yes, Wholesale Order Table for WooCommerce is compatible with the free plugin [Shipping Zones by Drawing](https://wordpress.org/plugins/shipping-zones-by-drawing-for-woocommerce/).


== Changelog ==

= 2.2.0 =

* Compatibility: Compatibility with WooCommerce blocks checkout and PHP 8.2

= 2.1.9 =

* Fix: Removal of deprecated action "woocommerce_add_order_item_meta"

= 2.1.8.1 =

* Bug fix: Nonces when add to cart fails

= 2.1.8 =

* Improvement: Ajax nonces

= 2.1.7 =

* Compatibility: Improved compatibility with WP 6.1

= 2.1.6 =

* Compatibility: Improved compatibility with 3rd party plugins regarding min/max quantities.

= 2.1.5 =

* Fix: Replacing nested method for cart counts.

= 2.1.4 =
 
* New: Added event 'otfwtable_shown' when table is shown
 
= 2.1.3 =

* Fix: Variation product suffix in table do not display

= 2.1.2 =

* New: Warn if variation product has selection to make when added to cart

= 2.1.1 =

* Fix: Variable products with parent managing stock.

= 2.1.0 =

* Fix: Table horizontal scrollable

= 2.0.8 =

* Fix: Product quantity failure when backorders are allowed

= 2.0.6.3 =
* Updated WodPress 5.6 compatibility

= 2.0.6.2 =
* IE improved compatibility

= 2.0.6.1 =
* IE improved compatibility


= 2.0.6 =
* Compatibility for WordPress 5.4
* Bug Fix: Subtotal for tax calculations

= 2.0.5.2 =

* Styling fixes for Chrome & Firefox browsers.

= 2.0.5.1 =

* Styling fixes for Edge & Safari browsers.

= 2.0.5 =

* New option to choose if to expand categories as default.
* Categories now order as menu_order as default at shop page. Users can now set the category order at category page.
* When using the shortcode with categories argument, categories will order as written in the shortcode.
* Improved styling of table header.

= 2.0.4.1 =
* Compatibility with WordPress 5.3.
* Bug fix: updating quantity for variational products without any stock management.

= 2.0.3 =
* Bug fix: Combining shortcode categories & show_categories.
* New feature: Allow to show category description along with the title.

= 2.0.2 =
* Better robustness back end.

= 2.0.1 =
* Bug fix: Error when product title is linked to product page.

= 2.0.0 =
* Major update with settings re-structured under the hood.

= 1.1.3 =
* Added ability show products by categories.

= 1.1.2 =
* Added ability to sort products by using shortcode [ordertable orderby=""].

= 1.1.0 =
* Changed text of checkout button and added options to choose button and label text.

= 1.0.9 =
* Better performance with WooCommerce 3.6
* Included limit to the [ordertable] shortcode. [ordertable limit=12] will show 12 products per page.



= 1.0.8 =
* Optimized page load.
* Default sorting changed to 'menu_order'.
* Bug fix showing admin page with shortcode.


= 1.0.7 =
* Minor bug fixes.

= 1.0.6 =
* Included support for shorcode [ordertable].

= 1.0.5 =
* Added 'Wholesale' to the title for better understanding of plugin purpose.

= 1.0.4 =
* Fixed: Posistion on sticky header


= 1.0.3 =
* Bug fixes

= 1.0.2 =
* Fixed deprecated methods

= 1.0.1 =
* Bug fixes

= 1.0.0 =
* Initial release

== Screenshots ==



1. Wholsesale Order Table with unit prices

2. Wholsesale Order Table with basic setup

3. Wholsesale Order Table settings

4. Wholsesale Order Table column settings
