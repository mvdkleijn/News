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
 * Class Category
 *
 * Allows CRUD access to categories and associations between articles and categories
 *
 * @since News version 0.3
 */


class Category {

	const CATEGORY		= "news_categories";
	const ASSIGNMENTS	= "news_category_assignment";

	public $id;
	public $name;
	public $slug;
	public $parent;
	public $description;
	public $status;

	public function __construct($id=NULL) {
		if($id != NULL) {
			self::getCategory($id);
		}
	}

	private function executeSql($sql) {
		global $__CMS_CONN__;
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCategory($id=NULL) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::CATEGORY."";
		if($id != NULL) $sql .= " WHERE id='$id'";
		$category = self::executeSql($sql);
		if(count($category) != 0) {
			foreach($category[0] as $article => $value) {
				$this->$article = $value;
			}
		}
	}

	public function getTrashedCategories() {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::CATEGORY." WHERE status='deleted'";
		$categories = self::executeSql($sql);
		$array = array();
		if(count($categories) != 0) {
			foreach($categories as $category) {
				$thisCategory = new Category;
				$thisCategory->getCategory($category['id']);
				$array[] = $thisCategory;
			}
		}
		return $array;
	}

	public function getAllCategories($active=FALSE) {
		$sql = "SELECT id FROM ".TABLE_PREFIX.self::CATEGORY."";
		if($active == TRUE) $sql .= " WHERE status='active'";
		$categories = self::executeSql($sql);
		$array = array();
		foreach($categories as $category) {
			$thisCategory = new Category;
			$thisCategory->getCategory($category['id']);
			$array[] = $thisCategory;
		}
		return $array;
	}

	public function getArticleCategories($articleID) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ASSIGNMENTS."";
		if($articleID != NULL) $sql .= " WHERE article='$articleID'";
		$categories = self::executeSql($sql);
		$array = array();
		foreach($categories as $category) {
			$thisCategory = new Category;
			$thisCategory->getCategory($category['category']);
			$array[] = $thisCategory;
		}
		return $array;
	}

	public function getCategoryBySlug($slug) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::CATEGORY."";
		if($slug) $sql .= " WHERE slug='$slug'";
		$result = self::executeSql($sql);
		$category = new Category;
		$category->getCategory($result[0]['id']);
		return $category;
	}

	public function getArticlesInCategory($id, $order=NULL, $limit=NULL, $offset=NULL) {
		$sql = "SELECT * FROM ".TABLE_PREFIX.self::ASSIGNMENTS."";
		$sql .= " WHERE category='$id'";
		if($limit != NULL) $sql .= " LIMIT $limit";
		if($offset != NULL) $sql .= " OFFSET $offset";
		$results = self::executeSql($sql);
		foreach($results as $result) {
			$articleArray[] = $result['article'];
		}
		$articles = Article::getArticles(TRUE, $order);
		foreach($articles as $article) {
			if(in_array($article->id, $articleArray)) {
				$finalArray[] = $article;
			}
		}
		return $finalArray;
	}

	public function updateCategories($categories, $id) {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::ASSIGNMENTS." WHERE article='$id'";
		self::executeSql($sql);
		foreach($categories as $key => $value) {
			$sql = "INSERT INTO ".TABLE_PREFIX.self::ASSIGNMENTS."
						(`article`, `category`)
					VALUES
						('".filter_var($id, FILTER_SANITIZE_STRING)."', '".filter_var($value, FILTER_SANITIZE_STRING)."');";
			self::executeSql($sql);
		}
	}

	public function saveCategory($_POST) {
		if(isset($_POST['id'])) {
			$sql = "UPDATE ".TABLE_PREFIX.self::CATEGORY." SET
						parent='".filter_var($_POST['parent'], FILTER_SANITIZE_STRING)."',
						name='".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."',
						slug='".filter_var($_POST['slug'], FILTER_SANITIZE_STRING)."',
						description='".filter_var($_POST['description'], FILTER_SANITIZE_MAGIC_QUOTES)."'
					WHERE id='".$_POST['id']."'";
			self::executeSql($sql);
			Observer::notify('news_category_saved', $_POST['id']);
		} else {
			$sql = "INSERT INTO ".TABLE_PREFIX.self::CATEGORY."
						(`id`, `parent`, `name`, `slug`, `description`, `status`)
					VALUES
						('', '".filter_var($_POST['parent'], FILTER_SANITIZE_STRING)."', '".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."', '".filter_var($_POST['slug'], FILTER_SANITIZE_STRING)."', '".filter_var($_POST['description'], FILTER_SANITIZE_MAGIC_QUOTES)."', 'active');";
			$result = self::executeSql($sql);
			global $__CMS_CONN__;
			$this->db = $__CMS_CONN__;
			$insertID = $this->db->lastInsertId();
			Observer::notify('news_category_added', $insertID);
		}
	}

	public function deleteCategory($id) {
		self::removeReferencesToCategory($id);
		$sql = "UPDATE ".TABLE_PREFIX.self::CATEGORY." SET
					status='deleted'
				WHERE id='".$id."'";
		self::executeSql($sql);
	}

	public function restoreCategory($id) {
		$sql = "UPDATE ".TABLE_PREFIX.self::CATEGORY." SET
					status='active'
				WHERE id='".$id."'";
		self::executeSql($sql);
	}

	public function removeReferencesToCategory($id) {
		$sql = "DELETE FROM ".TABLE_PREFIX.self::ASSIGNMENTS." WHERE category='$id'";
		self::executeSql($sql);
	}

}