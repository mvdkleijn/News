<h1>Tags</h1>

<ul class="tags">
<?php

foreach($tags as $tag) { ?>

<li class="tag" id="<?php echo $tag->slug; ?>"><a href="<?php echo $tag->url; ?>"><?php echo $tag->tag; ?></a></li>

<?php } ?>
</ul>