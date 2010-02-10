<?php
	$settings = Plugin::getAllSettings('news');
?>
<?php Observer::notify('news_article_view_start', $article->id); ?>
<h1><?php echo $article->name; ?></h1>
<p>Published on <?php echo date('jS F, Y', $article->datePublished); ?> by <?php echo $article->author->name; ?><br />
	Category:
	<?php	if(count($article->categories) == 0) {
				echo '<em>none</em>';
			} else {
		
				foreach($article->categories as $category) {
	?>
		<a href="<?php echo BASE_URL . $settings['folder'] .'/categories/'; ?><?php echo $category->slug; ?>"><?php echo $category->name; ?></a>
	<?php
				}
			}
	?><br />
	Tags:
	<?php
			if(count($article->tags) == 0) {
				echo '<em>none</em>';
			} else {
			
				$i = 1;
				foreach($article->tags as $tag) {?>
		<a href="<?php echo $tag->url; ?>"><?php echo $tag->tag; ?></a><?php if($i < count($article->tags)) { echo ','; } ?>
	<?php
					$i++;
				}
			}
	?></p>
<?php echo $article->intro; ?>
<?php if($article->imageID != NULL && $article->imageID != 0) {
?>
<img src="<?php echo Albums::urlToImage($article->imageID, $settings['fullImageWidth']); ?>" alt="<?php echo $article->name; ?>" />
<p>&nbsp;</p>
<?php } ?>
<?php echo $article->article; ?>
<?php Observer::notify('news_article_view_end', $article->id); ?>