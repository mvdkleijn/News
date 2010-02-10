<h1>Viewing Category - <?php echo $category->name; ?></h1>

<?php echo $category->description; ?>

<p><?php  echo $pagination->createLinks(); ?></p>

<?php

		foreach($articlesInCategory as $article) {
?>
<div class="article">
	<h2><a href="<?php echo $article->url; ?>"><?php echo $article->name; ?></a></h2>
	<p>Published on <?php echo date('jS F Y \a\t H:i', $article->datePublished); ?> by <?php echo $article->author->name; ?></p>
	<?php echo $article->intro; ?>

	<p><a href="<?php echo $article->url; ?>">Read more</a></p>
</div>
<?php	}	?>

<p><?php echo $pagination->createLinks(); ?></p>