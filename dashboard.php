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
 * Dashboard
 *
 * Provides a set of observer listeners that then post into Mike Tuupola's Dashboard Plugin
 * http://www.appelsiini.net/projects/dashboard
 *
 * @since News version 0.9
 */


Observer::observe('news_settings_updated', 'news_settings_updated');

Observer::observe('news_article_added', 'news_article_added');
Observer::observe('news_article_not_added', 'news_article_not_added');
Observer::observe('news_article_deleted', 'news_article_deleted');
Observer::observe('news_article_restored', 'news_article_restored');
Observer::observe('news_article_permanently_removed', 'news_article_permanently_removed');
Observer::observe('news_article_updated', 'news_article_updated');
Observer::observe('news_article_status_updated', 'news_article_status_updated');

Observer::observe('news_category_added', 'news_category_added');
Observer::observe('news_category_nosave', 'news_category_nosave');
Observer::observe('news_category_saved', 'news_category_saved');
Observer::observe('news_category_restored', 'news_category_restored');
Observer::observe('news_category_deleted', 'news_category_deleted');
Observer::observe('news_category_permanent_delete', 'news_category_permanent_delete');


// Settings

function news_settings_updated() {
	$message = "The Settings for the News Plugin were updated by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 6);
}


// News Articles

function news_article_added($articleID) {
	$article = new Article;
	$article->getArticle('id', $articleID);
	$message = "News Article <a href=\"".get_url('news/article/'.$article->id)."\">".$article->name."</a> was created by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 6);
}

function news_article_not_added() {
	$message = "".AuthUser::getRecord()->name." created an article, but it didn't have enough information to save!";
	dashboard_log_event($message, 'news', 3);
}

function news_article_deleted($articleID) {
	$article = new Article;
	$article->getArticle('id', $articleID);
	$message = "The article <a href=\"".get_url('news/article/'.$article->id)."\">".$article->name."</a> was deleted by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 5);
}

function news_article_restored($articleID) {
	$article = new Article;
	$article->getArticle('id', $articleID);
	$message = "".AuthUser::getRecord()->name." restored the article <a href=\"".get_url('news/article/'.$article->id)."\">".$article->name."</a>";
	dashboard_log_event($message, 'news', 6);
}

function news_article_permanently_removed($articleID) {
	$article = new Article;
	$article->getArticle('id', $articleID);
	$message = "The news article <strong>".$article->name."</strong> was <strong>permanently removed</strong> by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 5);
}

function news_article_updated($articleID) {
	$article = new Article;
	$article->getArticle('id', $articleID);
	$message = "News Article <a href=\"".get_url('news/article/'.$article->id)."\">".$article->name."</a> was updated by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 5);
}

function news_article_status_updated($articleID) {
	$article = new Article;
	$article->getArticle('id', $articleID);
	$message = "The status of the article <a href=\"".get_url('news/article/'.$article->id)."\">".$article->name."</a> was set to <strong>".$article->published."</strong> by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 6);
}


// Categories

function news_category_added($categoryID) {
	$category = new Category($categoryID);
	$message = "A new category, <a href=\"".get_url('news/category/'.$category->id)."\">".$category->name."</a> was added by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 6);
}

function news_category_nosave() {
	$message = "".AuthUser::getRecord()->name." added a category, but there wasn't enought information to save it!";
	dashboard_log_event($message, 'news', 3);
}

function news_category_saved($categoryID) {
	$category = new Category($categoryID);
	$message = "The category <a href=\"".get_url('news/category/'.$category->id)."\">".$category->name."</a> was updated by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 5);
}

function news_category_restored($categoryID) {
	$category = new Category($categoryID);
	$message = "".AuthUser::getRecord()->name." restored the <a href=\"".get_url('news/category/'.$category->id)."\">".$category->name."</a> category ";
	dashboard_log_event($message, 'news', 5);
}

function news_category_deleted($categoryID) {
	$category = new Category($categoryID);
	$message = "".AuthUser::getRecord()->name." removed the <a href=\"".get_url('news/category/'.$category->id)."\">".$category->name."</a> category";
	dashboard_log_event($message, 'news', 5);
}

function news_category_permanent_delete($categoryID) {
	$category = new Category($categoryID);
	$message = "The category <strong>".$category->name."</strong> was <strong>permanently removed</strong> by ".AuthUser::getRecord()->name."";
	dashboard_log_event($message, 'news', 5);
}
