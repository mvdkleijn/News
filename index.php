<?php

/**
 * Copyright (C) 2010, Andrew Waters <andrew@band-x.org>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * All copyright notices and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

/**
 * @package news
 *
 * @author Andrew Waters <andrew@band-x.org>
 * @version 1.0
 * @license http://creativecommons.org/licenses/MIT MIT License
 * @copyright Andrew Waters, 2010
 */


Plugin::setInfos(array(
	'id'					=>	'news',
	'title'					=>	'News',
	'description'   		=>	'A News plugin which allows articles to be drafted, moderated, tagged and categorised.<br />Also provides an XML (RSS) output, permalinks and so much more...',
	'license'				=>	'MIT',
	'author'				=>	'Andrew Waters',
	'website'				=>	'http://www.band-x.org/',
	'update_url'			=>	'http://www.band-x.org/update.xml',
	'version'				=>	'1.0.0',
	'require_wolf_version'	=>	'0.6.0',
	'type'					=>	'both'
));

Plugin::addController('news', 'News', 'administrator,developer,editor', TRUE);

if(defined('CMS_BACKEND') && Plugin::isEnabled('dashboard')) {
	include('dashboard.php');
}

include('models/News.php');
include('models/Article.php');
include('models/Tag.php');
include('models/Category.php');

$settings	=	Plugin::getAllSettings('news');

if(Plugin::isEnabled('news')) {

	Dispatcher::addRoute(array(

		'/news'														=>	'/plugin/news/index',
		'/news/'													=>	'/plugin/news/index',

		'/news/noEditor'											=>	'/plugin/news/noEditor',
		'/news/notConfigured'										=>	'/plugin/news/notConfigured',
		'/news/documentation'										=>	'/plugin/news/documentation',
		'/news/trash'												=>	'/plugin/news/trash',
		'/news/restore/:num'										=>	'/plugin/news/restore/$1',
		'/news/add'													=>	'/plugin/news/add',
		'/news/addArticle'											=>	'/plugin/news/addArticle',
		'/news/delete/:any'											=>	'/plugin/news/delete/$1',
		'/news/article/:any'										=>	'/plugin/news/article/$1',
		'/news/updateStatus'										=>	'/plugin/news/updateStatus',
		'/news/updateArticle'										=>	'/plugin/news/updateArticle',
		'/news/settings'											=>	'/plugin/news/settings',
		'/news/saveSettings'										=>	'/plugin/news/saveSettings',
		'/news/saveSettings/:any'									=>	'/plugin/news/saveSettings/$1',

		'/news/categories'											=>	'/plugin/news/categories',
		'/news/category/:any'										=>	'/plugin/news/categories/$1',
		'/news/addCategory'											=>	'/plugin/news/categories/add',
		'/news/saveCategory'										=>	'/plugin/news/categories/save',
		'/news/deleteCategory/:num'									=>	'/plugin/news/categories/delete/$1',
		'/news/restoreCategory/:num'								=>	'/plugin/news/categories/restore/$1',

		'/news/permanentDelete/:any/:num'							=>	'/plugin/news/permanentDelete/$1/$2',

		'/'.$settings['folder'].''									=>	'/plugin/news/index',
		'/'.$settings['folder'].'/'									=>	'/plugin/news/index',
		'/'.$settings['folder'].'/page'								=>	'/plugin/news/index/1',
		'/'.$settings['folder'].'/page/'							=>	'/plugin/news/index/1',
		'/'.$settings['folder'].'/page/:num'						=>	'/plugin/news/index/$1',
		'/'.$settings['folder'].'/page/:num/'						=>	'/plugin/news/index/$1',
		'/'.$settings['folder'].'/:num/:num/:num/:any'				=>	'/plugin/news/article/$4/$1/$2/$3',
		'/'.$settings['folder'].'/:num/:num/:num'					=>	'/plugin/news/viewBy/$1/$2/$3',
		'/'.$settings['folder'].'/:num/:num/:num/'					=>	'/plugin/news/viewBy/$1/$2/$3',
		'/'.$settings['folder'].'/:num/:num'						=>	'/plugin/news/viewBy/$1/$2',
		'/'.$settings['folder'].'/:num/:num/'						=>	'/plugin/news/viewBy/$1/$2',
		'/'.$settings['folder'].'/:num'								=>	'/plugin/news/viewBy/$1',
		'/'.$settings['folder'].'/:num/'							=>	'/plugin/news/viewBy/$1',
		'/'.$settings['folder'].'/notfound'							=>	'/plugin/news/notfound',
		'/'.$settings['folder'].'/notfound/'						=>	'/plugin/news/notfound',
		'/'.$settings['folder'].'/categories'						=>	'/plugin/news/categories/index',
		'/'.$settings['folder'].'/categories/'						=>	'/plugin/news/categories/index',
		'/'.$settings['folder'].'/categories/:any'					=>	'/plugin/news/categories/view/$1',
		'/'.$settings['folder'].'/categories/:any/'					=>	'/plugin/news/categories/view/$1',
		'/'.$settings['folder'].'/categories/:any/page'				=>	'/plugin/news/categories/view/$1/1',
		'/'.$settings['folder'].'/categories/:any/page/'			=>	'/plugin/news/categories/view/$1/1',
		'/'.$settings['folder'].'/categories/:any/page/:num'		=>	'/plugin/news/categories/view/$1/$2',
		'/'.$settings['folder'].'/categories/:any/page/:num/'		=>	'/plugin/news/categories/view/$1/$2',
		'/'.$settings['folder'].'/tags'								=>	'/plugin/news/tags/index',
		'/'.$settings['folder'].'/tags/'							=>	'/plugin/news/tags/index',
		'/'.$settings['folder'].'/tags/:any'						=>	'/plugin/news/tags/$1',
		'/'.$settings['folder'].'/tags/:any/'						=>	'/plugin/news/tags/$1',
		'/'.$settings['folder'].'/tags/:any/page'					=>	'/plugin/news/tags/$1/1',
		'/'.$settings['folder'].'/tags/:any/page/'					=>	'/plugin/news/tags/$1/1',
		'/'.$settings['folder'].'/tags/:any/page/:num'				=>	'/plugin/news/tags/$1/$2',
		'/'.$settings['folder'].'/tags/:any/page/:num/'				=>	'/plugin/news/tags/$1/$2',
		'/'.$settings['folder'].'/tagcloud'							=>	'/plugin/news/tagcloud',
		'/'.$settings['folder'].'/tagcloud/'						=>	'/plugin/news/tagcloud',

		'/rss'														=>	'/plugin/news/viewFeed',
		'/rss/'														=>	'/plugin/news/viewFeed',
		'/rss.xml'													=>	'/plugin/news/viewFeed',
		'/rss.xml/'													=>	'/plugin/news/viewFeed',

		'/permalink/'.$settings['folder'].'/:any'					=>	'/plugin/news/article/$1',
		'/permalink/'.$settings['folder'].'/:any/'					=>	'/plugin/news/article/$1',

	));

}