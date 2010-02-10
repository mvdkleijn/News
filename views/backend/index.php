<script type="text/javascript">
	function dropdown(sel){ 
		c = confirm('You are about to change the publish status.\n\nAre you sure you want to do this?');
		if(c) { sel.form.submit(); } else { sel.selectedIndex = 0; } 
	} 
</script>

<h1>News</h1>

<?php
	if(count($articles) == 0) { 
?>
<p class="noArticlesYet">You haven't added any articles yet. <a href="<?php echo get_url('news/add'); ?>">Why not get started</a>?</p>
<?php
	} else {
?>
<table class="index" id="news" border="0">
	<thead>
		<tr>
			<th>Title</th>
			<th>Author</th>
			<th>Tags</th>
			<th>Categories</th>
			<th>Publish Date</th>
			<th>Status</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
<?php
	foreach($articles as $article) {
		if($article->status == 'active') {
?>
	<tr class="<?php echo odd_even() . ' ' . $article->published; ?>">
		<td width="20%"><a href="<?php echo get_url('news/article/'.$article->id.''); ?>"><?php echo $article->name; ?></a></td>
		<td width="10%"><small>
		<?php if(AuthUser::hasPermission('administrator,developer')) { ?>
			<a href="<?php echo get_url('user/edit/'.$article->author->id.''); ?>"><?php echo $article->author->name; ?></a>
		<?php } else { ?>
			<?php echo $article->author->name; ?>
		<?php } ?></small>
		</td>
		<td width="10%"><small><?php
				$i = 1;
				foreach($article->tags as $tag) {
					echo $tag->tag;
					if($i < count($article->tags)) echo ', ';
					$i++;
				}
?></small></td>
		<td width="10%">
			<small>
			<?php
					$i = 1;
					foreach($article->categories as $category) {
			?>
				<?php
						echo $category->name;
						if($i != count($article->categories)) echo ', ';
						$i++;
				?>
			<?php
					}
			?></small>
		</td>
		<td width="40%">
			<small>
				<?php echo date('l jS F, Y \a\t H:i a', $article->datePublished); ?>
			</small>
		</td>
		<td width="10%">
			<form method="post" action="<?php echo get_url('news/updateStatus'); ?>">
				<input type="hidden" name="id" value="<?php echo $article->id; ?>" />
				<select name="published" onchange="return dropdown(this)">
					<option value="published"<?php if($article->published == 'published') { echo ' selected="selected"'; } ?>>Published</option>
					<option value="pending"<?php if($article->published == 'pending') { echo ' selected="selected"'; } ?>>Pending Review</option>
					<option value="draft"<?php if($article->published == 'draft') { echo ' selected="selected"'; } ?>>Draft</option>
				</select>
			</form>
		</td>
		<td width="5%"><a href="<?php echo get_url('news/delete/'.$article->id.''); ?>" onclick="return confirm('Are you sure you wish to delete this news article?');"><img src="<?php echo URL_PUBLIC; ?>admin/images/icon-remove.gif" /></a></td>
		
	</tr>
<?php	}
	} ?>
</table>
<?php
	}
?>