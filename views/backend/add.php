<h1>You're adding a new Article to the News Section</h1>

<form action="<?php echo get_url('news/addArticle'); ?>" method="POST">

	<table class="index" id="addNews">
		<tr>
			<td class="label">Title</td>
			<td class="field"><input class="textbox" type="text" name="name" id="nametoslug" /></td>
			<td class="help">The news article title</td>
		</tr>
		<tr>
			<td class="label">Slug</td>
			<td class="field"><input class="textbox" type="text" name="slug" id="slug" /></td>
			<td class="help">The last part of the URL for this article</td>
		</tr>
		<tr>
			<td class="label">Permalink</td>
			<td class="field"><input class="textbox" type="text" name="permalink" id="permalink" /></td>
			<td class="help">This cannot be edited once this page is added!</td>
		</tr>
		<tr>
			<td class="label">&nbsp;</td>
			<td class="field" colspan="2"><input type="submit" value="Add this article!" /> or <a href="<?php echo get_url('news'); ?>">cancel</a> <strong><small>(It won't be published until you're ready!)</small></strong></td>
		</tr>
	</table>

</form>