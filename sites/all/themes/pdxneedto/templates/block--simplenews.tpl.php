<section id="block-<?php print $block->module . '-' . $block->delta; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php print render($title_prefix); ?><div class="postblockover">
  <?php if (!empty($block->subject)): ?>
    <div class="block-title">
      <div class="title"<?php print $title_attributes; ?>><?php
      echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/rabota-s-rassylkami.html">Помощь</a>';
      print $block->subject ?></div>
    </div>
  <?php endif;?>
  <?php print render($title_suffix); ?>
  <div class="content">
    <?php print $content; ?>
  </div>
</div></section> <!-- /block -->