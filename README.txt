NEWS PLUGIN
------------

Author - Andrew Waters at band-x (andrew@band-x.org)

This plugin allows you to add a news or blog section to your site.

It has Archive listings, an internal Tag manager, RSS producer, Categories, Permalinks and future publishing options

Icons in this plugin are a mixture from Function and Web Injection icon packs:

	http://wefunction.com/2008/07/function-free-icon-set
	http://www.tutorial9.net/resources/free-icon-pack-web-injection/



REQUIREMENTS
------------

In order to run the plugin, you must have Wolf version 0.6.0 or later as it makes use of the new Dispatcher functionality in the core.
Requires PHP >= 5.2 as there are a couple of methods in the News Model that use the new date_parse() function



HOW TO SETUP
------------

1.	Upload the plugin to your Wolf installed 'plugins' directory.
2.	Install the plugin via the Administration section of your Wolf installation.
3.	Read the Documentation and set up the Settings as you want them.
4.	Be sure to add any Navigation items you need - because this section is dynamic, it doesn't use the "Pages" system and will
	therefore not be included by standard layout navigation.
5.	Get writing!



WHAT IT INSTALLS / UPDATES
--------------------------

plugin_settings		-	bundled settings which are configurable via the Settings page. 'plugin_id' is "news".

news				-	This table stores information about the News articles themselves.
news_tags			-	This table contains tagging information for news articles.
news_categories		-	this table contains information about categories you can store news in.



NOTES
-----

	The table names mentioned above can be changed in News.php if they conflict with existing tables in your database.

	Because this section uses the Dispatcher there are several variables in your layout that may be beyond the scope of the page call. For example, Page Titles!

	The content is currently set up to be editable via Martijn's TinyMCE WYSIWYG editor plugin (http://vanderkleijn.net/frog-cms/plugins/tinymce.html)

	Generated Observer Events:

		CONTENT:

			news_article_view_start							-	this allows you to observe (and potentially insert) at the top of the article
			news_article_view_end							-	this allows you to observe (and potentially insert) underneath the article

			news_rss_content_start							-	this allows you to insert HTML at the begining of the RSS Description Element
			news_rss_content_end							-	this allows you to insert HTML at the end of the RSS Description Element


		CRUD:

			news_settings_updated							-	Settings for the News plugin were updated

			news_article_added($articleID)					-	A new article was added
			news_article_not_added							-	A new article couldn't be added because some info was missing
			news_article_deleted($articleID)				-	An existing article was deleted
			news_article_restored($articleID)				-	An administrator restored an article
			news_article_permanently_removed($articleID)	-	An administrator permanently removed an article
			news_article_updated($articleID)				-	An article was updated
			news_article_status_updated($articleID)			-	An articles publish status was updated

			news_category_added($categoryID)				-	A new category was added
			news_category_nosave()							-	A category couldn't be saved because it didn't have a name
			news_category_saved($categoryID)				-	A category was updated
			news_category_restored($categoryID)				-	A category was restored
			news_category_deleted($categoryID)				-	A category was deleted


	For more information about Observers please read this page: http://www.wolfcms.org/wiki/the_observer_system

	For security purposes, you can only attribute authorship to an editor - admins and developers are hidden from the drop down list.

	Deleting Articles is NON Destructive. If you need to recover a deleted article, ask your database administrator to find the article and amend the
	status value to 'active'. Or ask an adminsitrator / developer to remove the article from the trash!

	Recommend installing the Albums plugin which will then manage all the news images (and more!) - http://www.band-x.org/downloads/albums



CHANGELOG
---------

2010-02-12	<andrew@band-x.org>
			1.0		-	First Release