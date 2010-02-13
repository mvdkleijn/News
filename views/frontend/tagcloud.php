<h2>TagCloud</h2>

<div class="tagcloud">
<?php

$tags = Tag::countTags();
$max = Tag::maxCount();
$maxEM = 3;
foreach($tags as $tag) {
	$size = ($max / $maxEM) * $tag->count;
	if($size < 0.9) $size = 0.9;
	if($size > $maxEM) $size = $maxEM;

?><span class="tag" style="font-size:<?php echo $size; ?>em" id="<?php echo $tag->slug; ?>"><a href="<?php echo $tag->url; ?>"><?php echo $tag->tag; ?></a></span>
<?php
}
?>
</div>