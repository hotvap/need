<?php // node template
// drupal_goto('node', array(), 301);


$num=db_query('select field_delete_value from {field_data_field_delete} where entity_type=\'user\' and entity_id='.$node->uid);
$num=$num->fetchAssoc();
if( isset($num['field_delete_value']) and $num['field_delete_value']==1 ){
    echo '<div class="inmod">Извините, учетная запись пользователя<br />временно удалена</div>';
}else{

if( isset( $node->field_delete['und'][0]['value'] ) and $node->field_delete['und'][0]['value']==1 ){
    echo '<div class="inmod">Извините, объявление <br /><span>'.$node->title.'</span> <br />удалено автором</div>';
}elseif( isset( $node->field_block['und'][0]['value'] ) and $node->field_block['und'][0]['value']==1 ){
    echo '<div class="inmod">Извините, объявление <br /><span>'.$node->title.'</span> <br />заблокировано модератором</div>';
}else{

global $user;
$isaccess=0;
if(isset($user->roles[3]) or isset($user->roles[4])){
    $isaccess=1;
}else{
    if( $user->uid==$node->uid ){
        $isaccess=1;
    }
}
if( !$node->status and  !$isaccess ){
    echo '<div class="inmod">Извините, объявление <br /><span>'.$node->title.'</span> <br />на модерации</div>';
}else{

if( !$node->status ){
    echo '<div class="wrnblock">';
    echo 'Данное объявление находится на модерации.';
    if(isset($user->roles[3]) or isset($user->roles[4])){
        echo ' <span class="foradmin_publ'.$node->nid.'"><a href="javascript: void(0);" onclick="foradmin_publ('.$node->nid.');">Опубликовать?</a></span>';
    }else{
        echo ' Вероятно, вы видите его только потому, что являетесь автором объявления.';
    }
    echo '</div>';
}
?>
<div id="node-<?php print $node->nid; ?>" class="node_uid_<?php
print $node->uid.' '.$classes;
if( isset( $node->field_premium['und'][0]['value'] ) and is_numeric($node->field_premium['und'][0]['value']) and $node->field_premium['und'][0]['value']>0 ){
    echo ' node_premium';
}
?>"<?php print $attributes; ?>>
<div class="itemsubtitle">
<div class="itemsubtitle_item"><strong>Артикул:</strong > N1<?php echo $node->nid; ?></div>
<?php

        if(isset($node->field_state['und'][0]['value']) and is_numeric($node->field_state['und'][0]['value'])){
            $name='';
            switch($node->field_state['und'][0]['value']){
            case 1:
                $name='отличное';
                break;
            case 2:
                $name='хорошее';
                break;
            case 3:
                $name='есть потертости';
                break;
            case 4:
                $name='плохое, но функцию выполняет';
                break;
            }
            if( isset($name) and strlen($name) ){
                echo '<div class="itemsubtitle_item"><strong>Состояние:</strong> '.$name.'</div>';
            }
        }

        if(isset($node->field_brand['und'][0]['tid']) and is_numeric($node->field_brand['und'][0]['tid']) and isset($node->taxonomy_catalog['und'][0]['tid']) and is_numeric($node->taxonomy_catalog['und'][0]['tid'])){
            $name=db_query('select name from {taxonomy_term_data} where tid='.$node->field_brand['und'][0]['tid']);
            $name=$name->fetchAssoc();
            if( isset($name['name']) and strlen($name['name']) ){
                echo '<div class="itemsubtitle_item"><strong>Бренд:</strong> <a target="_blank" href="'.$GLOBALS['base_url'].'/'.PDXCAT_NAME.'/'.$node->taxonomy_catalog['und'][0]['tid'].'?field_brand='.$node->field_brand['und'][0]['tid'].'">'.$name['name'].'</a></div>';
            }
        }
        
        $name='';
        if(isset($node->field_instruction['und'][0]['value']) and $node->field_instruction['und'][0]['value']==1){
            $name='<span class="fa fa-check">&nbsp;</span>';
//        }else{
//            $name='<span class="fa fa-times">&nbsp;</span>';
        }
        if( isset($name) and strlen($name) ){
            echo '<div class="itemsubtitle_item"><strong>Инструкция:</strong> '.$name.'</div>';
        }


        if( isset($node->field_price_min['und'][0]['value']) and is_numeric($node->field_price_min['und'][0]['value']) ){
            $suffix= 'часов';
            $tmpis=$node->field_price_min['und'][0]['value'];
            if( $tmpis>23 ){
                $tmpis=round($tmpis/24, 0);
                $suffix= 'дней';
                if( $tmpis==1 ){
                    $suffix= 'дня';
                }
            }
            if( $tmpis>29 ){
                $tmpis=round($tmpis/30, 0);
                $suffix= 'месяца';
            }
            echo '<div class="itemsubtitle_item"><strong>Срок аренды:</strong> от '.$tmpis.' '.$suffix.'</div>';
        }

        
?>
</div>

  <div id="node_for_time" class="node-container node_time_<?php print $node->created; ?>">
    <?php if ($display_submitted): ?>
      <div class="submitted-info">
      <span class="node-date"><?php echo date('d', $node->created) .' '. pdxneedto_month_declination_ru(format_date($node->created,'custom','F'),date('n',$node->created)) .' '. date('Y', $node->created); ?></span>
      <?php if(!$node->status): ?>
        <span class="node-status-unpublished"><?php print t('unpublished'); ?></span>
      <?php endif; ?>
      </div>
    <?php endif; ?>

    <article class="nodecontent<?php
    
    $sign='';
    if( defined('PDX_CITY_CUR') ){
        $sign=PDX_CITY_CUR;
    }
    
    if( isset( $node->field_cur['und'][0]['value'] ) and $node->field_cur['und'][0]['value']>1 ){
        switch($node->field_cur['und'][0]['value']){
        case 2:
            echo ' nodeinval';
            $sign='$';
            break;
        default:
            echo ' nodeinval2';
            $sign='€';
            break;
        }
    }
    if( strlen($sign) ){
        $sign='<span class="signcur">'.$sign.'</span>';
    }
    ?>"<?php print $content_attributes; ?>>
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
        
        echo '<div class="nodeimage_left">';
        $allimgs=array();
        $imgis='';
        if(isset($node->field_image['und'][0]['uri']) and strlen($node->field_image['und'][0]['uri'])){
            $imgis=str_replace(' src=', ' data-original=', theme('image_style', array('style_name' => 'product_full', 'path' => $node->field_image['und'][0]['uri'], 'alt' => $node->field_image['und'][0]['alt'], 'title' => $node->field_image['und'][0]['alt'], 'attributes' => array('class' => 'image lazy'), 'getsize' => FALSE,)));
            if(!isset($node->field_image['und'][1]['uri']) ){
                print '<div class="nodeimage nodeimage_one"><div class="swiper-slide"><span class="zoom" data-zoom-image="'.image_style_url('original', $node->field_image['und'][0]['uri']).'">'.$imgis.'</span></div></div>';
            }else{
                foreach( $node->field_image['und'] as $img ){
                    $allimgs[]='<div class="swiper-slide"><span class="zoom" data-zoom-image="'.image_style_url('original', $img['uri']).'">'.str_replace(' src=', ' data-original=', theme('image_style', array('style_name' => 'product_full', 'path' => $img['uri'], 'alt' => $img['alt'], 'title' => $img['alt'], 'attributes' => array('class' => 'image lazy'), 'getsize' => FALSE,))).'</span></div>';
                }
            }
        }
        
        if( isset($allimgs) and is_array($allimgs) and count($allimgs) ){
            echo '<div class="nodeimage"><div class="swiper-container swiper-container-node"><div class="swiper-wrapper">'.implode('', $allimgs).'</div><div class="swiper-pagination swiper-pagination-node"></div></div>';
            echo '</div>
';
        }
        
        echo '<div>';
        if(isset($node->field_youtube['und'][0]['input']) and strlen($node->field_youtube['und'][0]['input'])){
            echo '<div id="aproductvideo_'.$node->nid.'_0" class="aproductvideo nid_video_'.$node->nid.'_0" onclick="showvideo('.$node->nid.', 0);"><img alt="" src="'.PDX_IMGPATH.'/img/google_youtube.png" /> смотреть видео</div>';
        }
        echo '</div>';

        echo '</div>';
      
        echo '<div class="nodeimage_right">';
        
        
echo '<div class="node_title">';
if( isset( $node->field_premium['und'][0]['value'] ) and is_numeric($node->field_premium['und'][0]['value']) and $node->field_premium['und'][0]['value']>0 and $node->field_premium['und'][0]['value']>time() ){
    echo '<div class="node_premium_ico node_premium_'.$node->field_premium['und'][0]['value'].'" title="Это Premium-объявление">&nbsp;</div>';
}
echo '<div class="subline">';
$numcomment=db_query('SELECT COUNT(r.field_rat1_value) FROM {field_data_field_rat1} as r inner join {field_data_field_item} as i on (r.entity_id=i.entity_id and i.entity_type=\'node\') inner join {node} as n on n.nid=r.entity_id WHERE n.status=1 and r.field_rat1_value>0 and r.entity_type=\'node\' and i.field_item_nid = '.$node->nid);
$numcomment=$numcomment->fetchAssoc();
if( isset($numcomment['COUNT(r.field_rat1_value)']) and is_numeric($numcomment['COUNT(r.field_rat1_value)']) and $numcomment['COUNT(r.field_rat1_value)']>0 ){

    $avg=0;
    $cmts=db_query('SELECT r.field_rat1_value FROM {field_data_field_rat1} as r inner join {field_data_field_item} as i on (r.entity_id=i.entity_id and i.entity_type=\'node\') inner join {node} as n on n.nid=r.entity_id WHERE n.status=1 and r.field_rat1_value>0 and r.entity_type=\'node\' and i.field_item_nid = '.$node->nid);
    while( $cmt=$cmts->fetchAssoc() ){
        $avg+=$cmt['field_rat1_value']*2;
    }
    $avg=intval($avg/$numcomment['COUNT(r.field_rat1_value)']);
    
    echo '<span class="user_rat">';
    if( $avg>=1 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg>=2 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg>=3 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg>=4 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg>=5 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg>=6 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg>=7 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg>=8 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg>=9 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    if( $avg==10 ){ echo '<div class="ratis raty">&nbsp;</div>'; }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
    echo '</span>';

    echo '<a href="#comments">';
    echo pdxnumfoot($numcomment['COUNT(r.field_rat1_value)'], 'отзыв', 'отзывов', 'отзыва');
    echo '</a>';
/*
}else{
    echo '<span class="user_rat">';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '<div class="ratis ratn">&nbsp;</div>';
    echo '</span>';
*/
}else{
    echo '&nbsp;';
}

echo '</div>
</div>';

        
        if(isset($node->body['und'][0]['value']) and strlen($node->body['und'][0]['value'])){
            print '<div class="body_restrict">';
            $ismytext=$node->body['und'][0]['value'];
            if( mb_strpos($ismytext, '<p>')===false and mb_strpos($ismytext, '<li>')===false and mb_strpos($ismytext, '<div>')===false ){
                $ismytext=nl2br($ismytext);
            }
            $ismytext=str_replace('<a ', '<noindex><a rel="nofollow" target="_blank" ', $ismytext);
            $ismytext=str_replace('</a>', '</a></noindex>', $ismytext);
            echo $ismytext;
            echo '</div><div class="body_restrict_more"></div>';
        }
        if(isset($node->field_url['und'][0]['value']) and strlen($node->field_url['und'][0]['value'])){
            $node->field_url['und'][0]['value']=str_replace('"', '', str_replace("'", '', trim(filter_xss(strip_tags($node->field_url['und'][0]['value'])))));
            if( strpos($node->field_url['und'][0]['value'], 'http://')===false and strpos($node->field_url['und'][0]['value'], 'https://')===false ){
                $node->field_url['und'][0]['value']='http://'.$node->field_url['und'][0]['value'];
                $node->field_url['und'][0]['value']=str_replace('///', '//', $node->field_url['und'][0]['value']);
            }
            echo '<div class="item_url">более подробный обзор товара, цена в продаже, отзывы:<br /><noindex><a target="_blank" rel="nofollow" href="'.$node->field_url['und'][0]['value'].'">'.$node->field_url['und'][0]['value'].'</a></noindex></div>';
        }

// ------------- цены начало
    echo '<div class="item_block item_block_price">';



    echo '<div class="price">';
    $price1=$price2=$price4=$price3=$yesprice=$pricelen=0;
    
    if( isset($node->field_price_month['und'][0]['value']) and is_numeric($node->field_price_month['und'][0]['value']) ){
        $price4=$node->field_price_month['und'][0]['value'];
        $price3=round($node->field_price_month['und'][0]['value']/4, 2);
        $price2=round($node->field_price_month['und'][0]['value']/30, 2);
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_week['und'][0]['value']) and is_numeric($node->field_price_week['und'][0]['value']) ){
        $price3=$node->field_price_week['und'][0]['value'];
        $price2=round($node->field_price_week['und'][0]['value']/7, 2);
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_day['und'][0]['value']) and is_numeric($node->field_price_day['und'][0]['value']) ){
        $price2=$node->field_price_day['und'][0]['value'];
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        if( !isset($price3) or !is_numeric($price3) or $price3<1 ){
            $price3=$price2*7;
        }
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($node->field_price_hour['und'][0]['value']) and is_numeric($node->field_price_hour['und'][0]['value']) ){
        $price1=$node->field_price_hour['und'][0]['value'];
        if( !isset($price2) or !is_numeric($price2) or $price2<1 ){
            $price2=$price1*24;
        }
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        if( !isset($price3) or !is_numeric($price3) or $price3<1 ){
            $price3=$price2*7;
        }
        $yesprice=1;
    }
    if( $yesprice ){
        echo '<script type="text/javascript"> var calcp=new Array(); calcp[0]=';
        if( isset($price1) and is_numeric($price1) ){
            echo $price1;
        }else{
            echo '0';
        }
        echo '; calcp[1]=';
        if( isset($price2) and is_numeric($price2) ){
            echo round($price2/24, 3);
        }else{
            echo '0';
        }
        echo '; calcp[2]=';
        if( isset($price3) and is_numeric($price3) ){
            echo round($price3/168, 3);
        }else{
            echo '0';
        }
        echo '; calcp[3]=';
        if( isset($price4) and is_numeric($price4) ){
            echo round($price4/720, 3);
        }else{
            echo '0';
        }
        echo ';';
        echo ' </script>';
    }
    if( isset($node->field_price_min['und'][0]['value']) and is_numeric($node->field_price_min['und'][0]['value']) ){
        if( $node->field_price_min['und'][0]['value']>23 ){
            $price1=0;
            if( $node->field_price_min['und'][0]['value']>167 ){
                $price2=0;
            }
        }
    }
    
    if( $yesprice ){
        $nodiscount=$isdisc=0;
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            echo '<div class="price1">';
            if( isset( $node->field_cur['und'][0]['value'] ) and $node->field_cur['und'][0]['value']>1 ){
                echo '* ';
            }
            echo '<strong class="isprice">';
            $tmponly=number_format($price1, 0, '', ' ');
/*
            if( $pricelen>0 and $pricelen>strlen($tmponly) ){
                switch($pricelen-strlen($tmponly)){
                case 10: echo '&nbsp;';
                case 9: echo '&nbsp;';
                case 8: echo '&nbsp;';
                case 7: echo '&nbsp;';
                case 6: echo '&nbsp;';
                case 5: echo '&nbsp;';
                case 4: echo '&nbsp;';
                case 3: echo '&nbsp;';
                case 2: echo '&nbsp;';
                case 1: echo '&nbsp;';
                }
            }
*/
            echo $tmponly.'</strong>';
            echo ' '.$sign;
            echo ' / час</div>';
        }else{
            echo '<div class="price0"><strong>';
/*
            if( $pricelen>0 and $pricelen>1 ){
                switch($pricelen-1){
                case 10: echo '&nbsp;';
                case 9: echo '&nbsp;';
                case 8: echo '&nbsp;';
                case 7: echo '&nbsp;';
                case 6: echo '&nbsp;';
                case 5: echo '&nbsp;';
                case 4: echo '&nbsp;';
                case 3: echo '&nbsp;';
                case 2: echo '&nbsp;';
                case 1: echo '&nbsp;';
                }
            }
*/
            echo '-</strong>';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / час</div>';
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*24;
        }
        if( isset($price2) and is_numeric($price2) and $price2>0 ){
            echo '<div class="price2">';
            if( isset( $node->field_cur['und'][0]['value'] ) and $node->field_cur['und'][0]['value']>1 ){
                echo '* ';
            }
            echo '<strong class="isprice">';
            $tmponly=number_format($price2, 0, '', ' ');
/*
            if( $pricelen>0 and $pricelen>strlen($tmponly) ){
                switch($pricelen-strlen($tmponly)){
                case 10: echo '&nbsp;';
                case 9: echo '&nbsp;';
                case 8: echo '&nbsp;';
                case 7: echo '&nbsp;';
                case 6: echo '&nbsp;';
                case 5: echo '&nbsp;';
                case 4: echo '&nbsp;';
                case 3: echo '&nbsp;';
                case 2: echo '&nbsp;';
                case 1: echo '&nbsp;';
                }
            }
*/
            echo $tmponly.'</strong>';
            echo ' '.$sign;
            echo ' / день (24 ч)';
            if( $nodiscount>0 and $nodiscount>$price2 ){
                $isdisc=$nodiscount-$price2;
                $isdisc=floor(($isdisc/$nodiscount)*100);
                if( $isdisc>0 ){
                    echo ' <sub title="Скидка за весь период, по сравнению с ценой за минимальный период">'.$isdisc.'%</sub>';
                }
            }
            echo '</div>';
        }else{
            echo '<div class="price0"><strong>';
/*
            if( $pricelen>0 and $pricelen>1 ){
                switch($pricelen-1){
                case 10: echo '&nbsp;';
                case 9: echo '&nbsp;';
                case 8: echo '&nbsp;';
                case 7: echo '&nbsp;';
                case 6: echo '&nbsp;';
                case 5: echo '&nbsp;';
                case 4: echo '&nbsp;';
                case 3: echo '&nbsp;';
                case 2: echo '&nbsp;';
                case 1: echo '&nbsp;';
                }
            }
*/
            echo '-</strong>';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / день</div>';
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*168;
        }elseif( isset($price2) and is_numeric($price2) and $price2>0 ){
            $nodiscount=$price2*7;
        }
        if( isset($price3) and is_numeric($price3) and $price3>0 ){
            echo '<div class="price3">';
            if( isset( $node->field_cur['und'][0]['value'] ) and $node->field_cur['und'][0]['value']>1 ){
                echo '* ';
            }
            echo '<strong class="isprice">';
            $tmponly=number_format($price3, 0, '', ' ');
/*
            if( $pricelen>0 and $pricelen>strlen($tmponly) ){
                switch($pricelen-strlen($tmponly)){
                case 10: echo '&nbsp;';
                case 9: echo '&nbsp;';
                case 8: echo '&nbsp;';
                case 7: echo '&nbsp;';
                case 6: echo '&nbsp;';
                case 5: echo '&nbsp;';
                case 4: echo '&nbsp;';
                case 3: echo '&nbsp;';
                case 2: echo '&nbsp;';
                case 1: echo '&nbsp;';
                }
            }
*/
            echo $tmponly.'</strong>';
            echo ' '.$sign;
            echo ' / неделю';
            if( $nodiscount>0 and $nodiscount>$price3 ){
                $isdisc=$nodiscount-$price3;
                $isdisc=floor(($isdisc/$nodiscount)*100);
                if( $isdisc>0 ){
                    echo ' <sub title="Скидка за весь период, по сравнению с ценой за минимальный период">'.$isdisc.'%</sub>';
                }
            }
            echo '</div>';
        }else{
            echo '<div class="price0"><strong>';
/*
            if( $pricelen>0 and $pricelen>1 ){
                switch($pricelen-1){
                case 10: echo '&nbsp;';
                case 9: echo '&nbsp;';
                case 8: echo '&nbsp;';
                case 7: echo '&nbsp;';
                case 6: echo '&nbsp;';
                case 5: echo '&nbsp;';
                case 4: echo '&nbsp;';
                case 3: echo '&nbsp;';
                case 2: echo '&nbsp;';
                case 1: echo '&nbsp;';
                }
            }
*/
            echo '-</strong>';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / неделю</div>';
        }
        if( isset($price1) and is_numeric($price1) and $price1>0 ){
            $nodiscount=$price1*672;
        }elseif( isset($price2) and is_numeric($price2) and $price2>0 ){
            $nodiscount=$price2*28;
        }elseif( isset($price3) and is_numeric($price3) and $price3>0 ){
            $nodiscount=$price2*4;
        }
        if( isset($price4) and is_numeric($price4) and $price4>0 ){
            echo '<div class="price4">';
            if( isset( $node->field_cur['und'][0]['value'] ) and $node->field_cur['und'][0]['value']>1 ){
                echo '* ';
            }
            echo '<strong class="isprice">'.number_format($price4, 0, '', ' ').'</strong>';
            echo ' '.$sign;
            echo ' / месяц (30 дн)';
            if( $nodiscount>0 and $nodiscount>$price4 ){
                $isdisc=$nodiscount-$price4;
                $isdisc=floor(($isdisc/$nodiscount)*100);
                if( $isdisc>0 ){
                    echo ' <sub title="Скидка за весь период, по сравнению с ценой за минимальный период">'.$isdisc.'%</sub>';
                }
            }
            echo '</div>';
        }else{
            echo '<div class="price4">&nbsp;</div>';
        }
       
    }else{
        echo '<strong>цену уточняйте</strong>';
    }
    echo '</div>';

    if(isset($node->field_price_about['und'][0]['value']) and strlen($node->field_price_about['und'][0]['value'])){
        print '<div class="price_more"><div class="body_restrict">';

        $ismytext=$node->field_price_about['und'][0]['value'];
        if( mb_strpos($ismytext, '<p>')===false and mb_strpos($ismytext, '<li>')===false and mb_strpos($ismytext, '<div>')===false ){
            $ismytext=nl2br($ismytext);
        }
        $ismytext=str_replace('<a ', '<noindex><a rel="nofollow" target="_blank" ', $ismytext);
        $ismytext=str_replace('</a>', '</a></noindex>', $ismytext);
        echo $ismytext;

        echo '</div><div class="body_restrict_more"></div></div>';
    }

    $rent_title='Хочу арендовать';
    $rent_time='&nbsp;';
    if( isset($node->field_status['und'][0]['value']) and $node->field_status['und'][0]['value']==2 ){
        $rent_time='в аренде';
        if( isset($node->field_datend['und'][0]['value']) and strlen($node->field_datend['und'][0]['value']) ){
            $eventdate=strtotime($node->field_datend['und'][0]['value']);
            if( isset($eventdate) and is_numeric($eventdate) and $eventdate>0 and $eventdate>time() ){
                $rent_time.='<span class="item_offline_text_dt item_offline_text_dt_'.$eventdate.'"></span>';
            }
        }
        $rent_title='Арендовать после';
    }

    $rentfrom=1;
    $rented=array('1'=>'час(а)', '2'=>'день(я)', '3'=>'неделя(и)', '4'=>'месяц(а)');
    if( isset($node->field_price_min['und'][0]['value']) and is_numeric($node->field_price_min['und'][0]['value']) and $node->field_price_min['und'][0]['value']>0 ){
        if( $node->field_price_min['und'][0]['value']>23 ){
            unset($rented[1]);
            if( $node->field_price_min['und'][0]['value']>167 ){
                unset($rented[2]);
                if( $node->field_price_min['und'][0]['value']>719 ){
                    unset($rented[3]);
                }else{
                    $rentfrom=intval($node->field_price_min['und'][0]['value']/168);
                }
            }else{
                $rentfrom=intval($node->field_price_min['und'][0]['value']/24);
            }
        }else{
            $rentfrom=$node->field_price_min['und'][0]['value'];
        }
    }
    
    if( $yesprice ){
        echo '<div class="calc">';
        echo '<div class="calc_body">';
        echo '<div class="calc_in"><div class="lbl">Период аренды:</div><input type="text" value="'.$rentfrom.'" class="form-text" size="2" onclick=" jQuery(this).select(); " id="calc'.$node->nid.'count" onkeyup=" window.setTimeout(\'recalc('.$node->nid.');\', 333); " /> <select id="calc'.$node->nid.'ed" onchange=" recalc('.$node->nid.'); ">';
        if( isset($rented) and is_array($rented) and count($rented) ){
            foreach( $rented as $rentin=>$rentout ){
                echo '<option value="'.$rentin.'">'.$rentout.'</option>';
            }
        }
        echo '</select>';
        if( isset( $node->field_delivery1_price['und'][0]['value'] ) and is_numeric( $node->field_delivery1_price['und'][0]['value'] ) and $node->field_delivery1_price['und'][0]['value']>0 ){
            echo ' <label><input type="checkbox" value="'.$node->field_delivery1_price['und'][0]['value'].'" class="form-checkbox" id="calc'.$node->nid.'bx" onchange=" recalc('.$node->nid.'); " /> + доставка по городу</label>';
        }
        echo '</div><div class="lbl">Примерная стоимость (для ознакомления):</div><div class="calc_res" id="calc'.$node->nid.'res"></div></div>';
        echo '</div>';
    }
    echo '<div class="rent_button">';
    if($user->uid){
        echo '<div class="inrent1">'.$rent_time.'</div>';
        echo '<div class="rentis" onclick=" jQuery(\'#showrent'.$node->nid.'\').show(); dealarch('.$node->nid.'); ">'.$rent_title.'</div>';

        echo '<div style="display: none;" class="inlinebodydiv" id="showrent'.$node->nid.'"><img class="closeimg" onclick=" jQuery(this).parent().hide(); " alt="x" src="'.PDX_IMGPATH.'/img/ico_close.png" /><div class="inlinetitle"><div class="inlinetitlein"><a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/otvet-na-obyavlenie.html" title="Открыть справку в новом окне">&nbsp;</a>Заявка на аренду товара "<span>'.$node->title.'</span>"</div></div><div class="fieldset-wrapper"><div class="cnt rentcnt">';

        echo '<div class="rentcnt">Арендовать товар на <input type="text" value="'.$rentfrom.'" class="form-text" size="2" id="rent'.$node->nid.'count" /> <select id="rent'.$node->nid.'ed">';
        if( isset($rented) and is_array($rented) and count($rented) ){
            foreach( $rented as $rentin=>$rentout ){
                echo '<option value="'.$rentin.'">'.$rentout.'</option>';
            }
        }
        echo '</select> , начиная с <input type="text" id="rent'.$node->nid.'date" value="';
        if( isset($eventdate) and is_numeric($eventdate) and $eventdate>0 and $eventdate>time() ){
            echo date('Y-m-d', $eventdate);
        }
        echo '" class="form-text form-dt" /> <select id="rent'.$node->nid.'d"><option value="0">Способ доставки</option>';
        if( isset( $node->field_delivery1['und'][0]['value'] ) and $node->field_delivery1['und'][0]['value']==1 ){
            echo '<option value="1">Самовывоз</option>';
        }
        if( isset( $node->field_delivery2['und'][0]['value'] ) and $node->field_delivery2['und'][0]['value']==1 ){
            echo '<option value="2">Доставка по городу</option>';
        }
        if( isset( $node->field_delivery3['und'][0]['value'] ) and $node->field_delivery3['und'][0]['value']==1 ){
            echo '<option value="3">Доставка вне города</option>';
        }
        
        echo '</select></div>';
        echo '<div class="zalogis"><div class="lbl">Если в качестве залога вы хотите оставить предмет, опишите его:</div><div><input type="text" value="" id="rent'.$node->nid.'zalog" class="form-text" /></div></div>';
        echo '<div class="rentsmb"><span class="is_a" onclick="rentsmb('.$node->nid.');">Отправить заявку арендодателю</span></div>';
        echo '<div class="clear">&nbsp;</div>';
        echo '<div class="item_block item_block_arch">';
        echo '<div class="item_block_ttl">Архив Ваших сделок по данному товару: *</div>';
        echo '<div id="dealarch_'.$node->nid.'">&nbsp;</div>';
        echo '</div>';
        echo '</div></div></div>';
    }else{
        echo '<div class="inrent1 inrent1_offline">'.$rent_time.'</div>';
        echo '<div class="rentis" onclick=" stpr(event || window.event); jQuery(\'#forreg\').addClass(\'regshow\'); ">'.$rent_title.'</div>';
    }
    echo '</div>';
    echo '<div class="favbutton favbutton_node fav_node_'.$node->nid.'" style="display: none;"><span class="fv" onclick="favswitch(\'node\', '.$node->nid.');">&nbsp;</span></div>';
// ----------------- цена конец


    echo '</div>';

//      print render($content);


      echo '</div>';
      
// ------------- завершение левого и правого блока      
echo '<div class="clear">&nbsp;</div><div class="item_bottom item_bottom_cnt">';
echo '<div class="abuseme abusenid" onclick=" abuse(2, '.$node->nid.', this); "><span></span><img alt="" src="'.PDX_IMGPATH.'/img/msg_alert.png" />Пожаловаться на объявление</div>';

        if( isset($node->field_time['und'][0]['value']) and strlen($node->field_time['und'][0]['value']) ){
            echo '<div class="item_block">';
            echo '<div class="item_block_ttl">Время работы арендодателя:</div>';
            echo '<div class="body_restrict">'.nl2br($node->field_time['und'][0]['value']).'</div><div class="body_restrict_more"></div>';
            echo '</div>';
        }
        
        $atmp=array();
        $atmpind=0;
        
        $tmponly='';
        $tmponly.= '<li class="';
        if(isset($node->field_rent_apply1['und'][0]['value']) and $node->field_rent_apply1['und'][0]['value']==1){
//            $tmponly.= 'is_yes2';
            $atmpind=1;
        }else{
            $tmponly.= 'is_no';
            $atmpind=0;
        }
        $tmponly.= '">требуется предоставить скан страниц паспорта;</li>';
        $atmp[$atmpind][]=$tmponly;

        $tmponly='';
        $tmponly.= '<li class="';
        if(isset($node->field_rent_apply4['und'][0]['value']) and $node->field_rent_apply4['und'][0]['value']==1){
//            $tmponly.= 'is_yes2';
            $atmpind=1;
        }else{
            $tmponly.= 'is_no';
            $atmpind=0;
        }
        $tmponly.= '">только резидентам и лицам, имеющим вид на жительство;</li>';
        $atmp[$atmpind][]=$tmponly;

        $tmponly='';
        $tmponly.= '<li class="';
        if(isset($node->field_rent_apply3['und'][0]['value']) and $node->field_rent_apply3['und'][0]['value']==1){
//            $tmponly.= 'is_yes2';
            $atmpind=1;
        }else{
            $tmponly.= 'is_no';
            $atmpind=0;
        }
        $tmponly.= '">требуется заполнить договор проката';
        if(isset($node->field_rent_apply3['und'][0]['value']) and $node->field_rent_apply3['und'][0]['value']==1){
            $isfile=db_query('select field_dogovor_fid from {field_data_field_dogovor} where entity_type=\'user\' and entity_id='.$node->uid);
            $isfile=$isfile->fetchAssoc();
            if( isset($isfile['field_dogovor_fid']) and is_numeric($isfile['field_dogovor_fid']) ){
                $uri = file_load($isfile['field_dogovor_fid'])->uri;
                $uri = file_create_url($uri);
                if(isset($uri) and strlen($uri)){
                    $tmponly.= ' <a class="is_em" href="'.$uri.'">(скачать образец договора арендодателя)</a>';
                }
            }
        }
        $tmponly.= ';</li>';
        $atmp[$atmpind][]=$tmponly;

        $tmponly='';
        $tmponly.= '<li class="';
        if(isset($node->field_rent_apply7['und'][0]['value']) and $node->field_rent_apply7['und'][0]['value']==1){
//            $tmponly.= 'is_yes2';
            $atmpind=1;
        }else{
            $tmponly.= 'is_no';
            $atmpind=0;
        }
        $tmponly.= '">требуется составить расписку;</li>';
        $atmp[$atmpind][]=$tmponly;

        $tmponly='';
        $tmponly.= '<li class="';
        if(isset($node->field_rent_apply2['und'][0]['value']) and $node->field_rent_apply2['und'][0]['value']==1){
//            $tmponly.= 'is_yes2';
            $atmpind=1;
        }else{
            $tmponly.= 'is_no';
            $atmpind=0;
        }
        $tmponly.= '">требуется оставить оригинал паспорта;</li>';
        $atmp[$atmpind][]=$tmponly;

        if(isset($node->field_rent_price['und'][0]['value']) and is_numeric($node->field_rent_price['und'][0]['value']) ){
            $tmponly='';
            if( $node->field_rent_price['und'][0]['value']==0 ){
                $tmponly.= '<li class="is_no">залоговая сумма не нужна *;</li>';
                $atmpind=0;
            }else{
                $tmponly.= '<li';
//                $tmponly.= ' class="is_yes2"';
                $tmponly.= '>требуется залоговая сумма в размере: ';
                $tmponly.= '<em><strong class="isprice">'.number_format($node->field_rent_price['und'][0]['value'], 0, '', ' ').'</strong>';
                $tmponly.= ' '.$sign;
                $tmponly.= '</em>';
                $tmponly.= ' *;</li>';
                $atmpind=1;
            }
            $atmp[$atmpind][]=$tmponly;
        }



        if(isset($node->field_rent_apply5['und'][0]['value']) and is_numeric($node->field_rent_apply5['und'][0]['value']) ){
            $tmponly='';
            $tmponly.= '<li';
//            $tmponly.= ' class="is_yes2"';
            $tmponly.= '>только лицам старше ';
                $tmponly.= '<em><strong>'.$node->field_rent_apply5['und'][0]['value'].'</strong> ';
                switch( $node->field_rent_apply5['und'][0]['value'] ){
                case 21:
                    $tmponly.= 'года';
                    break;
                default:
                    $tmponly.= 'лет';
                    break;
                }
                $tmponly.= '</em>';
            $tmponly.= ';</li>';
            $atmpind=1;
            $atmp[$atmpind][]=$tmponly;
        }


        if(isset($node->field_rent_apply6['und'][0]['value']) and strlen($node->field_rent_apply6['und'][0]['value']) ){
            $tmponly='';
            $tmponly.= '<li';
//            $tmponly.= ' class="is_yes2"';
            $tmponly.= '>другие условия:<br />';
            $tmponly.= '<div class="body_restrict">';

            $ismytext=$node->field_rent_apply6['und'][0]['value'];
            if( mb_strpos($ismytext, '<p>')===false and mb_strpos($ismytext, '<li>')===false and mb_strpos($ismytext, '<div>')===false ){
                $ismytext=nl2br($ismytext);
            }
            $ismytext=str_replace('<a ', '<noindex><a rel="nofollow" target="_blank" ', $ismytext);
            $ismytext=str_replace('</a>', '</a></noindex>', $ismytext);
            $tmponly.= $node->field_rent_apply6['und'][0]['value'];

            $tmponly.= '</div><div class="body_restrict_more"></div>';
            $tmponly.= '</li>';
            $atmpind=1;
            $atmp[$atmpind][]=$tmponly;
        }


        $tmponly='';
        $tmponly.= '<li class="';
        if(isset($node->field_onlycompany['und'][0]['value']) and $node->field_onlycompany['und'][0]['value']==1){
//            $tmponly.= 'is_yes2';
            $atmpind=1;
        }else{
            $tmponly.= 'is_no';
            $atmpind=0;
        }
        $tmponly.= '">только компаниям и юридическим лицам;</li>';
        $atmp[$atmpind][]=$tmponly;
        
        $tmponly='';
        if( isset($atmp[1]) and is_array($atmp[1]) and count($atmp[1]) ){
            $tmponly.= '<ul>';
            $tmponly.= implode('',$atmp[1]);
            $tmponly.= '</ul>';
        }
        if(isset($node->field_rent_price['und'][0]['value']) and is_numeric($node->field_rent_price['und'][0]['value']) and $node->field_rent_price['und'][0]['value']>0 ){
            $tmponly.= '<div class="prim">* при получении товара, арендатор оставляет залоговую сумму, не оплачивая время, на которое товар берется в аренду. После возвращения товара, арендатор получает залоговую сумму обратно, за вычетом стоимости времени, в течение которого он держал товар у себя.</div>';
        }
        if( isset($node->field_price_crashed['und'][0]['value']) and is_numeric($node->field_price_crashed['und'][0]['value']) ){
            $tmponly.= '<div class="prim">';
            $tmponly.= '** полная стоимость в случае поломки/потери: <strong class="isprice">'.number_format($node->field_price_crashed['und'][0]['value'], 0, '', ' ');
            $tmponly.= '</strong>';
            $tmponly.= ' '.$sign;
            $tmponly.= '</div>';
        }

        if( isset($tmponly) and strlen($tmponly) ){
            echo '<div class="item_block">';
            echo '<div class="item_block_ttl">Условия аренды:</div>';
            echo $tmponly;
            echo '</div>';
        }
//        if( isset($atmp[0]) and is_array($atmp[0]) and count($atmp[0]) ){ 
//            echo implode('',$atmp[0]);
//        }


    if(isset($node->field_tobuy['und'][0]['value']) and $node->field_tobuy['und'][0]['value']==1){
        echo '<div class="gift_yes">готов "подарить" товар, если стоимость аренды достигла полной стоимости товара</div>';
    }
      


        $tmponly='';
        if(isset($node->field_delivery1['und'][0]['value']) and $node->field_delivery1['und'][0]['value']==1){
            $tmponly.='<li class="';
//            if(isset($node->field_delivery1['und'][0]['value']) and $node->field_delivery1['und'][0]['value']==1){
//                echo 'is_yes';
//            }else{
//                echo 'is_no';
//            }
            $tmponly.='">арендатор самостоятельно забирает/возвращает товар;</li>';
        }

        if(isset($node->field_delivery2['und'][0]['value']) and $node->field_delivery2['und'][0]['value']==1){
            $tmponly.='<li class="';
//            if(isset($node->field_delivery2['und'][0]['value']) and $node->field_delivery2['und'][0]['value']==1){
//                echo 'is_yes';
//            }else{
//                echo 'is_no';
//            }
            $tmponly.='">доставка/возврат по адресу арендатора в пределах города';
            if( isset( $node->field_delivery1_price['und'][0]['value'] ) and is_numeric($node->field_delivery1_price['und'][0]['value']) ){
                $tmponly.=' (стоимость: ';
                if( $node->field_delivery1_price['und'][0]['value']==0 ){
                    $tmponly.='<strong>бесплатно</strong>';
                }else{
                    $tmponly.='<strong>'.number_format($node->field_delivery1_price['und'][0]['value'], 0, '', ' ').'</strong>';
                    $tmponly.=' '.$sign;
                }
                $tmponly.=')';
            }
            $tmponly.=';</li>';
        }
        
        if(isset($node->field_delivery3['und'][0]['value']) and $node->field_delivery3['und'][0]['value']==1){
            $tmponly.='<li class="';
//            if(isset($node->field_delivery3['und'][0]['value']) and $node->field_delivery3['und'][0]['value']==1){
//                echo 'is_yes';
//            }else{
//                echo 'is_no';
//            }
            $tmponly.='">доставка/возврат по адресу арендатора за пределами города;</li>';
        }
        
        if( isset($tmponly) and strlen($tmponly) ){
            echo '<div class="item_block">';
            echo '<div class="item_block_ttl">Варианты доставки/возврата:</div>';
            echo '<ul>';
            echo $tmponly;
            echo '</ul>';
            echo '</div>';
        }

      
      
      

    if(isset($node->field_youtube['und'][0]['input']) and strlen($node->field_youtube['und'][0]['input'])){
        echo '<div style="display: none;" class="inlinebodydiv" id="videoblock'.$node->nid.'"><img class="closeimg" onclick=" jQuery(this).parent().hide(); " alt="x" src="'.PDX_IMGPATH.'/img/ico_close.png" /><div class="inlinetitle"><div class="inlinetitlein">Видео про "'.$node->title.'"</div></div><div class="fieldset-wrapper"><div class="cnt"></div></div></div>';
    }

if( isset( $node->field_cur['und'][0]['value'] ) and $node->field_cur['und'][0]['value']>1 ){
    echo '<div class="aboutcurs">';
    switch( $node->field_cur['und'][0]['value'] ){
    case 2:
        echo '<div class="aboutcurs_right"><p>Наведите указатель мыши на цену предмета, чтобы увидеть цену в долларах.</p><p><strong>Курс НБ сегодня: <span class="showcurs"></span></strong></p></div>
    <div class="aboutcurs_left"><p>* стоимость аренды товара указана в долларах, и приводятся на сайте в национальной валюте для ознакомления, в пересчете по текущему курсу НБ. Реальная стоимость аренды будет зависеть от курса доллара на момент заключения договора проката.</p></div>';
        break;
    default:
        echo '<div class="aboutcurs_right"><p>Наведите указатель мыши на цену предмета, чтобы увидеть цену в евро.</p><p><strong>Курс НБ сегодня: <span class="showcurs2"></span></strong></p></div>
    <div class="aboutcurs_left"><p>* стоимость аренды товара указана в евро, и приводятся на сайте в национальной валюте для ознакомления, в пересчете по текущему курсу НБ. Реальная стоимость аренды будет зависеть от курса евро на момент заключения договора проката.</p></div>';
    }
    echo '</div>';
}
echo '</div>';
      
      ?>

<?php
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
    if( isset($content['links']['comment']) ){
        unset($content['links']['comment']);
    }
      if($content['links']): ?>

<div class="clear">&nbsp;</div><div class="item_bottom">
      <div class="links-container">
        <?php print render($content['links']); ?>
      </div>
      
      
<?php

    $soc_title=str_replace('"', '', 'Возьми в аренду в '.PDX_CITY_NAME2.': '.$node->title);
    if( mb_strlen($soc_title)>64 ){
        $soc_title=mb_substr($soc_title,0,61).'...';
    }
    $soc_desc='';
    if( isset($price1) and is_numeric($price1) and $price1>0 ){
        $soc_desc.=number_format($price1, 0, '', ' ').' '.strip_tags($sign).' / час. ';
    }elseif( isset($price2) and is_numeric($price2) and $price2>0 ){
        $soc_desc.=number_format($price2, 0, '', ' ').' '.strip_tags($sign).' / день. ';
    }elseif( isset($price3) and is_numeric($price3) and $price3>0 ){
        $soc_desc.=number_format($price3, 0, '', ' ').' '.strip_tags($sign).' / месяц. ';
    }
    if( isset($node->body['und'][0]['value']) and strlen($node->body['und'][0]['value']) ){
        $soc_desc.=str_replace('"', '', strip_tags($node->body['und'][0]['value']));
    }
    if( mb_strlen($soc_desc)>127 ){
        $soc_desc=mb_substr($soc_desc,0,123).'...';
    }
    $isimg='';
    if(isset($node->field_image['und'][0]['uri']) and strlen($node->field_image['und'][0]['uri'])){
        $isimg = image_style_url('product_full', $node->field_image['und'][0]['uri']);
    }
    echo pdxgetshare3($soc_title, $soc_desc, $isimg);
    
    
?>      
      
</div>
<div class="clear">&nbsp;</div>
<div class="liexport">Сохранить в <a href="javascript: void(0);" onclick=" window.location.replace('/needto_op.php?op=6&nid=<?php echo $node->nid; ?>'); " class="li_pdf" title="Сохранить объявление как PDF">PDF</a><a href="javascript: void(0);" onclick=" window.location.replace('/needto_op.php?op=7&nid=<?php echo $node->nid; ?>'); " class="li_pdf" title="Сохранить объявление как DOC">DOC</a></div>
<div class="sharehtml"><div class="ttl">Поделиться объявлением <br style="display: none;" />на своем сайте (HTML): (<a target="_blank" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/html/share/node/<?php echo $node->nid; ?>.htm">см. как выглядит</a>)</div>
<div class="sharehtmlmn">
<input type="text" value='<iframe width="100%" height="537" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/html/share/node/<?php echo $node->nid; ?>.htm" frameborder="0"></iframe>' onclick=" jQuery(this).select(); " />
</div>
</div>
    <?php endif; ?>
    <?php if (!$teaser): ?>
      <div class="clearfix">
        <?php print render($content['comments']); ?>
      </div>
    <?php endif; ?>
  </div><!--end node container-->
</div><!--end node-->

<?php

if( isset($numcomment['COUNT(r.field_rat1_value)']) and is_numeric($numcomment['COUNT(r.field_rat1_value)']) and $numcomment['COUNT(r.field_rat1_value)']>0 ){

    echo '<div id="comments" class="item_block">';
    echo '<div class="item_block_ttl">Отзывы арендаторов:</div>';

    $cmts=db_query('SELECT r.field_rat1_value, e.field_reason1_value, u.uid, u.picture, ur.field_userrat2_value, un.field_name_value FROM {field_data_field_rat1} as r inner join {field_data_field_item} as i on (r.entity_id=i.entity_id and i.entity_type=\'node\') inner join {field_data_field_reason1} as e on (r.entity_id=e.entity_id and e.entity_type=\'node\') inner join {field_data_field_user2} as u2 on (r.entity_id=u2.entity_id and u2.entity_type=\'node\') left join {users} as u on u2.field_user2_uid=u.uid left join {field_data_field_userrat2} as ur on (u.uid=ur.entity_id and ur.entity_type=\'user\') left join {field_data_field_name} as un on (u.uid=un.entity_id and un.entity_type=\'user\') inner join {node} as n on n.nid=r.entity_id WHERE n.status=1 and r.field_rat1_value>0 and r.entity_type=\'node\' and i.field_item_nid = '.$node->nid.' order by n.created desc limit 0,7');
    while( $cmt=$cmts->fetchAssoc() ){
        echo '<div class="cmt_item">';

        echo '<div class="cmt_item_left">';

        if( isset($cmt['field_userrat2_value']) and is_numeric($cmt['field_userrat2_value']) and $cmt['field_userrat2_value']>0 ){
                $cmt['field_userrat2_value']=intval($cmt['field_userrat2_value']/10);
                echo '<div class="user_rat">';
                if( $cmt['field_userrat2_value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                echo '</div>';
            
        }
        
        if( isset($cmt['uid']) and is_numeric($cmt['uid']) ){
            echo '<div class="cmt_item_name"';
            if( isset($cmt['picture']) and is_numeric($cmt['picture']) and $cmt['picture']>0 ){
                $uri = file_load($cmt['picture'])->uri;
                if( isset($uri) and strlen($uri) ){
                    $uri = image_style_url('user', $uri);
                    echo ' style="background: transparent url(\''.$uri.'\') no-repeat left top;"';
                }
            }else{
                echo ' style="background: transparent url(\'/sites/all/themes/pdxneedto/img/nophoto.png\') no-repeat left top;"';
            }
            echo '><a href="/user/'.$cmt['uid'].'">';
            echo $cmt['field_name_value'];
            echo '</a></div>';
        }else{
            echo '<div class="cmt_item_name" style="background: transparent url(\'/sites/all/themes/pdxneedto/img/nouser.png\') no-repeat left top;">пользователь удален</div>';
        }
        
        echo '</div>';

        echo '<div class="cmt_item_right">';
        if( isset($cmt['field_rat1_value']) and is_numeric($cmt['field_rat1_value']) and $cmt['field_rat1_value']>0 ){
                $cmt['field_rat1_value']=$cmt['field_rat1_value']*2;
                echo '<div class="user_rat">';
                if( $cmt['field_rat1_value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                echo '</div>';
        }
        
        echo '<div class="cmt_desc">';
        if( isset($cmt['field_reason1_value']) and strlen($cmt['field_reason1_value']) ){
            echo nl2br($cmt['field_reason1_value']);
        }
        echo '</div>';

        echo '</div>';
        
        echo '<div class="clear">&nbsp;</div></div>';
    }


    echo '</div>';

}
}
}
}
?>