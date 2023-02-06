=== Vitt Attribute Hierarchy for WooCommerce ===
Contributors: Matias Marinez
Tags: woocommerce,attribute,hierarchy
Requires at least: 6.0
Tested up to: 6.1.1
Stable tag: 1.0.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin works simulating a hierarchy for attributes and subattributes.

== Description ==

The plugin looks up the sub-attributes in your product, then for each value it looks up its parent and adds the parent attribute and the parent value in the product.
When using a filter with the main attribute on the store page, all the products that have that main attribute are returned, which are the same products that contain the associated sub-attributes.

Important:
You can't create multiple hierarchy levels, just one.
Two diferent attributes cannot contain the same sub-attribute.

== Installation ==
###Before You Start
Here are some things to know before you begin this process.
- This plugin requires you to have the [WooCommerce plugin](https://woocommerce.com/) already installed and activated in WordPress.
- Your hosting environment must meet [WooCommerce's minimum requirements](https://docs.woocommerce.com/document/server-requirements), including PHP 7.0 or greater.
- We recommend you use this plugin in a staging environment before installing it on production servers. To learn more about staging environments, [check out these related Wordpress plugins](https://wordpress.org/plugins/search.php?q=staging).

== Documentation ==
[vitt attribute hierarcht for woocommerce](https://github.com/vittfiles/vitt-attribute-hierarchy)
== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Screenshots are stored in the /assets directory.
2. This is the second screen shot

== Changelog ==

= 1.0.1 =
* First version