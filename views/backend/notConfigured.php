<h1>Just one more thing...</h1>

<?php if(AuthUser::hasPermission('administrator,developer')) { ?>

<p>We need to quickly set up the layouts you want to use on the frontend. You can edit these afterwards in the settings.</p>

<form method="post" action="<?php echo get_url('news/saveSettings/notConfigured'); ?>">
	<table class="fieldset" id="settings">
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

<?php } else { ?>
<p>The news set up is not complete. Please ask your system administrator to configure it properly.</p>
<?php } ?>