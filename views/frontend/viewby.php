<?php

	switch($type) {
		case('year') :	$newsFor = "in " . $ymd['year'];
						break;
		case('month') :	$time = strtotime($ymd['year'].'-'.$ymd['month']);
						$newsFor = "in " . date('F Y', $time);
						break;
		case('day') :	$time = strtotime($ymd['year'].'-'.$ymd['month'].'-'.$ymd['day']);
						$newsFor = "on " . date('jS F, Y', $time);
						break;
	}

?>

<h1>News Articles published <?php echo $newsFor; ?></h1>

<?php
		foreach($articles as $article) {
?>
<div class="article">
	<h2><a href="<?php echo $article->url; ?>"><?php echo $article->name; ?></a></h2>
	<p>Published on <?php echo date('jS F Y \a\t H:i', $article->datePublished); ?> by <?php echo $article->author->name; ?></p>
	<?php echo $article->intro; ?>

	<p><a href="<?php echo $article->url; ?>">Read more</a></p>
</div>
<?php	}	?>