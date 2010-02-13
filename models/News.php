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
 * @subpackage models
 *
 * @author Andrew Waters <andrew@band-x.org>
 * @version 1.0
 * @license http://creativecommons.org/licenses/MIT MIT License
 * @copyright Andrew Waters, 2010
 */

/**
 * Class News
 *
 * Function that either apply to categores, tags and articles together or none of those
 *
 * @since News version 0.1
 */


class News {

	const ARTICLE		=	"news";
	const TAGS			=	"news_tags";
	const CATEGORY		=	"news_categories";
	const ASSIGNMENTS	=	"news_category_assignment";
	const SETTINGS		=	"plugin_settings";

	function executeSql($sql) {
		global $__CMS_CONN__;
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function saveSettings($_POST) {
		Plugin::setAllSettings($_POST, 'news');		
	}

	public function permanentDelete($type, $id) {
		if($type == 'category') {
			$sql = "DELETE FROM ".TABLE_PREFIX.self::CATEGORY." WHERE id='$id'";
			self::executeSql($sql);
		} elseif($type == 'article') {
			$sql = "DELETE FROM ".TABLE_PREFIX.self::ARTICLE." WHERE id='$id'";
			self::executeSql($sql);
			$sql = "DELETE FROM ".TABLE_PREFIX.self::TAGS." WHERE articleID='$id'";
			self::executeSql($sql);
			$sql = "DELETE FROM ".TABLE_PREFIX.self::ASSIGNMENTS." WHERE article='$id'";
			self::executeSql($sql);
		}
	}

	public function checkLayoutsAreConfigured() {
		$settings = Plugin::getAllSettings('news');
		if($settings['viewByLayout'] == 0 || $settings['viewByLayout'] == NULL) {
			return 0;
		} elseif($settings['pagenotfoundLayout'] == 0 || $settings['pagenotfoundLayout'] == NULL) {
			return 0;
		} elseif ($settings['listingLayout'] == 0 || $settings['listingLayout'] == NULL) {
			return 0;
		} elseif($settings['rssLayout'] == 0 || $settings['rssLayout'] == NULL) {
			return 0;
		} elseif($settings['articleLayout'] == 0 || $settings['articleLayout'] == NULL) {
			return 0;
		} else {
			return 1;		
		}
	}

}