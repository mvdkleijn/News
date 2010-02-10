<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            $("#currentTags").tagTo("#tags");
        });    
    })(jQuery);
</script>


<h1>Editing News Article</h1>

<p><small><a href="<?php echo get_url('news'); ?>">&laquo; Back to News</a></small></p>

<?php	if($article->status == 'deleted')	{
			if(AuthUser::hasPermission('administrator,developer')) {
				$messgae = 'you will need to <a onclick="return confirm(\'Are you sure you wish to RESTORE this news article?\');" href="'.get_url('news/restore/'.$article->id.'').'">restore</a> it in order for it to be available again';
			} else {
				$messgae = 'you will need to ask an Administrator to restore it';
			}
?>
<p class="deletedArticle">This article has been deleted - <?php echo $messgae; ?></p>
<?php	} ?>

<form method="post" enctype="multipart/form-data" action="<?php echo get_url('news/updateArticle'); ?>">

	<input type="hidden" name="id" value="<?php echo $article->id; ?>" />

	<table class="fieldset" id="editNews" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="title" colspan="3">General</td>
		</tr>
		<tr>
			<td class="label">Name</td>
			<td class="field"><input type="text" name="name" id="nametoslug" class="textbox" value="<?php echo $article->name; ?>" /></td>
			<td class="help">News Article Name</td>
		</tr>
		<tr>
			<td class="label">Slug</td>
			<td class="field"><input type="text" name="slug" id="slug" class="textbox" value="<?php echo $article->slug; ?>" /></td>
			<td class="help">The slug is the last part of the URL in the frontend</td>
		</tr>
		<tr>
			<td class="label">Permalink</td>
			<td class="field"><small>
								<a id="permalink" href="<?php echo URL_PUBLIC; if(USE_MOD_REWRITE == FALSE) echo '?'; ?>permalink/news/<?php echo $article->permalink; ?>" target="_blank">
										 <?php echo URL_PUBLIC; if(USE_MOD_REWRITE == FALSE) echo '?'; ?>permalink/news/<?php echo $article->permalink; ?></a></small></td>
			<td class="help">This is a permanent link to this article for the outside world</td>
		</tr>
		<tr>
			<td class="label">Categories</td>
			<td class="field">
				<?php

foreach($allCategories as $category) {
	if($category->parent == 0) {
		$selected = '';
		foreach($article->categories as $articleCategory) {
			if($category->id == $articleCategory->id) {
				$selected = ' checked="checked"';
			}
		}
?>
<input type="checkbox" name="categories[]" value="<?php echo $category->id; ?>"<?php echo $selected; ?>> <?php echo $category->name; ?><br />
<?php
		foreach($allCategories as $subCategory) {
			$selected = '';
			if($subCategory->parent == $category->id) {
				foreach($article->categories as $articleCategory) {
					if($subCategory->id == $articleCategory->id) {
						$selected = ' checked="checked"';
					}
				}
?>
	<span class="subFiller">&#124;&#8212;&nbsp;</span><input type="checkbox" name="categories[]" value="<?php echo $subCategory->id; ?>"<?php echo $selected; ?> /> <small><?php echo $subCategory->name; ?></small><br />
<?php
			}
		}
	}
}
?>
			</td>
			<td class="help">You can add this article to a category, which helps join up your articles (note: different to Tagging!)</td>
		</tr>
		<tr>
			<td class="label">Tags</td>
			<td class="field">

			<input type="text" name="tags" id="tags" class="textbox" value="<?php
				$i = 1;
				$thisTagsArray = array();
				foreach($article->tags as $tag) {
					$thisTagsArray[] = $tag->tag;
					echo $tag->tag;
					if($i < count($article->tags)) echo ', ';
					$i++;
				}
			?>" />
			<div id="currentTags">
				<p><small><strong>Recommended Tags:</strong></small></p>
			<?php

				$usedTags = array();
				foreach($allTags as $tag) {
					$selected = '';
					if(!in_array($tag->tag, $usedTags)) {
						$usedTags[] = $tag->tag;
						if(in_array($tag->tag, $thisTagsArray)) {
							$selected = ' class="selected"';
						}
			?>
						<a href="#"<?php echo $selected; ?>><?php echo $tag->tag; ?></a> 
			<?php	}
				} ?>
			</div>
			</td>
			<td class="help">Tags for this article <strong>(comma separated)</strong></td>
		</tr>
		<tr>
			<td class="title" colspan="3">Content</td>
		</tr>
<?php	if(Plugin::isEnabled('albums')) { ?>
		<tr>
			<td class="label">Image</td>
			<td class="field">
			<?php if($article->imageID != NULL && $article->imageID != 0) { ?>
				<img src="<?php echo Albums::urlToImage($article->imageID, 385); ?>" /><br />
			<?php } ?>
				<select name="imageID">
					<option value="NULL">-- SELECT ONE --</option>
					<?php

						foreach($albums as $album) {
							foreach($images as $imageList) {
								if($imageList['album'] == $album['id']) {
									$selected = '';
									if($image[0]['id'] == $imageList['id']) $selected = ' selected="selected"';
									echo '<option value="'.$imageList['id'].'"'.$selected.'>'.$album['name'].' -> '.$imageList['name'].'</option>';
								}
							}
						}

					?>
				</select>
			</td>
			<td class="help">You need to set an image for each article. You can pick an image from any album.</a></td>
		</tr>
<?php	}  ?>
		<tr>
			<td class="label">Intro</td>
			<td class="field"><textarea name="intro" id="intro" class="textbox" style="height:50px"><?php echo $article->intro; ?></textarea></td>
			<td class="help">This will appear where news articles are highlighted and the RSS feed</td>
		</tr>
		<tr>
			<td class="label">Article</td>
			<td class="field"><textarea name="article" id="article" class="textbox" style="height:400px"><?php echo $article->article; ?></textarea></td>
			<td class="help">This is a continuation of the intro.<br /><strong>Please don't duplicate the intro content here</strong></td>
		</tr>
		<tr>
			<td class="title" colspan="3">Publishing</td>
		</tr>
		<tr>
			<td class="label">Author</td>
			<td class="field">
				<select name="author">
<?php	foreach ($permissions as $permission) {
			$excludedPermissions = array(1, 2);
			if(!in_array($permission->id, $excludedPermissions)) {
?>
					<optgroup label="<?php echo $permission->name; ?>"> 
<?php		foreach($users as $user) {
					$user_permissions = ($user instanceof User) ? $user->getPermissions(): array();
					if (in_array($permission->name, $user_permissions)) {
	
?>
						<option value="<?php echo $user->id; ?>"<?php if($user->id == $article->author->id) echo ' selected="selected"'; ?>><?php echo $user->name; ?></option>
<?php
		 			}
				}
?>
					</optgroup>
<?php 		}
		} ?>
				</select>
			</td>
			<td class="help">Who should be credited with authorship?<strong>They must be an editor to be credited!</strong></td>
		</tr>
		<tr>
			<td class="label">Publish Date</td>
			<td class="field">
				<input onclick="displayDatePicker('datePublished');" id="datePublished" maxlength="10" name="datePublished" class="textbox" style="width:90px;" size="10" type="text" value="<?php echo date('Y-m-d', $article->datePublished); ?>" /> 
				<img onclick="displayDatePicker('datePublished');" src="<?php echo URL_PUBLIC;?>admin/images/icon_cal.gif" alt="Calendar" />
				<select name="hour">
					<?php

						$hour = -1;
						do {
							$hour++;
							if($hour < 10) {
								$hour = '0' . $hour;
							}
							if($hour == date('H', $article->datePublished)) {
								$selected = ' selected="selected"';
							}
							else {
								$selected = '';
							}
							echo '<option value="'.$hour.'"'.$selected.'>'.$hour.'</option>';
						}
						while($hour <= 23);

					?>
				</select> : 
				<select name="minute">
					<?php

						$minute = -1;
						do {
							$minute++;
							if($minute < 10) {
								$minute = '0' . $minute;
							}
							if($minute == date('i', $article->datePublished)) {
								$selected = ' selected="selected"';
							}
							else {
								$selected = '';
							}
							echo '<option value="'.$minute.'"'.$selected.'>'.$minute.'</option>';
						}
						while($minute <= 59);

					?>
				</select>
			</td>
			<td class="help">If you're setting a future publishing date, this article will not be available in the frontend other than through the permalink reference above</td>
		</tr>
		<tr>
			<td class="label">Published</td>
			<td class="field">
				<select name="published">
					<option value="published"<?php if($article->published == 'published') { echo ' selected="selected"'; } ?>>Published</option>
					<option value="pending"<?php if($article->published == 'pending') { echo ' selected="selected"'; } ?>>Pending Review</option>
					<option value="draft"<?php if($article->published == 'draft') { echo ' selected="selected"'; } ?>>Draft</option>
				</select>
			</td>
			<td class="help">Should we publish this article to the site? <strong>If set to "no", the publish date above will be ignored</strong></td>
		</tr>
		<tr>
			<td class="label">&nbsp;</td>
			<td class="field" colspan="2">
				<input type="submit" value="Save Article" /> or <a href="<?php echo get_url('news'); ?>">cancel changes</a>
			</td>
		</tr>
	</table>
</form>
<?php	if(Plugin::isEnabled('tinymce')) { ?>
<script type="text/javascript">
	setTextAreaToolbar('intro', 'tinymce');
	setTextAreaToolbar('article', 'tinymce');
</script>
<?php	} ?>