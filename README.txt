=== Amazon Affiliate Affix ===

== Description ==
Wordpress plugin for adding a widget to the sidebar containing Amazon Associate 
(affiliate) links that affixes to the screen as the user scrolls.

== Installation ==
* Upload the plugin folder to the `/wp-content/plugins/` directory.
* Activate the plugin through the 'Plugins' menu in WordPress.

== Usage ==
The plugin must be activated already and the widget installed in at least one
sidebar. You should give it a suitable title as well.

For each post where you want to show Amazon affiliate links, just add a custom
meta field. The key should be 'amazon_product' and the value should be:

'Title | Description | Product Image Affiliate Code | Affiliate Link URL'

Make sure that the pipe character is the delimiter and that it does not appear
anywhere else. The description can handle HTML as well. You can add as many of
these meta fields as you wish per post or page. However, it is recommended to 
add at maximum only three products; any more than three will be difficult for 
most display resolutions to handle.

Hope the plugin is useful and profitable for you!
