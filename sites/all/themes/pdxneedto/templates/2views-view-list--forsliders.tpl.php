<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $options['type'] will either be ul or ol.
 * @ingroup views_templates
 */
?>
<?php print $wrapper_prefix; ?>
  <?php if (!empty($title)) : ?>
    <h3 id="colterm<?php
$bid=0;
if( strpos($title,'|pdx|')===false ){}else{
    $title=explode('|pdx|', $title);
    $bid=$title[1];
    $title=$title[0];
}
echo $bid;

  ?>" class="coltermtitle"><?php
  
  print $title; ?></h3>
  <?php endif; ?>
<div class="prerotator prerotator_event prerotator_<?php echo $bid; ?>">
<?php
if( is_array($rows) and count($rows)>4){ ?>
<img src="/sites/all/themes/pdxgoldfish/img/arrow_left.png" alt="" title="" class="prev prev_<?php echo $bid; ?> disabled" onclick=" window.setTimeout('updlazy(this)', 777); " />
<img src="/sites/all/themes/pdxgoldfish/img/arrow_right.png" alt="" title="" class="next next_<?php echo $bid; ?>" onclick=" window.setTimeout('updlazy(this)', 777); " />
<?php }  ?>
<div class="rotator rotator_<?php echo $bid; ?>">
  <?php print $list_type_prefix; ?>
    <?php foreach ($rows as $id => $row): ?>
      <li class="<?php print $classes_array[$id]; ?>"><?php print $row; ?></li>
    <?php endforeach; ?>
  <?php print $list_type_suffix; ?>
<?php print $wrapper_suffix; ?>
</div></div>
<?php  if( is_array($rows) and count($rows)>4){ ?>
<script type="text/javascript"> jQuery( function(){ jQuery(".rotator_<?php echo $bid; ?>").jCarouselLite({btnNext: ".next_<?php echo $bid; ?>", btnPrev: ".prev_<?php echo $bid; ?>", mouseWheel: true, circular: false, vertical: false, visible: 4});} ); </script>
<?php }  ?>
<div class="clear">&nbsp;</div>
