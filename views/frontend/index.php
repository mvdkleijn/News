<h1>News</h1>

<p><?php echo $pagination->createLinks(); ?></p>

<?php
	foreach($articles as $article) {
?>
	<div class="article">
		<h2><a href="<?php echo $article->url; ?>"><?php echo $article->name; ?></a></h2>
		<p><strong><?php echo date('jS F, Y', $article->datePublished); ?></strong></p>
<?php
	if($article->imageID != NULL && $article->imageID != 0) {
?>
		<img src="<?php echo Albums::urlToImage($article->imageID, $settings->shortImageWidth); ?>" alt="<?php echo $article->name; ?>" />
<?php
	}
?>
		<?php echo $article->intro; ?>

		<p><a href="<?php echo $article->url; ?>">Read the rest of this article</a></p>
	</div>
<?php
	}
?>

<p><?php echo $pagination->createLinks(); ?></p>