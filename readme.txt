=== Posts Compare find Plagiat ===
Contributors: spnova
Donate link: http://www.zhenskayalogika.ru/2009/11/13/plagin-posts-compare-%e2%80%93-na-strazhe-kontenta/
Tags: posts, compare, check, post, getting copied, plagiat, stealing
Requires at least: 2.0
Tested up to: 2.8
Stable tag: 1.1
Version: 1.1

This plugin allows you to check your posts for getting copied.

== Description ==
This plugin allows you to check your posts for getting copied.
Have you ever tried to find out if someone copies your unique texts and places them on another sites? If it's so, this plugin is for you. It will help you to find your texts somewhere else in Internet by using searchengines.

Plugin was checked on http://www.zhenskayalogika.ru/ and helped us to find several plagiarists.

About plugin work:
Plugin creates 'postscompare+search+result' table. After compare-cron.php file is run, the scrip takes your post(s) and checks some sentanses in searchengines. If it finds the same text, the script will create a record in the table with the result, if there are no such texts, the script will make record about no results in the table.
Bu the way, the table should have records about sentances found from your site too, but these records will not be listed in admin area.
If your table 'postscompare+search+result' has no records after some hours of the sript working, or all records tell about no results found, it means that the script works incorrectly. You'd better to write me and I'll try to help you.

== Installation ==

1. Upload full directory into your wp-content/plugins directory
2. Activate the plugin at plugin administration page
3. Set up cron job to http://yoursite.com/wp-content/plugins/posts-compare/compare-cron.php file run every minute. If your hosting has no cron, you can use free cron sites like: http://www.cronme.org/ or http://www.onlinecronjobs.com/
My cron command for example: (cd /www/zhenskayalogika.ru/httpdocs/wp-content/plugins/posts-compare; /usr/bin/php compare-cron.php >/dev/null)
4. Open the plugin configuration page, which is located under Options -> Posts Compare and check settings. But if you are not sure what they are for, don't touch anything, the plugin will work without problems.
5. The plugin will automatically check your posts for getting copied. Attention, for the first time, the script will need some time to check all your posts.

If you have any questions or suggestions, write me: serafimpanov@gmail.com

== Changelog ==

= Version 1.0, January 1, 2009 =

* Initial release.

= Version 1.1, November 14, 2009 =

* New wordpress version support added.

== Frequently Asked Questions == 

Support page with questions and answers: http://www.zhenskayalogika.ru/2009/11/13/plagin-posts-compare-%E2%80%93-na-strazhe-kontenta/
(Please use google translator for other languages or wirte in English)

== Screenshots ==

1. Administration interface in WordPress 2.7

== Licence ==

GPL

== Translations ==

en, ru
