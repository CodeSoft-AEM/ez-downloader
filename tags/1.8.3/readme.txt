=== EZ Downloader – Plugin & Theme ===
Contributors: drowranger
Tags: plugin installer, theme installer, package import, package export
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.8.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Install or update multiple plugins and one theme from direct ZIP URLs or the WordPress.org repository.

== Description ==

EZ Downloader provides an AJAX installer for multiple plugin ZIP URLs and one theme ZIP URL. It can also search and install public plugins from WordPress.org and export/import portable URL packages.

Persian name: دانلودر آسان، پلاگین و قالب

Features:

* Install or update multiple plugins and one theme in a sequential AJAX queue.
* Search WordPress.org plugins and install, update, or reinstall them without leaving the page.
* Export entered plugin and theme URLs to a portable JSON package.
* Import the package on another WordPress site and load all saved URLs automatically.
* Built-in Persian and English interface with RTL/LTR support.
* Existing plugins and themes are overwritten with the supplied package version.

== Installation ==

1. Upload the `ez-downloader` folder to `/wp-content/plugins/` or install the ZIP from WordPress.
2. Activate the plugin.
3. Open EZ Downloader from the WordPress admin menu.

== Changelog ==

= 1.8.3 =
* Moved plugin repository search directly below the plugin installer.
* Moved theme repository search directly below the theme installer.
* Forced repository search-button icons to white.
* Added a closed-by-default bilingual step-by-step accordion guide.

= 1.8.2 =
* Forced Vazirmatn on all headings and interface text.
* Linked Powered by CodeSoft to https://codsoft.ir/.
* Set the settings-page logo to 128×128 pixels.
* Added a separate AJAX WordPress.org theme search form.
* Added theme install/update/reinstall and Add to theme installer actions.
* Removed slug backgrounds and displayed plugin/theme slugs in bold.
* Reworked direct theme ZIP installation with explicit download errors, overwrite support, cache clearing, and post-install validation.

= 1.8.1 =
* Linked the author name to the official drowranger WordPress.org profile.
* Added a Settings action link on the WordPress Plugins screen.
* Expanded the settings interface to the full available width.
* Added Google Vazirmatn typography throughout the admin interface.
* Corrected the plugin logo asset URL.
* Removed LearnFA from useful links.
* Redesigned all primary, secondary, repository, add, and remove buttons.
* Improved Persian/English repository search with aliases and exact-slug lookup.
* Added an Add to plugin list action for repository search results.

= 1.8.0 =
* Added AJAX search and installation from the WordPress.org plugin repository.
* Added AJAX package export to a portable JSON file.
* Added AJAX package import and automatic field population.
* Added help popovers for package import and export.
* Added a notice explaining that existing items are updated.
* Added built-in Persian/English interface and RTL/LTR support.
* Changed the Persian page name to «دانلودر آسان، پلاگین و قالب».

= 1.7.0 =
* Added multiple plugin URL fields.
* Added a shared AJAX installation queue for plugins and one theme.
* Added useful links matching EZ Login.
