<?php // node template
global $user;
if( $user->uid ){
    drupal_goto('user/'.$user->uid.'/points', array(), 301);
}else{
    drupal_goto('node', array(), 301);
}

//http://schema.org/Product
// itemscope itemtype="http://data-vocabulary.org/Product"
 ?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <div class="node-container">
    <?php if ($display_submitted): ?>
      <div class="submitted-info">
      <?php print t('posted by'); ?>
      <span class="node-name"><?php print $name; ?></span>
      <?php print t('on'); ?>
      <span class="node-date"><?php print $date; ?></span>
      <?php if(!$node->status): ?>
        <span class="node-status-unpublished"><?php print t('unpublished'); ?></span>
      <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php print $user_picture; ?>

    <div class="nodecontent"<?php print $content_attributes; ?>>
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
      <?php
//global $user;
//if($user->uid==1){
//    echo '<pre>'.print_r($node, true).'</pre>';
//}

//*

echo '<div id="block-views-product-block">';
if(isset($node->uc_product_image['und'][0]['uri']) and strlen($node->uc_product_image['und'][0]['uri'])){
    if(!isset($node->uc_product_image['und'][1]['uri']) ){
        print '<a href="'.file_create_url($node->uc_product_image['und'][0]['uri']).'" title="" class="colorbox product_image" rel="gallery-'.$node->nid.'">'.theme('image_style', array('style_name' => 'product_full', 'path' => $node->uc_product_image['und'][0]['uri'], 'alt' => '', 'title' => '', 'attributes' => array('class' => 'image'), 'getsize' => FALSE,)).'</a>';
    }else{
        print render(module_invoke('views', 'block_view', 'portfoliofull-block'));
    }
}
echo '</div>';
//*/

//      print render($content);
      
/*
      if(isset($node->uc_product_image['und'][0]['uri']) and strlen($node->uc_product_image['und'][0]['uri'])){
			print '<a href="'.file_create_url($node->uc_product_image['und'][0]['uri']).'" title="" class="colorbox product_image" rel="gallery-'.$node->nid.'">'.theme('image_style', array('style_name' => 'product_full', 'path' => $node->uc_product_image['und'][0]['uri'], 'alt' => '', 'title' => '', 'attributes' => array('class' => 'image'), 'getsize' => FALSE,)).'</a>';
	  }
      
*/
/*
      $stocksite=uc_stock_level($node->model);
      if(is_numeric($stocksite) and $stocksite>0){
            echo $stocksite;
      }
*/      

echo '<div class="product_right"><div class="product_right_post">';

if (module_exists('uc_discounts')) {
    $discounts = get_codeless_discounts_for_product_and_quantity($node, 1);
    if (!empty($discounts)) {
      $total_discount_amount = 0;
      foreach ($discounts as $discount) {
        $total_discount_amount += round($discount->amount, 2);
      }
      $discounts = $node->sell_price - $total_discount_amount;
    }
    if(isset($discounts) and is_numeric($discounts)){
          echo '<div class="item-price">';
          echo '<div class="discount"><span>'.$discounts.'</span> руб</div>';
          echo '<div class="price"><span>'.intval($node->sell_price).'</span> руб</div>';
          echo '</div>';
    }else{
          echo '<div class="item-price"><span>'.intval($node->sell_price).'</span> руб</div>';
    }
}else{
    echo '<div class="item-price"><span>'.intval($node->sell_price).'</span> руб</div>';
    //uc_currency_format($node->sell_price)
}
/*
    if( isset($node->attributes) and is_array($node->attributes) and count($node->attributes) ){
        foreach( $node->attributes as $attribute ){
            if( isset($attribute->options) and is_array($attribute->options) and count($attribute->options) ){
                foreach( $attribute->options as $oid=>$option ){
                    echo '<span itemprop="offerDetails" itemscope itemtype="http://data-vocabulary.org/Offer"><meta itemprop="currency" content="BYR" /><span itemprop="price" content="'.round(($node->sell_price+$option->price),0).'"></span><meta itemprop="availability" content="in_stock" /></span>';
                }
            }
        }
    }
*/


    echo drupal_render(drupal_get_form('uc_product_add_to_cart_form_' . $node->nid, $node));

if(isset($node->body['und'][0]['value']) and strlen($node->body['und'][0]['value'])){
    print $node->body['und'][0]['value'];
}

//echo '<div class="product_flag">&nbsp;&nbsp;/&nbsp;&nbsp;'.flag_create_link('bookmarks', $node->nid).'</div>';    
//$node->taxonomy_catalog['und'][0]['tid']
            
      ?>
<?php
global $user;
if(isset($user->roles[3])){

        echo '<div class="foradmin_publ'.$node->nid.'"><a href="javascript: void(0);" onclick="foradmin_publ('.$node->nid.');">Депубликовать</a></div>';
        echo '<div class="foradmin_hit'.$node->nid.'"><a href="javascript: void(0);" onclick="foradmin_hit('.$node->nid.');">Сделать хитом/Убрать из хитов</a></div>';

    $pdxshowhelp2=variable_get('pdxshowhelp2', 0);
    if(!$pdxshowhelp2){
        echo '<div class="manage_part"><ul>';
        echo '<li><a href="/node/'.$node->nid.'/delete"><img alt="Уд" title="Удалить данный товар" src="/sites/all/libraries/img/adm_del.png" /></a></li>';
        echo '<li><a href="/node/'.$node->nid.'/edit/attributes">Атрибуты товара</a></li>';
        echo '<li><a href="/node/'.$node->nid.'/edit/options">Опции товара</a></li>';
        echo '<li><a href="/node/'.$node->nid.'/edit/features">Особенности товара</a></li>';
        echo '<li><a href="/node/add/'.$node->type.'"><img alt="+" title="Добавить новый товар" src="/sites/all/libraries/img/add.png" /></a></li>';
        echo '<li><a href="/admin/store/search?type='.$node->type.'"><img alt="Все" title="Все товары данного типа" src="/sites/all/libraries/img/all.png" /></a></li>';
        echo '</ul></div>';
    }
}
?>
<?php
echo '</div></div>';

?>
    <div class="clear">&nbsp;</div></div>
    <?php
    if( isset($content['links']['statistics']) ){
        unset($content['links']['statistics']);
    }
    if( isset($content['links']['translation']) ){
        unset($content['links']['translation']);
    }
      /* if($content['links']): ?>
      <div class="links-container">
        <?php print render($content['links']); ?>
      </div>
    <?php endif; */ ?>
    <?php if (!$teaser): ?>
      <div class="clearfix">
        <?php print render($content['comments']); ?>
      </div>
    <?php endif; ?>
  </div><!--end node container-->
</div><!--end node-->

<?php

/*
echo render($content['field_rating']);
$aggr2='';
$aggregaterating = fivestar_get_votes('node',$node->nid);
if( isset($aggregaterating) and is_array($aggregaterating) and count($aggregaterating) ){
    echo '<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
    $aggr2.='<span itemscope itemtype="http://data-vocabulary.org/Review-aggregate">';
    if( isset($aggregaterating['average']['value']) and is_numeric($aggregaterating['average']['value']) ){
        echo '<meta itemprop="ratingValue" content="'.round( ($aggregaterating['average']['value']/20),1).'" />';
        $aggr2.='<span itemprop="average">'.round( ($aggregaterating['average']['value']/20),1).'</span>';
    }
    if( isset($aggregaterating['count']['value']) and is_numeric($aggregaterating['count']['value']) ){
	   echo '<meta itemprop="ratingCount" content="'.$aggregaterating['count']['value'].'" />';
	   $aggr2.='<span itemprop="count">'.$aggregaterating['count']['value'].'</span>';
    }
    $best=db_query('select r.field_comment_rating_rating from {field_data_field_comment_rating} as r inner join {comment} as c on r.entity_id=c.cid where r.entity_type=\'comment\' and c.nid='.$node->nid.' order by r.field_comment_rating_rating desc limit 0,1');
    $best=$best->fetchAssoc();
    if( isset($best['field_comment_rating_rating']) and is_numeric($best['field_comment_rating_rating']) ){
	   echo '<meta itemprop="bestRating" content="'.round( ($best['field_comment_rating_rating']/20),1).'" />';
	   $aggr2.='<span itemprop="best">'.round( ($best['field_comment_rating_rating']/20),1).'</span>';
    }
    echo '</span>';
    $aggr2.='</span>';
    echo '<div style="display: none;">'.$aggr2.'</div>';
}
*/

/*
$myzebra='even';

echo '<div class="product_more"><h5>Описание товара:</h5><table>';
      
if(isset($node->field_diameter_top['und'][0]['value']) and strlen($node->field_diameter_top['und'][0]['value'])){
    if($myzebra=='even') $myzebra='odd'; else $myzebra='even';
    echo '<tr class="'.$myzebra.'"><td class="item">Верхний диаметр</td><td class="value">'.$node->field_diameter_top['und'][0]['value'].'</td></tr>';
}

echo '</table></div>';


//        if(isset($node->field_youvideo['und'][0]['input']) and strlen($node->field_youvideo['und'][0]['input'])){
//            echo render(field_view_field('node', $node, 'field_youvideo', array('label'=>'hidden', 'settings'=> array('youtube_size'=>'640x480') )));
//        }

$block=block_block_view(64);
if( isset($block['content']) and strlen($block['content']) ){
    echo '<div class="register_chat">'.$block['content'];
    if(isset($user->roles[3]) or isset($user->roles[4])){ echo '<a href="/admin/structure/block/manage/block/64/configure"><img alt="ред" src="/sites/all/libraries/img/adm_edit.png" /></a>';  }
    echo '</div>';
}

*/
?>