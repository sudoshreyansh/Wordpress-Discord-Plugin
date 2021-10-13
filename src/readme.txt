=== Live Members Counter For Discord ===
Contributors: sudoshreyansh
Tags: discord, members counter, discord server, discord server members, live counter, shortcode
Requires at least: 5.2
Tested up to: 5.5
Stable tag: 1.0.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Live Members Counter For Discord helps you display the total members count from all your Discord servers.

== Description ==

Live Members Counter For Discord is an one of a kind plugin which helps you embed the members count from all your Discord servers, and comes with an interactive dashboard to edit the appearance of your members counter.

* Only plugin to help you embed the total members count from multiple Discord Servers.
* Interactive customizer to customize the appearance of your counter
* Can be embedded by simply pasting the given shortcode where ever you want to display it

== Frequently Asked Questions ==

= Do I have to give some access to the plugin in my Discord server(s) ? =

Yes, to access the members count of your discord server, we need you to do some simple steps ( as mentioned in [Installation Instructions](#installation) ) which are totally secure and give this plugin access to get the members count through the Discord Rest API.

== Installation ==

To integrate the plugin with the Discord API, a Discord bot needs to be created and the bot token must be given to the plugin to be able to get members count.

= Discord Bot =

To be able to access the Discord API for your server we need a Discord Bot Token, for which we need to create a new Discord Bot. If you already have a bot, you can skip this step.

* Go to [Discord Developers](https://discord.com/developers/applications)
* On the top right corner, click on "New Application" button.
* Give your new discord dpplication a name in the popup.
* Once created, on your discord application's dashboard under the settings panel on the left-side of the screen, click on "Bot"
* On the "Bot" page, click on "Add Bot" and confirm the action.
Congrats your bot will be created.
* Now on the same "Bot" page, you can give your bot a username and a profile picture. Also there will be a "Token" section. Click on "Copy" to copy this token and store it as it will be required later.
* Scroll down and under the "Priviledged Intents" section, activate the checkbox under "Members Intent".
* Now on the left side of the screen, under "Settings", go to "OAuth2" to invite the bot to your Discord servers.
* Scroll down to the "Scopes" table and check "bot". Copy the resulting URL and open the URL on a new tab to invite the bot to your Discord servers which you want to count the members of.
Congrats you have successfully set up your Discord bot !

= Plugin Installation =

* From your WordPress website, go to Plugins -> Add New and add this plugin for automatic installation of the plugin.
* Now go to Settings -> Members Counter from your WordPress dashboard.
* Paste the Discord Bot Token we got while creating a new bot.
* Click on Save Changes.
* Now when you want the plugin to start noting down changes, just click on Start Counter.
Congrats ! The plugin has been installed.

* Now to embed the live counter from the DLMC settings page, modify the styling under the Embed Counter. Now you can copy the Embed Shortcode and paste it wherever you want it to show.

== Changelog ==

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.0 =
Initial Release