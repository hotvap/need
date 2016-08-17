<?php

$skipout=0;
if( arg(0)=='taxonomy' and is_string(arg(1)) and arg(1)=='term' and is_numeric(arg(2)) and isset($_SESSION['pdxpdx_par_vid']) and is_numeric($_SESSION['pdxpdx_par_vid']) ){

        drupal_not_found();
        drupal_exit();            

    switch( $_SESSION['pdxpdx_par_vid'] ){
    case 1:
    case 2:
    case 3:
    case 4:
    case 5:
    case 7:
    case 8:
    case 10:
// 6 - район
        drupal_not_found();
        drupal_exit();            
        break;
/*
    case 2:

        $isyes='';
        $tids=db_query('select d.tid, d.name from {field_data_field_part} as f inner join {taxonomy_term_data} as d on f.entity_id=d.tid where f.entity_type=\'taxonomy_term\' and f.field_part_tid='.arg(2).' and d.vid=8 order by d.weight');
        while( $tid=$tids->fetchAssoc() ){
            $path=path_load('taxonomy/term/'.$tid['tid']);
            if(strlen($path['alias'])) $path=$path['alias']; else $path='taxonomy/term/'.$tid['tid'];
            $isyes.= '<li class="partis'.$tid['tid'].'"><a href="/'.$path.'">'.$tid['name'].'</a></li>';
        }
        if( isset($isyes) and strlen($isyes) ){
            echo '<div class="taxpart_is taxpart2"><ul>'.$isyes;
            echo '</ul></div>';
        }else{
            echo '<div class="taxpart_is taxpart8"><ul>';
    
            $tids=db_query('select d.tid, d.name from {field_data_field_part} as f inner join {taxonomy_term_data} as d on f.entity_id=d.tid where f.entity_type=\'taxonomy_term\' and f.field_part_tid='.arg(2).' and d.vid=1 order by d.name');
            while( $tid=$tids->fetchAssoc() ){
                $path=path_load('taxonomy/term/'.$tid['tid']);
                if(strlen($path['alias'])) $path=$path['alias']; else $path='taxonomy/term/'.$tid['tid'];
                echo '<li class="partis'.$tid['tid'].'"><a href="/'.$path.'">'.$tid['name'].'</a></li>';
            }
            
            echo '</ul></div>';
        }
        
        break;
    case 8:
        echo '<div class="taxpart_is taxpart8"><ul>';

        $tids=db_query('select d.tid, d.name from {field_data_field_subpart} as f inner join {taxonomy_term_data} as d on f.entity_id=d.tid where f.entity_type=\'taxonomy_term\' and f.field_subpart_tid='.arg(2).' and d.vid=1 order by d.name');
        while( $tid=$tids->fetchAssoc() ){
            $path=path_load('taxonomy/term/'.$tid['tid']);
            if(strlen($path['alias'])) $path=$path['alias']; else $path='taxonomy/term/'.$tid['tid'];
            echo '<li class="partis'.$tid['tid'].'"><a href="/'.$path.'">'.$tid['name'].'</a></li>';
        }
        
        echo '</ul></div>';
        break;
    case 10:
        echo '<div class="taxpart_is taxpart10"><span class="lbl">Другие праздники: </span><ul>';

        $tids=db_query('select tid, name from {taxonomy_term_data} where vid=10 and tid<>'.arg(2).' order by weight');
        while( $tid=$tids->fetchAssoc() ){
            $path=path_load('taxonomy/term/'.$tid['tid']);
            if(strlen($path['alias'])) $path=$path['alias']; else $path='taxonomy/term/'.$tid['tid'];
            echo '<li class="partis'.$tid['tid'].'"><a href="/'.$path.'">'.$tid['name'].'</a></li>';
        }
        
        echo '</ul></div>';
        break;
*/
    }
    
    echo '<div class="term_desc_is term_desc_'.$_SESSION['pdxpdx_par_vid'].'">';
    $term=taxonomy_term_load(arg(2));
    if(isset($term->description) and strlen($term->description)){
        echo '<div class="term_desc admlnk pdxobj'.$term->tid.' pdxot">'.$term->description.'</div>';
    }else{
        echo '<div class="admlnk pdxobj'.$term->tid.' pdxot pdxptl"></div>';
    }
    echo '</div><div class="clear">&nbsp;</div>';
    
    
    if( $_SESSION['pdxpdx_par_vid']==4 ){
        echo '<p>Ниже представлены все товары бренда, которые есть на сайте.</p>';
    }
    if( $_SESSION['pdxpdx_par_vid']==6 ){
        echo render(module_invoke('views', 'block_view', 'areausers-user'));
        $skipout=1;
    }

}

if( !$skipout ){

global $user; 

$view = views_get_current_view();

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

  <?php if ($exposed):
   print $exposed;
   ?>
    <div class="view-sorts">
      <?php

    echo '<strong class="sort_label">Сортировать по</strong> ';

    $sorttitle='created';
    echo ' <a class="asort sortone';
if((isset($_REQUEST['sort_by']) and $_REQUEST['sort_by']==$sorttitle) or !isset($_REQUEST['sort_by'])){
    echo ' active';
}
echo '" href="javascript: void(0)" onclick=" jQuery(\'.view-display-id-item #edit-sort-by\').val(\''.$sorttitle.'\'); jQuery(\'#views-exposed-form-taxonomy-term-item\').submit(); ">дате создания</a> ';

    $sorttitle='field_rating_rating';
    echo ' <a class="asort sortone';
if((isset($_REQUEST['sort_by']) and $_REQUEST['sort_by']==$sorttitle) ){
    echo ' active';
}
echo '" href="javascript: void(0)" onclick=" jQuery(\'.view-display-id-item #edit-sort-by\').val(\''.$sorttitle.'\'); jQuery(\'#views-exposed-form-taxonomy-term-item\').submit(); ">рейтингу</a> ';




  if(isset($view->total_rows) and is_numeric($view->total_rows) and intval($view->total_rows)){
    echo '<div class="pager_find">Всего: <strong>'.$view->total_rows.'</strong></div>';
  }      
      
      ?>
    <div class="clear">&nbsp;</div></div>


  <div class="filter_block">

<?php

$filteris='delivery2';
echo '<div class="filter_item filter_item_checkbox">';
echo '<input type="checkbox" id="id_'.$filteris.'" value="1" class="form-checkbox"';
if( isset( $_GET[$filteris] ) and is_numeric( $_GET[$filteris] ) and $_GET[$filteris]==1 ){
    echo ' checked="checked"';
}
echo ' onchange=" if( jQuery(this).attr(\'checked\')==\'checked\' ){ jQuery(\'#edit-'.$filteris.'\').val(1); }else{ jQuery(\'#edit-'.$filteris.' option\').removeAttr(\'selected\'); } " />';
echo '<label for="id_'.$filteris.'">возможна доставка/возврат по адресу в пределах города</label>';
echo '</div>';

$filteris='delivery3';

echo '<div class="filter_item filter_item_checkbox">';
echo '<input type="checkbox" id="id_'.$filteris.'" value="1" class="form-checkbox"';
if( isset( $_GET[$filteris] ) and is_numeric( $_GET[$filteris] ) and $_GET[$filteris]==1 ){
    echo ' checked="checked"';
}
echo ' onchange=" if( jQuery(this).attr(\'checked\')==\'checked\' ){ jQuery(\'#edit-'.$filteris.'\').val(1); }else{ jQuery(\'#edit-'.$filteris.' option\').removeAttr(\'selected\'); } " />';
echo '<label for="id_'.$filteris.'">возможна доставка/возврат по адресу за пределами города</label>';
echo '</div>';


$filteris='status';
echo '<div class="filter_item filter_item_checkbox">';
echo '<input type="checkbox" id="id_'.$filteris.'" value="1" class="form-checkbox"';
if( isset( $_GET[$filteris] ) and is_numeric( $_GET[$filteris] ) and $_GET[$filteris]==1 ){
    echo ' checked="checked"';
}
echo ' onchange=" if( jQuery(this).attr(\'checked\')==\'checked\' ){ jQuery(\'#edit-'.$filteris.'\').val(1); }else{ jQuery(\'#edit-'.$filteris.' option\').removeAttr(\'selected\'); } " />';
echo '<label for="id_'.$filteris.'">только товары, сейчас доступные к аренде</label>';
echo '</div>';


$filteris='who';
echo '<div class="filter_item filter_item_select">';
echo '<div class="lbl">Тип арендодателя:</div>';
echo '<select onchange=" if( jQuery(this).val() ){ jQuery(\'#edit-'.$filteris.'\').val(jQuery(this).val()); }else{ jQuery(\'#edit-'.$filteris.' option\').removeAttr(\'selected\'); } "><option value="">не важно</option>';
echo '<option value="1"';
if( isset( $_GET[$filteris] ) and is_numeric( $_GET[$filteris] ) and $_GET[$filteris]==1 ){
    echo ' selected="selected"';
}
echo '>частное лицо</option>';
echo '<option value="2"';
if( isset( $_GET[$filteris] ) and is_numeric( $_GET[$filteris] ) and $_GET[$filteris]==2 ){
    echo ' selected="selected"';
}
echo '>компания или магазин</option>';
echo '</select>';
echo '</div>';


    $filteris='raion';
    echo '<div class="filter_item filter_item_select">';
    echo '<div class="lbl">Район расположения:</div>';
    echo '<select onchange=" if( jQuery(this).val() ){ jQuery(\'#edit-'.$filteris.'\').val(jQuery(this).val()); }else{ jQuery(\'#edit-'.$filteris.' option\').removeAttr(\'selected\'); } "><option value="">любой</option>';
    
    $vals=db_query('select d.tid, d.name from {taxonomy_term_data} as d where d.vid=6');
    while( $val=$vals->fetchAssoc() ){
        echo '<option value="'.$val['tid'].'"';
        if( isset( $_GET[$filteris] ) and is_numeric( $_GET[$filteris] ) and $_GET[$filteris]==$val['tid'] ){
            echo ' selected="selected"';
        }
        echo '>'.$val['name'].'</option>';
    }
    
    echo '</select>';
    echo '</div>';


$filteris='pricerent';
echo '<div class="filter_item filter_item_input filter_item_input_num">';
echo '<div class="lbl">Залоговая сумма не более:</div>';
echo '<input type="text" value="';
if( isset( $_GET[$filteris] ) and is_numeric( $_GET[$filteris] ) ){
    echo $_GET[$filteris];
}
echo '" class="filter-text" size="15" onchange=" if( jQuery(this).val() ){ jQuery(\'#edit-'.$filteris.'\').val( jQuery(this).val().replaceAll(\' \', \'\') ); }else{ jQuery(\'#edit-'.$filteris.'\').val(\'\'); } " />';
if( defined('PDX_CITY_CUR') ){
    echo ' '.PDX_CITY_CUR;
}
echo '</div>';



if( isset($_SESSION['pdxpdx_par_vid']) and is_numeric($_SESSION['pdxpdx_par_vid']) and $_SESSION['pdxpdx_par_vid']!=4 ){
    $filteris='brand';
    echo '<div class="filter_item filter_item_select filter_item_select_brand">';
    echo '<div class="lbl">Производитель:</div>';
    echo '<select onchange=" if( jQuery(this).val() ){ jQuery(\'#edit-'.$filteris.'\').val(jQuery(this).val()); }else{ jQuery(\'#edit-'.$filteris.' option\').removeAttr(\'selected\'); } "><option value="">любой</option>';
    
    echo '</select>';
    echo '</div>';
}

echo '<div class="filter_item filter_item_submit">';
echo '<input class="filter_submit" type=\'submit\' value="Фильтровать" onclick=" jQuery(\'.views-submit-button input.form-submit\').click(); " />';
echo '</div>';


?>

  <div class="clear">&nbsp;</div></div>

  <?php
    
   endif;
   
   
   
   
   ?>

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
    <div class="clear">&nbsp;</div>
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
<?php
//}
?>
</div><?php /* class view */ ?>

<?php
}
?>
<?php 
if( isset( $term->tid ) and is_numeric( $term->tid ) ){
    
    if( function_exists('pdxgetshare') ){
        $soc_title=str_replace('"', '', 'Возьми в аренду: '.$term->name);
        if( mb_strlen($soc_title)>64 ){
            $soc_title=mb_substr($soc_title,0,61).'...';
        }
        $soc_desc='';
        if( isset($term->description) and strlen($term->description) ){
            $soc_desc=str_replace('"', '', $term->description);
            if( mb_strlen($soc_desc)>127 ){
                $soc_desc=mb_substr($soc_desc,0,123).'...';
            }
        }
        echo pdxgetshare($soc_title, $soc_desc, $GLOBALS['base_url'].'/'.url('taxonomy/term/'.$term->tid));
    }
    
 ?>
<div class="clear">&nbsp;</div>
<?php
}
?>