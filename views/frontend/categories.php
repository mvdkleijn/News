<h1>Categories</h1>

<ul>
<?php
	foreach($categories as $category) {
?>
	<li class="categories">
		<a href="<?php echo BASE_URL . $settings['folder'] . '/categories/' . $category->slug; ?>"><?php echo $category->name; ?></a>
	</li>
<?php
	}
?>
</ul>