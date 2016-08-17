<?php

if(arg(0)=='taxonomy' and is_string(arg(1)) and arg(1)=='term' and is_numeric(arg(2))){
        
    
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<?php

foreach ($rows as $id => $row): ?>
  <div <?php if ($classes_array[$id]) { print 'class="' . $classes_array[$id] .'"';  } ?>>
    <?php print $row; ?>
  </div>
<?php endforeach;

}

?>
