
<div class="latestNewsSidebar">
	<h3>Latest News</h3>
	<div class="articles">
<?php
	foreach($articles as $article) {
?>
		<div class="newsArticle<?php if($article->slug == $slug) echo ' current'; ?>">
			<h4><a href="<?php echo $article->url; ?>"><?php echo $article->name; ?></a></h4>
			<?php echo $article->intro; ?>

			<p><small><?php echo date('jS F, Y', $article->datePublished);?></small></p>
		</div>
<?php
	}
?>
	</div>
<?php include('tagcloud.php'); ?>
</div>