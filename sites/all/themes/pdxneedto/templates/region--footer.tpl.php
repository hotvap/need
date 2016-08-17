  <div class="region_item" id="region_<?php echo $region; ?>">
  <?php
  if( drupal_is_front_page() ){}else{
    echo '<noindex>';
  }
  print $content;
  if( drupal_is_front_page() ){}else{
    echo '</noindex>';
  }
  ?>
  </div>