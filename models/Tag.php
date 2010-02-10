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
 * Class Tag
 *
 * Allows CRUD access to tags and associations between articles and tags
 *
 * @since News version 0.4
 */


class Tag {

	const TAGS	=	"news_tags";

	public $id;
	public $articleID;
	public $tag;
	public $slug;
	public $url;
	public $count;

	private function executeSql($sql) {
		global $__CMS_CONN__;
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	private function urlToTag() {
		$newsSettings = Plugin::getAllSettings('news');
		$folder = $newsSettings['folder'];
		$this->url = BASE_URL . $folder . '/tags/' . $this->slug;
	}

	public function getTag($by, $value) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::TAGS." WHERE $by='$value'";
		$articles = self::executeSql($sql);
		foreach($articles[0] as $article => $value) {
			$this->$article = $value;
			self::urlToTag();
		}
	}

	public function countTags() {
		$sql = "SELECT COUNT(*), id FROM ".TABLE_PREFIX.self::TAGS." GROUP BY tag";
		$results = self::executeSql($sql);
		$array = array();
		foreach($results as $result) {
			$tag = new Tag;
			$tag->getTag('id', $result['id']);
			$tag->count = $result['COUNT(*)'];
			$array[] = $tag;
		}
		return $array;
	}

	public function maxCount() {
		$sql = "SELECT COUNT(*), id, articleID FROM ".TABLE_PREFIX.self::TAGS." GROUP BY tag";
		$results = self::executeSql($sql);
		$array = array();
		foreach($results as $result) {
			$thisArticle = new Article();
			$thisArticle->getArticle('id', $result['articleID']);
			if($thisArticle->published == 'published' && $thisArticle->status == 'active') {
				$array[] = $result['COUNT(*)'];
			}
		}
		if(count($array) != 0) return(max($array));
	}

	public function getNewsTags($article=NULL, $unique=FALSE) {
		$sql = "SELECT id FROM ".TABLE_PREFIX.self::TAGS."";
		if($article != NULL) $sql .= " WHERE articleID='$article'";
		$tags = self::executeSql($sql);
		$usedTags = array();
		$array = array();
		foreach($tags as $tag) {
			$thisTags = new Tag;
			$thisTags->getTag('id', $tag['id']);
			if(!in_array($thisTags->tag, $usedTags)) {
				$usedTags[] = $thisTags->tag;
				$array[] = $thisTags;
			}
		}
		return $array;
	}

	public function updateTags($tags, $articleID) {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::TAGS." WHERE articleID='$articleID'";
		self::executeSql($sql);
		$tags = explode(',', $tags);
		foreach($tags as $tag) {
			if(trim($tag) != '') {
				$slug = self::slugify($tag);
				$sql = "INSERT INTO ".TABLE_PREFIX.self::TAGS." VALUES (
							'',
							'$articleID',
							'".filter_var(trim($tag), FILTER_SANITIZE_STRING)."',
							'".filter_var(trim($slug), FILTER_SANITIZE_STRING)."'
						)";
				self::executeSql($sql);
			}
		}
	}

	private function slugify($input) {
		$input = trim($input); 
		$remove  = array( "([\40])" , "([^a-zA-Z0-9-])", "(-{2,})" ); 
		$replace = array("-", "", "-"); 
		return strtolower(preg_replace($remove, $replace, $input));
	}

}