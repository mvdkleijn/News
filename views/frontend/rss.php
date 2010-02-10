<?php
	$settings = Plugin::getAllSettings('news');
?><?php echo '<?'; ?>xml version="1.0" encoding="UTF-8"<?php echo '?>'; ?> 
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<title><?php echo $settings['rssTitle']; ?></title>
	<link><?php echo BASE_URL ?></link>
	<language>en-us</language>
	<copyright>Copyright <?php echo date('Y'); ?>, <?php echo URL_PUBLIC; ?></copyright>
	<pubDate><?php echo strftime('%a, %d %b %Y %H:%M:%S %z'); ?></pubDate>
	<lastBuildDate><?php echo strftime('%a, %d %b %Y %H:%M:%S %z'); ?></lastBuildDate>
	<category>any</category>
	<docs>http://www.rssboard.org/rss-specification</docs>

<?php
	foreach($allArticles as $article) {
?>
	<item>
		<title><?php echo $article->name; ?></title>
		<description><![CDATA[ <?php

		Observer::notify('news_rss_content_start');

		switch($settings['rssContent']) {
			case('both')	 :	echo $article->intro . $article->article;
								break;
			case('intro') 	:	echo $article->intro;
								break;
			case('article') :	echo $article->article;
								break;
			case('none') 	:	break;
		}

		Observer::notify('news_rss_content_end');

		?> ]]></description>
		<pubDate><?php echo date('D, d M Y H:i:s \G\M\T', $article->datePublished); ?></pubDate>
		<link><?php echo $article->url; ?></link>
		<guid><?php echo $article->url; ?></guid>
<?php
	if($settings['rssShowAuthor'] == 'yes') {
?>		<author><?php echo $article->author->name; ?></author>
<?php
	}
?>
	</item>
<?php
	}
?>
</channel>
</rss>