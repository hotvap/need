<li id="views_slideshow_pager_field_item_<?php print $variables['location']; ?>_<?php print $variables['vss_id']; ?>_<?php print $variables['count']; ?>" class="<?php print $classes; ?>">
  <?php
  
    $item=str_replace('<img ', '<img class="lazy" ', $item);  
    $item=str_replace(' src', ' data-original', $item);  
  
  print $item; ?>
</li>