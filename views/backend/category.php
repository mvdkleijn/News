<h1>Editing Category</h1>

<?php

	if($category->status == 'deleted')	{
			if(AuthUser::hasPermission('administrator,developer')) {
				$message = 'you will need to <a onclick="return confirm(\'Are you sure you wish to RESTORE this category?\');" href="'.get_url('news/restoreCategory/'.$category->id.'').'">restore</a> it in order for it to be available again';
			} else {
				$message = 'you will need to ask an Administrator to restore it';
			}
?>
<p class="deletedCategory">This category has been deleted - <?php echo $message; ?></p>
<?php	}	?>
<form action="<?php echo get_url('news/saveCategory'); ?>" method="post">
<input type="hidden" name="id" value="<?php echo $category->id; ?>" />
	<table class="fieldset" id="addCategory">
		<tr>
			<td class="label">Parent Category</td>
			<td class="field">
				<select name="parent">
					<option value="0">-- none --</option>
<?php	foreach($allCategories as $allCategory) {
			$selected = '';
			if($category->parent == $allCategory->id) $selected = ' selected="selected"';
			if($category->id != $allCategory->id && $allCategory->parent == 0) {
?>
					<option value="<?php echo $allCategory->id; ?>"<?php echo $selected; ?>><?php echo $allCategory->name; ?></option>
<?php 		}
		} ?>
				</select>
			</td>
			<td class="help">If this is a subcategory, select a parent here</td>
		</tr>
		<tr>
			<td class="label">Name</td>
			<td class="field">
				<input name="name" type="text" class="textbox" value="<?php echo $category->name; ?>" id="nametoslug" />
			</td>
			<td class="help">A name for this category</td>
		</tr>
		<tr>
			<td class="label">Slug</td>
			<td class="field" id="permalink">
				<input name="slug" type="text" class="textbox" value="<?php echo $category->slug; ?>" id="slug" />
			</td>
			<td class="help">A slug for this category</td>
		</tr>
		<tr>
			<td class="label">Description</td>
			<td class="field">
				<textarea name="description" id="description"><?php echo $category->description; ?></textarea>
			</td>
			<td class="help">A description of the category</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">
				<input type="submit" value="Update this Category" /> or <a href="<?php echo get_url('news/categories'); ?>">cancel</a>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">
	setTextAreaToolbar('description', 'tinymce');
</script>
