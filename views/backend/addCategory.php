<h1>Adding a new Category</h1>

<form action="<?php echo get_url('news/saveCategory'); ?>" method="post">
	<table class="fieldset" id="addCategory">
		<tr>
			<td class="label">Parent Category</td>
			<td class="field">
				<select name="parent">
					<option value="0">-- none --</option>
<?php	foreach($categories as $allCategory) {
			if($allCategory->parent == 0) {
?>
					<option value="<?php echo $allCategory->id; ?>"><?php echo $allCategory->name; ?></option>
<?php 		}
		} ?>
				</select>
			</td>
			<td class="help">If this is a subcategory, select a parent here</td>
		</tr>
		<tr>
			<td class="label">Name</td>
			<td class="field">
				<input name="name" type="text" class="textbox" id="nametoslug" />
			</td>
			<td class="help">A name for this category</td>
		</tr>
		<tr>
			<td class="label">Slug</td>
			<td class="field" id="permalink">
				<input name="slug" type="text" class="textbox" id="slug" />
			</td>
			<td class="help">A slug for this category</td>
		</tr>
		<tr>
			<td class="label">Description</td>
			<td class="field">
				<textarea name="description" id="description"></textarea>
			</td>
			<td class="help">A description of the category</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">
				<input type="submit" value="Add this Category" /> or <a href="<?php echo get_url('news/categories'); ?>">cancel</a>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	setTextAreaToolbar('description', 'tinymce');
</script>
