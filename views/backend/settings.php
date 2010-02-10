<h1>News Settings</h1>

<form method="post" action="<?php echo get_url('news/saveSettings'); ?>">
	<table class="fieldset" id="settings">
		<tr>
			<td class="title" colspan="3">General</td>
		</tr>
		<tr>
			<td class="label">Folder</td>
			<td class="field"><input type="text" name="folder" class="textbox" value="<?php echo $settings['folder']; ?>" /></td>
			<td class="help">You can customise the name of the folder you're using here. For example, you may want "blog", "news" or something similar. It's probably not a good idea to change this once you're in production site as it will <strong>break permalinks</strong></td>
		</tr>
		<tr>
			<td class="label">Editors access Categories</td>
			<td class="field">
				<select name="editorsAllowedCategories">
					<option value="yes"<?php if($settings['editorsAllowedCategories'] == 'yes') echo ' selected="selected"'; ?>>Yes</option>
					<option value="no"<?php if($settings['editorsAllowedCategories'] == 'no') echo ' selected="selected"'; ?>>No</option>
				</select>
			</td>
			<td class="help">Should editors be allowed to create and edit categories?</td>
		</tr>
		<tr>
			<td class="label">Latest News Count</td>
			<td class="field">
				<select name="latestNewsCount">
					<?php
						$i = 1;
						while($i <= 10) {
					?>
						<option value="<?php echo $i; ?>"<?php if($i == $settings['latestNewsCount']) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
					<?php
							$i = $i + 1;
						}
					?>
				</select>
			</td>
			<td class="help">How many articles should be in the sidebar?</td>
		</tr>
		<tr>
			<td class="label">Items to Display</td>
			<td class="field">
				<select name="itemsToDisplay">
					<option value="0">All</option>
					<?php
						$i = 1;
						while($i <= 20) {
					?>
						<option value="<?php echo $i; ?>"<?php if($i == $settings['itemsToDisplay']) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
					<?php
							$i = $i + 1;
						}
					?>
				</select>
			</td>
			<td class="help">On listing pages, how many items should be shown per page?</td>
		</tr>
		<tr>
			<td class="label">Pages to Display</td>
			<td class="field">
				<select name="pagesToDisplay">
					<option value="0">All</option>
					<?php
						$i = 1;
						while($i <= 20) {
					?>
						<option value="<?php echo $i; ?>"<?php if($i == $settings['pagesToDisplay']) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
					<?php
							$i = $i + 1;
						}
					?>
				</select>
			</td>
			<td class="help">On listing pages, how many pages should we display on each side of the current page in the pagination?</td>
		</tr>
<?php	if(Plugin::isEnabled('albums')) { ?>
		<tr>
			<td class="label">Full Image Width</td>
			<td class="field"><input type="text" name="fullImageWidth" class="smallText" value="<?php echo $settings['fullImageWidth']; ?>" /> px</td>
			<td class="help">Number of pixels wide the main article page should display images from the Albums Plugin at</td>
		</tr>
		<tr>
			<td class="label">Listing Image Width</td>
			<td class="field"><input type="text" name="shortImageWidth" class="smallText" value="<?php echo $settings['shortImageWidth']; ?>" /> px</td>
			<td class="help">Number of pixels wide the image on the Article listing pages should be at</td>
		</tr>
<?php	} ?>
		<tr>
			<td class="title" colspan="3">RSS Output</td>
		</tr>
		<tr>
			<td class="label">Title</td>
			<td class="field"><input type="text" name="rssTitle" class="textbox" value="<?php echo $settings['rssTitle']; ?>" /></td>
			<td class="help">The name of the feed, often displayed in RSS readers</td>
		</tr>
		<tr>
			<td class="label">Show Author in RSS Feed</td>
			<td class="field">
				<select name="rssShowAuthor">
					<option value="yes"<?php if($settings['rssShowAuthor'] == 'yes') echo ' selected="selected"'; ?>>Yes</option>
					<option value="no"<?php if($settings['rssShowAuthor'] == 'no') echo ' selected="selected"'; ?>>No</option>
				</select>
			</td>
			<td class="help">You can choose to add the authors name to the RSS feed</td>
		</tr>
		<tr>
			<td class="label">What to show in RSS Content</td>
			<td class="field">
				<select name="rssContent">
					<option value="intro"<?php if($settings['rssContent'] == 'intro') echo ' selected="selected"'; ?>>Intro</option>
					<option value="article"<?php if($settings['rssContent'] == 'article') echo ' selected="selected"'; ?>>Article</option>
					<option value="both"<?php if($settings['rssContent'] == 'both') echo ' selected="selected"'; ?>>Intro and Article</option>
					<option value="none"<?php if($settings['rssContent'] == 'none') echo ' selected="selected"'; ?>>none</option>
				</select>
			</td>
			<td class="help">A bit of customisation for the content. There are also <strong>two observers</strong> you can hook into to display additional content. Please see the <a href="<?php echo get_url('news/documentation'); ?>">documentation</a> for more information</td>
		</tr>
		<tr>
			<td class="title" colspan="3">Layouts</td>
		</tr>
		<tr>
			<td class="label">News Listing Template</td>
			<td class="field">
				<select name="listingLayout">
				<?php
				foreach($layouts as $layout) {
					$selected = '';
					if($settings['listingLayout'] == $layout->id) $selected = ' selected="selected"';
				?>
					<option value="<?php echo $layout->id; ?>"<?php echo $selected; ?>><?php echo $layout->name; ?></option>
				<?php
				}
				?>
				</select>
			</td>
			<td class="help">Select the <a href="<?php echo get_url('layout'); ?>">layout</a> to use for main <strong>Listing</strong> page</td>
		</tr>
		<tr>
			<td class="label">News Article Template</td>
			<td class="field">
				<select name="articleLayout">
				<?php
				foreach($layouts as $layout) {
					$selected = '';
					if($settings['articleLayout'] == $layout->id) $selected = ' selected="selected"';
				?>
					<option value="<?php echo $layout->id; ?>"<?php echo $selected; ?>><?php echo $layout->name; ?></option>
				<?php
				}
				?>
				</select>			
			</td>
			<td class="help">Select the <a href="<?php echo get_url('layout'); ?>">layout</a> to use for the <strong>Article</strong> page</td>
		</tr>
		<tr>
			<td class="label">RSS Layout Template</td>
			<td class="field">
				<select name="rssLayout">
				<?php
				foreach($layouts as $layout) {
					$selected = '';
					if($settings['rssLayout'] == $layout->id) $selected = ' selected="selected"';
				?>
					<option value="<?php echo $layout->id; ?>"<?php echo $selected; ?>><?php echo $layout->name; ?></option>
				<?php
				}
				?>
				</select>
			</td>
			<td class="help">Select the <a href="<?php echo get_url('layout'); ?>">layout</a> to use for the <strong>RSS</strong> feed</td>
		</tr>
		<tr>
			<td class="label">Article Not Found Template</td>
			<td class="field">
				<select name="pagenotfoundLayout">
				<?php
				foreach($layouts as $layout) {
					$selected = '';
					if($settings['pagenotfoundLayout'] == $layout->id) $selected = ' selected="selected"';
				?>
					<option value="<?php echo $layout->id; ?>"<?php echo $selected; ?>><?php echo $layout->name; ?></option>
				<?php
				}
				?>
				</select>
			</td>
			<td class="help">Select the <a href="<?php echo get_url('layout'); ?>">layout</a> to use when an <strong>article cannot be found</strong></td>
		</tr>
		<tr>
			<td class="label">Archive Template</td>
			<td class="field">
				<select name="viewByLayout">
				<?php
				foreach($layouts as $layout) {
					$selected = '';
					if($settings['viewByLayout'] == $layout->id) $selected = ' selected="selected"';
				?>
					<option value="<?php echo $layout->id; ?>"<?php echo $selected; ?>><?php echo $layout->name; ?></option>
				<?php
				}
				?>
				</select>
			</td>
			<td class="help">Select the <a href="<?php echo get_url('layout'); ?>">layout</a> to use when we <strong>show archives</strong> for Year, Month or Day</td>
		</tr>
		<tr>
			<td class="label">&nbsp;</td>
			<td class="field" colspan="2">
				<input type="submit" value="Update Settings">
			</td>
		</tr>
	</table>
</form>