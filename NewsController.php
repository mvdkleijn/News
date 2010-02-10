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

/**
 * Class NewsController
 *
 * Manages interactions between model and view for the news plugin
 *
 * @since News version 0.1
 */


class NewsController extends PluginController {

	public function __construct() {
		$settings = Plugin::getAllSettings('news');
		if(defined('CMS_BACKEND')) {
			$this->setLayout('backend');
			$this->assignToLayout('sidebar', new View('../../plugins/news/views/backend/sidebar'));
			$slug = end(explode('/', $_SERVER['REQUEST_URI']));
			if(Article::countEditors() == 0 && $slug != 'noEditor') {
				redirect(get_url('news/noEditor'));
			}
		} else {
			$layout = Layout::findById($settings['listingLayout']);
			$this->setLayout($layout->name);
		}
	}

	public function index($page=NULL) {
		if(defined('CMS_BACKEND')) {
			$articles = Article::getArticles(FALSE, 'datePublished');
			$this->display('../plugins/news/views/backend/index', array('articles' => $articles));
		} else {
			$settings = Plugin::getAllSettings('news');
			$count = count(Article::getArticles(TRUE));
			if($page == NULL) $page = 1;
			$offset = ($page - 1) * $settings['itemsToDisplay'];
			$articles = Article::getArticles(TRUE, 'datePublished', $settings['itemsToDisplay'], $offset);
			$pagination = self::createPages($count, $page, 'index');
			$this->display('../../plugins/news/views/frontend/index', array('articles' => $articles, 'count' => $count, 'page' => $page, 'pagination' => $pagination));
		}
	}

	private function createPages($count, $page, $context, $tag=NULL, $category=NULL) {
		use_helper('Pagination');
		$pagination = new Pagination;
		$settings = Plugin::getAllSettings('news');
		if($context == 'index') {
			$settings['folder'] = $settings['folder'];
		} elseif($context == 'tags') {
			$settings['folder'] = $settings['folder'] . '/tags/' . $tag;
		} elseif($context == 'categories') {
			$settings['folder'] = $settings['folder'] . '/categories/' . $category;
		}
		if($settings['pagesToDisplay'] == 0) { $settings['pagesToDisplay'] = 10000000000; }
		$paginationSettings['base_url'] = BASE_URL . $settings['folder'] . '/page/';
		$paginationSettings['total_rows'] = $count;
		$paginationSettings['per_page'] = $settings['itemsToDisplay'];
		$paginationSettings['num_links'] = $settings['pagesToDisplay'];
		$paginationSettings['cur_page'] = $page;
		$paginationSettings['cur_tag_open'] = '&nbsp;<span class="page current">';
		$paginationSettings['cur_tag_close'] = '</span>';
		$paginationSettings['num_tag_open'] = '&nbsp;<span class="page">';
		$paginationSettings['num_tag_close'] = '</span>';
		$pagination->initialize($paginationSettings);
		return $pagination;
	}

	public function noEditor() {
		if(defined('CMS_BACKEND')) {
			$this->display('../plugins/news/views/backend/noEditor');
		}
	}

	public function tags($id=NULL, $dud=NULL, $page=NULL) {
		if($id == 'index') {
			$this->display('../../plugins/news/views/frontend/tags', array('tags' => Tag::getNewsTags(NULL, TRUE)));
		} else {
			$settings = Plugin::getAllSettings('news');
			if($page == NULL) $page = 1;
			$offset = ($page - 1) * $settings['itemsToDisplay'];
			$articles = Article::getArticlesWithTag($id, $settings['itemsToDisplay'], $offset);
			if(count($articles) != 0) {
				$count = count(Article::getArticlesWithTag($id));
				$tag = new Tag;
				$tag->getTag('slug', $id);
				$pagination = self::createPages($count, $page, 'tags', $id);
				$this->display('../../plugins/news/views/frontend/tag', array('articles' => $articles, 'tag' => $tag, 'pagination' => $pagination));
			} else {
				redirect(get_url('news/notfound'));
			}
		}
	}

	public function tagcloud() {
		$this->display('../../plugins/news/views/frontend/tagcloud');
	}

	public function settings() {
		if(defined('CMS_BACKEND')) {
			if(AuthUser::hasPermission('administrator,developer')) {
				$layouts = Layout::findAll();
				$this->display('../plugins/news/views/backend/settings', array('settings' => Plugin::getAllSettings('news'), 'layouts' => $layouts));
			}
		}
	}

	public function documentation() {
		if(defined('CMS_BACKEND')) {
			if(AuthUser::hasPermission('administrator,developer')) {
				$this->display('../plugins/news/views/backend/documentation');
			}
		}
	}

	public function trash() {
		if(defined('CMS_BACKEND')) {
			if(AuthUser::hasPermission('administrator,developer')) {
				$this->display('../plugins/news/views/backend/trash', array('articles' => Article::getTrashedArticles(), 'categories' => Category::getTrashedCategories()));
			}
		}
	}

	public function restore($id) {
		if(defined('CMS_BACKEND')) {
			if(AuthUser::hasPermission('administrator,developer')) {
				Article::restore($id);
				Flash::set('success', __('This article has been restored'));
				Observer::notify('news_article_restored', $id);
				redirect(get_url('news'));
			}
		}
	}

	public function saveSettings() {
		if(defined('CMS_BACKEND')) {
			if (AuthUser::hasPermission('administrator,developer')) {
				News::saveSettings($_POST);
				Observer::notify('news_settings_updated');
				Flash::set('success', __('Your settings have been updated'));
				redirect(get_url('news/settings'));
			}
		}
	}

	public function categories($id=NULL, $idTwo=NULL, $dud=NULL, $page=NULL) {
		if(defined('CMS_BACKEND')) {
			if(is_numeric($id)) {
				$category = new Category($id);
				$categories = Category::getAllCategories(TRUE);
				$this->display('../plugins/news/views/backend/category', array('category' => $category, 'allCategories' => $categories));
			} elseif($id == 'add') {
				$categories = Category::getAllCategories(TRUE);
				$this->display('../plugins/news/views/backend/addCategory', array('categories' => $categories));
			} elseif($id == 'save') {
				if($_POST['name'] != '') {
					Category::saveCategory($_POST);
					Flash::set('success', __('This category has been updated'));
					redirect(get_url('news/categories'));
				} else {
					Flash::set('error', __('You need to give this category a name'));
					Observer::notify('news_category_nosave');
					redirect(get_url('news/addCategory'));
				}
			} elseif($id == 'restore' && $idTwo != '') {
				Category::restoreCategory($idTwo);
				Flash::set('success', __('This category has been restored'));
				Observer::notify('news_category_restored', $idTwo);
				redirect(get_url('news/categories'));
			} elseif($id == 'delete' && is_numeric($idTwo)) {
				Category::deleteCategory($idTwo);
				Flash::set('success', __('This category has been deleted'));
				Observer::notify('news_category_deleted', $idTwo);
				redirect(get_url('news/categories'));
			} else {
				$categories = Category::getAllCategories(TRUE);
				$this->display('../plugins/news/views/backend/categories', array('categories' => $categories));
			}
		} else {
			if($id == 'view') {
				$category = Category::getCategoryBySlug($idTwo);
				if(count($category) != 0) {
					$settings = Plugin::getAllSettings('news');
					$count = count(Category::getArticlesInCategory($category->id));
					if($page == NULL) $page = 1;
					$offset = ($page - 1) * $settings['itemsToDisplay'];
					$pagination = self::createPages($count, $page, 'categories', NULL, $idTwo);
					$articlesInCategory = Category::getArticlesInCategory($category->id, 'datePublished', $settings['itemsToDisplay'], $offset);
					if(count($articlesInCategory) > 0) {
						$this->display('../../plugins/news/views/frontend/category', array('category' => $category, 'articlesInCategory' => $articlesInCategory, 'pagination' => $pagination));
					} else {
						redirect(get_url('news/notfound'));
					}
				} else {
					redirect(get_url('news/notfound'));
				}
			} elseif($id == 'index') {
				$settings = Plugin::getAllSettings('news');
				$categories = Category::getAllCategories(TRUE);
				$this->display('../../plugins/news/views/frontend/categories', array('categories' => $categories, 'settings' => $settings));
			}
		}
	}

	public function article($slug, $year=NULL, $month=NULL, $day=NULL) {
		if(defined('CMS_BACKEND')) {
			$id = $slug;
			$article = new Article;
			$article->getArticle('id', $id);
			if(Plugin::isEnabled('Albums')) {
				$image = Albums::getImage($article->imageID);
				$images = Albums::getImages();
				$albums = Albums::getAlbumList('name');
			} else {
				$image = '';
				$images = array();
				$albums = array();
			}
			$users = User::findAll();
			$tags = Tag::getNewsTags($article->id);
			$allTags = Tag::getNewsTags();
			$this->display('../plugins/news/views/backend/article', array('article' => $article, 'image' => $image, 'images' => $images, 'albums' => $albums, 'tags' => $tags, 'allTags' => $allTags, 'users' => $users, 'permissions' => Record::findAllFrom('Permission'), 'allCategories' => Category::getAllCategories(TRUE)));
		} else {
			$settings = Plugin::getAllSettings('news');
			$layout = Layout::findById($settings['articleLayout']);
			$this->setLayout($layout->name);
			if($year != NULL && $month != NULL && $day != NULL && $slug != NULL) {
				$article = new Article;
				$article->getArticle('slug', $slug);
				if($year == date('Y', $article->datePublished) && $month == date('m', $article->datePublished) && $day == date('d', $article->datePublished)) {
					if($article->status == 'active' && $article->published == 'published') {
						$now = time();
						if($now >= $article->datePublished) {
							$categories = Category::getCategory($article->id);
							$this->display('../../plugins/news/views/frontend/article', array('news' => Article::getArticles(TRUE, 'datePublished'), 'article' => $article));
						} else {
							redirect(get_url('news/notfound'));
						}
					}
				}

				redirect(get_url('news/notfound'));
			} else {
				$article = new Article;
				$article->getArticle('permalink', $slug);
				if(count($article) == 1) {
					$now = time();
					if($now >= $article->datePublished && $article->status == 'active') {
						$thisArticleCategories = array();
						$this->display('../../plugins/news/views/frontend/article', array('news' => Article::getArticles(TRUE), 'article' => $article));
					} else {
						redirect(get_url('news/notfound'));
					}
				} else {
					redirect(get_url('news/notfound'));
				}
			}
		}		
	}

	public function viewBy($year=NULL, $month=NULL, $day=NULL) {
		$settings = Plugin::getAllSettings('news');
		$layout = Layout::findById($settings['viewByLayout']);
		$this->setLayout($layout->name);
		$ymd = array('year' => $year, 'month' => $month, 'day' => $day);
		if($year != NULL && $month != NULL && $day != NULL) {
			$articles = Article::findByDay($year, $month, $day);
			$this->display('../../plugins/news/views/frontend/viewby', array('type' => 'day', 'ymd' => $ymd, 'articles' => $articles));
		} elseif ($year != NULL && $month != NULL && $day == NULL) {
			$articles = Article::findByMonth($year, $month);
			$this->display('../../plugins/news/views/frontend/viewby', array('type' => 'month', 'ymd' => $ymd, 'articles' => $articles));
		} elseif($year != NULL && $month == NULL && $day == NULL) {
			$articles = Article::findByYear($year);
			$this->display('../../plugins/news/views/frontend/viewby', array('type' => 'year', 'ymd' => $ymd, 'articles' => $articles));
		}
	}

	public function notfound() {
		$settings = Plugin::getAllSettings('news');
		$layout = Layout::findById($settings['pagenotfoundLayout']);
		$this->setLayout($layout->name);
		header('HTTP/1.0 404 Not Found');
		$this->display('../../plugins/news/views/frontend/notfound');
	}

	public function add() {
		$this->display('../plugins/news/views/backend/add');
	}

	public function addArticle() {
		if($_POST['name'] != '' && $_POST['slug'] != '' && $_POST['permalink'] != '') {
			$newArticleID = Article::addArticle($_POST);
			Flash::set('success', __('This article has been added. You can now give us more information'));
			Observer::notify('news_article_added', $newArticleID);
			redirect(get_url('news/article/'.$newArticleID.''));
		} else {
			Flash::set('error', __('This article has NOT been added. You need to complete all fields!'));
			Observer::notify('news_article_not_added');
			redirect(get_url('news/add'));
		}
	}

	public function delete($id) {
		Observer::notify('news_article_deleted', $id);
		Article::deleteArticle($id);
		Flash::set('success', __('This article has been deleted'));
		redirect(get_url('news'));
	}

	public function permanentDelete($type=NULL, $id=NULL) {
		if($type != NULL && $id != NULL) {
			if($type == 'article') {
				Observer::notify('news_article_permanently_removed', $id);
			} elseif($type == 'category') {
				Observer::notify('news_category_permanent_delete', $id);
			}
			News::permanentDelete($type, $id);
			Flash::set('success', __('This has been permanently removed'));
			redirect(get_url('news/trash'));
		}
	}

	public function updateArticle() {
		Article::updateArticle($_POST, $_POST['id']);
		Flash::set('success', __(''.$_POST['name'].' has been updated'));
		Observer::notify('news_article_updated', $_POST['id']);
		redirect(get_url('news'));
	}

	public function updateStatus() {
		Article::updateStatus($_POST);
		Flash::set('success', __('The article status has been updated'));
		Observer::notify('news_article_status_updated', $_POST['id']);
		redirect(get_url('news'));
	}

	public function viewFeed() {
		$settings = Plugin::getAllSettings('news');
		$layout = Layout::findById($settings['rssLayout']);
		$this->setLayout($layout->name);
		$articles = Article::getArticles(TRUE, 'datePublished');
		$this->display('../../plugins/news/views/frontend/rss', array('allArticles' => $articles));
	}

	public function sidebar() {
		$this->display('../../plugins/news/views/frontend/sidebar');
	}

	public function content($part=FALSE, $inherit=FALSE) {
		$url = $this->url;
		list($parent) = split('/', $url);
		if ($parent == '') { $parent = 'home'; }
		$parent = str_replace(' ', '-',$parent);
		$slug = end(explode('/', $_SERVER['REQUEST_URI']));
		$settings = Plugin::getAllSettings('news');
		$articles = Article::getArticles(TRUE, 'datePublished', $settings['latestNewsCount']);
		if($part == 'sidebar') include('views/frontend/sidebar.php');
		if($part == 'col3') echo '1';
		if(!$part) { return $this->content; }
	}

}