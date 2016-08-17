<?php

/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
?>
<div class="<?php print $classes; ?>">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>

<div class="stock_more_left">
<div class="first"><a href="/node/add/product"><strong>Добавить товар</strong></a></div>
<div style="display: none;"><div id="stocksettings">

<?php
//<div><a class="colorbox-inline" href="?width=477&height=433&inline=true#stocksettings">Настройки склада</a></div>
$block=block_block_view(34);
if( isset($block['content']) and strlen($block['content']) ){
    echo '<div class="course"><div class="title">Курсы валют</div>'.$block['content'];
//    if(isset($user->roles[3]) or isset($user->roles[4])){ echo '<a href="/admin/structure/block/manage/block/34/configure"><img alt="ред" src="/sites/all/libraries/img/adm_edit.png" /></a>';  }
    echo '</div>';
}

?>

</div></div>
</div>
    
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>

<div class="stock_more_right">
<div><a href="javascript: void(0);" onclick=" location.replace( jQuery('table.views-table th.f_field_discount a').attr('href') ); ">Сортировать по скидкам</a></div>
<div><a href="javascript: void(0);" onclick=" location.replace( jQuery('table.views-table th.f_status a').attr('href') ); ">Опубликованные / Отключенные</a></div>
</div>

    <div class="clear">&nbsp;</div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>
