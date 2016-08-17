<?php // node template
 drupal_goto('findmy', array(), 301);
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <div class="node-container">
    <?php if ($display_submitted): ?>
      <div class="submitted-info">
      <span class="node-date"><?php echo date('d', $node->created) .' '. pdxneedto_month_declination_ru(format_date($node->created,'custom','F'),date('n',$node->created)) .' '. date('Y', $node->created); ?></span>
      <?php if(!$node->status): ?>
        <span class="node-status-unpublished"><?php print t('unpublished'); ?></span>
      <?php endif; ?>
      </div>
    <?php endif; ?>

    <article class="nodecontent"<?php print $content_attributes; ?>>
      <?php
         hide($content['comments']);
         hide($content['links']);
      ?>
      <?php print render($title_prefix); ?>
      <?php if (!$page and arg(0)!='form'): ?>
        <div class="title">
          <a href="<?php print $node_url; ?>"><?php print $title; ?></a>
        </div>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print render($content); ?>

<?php
global $user;
if(isset($user->roles[3])){
    $pdxshowhelp2=variable_get('pdxshowhelp2', 0);
    if(!$pdxshowhelp2){
        echo '<div class="manage_part"><ul>';
        echo '<li><a href="/node/'.$node->nid.'/delete"><img alt="Уд" title="Удалить данную публикацию" src="/sites/all/libraries/img/adm_del.png" /></a></li>';
        echo '<li><a href="/node/add/'.$node->type.'"><img alt="+" title="Добавить новую публикацию данного типа" src="/sites/all/libraries/img/add.png" /></a></li>';
        echo '<li><a href="/admin/store/search?type='.$node->type.'"><img alt="Все" title="Все публикации данного типа" src="/sites/all/libraries/img/all.png" /></a></li>';
        echo '</ul></div>';
    }
}

?>
    <div class="clear">&nbsp;</div></article>
    <?php
    if( isset($content['links']['statistics']) ){
        unset($content['links']['statistics']);
    }
    if( isset($content['links']['translation']) ){
        unset($content['links']['translation']);
    }
    if( isset( $content['links']['print_mail'] ) ){
        unset($content['links']['print_mail']);
    }
      if($content['links']): ?>
      <div class="links-container">
        <?php print render($content['links']); ?>
      </div>
    <?php endif; ?>
    <?php if (!$teaser): ?>
      <div class="clearfix">
        <?php print render($content['comments']); ?>
      </div>
    <?php endif; ?>
  </div><!--end node container-->
</div><!--end node-->