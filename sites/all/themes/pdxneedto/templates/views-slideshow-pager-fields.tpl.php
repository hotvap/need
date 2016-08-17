<?php
$count = substr_count($rendered_field_items,'<img ');
//$widget_id=='widget_pager_bottom_slider-block'
?>
<div class="prerotator">
<?php if(intval($count) and $count>4){ ?>
<img src="/sites/all/themes/pdxneedto/img/arrow_left.png" alt="" title="" class="prev disabled" /><img src="/sites/all/themes/pdxneedto/img/arrow_right.png" alt="" title="" class="next" />
<?php } ?>
<div class="rotator">
<ul id="<?php print $widget_id; ?>" class="<?php print $classes; ?>">
  <?php print $rendered_field_items; ?>
</ul>
</div></div>
<?php if(intval($count) and $count>4){ ?>
<script type="text/javascript"> jQuery( function(){ jQuery(".rotator").jCarouselLite({btnNext: ".next", btnPrev: ".prev", mouseWheel: true, circular: false, visible: 3});} ); </script>
<?php } ?>