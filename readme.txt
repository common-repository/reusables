=== WordPress Reusables ===
Contributors: ooeygui
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=25WYW3FP76G2U
Tags: cms, reusables, content, type, blocks, reus, management, manage, post, page, widget, region, plugin, sidebar, user, roles, permissions, segmented
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 3.1.0

Manage content more efficiently by storing and editing in a single location on WordPress and display user-role specific content. 

== Description ==

WordPress Reusables was created to help developers manage their content more efficiently. By allowing content to be stored and edited in a single location on WordPress it is possible to update a single piece of content that will update retroactively anywhere that the reusable is being used. This plugin creates a more front-end approach to templatizing content and even makes it possible to create or modify an entire theme without touching the PHP code. You may also display entire pieces of content based on a users role thus adding another dimension of personalization to your site.

This plugin also integrates with the [Custom Content Types](http://wordpress.org/extend/plugins/custom-content-types) and [Capability Manager](http://wordpress.org/extend/plugins/capsman) plugins.

*New in Version 3.0*

*   **Reusable regions** are widgets that admins can place into dynamic sidebars, reusables can then be assigned to regions from post/page edit screens.

*   **User roles** allow admins to display reusables based on a users role.

*   **WYSIWYG** the TinyMCE WYSIWYG editor has been added to the reusable edit screen.

*   **Reusable content type** is now available thanks to WordPress 3.0.

*   **Predefined meta variables** for easier access to WordPress bloginfo() variables.

*   **Completley overhauled code** for better performance.

== Installation ==

1. Upload the `reusables` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the `[reus]` shortcode in posts, pages, and other reusables or use the `reus()` function in template files.


== Frequently Asked Questions ==

= What tables is the new version 3.0 of reusables using? =

`wp_posts`
`wp_post_reus`
`wp_reus_regions`
`wp_reus_roles`

= Will my old theme work with version 3.0? =

No, the `get_reus()` function no longer behaves the same way. It will return the reusable as an object. Use the `reus(id [, meta])` function instead. You'll also want to make sure you use the `id` of the reusable rather than the `name`, since `name` is no longer a unique identifier and will not work. This also applies to the `[reus]` short code.

== Screenshots ==

1. `/screenshots/screenshot-1.png`
2. `/screenshots/screenshot-2.png`
3. `/screenshots/screenshot-3.png`
4. `/screenshots/screenshot-4.png`

== Changelog ==

= 3.0 =
*   **Reusable regions** are widgets that admins can place into dynamic sidebars, reusables can then be assigned to regions from post/page edit screens.
*   **User roles** allow admins to display reusables based on a users role.
*   **WYSIWYG** the TinyMCE WYSIWYG editor has been added to the reusable edit screen.
*   **Reusable content type** is now available thanks to WordPress 3.0.
*   **Predefined meta variables** for easier access to WordPress bloginfo() variables.
*   **Completley overhauled code** for better performance.

= 2.0 =
*   Added the ability to nest reusables
*   Created meta variables
*   Added reusable descriptions
*   Completley rewrote the code to perform more efficiently

== Upgrade Notice ==

= 3.1.0 =
Added integration with WordPress capabilities for management using capability plugins such as the Capability Manager plugin.