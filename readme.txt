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

For more information, visit us at http://calcfusion.com/

== Installation ==
### I. Installation

There are 3 ways you can install the CalcFusion Plugin for Wordpress, each section below provides a step-by-step guide for each method.

__A. Automatic Plugin Installation using WordPress Admin__

1. Login to WordPress Admin.
2. From the Admin Panel, select Plugins.
3. Click Add New.
4. In the Search Plugins textbox, enter CalcFusion and hit Enter.
5. Select the CalcFusion Plugin from the search results list and click Install Now.
6. A popup window will be displayed, asking you to confirm if you want to install the plugin. Click OK.
7. WordPress will then download and install the plugin. Click Activate Plugin to activate it.

__B. Manual Plugin Installation using WordPress Admin__

This method can be used if you have already downloaded the CalcFusion Plugin in .zip format (click [here](https://downloads.wordpress.org/plugin/calcfusion-for-wp.zip) to download the plugin).

1. Login to WordPress Admin.
2. From the Admin Panel, select Plugins.
3. Click Add New.
4. Click Upload Plugin.
5. Click Choose File and select the .zip file.
6. WordPress will then decompress the file and install the plugin. Click Activate Plugin to activate it.

__C. Manual Plugin Installation using FTP__

This method requires FTP familiarity and can be used if you have already downloaded the CalcFusion Plugin in .zip format (click [here](https://downloads.wordpress.org/plugin/calcfusion-for-wp.zip) to download the plugin).

1. After downloading the .zip file, extract the plugin folder (calcfusion-for-wp) to your desktop.
2. Login to your WordPress host using an FTP program.
3. Upload the calcfusion-for-wp folder (from your desktop) to the wp-content/plugins folder in your WordPress directory.
4. Login to WordPress Admin.
5. From the Admin Panel, select Plugins.
6. From the list of installed plugins, search for CalcFusion Plugin for Wordpress and click Activate to activate it.

###II. CalcFusion Setup Page

Once the plugin has been installed, you need to setup your CalcFusion parameters.

1. Login to WordPress Admin.
2. From the Admin Panel, select Settings.
3. Under Settings, select CalcFusion. The CalcFusion Setup Page will be displayed.
4. The Setup Page is divided into 2 sections:

  a. Account Parameters - This is where you enter your CalcFusion credentials (Account ID, User Name, Password (SHA1) and App Key).
      It is also in this section where you specify the CalcFusion End Point URL (URL of the web services).
  b. Login Test - The Login Test section provides a way for validating your Account Parameters.
      Just click on the Test button and the Test Result will display whether or not it was able to successfully login to CalcFusion.
      
5. Save your CalcFusion parameters by clicking Save Changes. You are now ready to use CalcFusion from within WordPress.

== Frequently Asked Questions ==
__On which WordPress PHP version will the plugin work?__

The plugin only works for WordPress PHP version 5.5 or higher.  Certain features may not work depending on your WordPress PHP version and setting.
If you are running WordPress in a lower version, please contact us and we can provide you with a solution for your version.

__Does CalcFusion provide methods for both synchronous and asynchronous web service calls?__

Yes, CalcFusion provides methods for both synchronous and asynchronous web service calls. To enable asynchronous calls, the CURL AsynchDNS setting must be set to "Yes".

__My CalcFusion computations are not being refreshed, what do I do?__

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