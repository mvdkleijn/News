<h1>Categories</h1>

<?php

if(count($categories) == 0) { ?>
<p class="noCategoriesYet">You haven't added any categories yet. <a href="<?php echo get_url('news/addCategory'); ?>">Why not get started</a>?</p>
<?php } else { ?>
<table class="index">
	<thead>
		<tr>
			<th>Category Name</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php foreach($categories as $category) { ?>
		<tr class="<?php echo odd_even(); ?>">
			<td><a href="<?php echo get_url('news/category/'.$category->id.''); ?>"><?php echo $category->name; ?></a></td>
			<td><a href="<?php echo get_url('news/deleteCategory/'.$category->id.''); ?>" onclick="return confirm('Are you sure you wish to delete this category?');"><img src="<?php echo URL_PUBLIC; ?>admin/images/icon-remove.gif" /></a></td>
		</tr>
<?php } ?>
	</tbody>
</table>

<?php } ?>