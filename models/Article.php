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
 * Class Article
 *
 * All information about articles
 *
 * @since News version 0.2
 */


class Article {

	const ARTICLE	=	"news";
	const TAGS		=	"news_tags";

	public $id;
	public $name;
	public $slug;
	public $permalink;
	public $intro;
	public $article;
	public $imageID;
	public $datePublished;
	public $published;
	public $author;
	public $status;
	public $url;
	public $tags;
	public $categories;

	private function executeSql($sql) {
		global $__CMS_CONN__;
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function countEditors() {
		global $__CMS_CONN__;
		$sql = "SELECT * FROM `".TABLE_PREFIX."user_permission` WHERE permission_id='3';";
		$pdo = $__CMS_CONN__->prepare($sql);
		$pdo->execute();
		return $pdo->rowCount();
	}

	public function getArticle($by, $value) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ARTICLE." WHERE $by='$value'";
		$articles = self::executeSql($sql);
		foreach($articles[0] as $article => $value) {
			$this->$article = $value;
			self::setArticleUrl();
			self::getAuthorInfo();
			$tags = Tag::getNewsTags($this->id);
			$this->tags = $tags;
			$categories = Category::getArticleCategories($this->id);
			$this->categories = $categories;
		}
	}

	public function getTrashedArticles() {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ARTICLE." WHERE status='deleted'";
		$articles = self::executeSql($sql);
		$array = array();
		foreach($articles as $article) {
			$thisArticle = new Article();
			$thisArticle->getArticle('id', $article['id']);
			$thisArticle->setArticleUrl($thisArticle->name);
			$array[] = $thisArticle;
		}
		return $array;
	}

	public function getArticlesWithTag($slug, $limit=NULL, $offset=NULL) {
		$sql = "SELECT articleID FROM ".TABLE_PREFIX.self::TAGS." WHERE slug='$slug'";
		if($limit != NULL) $sql .= " LIMIT $limit";
		if($offset != NULL) $sql .= " OFFSET $offset";
		$articles = self::executeSql($sql);
		$array = array();
		foreach($articles as $article) {
			$thisArticle = new Article();
			$thisArticle->getArticle('id', $article['articleID']);
			$thisArticle->setArticleUrl($thisArticle->name);
			if($thisArticle->published == 'published' && $thisArticle->status == 'active') {
				$array[] = $thisArticle;
			}
		}
		return array_reverse($array);
	}

	public function getArticles($published=TRUE, $order=NULL, $limit=NULL, $offset=NULL) {
		$sql = "SELECT id FROM ".TABLE_PREFIX.self::ARTICLE."";
		if($published == TRUE) $sql .= " WHERE status='active' AND published='published'";
		if($order != NULL) $sql .= " ORDER BY $order DESC";
		if($limit != NULL) $sql .= " LIMIT $limit";
		if($offset != NULL) $sql .= " OFFSET $offset";
		$articles = self::executeSql($sql);
		$array= array();
		foreach($articles as $article) {
			$thisArticle = new Article();
			$thisArticle->getArticle('id', $article['id']);
			$thisArticle->setArticleUrl($thisArticle->name);
			$array[] = $thisArticle;
		}
		return $array;
	}

	public function findByDay($year, $month, $day) {
		$thisArray = array();
		$articles = self::getArticles(TRUE);
		foreach($articles as $article) {
			if(date('Y', $article->datePublished) == $year && date('m', $article->datePublished) == $month && date('d', $article->datePublished) == $day) {
				$thisArray[] = $article;
			}
		}
		return $thisArray;
	}

	public function findByMonth($year, $month) {
		$thisArray = array();
		$articles = self::getArticles(TRUE);
		foreach($articles as $article) {
			if(date('Y', $article->datePublished) == $year && date('m', $article->datePublished) == $month) {
				$thisArray[] = $article;
			}
		}
		return $thisArray;
	}

	public function findByYear($year) {
		$thisArray = array();
		$articles = self::getArticles(TRUE);
		foreach($articles as $article) {
			if(date('Y', $article->datePublished) == $year) {
				$thisArray[] = $article;
			}
		}
		return $thisArray;
	}

	private function setArticleUrl() {
		$newsSettings = Plugin::getAllSettings('news');
		$folder = $newsSettings['folder'];
		$year = date('Y', $this->datePublished);
		$month = date('m', $this->datePublished);
		$day = date('d', $this->datePublished);
		$this->url = BASE_URL . $folder . '/' . $year . '/' . $month .'/'. $day . '/' . $this->slug;
	}

	private function getAuthorInfo() {
		$users = User::findAll();
		foreach($users as $user) {
			if($user->id == $this->author) $this->author = $user;
		}
	}

	public function addArticle($_POST) {
		// Strip nasty characters out of slug and permalink
		$slug = filter_var($_POST['slug'], FILTER_SANITIZE_STRING);
		$slug = self::slugify($slug);
		$permalink = filter_var($_POST['permalink'], FILTER_SANITIZE_STRING);
		$permalink = self::slugify($permalink);
		$permalink = self::getUniqueReference('permalink', $permalink);
		$authorId = AuthUser::getRecord()->id;
		$sql = "INSERT INTO ".TABLE_PREFIX.self::ARTICLE."
					(`id`, `name`, `slug`, `permalink`, `published`, `datePublished`, `author`)
				VALUES
					('', '".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."', '".$slug."', '".$permalink."', 'draft', '".time()."', '".$authorId."');";
		$result = self::executeSql($sql);
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
		$insertID = $this->db->lastInsertId();
		return $insertID;
	}

	public function updateArticle($_POST, $articleID) {
		Tag::updateTags($_POST['tags'], $articleID);
		if(!isset($_POST['categories'])) $_POST['categories'] = array();
		Category::updateCategories($_POST['categories'], $articleID);
		// Strip nasty characters out of slug
		$slug = filter_var($_POST['slug'], FILTER_SANITIZE_STRING);
		$slug = self::slugify($slug);
		foreach($_POST as $key => $value) {
			if($key != 'id' && $key != 'datePublished' && $key != 'tags' && $key != 'slug') {
				$sql = "UPDATE ".TABLE_PREFIX.self::ARTICLE." SET
							".$key."='".filter_var($value, FILTER_SANITIZE_MAGIC_QUOTES)."'
						WHERE id='".$_POST['id']."'";
				self::executeSql($sql);
			}
			if($key == 'slug') {
				$sql = "UPDATE ".TABLE_PREFIX.self::ARTICLE." SET
							slug='".filter_var($slug, FILTER_SANITIZE_STRING)."'
						WHERE id='".$_POST['id']."'";
				self::executeSql($sql);
			}
			if($key == 'datePublished') {
				$date = $value;
				$time = $_POST['hour'] .':'.$_POST['minute']; 
				$time = self::createTimestamp($date, $time);
				$sql = "UPDATE ".TABLE_PREFIX.self::ARTICLE." SET
							datePublished='".filter_var($time, FILTER_SANITIZE_STRING)."'
						WHERE id='".$_POST['id']."'";
				self::executeSql($sql);
			}
		}
	}

	public function updateStatus($_POST) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ARTICLE." SET
					published='".$_POST['published']."'
				WHERE id='".$_POST['id']."'
		";
		self::executeSql($sql);
	}

	public function deleteArticle($id) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ARTICLE." SET
					status='deleted'
				WHERE id='".$id."'";
		self::executeSql($sql);
	}

	public function restore($id) {
		$sql = "UPDATE ".TABLE_PREFIX.self::ARTICLE." SET
					status='active'
				WHERE id='".$id."'";
		self::executeSql($sql);
	}

	private function slugify($input) {
		$input = trim($input);
		$remove  = array( "([\40])" , "([^a-zA-Z0-9-])", "(-{2,})" );
		$replace = array("-", "", "-");
		return strtolower(preg_replace($remove, $replace, $input));
	}

	private function getUniqueReference($key, $value, $id=NULL) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ARTICLE."";
		$sql .= " WHERE $key='$value'";
		$results = self::executeSql($sql);
		$count = count($results);
		if($count > 0) {
			$value = $value . '-';
			$value = self::getUniqueReference($key, $value, $id);
		}
		return $value;
	}

	private function createTimestamp($date, $time) {
		// PHP > 5.2 needed for date_parse()
		$date = date_parse("$date $time");
		$timestamp = mktime($date['hour'], $date['minute'], '00', $date['month'], $date['day'], $date['year']);
		return $timestamp;
	}

}