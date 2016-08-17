<?php // node template
global $user; if(isset($user->roles[3]) or isset($user->roles[4])){  }else{
    $lnk='%%'.request_path().'.html';
    $lnk=str_replace('%%help/', '', $lnk);
    $lnk=str_replace('%%', '', $lnk);
    $lnk='http://help.needto.me/'.$lnk;
    drupal_goto($lnk, array(), 301);
}
// drupal_goto('node', array(), 301);
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
//    if( isset($content['links']['statistics']) ){
//        unset($content['links']['statistics']);
//    }
    if( isset($content['links']['translation']) ){
        unset($content['links']['translation']);
    }
    if( isset( $content['links']['print_mail'] ) ){
        unset($content['links']['print_mail']);
    }

      if($content['links']):
      
    $soc_title=str_replace('"', '', $node->title);
    if( mb_strlen($soc_title)>127 ){
        $soc_title=mb_substr($soc_title,0,123).'...';
    }
    $soc_desc='';
    if( isset($node->body['und'][0]['value']) and strlen($node->body['und'][0]['value']) ){
        $soc_desc=str_replace('"', '', $node->body['und'][0]['value']);
        if( mb_strlen($soc_desc)>127 ){
            $soc_desc=mb_substr($soc_desc,0,123).'...';
        }
    }
    echo pdxgetshare2($soc_title, $soc_desc);
      
 ?>

      <div class="links-container">
        <?php print render($content['links']); ?>
      </div>
    <?php endif;

$in=array();
$outs=db_query('select m2.link_path, m2.link_title from {menu_links} as m inner join {menu_links} as m2 on ( m.mlid=m2.plid ) where m.menu_name=\'menu-help\' and m.link_path=\'node/'.$node->nid.'\' order by m2.weight ASC');
while( $out=$outs->fetchAssoc() ){
    $path=path_load($out['link_path']); if(strlen($path['alias'])) $path=$path['alias']; else $path=$out['link_path'];
    $in[]='<li><a href="/'.$path.'">'.$out['link_title'].'</a></li>';
}
if( isset($in) and is_array($in) and count($in) ){
    echo '<div class="taxpart_is taxpart_help"><div class="title">Читайте далее:</div><ul>';
    echo implode('',$in);
    echo '</ul></div>';
}else{
    $isplid=0;
    $outs=db_query('select m2.link_path, m2.link_title, m2.plid from {menu_links} as m inner join {menu_links} as m2 on ( m.plid=m2.plid ) where m.menu_name=\'menu-help\' and m.link_path=\'node/'.$node->nid.'\' and m.depth>1 order by m2.weight ASC');
    while( $out=$outs->fetchAssoc() ){
        $path=path_load($out['link_path']); if(strlen($path['alias'])) $path=$path['alias']; else $path=$out['link_path'];
        $active='';
        if( $out['link_path']=='node/'.$node->nid ){
            $active=' class="active"';
        }
        $in[]='<li><a href="/'.$path.'"'.$active.'>'.$out['link_title'].'</a></li>';
        $isplid=$out['plid'];
    }
    if( isset($in) and is_array($in) and count($in) ){
        if( count($in)>1 ){
            echo '<div class="taxpart_is taxpart_help"><div class="title">Читайте далее:</div><ul>';
            echo implode('',$in);
            if( isset($isplid) and $isplid>0 ){
                $isplid=db_query('select link_path, link_title from {menu_links} where mlid='.$isplid);
                $isplid=$isplid->fetchAssoc();
                if( isset($isplid['link_title']) and strlen($isplid['link_title']) ){
                    echo '<li class="article_back"><a href="'.url($isplid['link_path'], array('absolute'=>TRUE)).'">Наверх к «';
                    echo $isplid['link_title'];
                    echo '»</a></li>';
                }
            }
            echo '</ul></div>';
        }elseif( $isplid>0 ){
            $in=array();
            $outs=db_query('select m2.link_path, m2.link_title, m2.mlid from {menu_links} as m inner join {menu_links} as m2 on ( m.plid=m2.plid and  m2.menu_name=\'menu-help\' ) where m.menu_name=\'menu-help\' and m.mlid='.$isplid.' and m.depth=1 order by m2.weight ASC');
            while( $out=$outs->fetchAssoc() ){
                $path=path_load($out['link_path']); if(strlen($path['alias'])) $path=$path['alias']; else $path=$out['link_path'];
                $active='';
                if( $out['mlid']==$isplid ){
                    $active=' class="active"';
                }
                $in[]='<li><a href="/'.$path.'"'.$active.'>'.$out['link_title'].'</a></li>';
            }
            if( isset($in) and is_array($in) and count($in) ){
                echo '<div class="taxpart_is taxpart_help"><div class="title">Читайте далее:</div><ul>';
                echo implode('',$in);
                echo '</ul></div>';
            }
        }
    }
}
    
    ?>
    <?php if (!$teaser): ?>
      <div class="clearfix">
        <?php print render($content['comments']); ?>
      </div>
    <?php endif; ?>
  </div><!--end node container-->
</div><!--end node-->