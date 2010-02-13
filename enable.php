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


	global $__CMS_CONN__;

	/**
		Sanity Check - decide whether we're enabling for the first time or after a disable
	**/

	$sql = "SELECT * FROM `".TABLE_PREFIX."plugin_settings` WHERE plugin_id='albums';";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();
	$rowCount = $pdo->rowCount();

	if($rowCount == 0) {
		$sql =	"
					INSERT INTO `".TABLE_PREFIX."plugin_settings` (`plugin_id`,`name`,`value`)
					VALUES
						('news','folder','news'),
						('news','latestNewsCount','6'),
						('news','itemsToDisplay','10'),
						('news','pagesToDisplay','0'),
						('news','fullImageWidth','700'),
						('news','shortImageWidth','400'),
						('news','articleLayout',''),
						('news','rssLayout',''),
						('news','listingLayout',''),
						('news','pagenotfoundLayout',''),
						('news','viewByLayout',''),
						('news','rssTitle','RSS Feed'),
						('news','rssShowAuthor','yes'),
						('news','rssContent','both'),
						('news','editorsAllowedCategories','no')
				;";
	}
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();


	/**
		Let's create the tables. If they exist, they won't be overwritten
	**/

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."news` (
					`id` int(11) NOT NULL auto_increment,
					`name` varchar(128) default NULL,
					`slug` varchar(128) default NULL,
					`permalink` varchar(128) default NULL,
					`intro` varchar(512) default NULL,
					`article` varchar(4096) default NULL,
					`imageID` int(11) default NULL,
					`datePublished` int(11) default NULL,
					`published` enum('published','pending','draft') NOT NULL default 'draft',
					`author` int(11) default NULL,
					`status` enum('active','deleted') NOT NULL default 'active',
					PRIMARY KEY	(`id`)
				) AUTO_INCREMENT=0;
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."news_categories` (
					`id` int(11) NOT NULL auto_increment,
					`parent` int(11) default NULL,
					`name` varchar(256) default NULL,
					`slug` varchar(256) default NULL,
					`description` varchar(5012) default NULL,
					`status` enum('active','deleted') NOT NULL default 'active',
					PRIMARY KEY	(`id`)
				) AUTO_INCREMENT=0;
			";

	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."news_category_assignment` (
					`article` int(11) default NULL,
					`category` int(11) default NULL
				) AUTO_INCREMENT=0;
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."news_tags` (
					`id` int(11) NOT NULL auto_increment,
					`articleID` int(11) default NULL,
					`tag` varchar(128) default NULL,
					`slug` varchar(128) default NULL,
					PRIMARY KEY	(`id`)
				);
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	exit();