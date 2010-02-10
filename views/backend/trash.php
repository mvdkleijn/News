<?php

$coreRoot = end(explode('/', CORE_ROOT)); ?>

<h1>Deleted Articles and Categories</h1>

<h2>Articles</h2>

<?php if(count($articles) == 0) { ?>
<p><em>There are no articles in the trash at the moment</em></p>
<?php
} else { ?>
<table class="index">
	<thead>
		<tr>
			<th>Name</th>
			<th>Publish Date</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
<?php foreach($articles as $article) { ?>
	<tr class="<?php echo odd_even(); ?>">
		<td><a href="<?php echo get_url('news/article/'.$article->id.''); ?>"><?php echo $article->name; ?></a></td>
		<td><?php echo date('l jS F, Y \a\t H:i a', $article->datePublished); ?></td>
		<td style="width:10%">
			<a onclick="return confirm('Are you sure you wish to RESTORE this article?');" href="<?php echo get_url('news/restore/'.$article->id.''); ?>">
				<img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/recover.png'; ?>" title="Recover this Aricle" alt="Recover" />
			</a>
			<a onclick="return confirm('Are you sure you wish to PERMANENTLY DELETE this article?');" href="<?php echo get_url('news/permanentDelete/article/'.$article->id.''); ?>">
				<img src="<?php echo URL_PUBLIC; ?>admin/images/icon-remove.gif" title="Permanently remove this article" alt="Remove Permanantly" />
			</a>
		</td>
	</tr>
<?php } ?>
</table>
<?php } ?>

<h2>Categories</h2>

<?php if(count($categories) == 0) { ?>
<p><em>There are no categories in the trash at the moment</em></p>
<?php
} else { ?>
<table class="index">
	<thead>
		<tr>
			<th>Name</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
<?php foreach($categories as $category) { ?>
	<tr class="<?php echo odd_even(); ?>">
		<td><a href="<?php echo get_url('news/category/'.$category->id.''); ?>"><?php echo $category->name; ?></a></td>
		<td style="width:10%">
			<a onclick="return confirm('Are you sure you wish to RESTORE this category?');" href="<?php echo get_url('news/restoreCategory/'.$category->id.''); ?>">
				<img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/recover.png'; ?>" title="Recover this Category" alt="Recover Category" />
			</a>

			<a onclick="return confirm('Are you sure you wish to PERMANENTLY DELETE this category?');" href="<?php echo get_url('news/permanentDelete/category/'.$category->id.''); ?>">
				<img src="<?php echo URL_PUBLIC; ?>admin/images/icon-remove.gif" title="Permanently remove this Category" alt="Remove Category Permanantly" />
			</a>
		</td>
	</tr>
<?php } ?>
</table>
<?php } ?>