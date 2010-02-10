<?php

	$coreRoot = end(explode('/', CORE_ROOT));
	$settings = Plugin::getAllSettings('news');

?>
<div class="box">
	<p class="button"><a href="<?php echo get_url('news'); ?>"><img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/articles.png'; ?>" align="middle" alt="Articles" /> Articles</a></p>
	<p class="button"><a href="<?php echo get_url('news/add'); ?>"><img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/add.png'; ?>" align="middle" alt="Add News" /> Add a new Article</a></p>
<?php if(($settings['editorsAllowedCategories'] == 'yes' && AuthUser::hasPermission('editor'))  || AuthUser::hasPermission('administrator,developer')) { ?>
	<p class="button"><a href="<?php echo get_url('news/categories'); ?>"><img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/categories.png'; ?>" align="middle" alt="Categories" /> Categories</a></p>
	<p class="button"><a href="<?php echo get_url('news/addCategory'); ?>"><img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/addCategory.png'; ?>" align="middle" alt="Add Category" /> Add a new Category</a></p>
<?php } ?>
</div>
<?php if(AuthUser::hasPermission('administrator,developer')) { ?>
<div class="box">
	<p><strong>Administration Options:</strong></p>
	<p class="button"><a href="<?php echo get_url('news/trash'); ?>"><img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/trash.png'; ?>" align="middle" alt="Trash" /> Trash</a></p>
	<p class="button"><a href="<?php echo get_url('news/settings'); ?>"><img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/settings.png'; ?>" align="middle" alt="Settings" /> Settings</a></p>
	<p class="button"><a href="<?php echo get_url('news/documentation'); ?>"><img src="<?php echo URL_PUBLIC . $coreRoot . '/plugins/news/images/documentation.png'; ?>" align="middle" alt="Documentation" /> Documentation</a></p>
</div>
<?php } ?>