=== CalcFusion for WP ===
Contributors: CalcFusion
Tags: computation engine
Requires at least: 3.5
Tested up to: 4.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

CalcFusion brings flexibility by utilizing Excel computations and functions as a backend system to your existing WordPress site. 

== Description ==
### What is CalcFusion? 

A Computation Engine that...

* lets users create business rules and formulas with Excel.
* mixes live data from your WordPress site with local data stored on Excel.
* receives data and sends computed results from/to your WordPress site via web services.

= Learn more about CalcFusion at http://calcfusion.com/ =


= Why CalcFusion? =
CalcFusion leverages on Excel's power and flexibility.
Writing complex functions on Excel is straightforward due to its spreadsheet interface that allows for easy modifications and testing, while writing codes for the same functions can be unpredictable and tedious as programmers have to make sure that their codes are well tested and maintainable.
Cut the development time in half by designing your web app interface on WordPress and using CalcFusion and Excel as you back-end. Use real-time data for your computations, and display Excel computed results instantly on WordPress.

= How does it Work? =
[youtube https://www.youtube.com/watch?x-yt-cl=84411374&v=2_OqsgaUIwc&x-yt-ts=1421828030&feature=player_embedded]

= Try our live implementation of CalcFusion for WP at http://calcfusion.com/live-demo/ =


== Installation ==
### I. Installation

There are 3 ways you can install the CalcFusion Plugin for Wordpress, each section below provides a step-by-step guide for each method.

####A. Automatic Plugin Installation using WordPress Admin
1. Login to WordPress Admin.
1. From the Admin Panel, select Plugins.
1. Click Add New.
1. In the Search Plugins textbox, enter CalcFusion and hit Enter.
1. Select the CalcFusion Plugin from the search results list and click Install Now.
1. A popup window will be displayed, asking you to confirm if you want to install the plugin. Click OK.
1. WordPress will then download and install the plugin. Click Activate Plugin to activate it.

####B. Manual Plugin Installation using WordPress Admin
This method can be used if you have already downloaded the CalcFusion Plugin in .zip format (click [here](https://downloads.wordpress.org/plugin/calcfusion-for-wp.zip) to download the plugin).

1. Login to WordPress Admin.
1. From the Admin Panel, select Plugins.
1. Click Add New.
1. Click Upload Plugin.
1. Click Choose File and select the .zip file.
1. WordPress will then decompress the file and install the plugin. Click Activate Plugin to activate it.

####C. Manual Plugin Installation using FTP
This method requires FTP familiarity and can be used if you have already downloaded the CalcFusion Plugin in .zip format (click [here](https://downloads.wordpress.org/plugin/calcfusion-for-wp.zip) to download the plugin).

1. After downloading the .zip file, extract the plugin folder (calcfusion-for-wp) to your desktop.
1. Login to your WordPress host using an FTP program.
1. Upload the calcfusion-for-wp folder (from your desktop) to the wp-content/plugins folder in your WordPress directory.
1. Login to WordPress Admin.
1. From the Admin Panel, select Plugins.
1. From the list of installed plugins, search for CalcFusion Plugin for Wordpress and click Activate to activate it.

###II. CalcFusion Setup Page
Once the plugin has been installed, you need to setup your CalcFusion parameters.

1. Login to WordPress Admin.
1. From the Admin Panel, select Settings.
1. Under Settings, select CalcFusion. The CalcFusion Setup Page will be displayed.
1. Enter your CalcFusion credentials and specify the CalcFusion End Point URL.
1. Save your account parameters by clicking Save Changes. 
1. Validate your account parameters by clicking on the Test button. The Test Result will display whether or not you were able to successfully login to CalcFusion.


== Frequently Asked Questions ==
####On which WordPress PHP version will the plugin work?
The plugin only works for WordPress PHP version 5.5 or higher.  Certain features may not work depending on your WordPress PHP version and setting.
If you are running WordPress in a lower version, please contact us and we can provide you with a solution for your version.

####Does CalcFusion provide methods for both synchronous and asynchronous web service calls?
Yes, CalcFusion provides methods for both synchronous and asynchronous web service calls. To enable asynchronous calls, the CURL AsynchDNS setting must be set to "Yes".

####My CalcFusion computations are not being refreshed, what do I do?
For performance, web hosting service providers (such as WordPress) implement aggressive caching.
To be able to refresh your CalcFusion computations, a solution is to implement caching exclusions on pages or file paths that should not be cached.
You can check on how caching exclusions can be done, or you can contact your service provider for assistance.

For more information, please click here to view the CalcFusion [FAQ page](http://calcfusion.com/more/faq/).

== Screenshots ==
1. CalcFusion Setup Page (under Settings > CalcFusion)

== Changelog ==
= 1.0.0 =
* Initial release.

== Upgrade Notice ==
= 1.0.0 =
* Initial release.