<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


if( isset($_GET['nid']) and is_numeric($_GET['nid']) ){
    global $user; if( isset($user->roles[3]) or isset($user->roles[4]) or isset($user->roles[5]) ){
        echo '<div>';
        echo '<a target="_blank" href="/ppp.php?nid='.$_GET['nid'].'"><strong>Печать ценника</strong></a>';
        echo '<div style="float: right; padding: 7px 0 0 39px; height: 19px; background: transparent url(\'/sites/all/themes/pdxbagz/img/edit.png\') no-repeat left top;"><a href="/node/'.$_GET['nid'].'/edit?destination=admin/mystock">Редактировать товар</a></div>';
        echo '<div class="clear">&nbsp;</div></div>';
        echo '<div class="stock_prinfo">';

/*
$fld='manufacturer';
$fields=db_query('select data from {field_config} where field_name=\'field_'.$fld.'\'');
$fields=$fields->fetchAssoc();
if(isset($fields['data'])){
    $fields['data']=unserialize($fields['data']);
    if(isset($fields['data']['settings']['allowed_values']) and is_array($fields['data']['settings']['allowed_values']) and count($fields['data']['settings']['allowed_values'])){
        echo '<div class="item"><div class="label">Бренд</div><select class="pr_'.$fld.'" name="pr_'.$fld.'" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', \'value\'); } "><option value="">Выберите...</option>';
        $curitem=db_query('select field_'.$fld.'_value from {field_data_field_'.$fld.'} where entity_type=\'node\' and entity_id='.$_GET['nid']);
        $curitem=$curitem->fetchAssoc();
        
        foreach($fields['data']['settings']['allowed_values'] as $term=>$name){
            echo '<option value="'.$term.'"';
            if( isset($curitem['field_'.$fld.'_value']) and $curitem['field_'.$fld.'_value']==$name ){
                echo ' selected="selected"';
            }
            echo '>'.$name.'</option>';
        }
        echo '</select></div>';
    }
}
*/

$fld='discount';
$fields=db_query('select data from {field_config} where field_name=\'field_'.$fld.'\'');
$fields=$fields->fetchAssoc();
if(isset($fields['data'])){
    $fields['data']=unserialize($fields['data']);
    if(isset($fields['data']['settings']['allowed_values']) and is_array($fields['data']['settings']['allowed_values']) and count($fields['data']['settings']['allowed_values'])){
        $curitem=db_query('select field_'.$fld.'_value from {field_data_field_'.$fld.'} where entity_type=\'node\' and entity_id='.$_GET['nid']);
        $curitem=$curitem->fetchAssoc();

        echo '<div class="item"><div class="label">Скидка</div><select class="pr_'.$fld.'" name="pr_'.$fld.'" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', \'value\'); } "><option value="">Выберите...</option>';
        foreach($fields['data']['settings']['allowed_values'] as $term=>$name){
            echo '<option value="'.$term.'"';
            if( isset($curitem['field_'.$fld.'_value']) and $curitem['field_'.$fld.'_value']==$term ){
                echo ' selected="selected"';
            }
            echo '>'.$name.'</option>';
        }
        echo '</select></div>';
    }
}

/*
$fld='kef';
echo '<div class="item"><div class="label">Коэффициент</div><input size="11" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
$curitem=db_query('select field_'.$fld.'_value from {field_data_field_'.$fld.'} where entity_type=\'node\' and entity_id='.$_GET['nid']);
$curitem=$curitem->fetchAssoc();
if( isset($curitem['field_'.$fld.'_value']) and strlen($curitem['field_'.$fld.'_value']) ){
    echo str_replace('"', '&quot;', $curitem['field_'.$fld.'_value']);
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', \'value\'); } " /></div>';
*/

$cost=db_query('select sell_price, weight, length, width, height from {uc_products} where nid='.$_GET['nid']);
$cost=$cost->fetchAssoc();

/*
$sign=db_query('select field_sign_value from {field_data_field_sign} where entity_type=\'node\' and entity_id='.$_GET['nid']);
$sign=$sign->fetchAssoc();


$fld='cost1';
echo '<div class="item"><div class="label">Цена закупки (белка)</div><input size="21" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
if( isset($sign['field_sign_value']) and $sign['field_sign_value']==1 and isset($cost['cost']) and is_numeric($cost['cost']) and $cost['cost']>0 ){
    echo $cost['cost'];
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'product\', \''.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'product\', \''.$fld.'\', \'value\'); } " /></div>';


$fld='cost2';
echo '<div class="item"><div class="label">Цена закупки (россия)</div><input size="21" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
if( isset($sign['field_sign_value']) and $sign['field_sign_value']==2 and isset($cost['cost']) and is_numeric($cost['cost']) and $cost['cost']>0 ){
    echo $cost['cost'];
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'product\', \''.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'product\', \''.$fld.'\', \'value\'); } " /></div>';


$fld='cost3';
echo '<div class="item"><div class="label">Цена закупки (доллары)</div><input size="21" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
if( isset($sign['field_sign_value']) and $sign['field_sign_value']==3 and isset($cost['cost']) and is_numeric($cost['cost']) and $cost['cost']>0 ){
    echo $cost['cost'];
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'product\', \''.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'product\', \''.$fld.'\', \'value\'); } " /></div>';
*/

$fld='weight';
echo '<div class="item"><div class="label">Вес, кг</div><input size="11" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
if( isset($cost[$fld]) and is_numeric($cost[$fld]) and $cost[$fld]>0 ){
    echo $cost[$fld];
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'product\', \''.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'product\', \''.$fld.'\', \'value\'); } " /></div>';


$fld='height';
echo '<div class="item"><div class="label">Высота</div><input size="11" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
if( isset($cost[$fld]) and is_numeric($cost[$fld]) and $cost[$fld]>0 ){
    echo $cost[$fld];
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'product\', \''.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'product\', \''.$fld.'\', \'value\'); } " /></div>';


$fld='width';
echo '<div class="item"><div class="label">Ширина</div><input size="11" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
if( isset($cost[$fld]) and is_numeric($cost[$fld]) and $cost[$fld]>0 ){
    echo $cost[$fld];
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'product\', \''.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'product\', \''.$fld.'\', \'value\'); } " /></div>';


$fld='length';
echo '<div class="item"><div class="label">Глубина</div><input size="11" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
if( isset($cost[$fld]) and is_numeric($cost[$fld]) and $cost[$fld]>0 ){
    echo $cost[$fld];
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'product\', \''.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'product\', \''.$fld.'\', \'value\'); } " /></div>';

/*
$fld='diagonal';
$fields=db_query('select data from {field_config} where field_name=\'field_'.$fld.'\'');
$fields=$fields->fetchAssoc();
if(isset($fields['data'])){
    $fields['data']=unserialize($fields['data']);
    if(isset($fields['data']['settings']['allowed_values']) and is_array($fields['data']['settings']['allowed_values']) and count($fields['data']['settings']['allowed_values'])){
        $curitem=db_query('select field_'.$fld.'_value from {field_data_field_'.$fld.'} where entity_type=\'node\' and entity_id='.$_GET['nid']);
        $curitem=$curitem->fetchAssoc();
        
        echo '<div class="item"><div class="label">Диагональ</div><select class="pr_'.$fld.'" name="pr_'.$fld.'" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', \'value\'); } "><option value="">Выберите...</option>';
        foreach($fields['data']['settings']['allowed_values'] as $term=>$name ){
            echo '<option value="'.$term.'"';
            if( isset($curitem['field_'.$fld.'_value']) and $curitem['field_'.$fld.'_value']==$term ){
                echo ' selected="selected"';
            }
            echo '>'.$name.'</option>';
        }
        echo '</select></div>';
    }
}
*/

        echo '<div class="clear">&nbsp;</div>';

/*
$items=array();
$fld='material';
$outs=db_query('select field_'.$fld.'_tid from {field_data_field_'.$fld.'} where entity_type=\'node\' and entity_id='.$_GET['nid']);
while($out=$outs->fetchAssoc()){
    $items[]=$out['field_'.$fld.'_tid'];
}
$terms = taxonomy_get_tree(4);
if($terms){
    echo '<div class="item"><div class="label">Материал</div><select size="7" multiple="multiple" class="pr_'.$fld.'" name="pr_'.$fld.'[]" onchange=" if( jQuery(this).val() ){  admchangemulti('.$_GET['nid'].', \'fieldmulti\', \'field_'.$fld.'\', this, \'tid\' ); }else{ admdel('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', \'tid\'); } ">';
    foreach($terms as $term){
        echo '<option value="'.$term->tid.'"';
        if( array_search($term->tid, $items)===false ){}else{
            echo ' selected="selected"';
        }
        echo '>'.$term->name.'</option>';
    }
    echo '</select></div>';
}
*/

$items=array();
$fld='brand';
$outs=db_query('select field_'.$fld.'_tid from {field_data_field_'.$fld.'} where entity_type=\'node\' and entity_id='.$_GET['nid']);
while($out=$outs->fetchAssoc()){
    $items[]=$out['field_'.$fld.'_tid'];
}
$terms = taxonomy_get_tree(5);
if($terms){
    echo '<div class="item"><div class="label">Производитель</div><select class="pr_'.$fld.'" name="pr_'.$fld.'" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', jQuery(this).val(), \'tid\' ); }else{ admdel('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', \'tid\'); } ">';
    foreach($terms as $term){
        echo '<option value="'.$term->tid.'"';
        if( array_search($term->tid, $items)===false ){}else{
            echo ' selected="selected"';
        }
        echo '>'.$term->name.'</option>';
    }
    echo '</select></div>';
}

        echo '<div style="float: right;">';

$fld='sell_price';
echo '<div class="item2"><div class="label">Цена продажи</div><input size="21" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
if( isset($cost[$fld]) and is_numeric($cost[$fld]) and $cost[$fld]>0 ){
    echo $cost[$fld];
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'product\', \''.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'product\', \''.$fld.'\', \'value\'); } " /></div>';


/*
$fld='volume';
echo '<div class="item2"><div class="label">Объем, л</div><input size="11" type="text" class="form-text pr_'.$fld.'" name="pr_'.$fld.'" value="';
$curitem=db_query('select field_'.$fld.'_value from {field_data_field_'.$fld.'} where entity_type=\'node\' and entity_id='.$_GET['nid']);
$curitem=$curitem->fetchAssoc();
if( isset($curitem['field_'.$fld.'_value']) and strlen($curitem['field_'.$fld.'_value']) ){
    echo str_replace('"', '&quot;', $curitem['field_'.$fld.'_value']);
}
echo '" onchange=" if( jQuery(this).val() ){  admchange('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', jQuery(this).val(), \'value\' ); }else{ admdel('.$_GET['nid'].', \'field\', \'field_'.$fld.'\', \'value\'); } " /></div>';
*/

        echo '</div>';

        echo '<div class="clear">&nbsp;</div></div>';
        echo '<div style="margin-top: 59px;">';
        
        $inreserve=db_query('select p.nid from {uc_orders} as o inner join {uc_order_products} as p on o.order_id=p.order_id where (o.order_status=\'pending\' or o.order_status=\'processing\') and p.nid='.$_GET['nid']);
        $inreserve=$inreserve->fetchAssoc();
        if( isset($inreserve['nid']) and is_numeric($inreserve['nid']) ){
            echo '<div style="text-align: center; font-size: 18pt; color: #009846; padding-bottom: 15px;">Товар в резерве!</div>';
            echo '<div style="text-align: center;"><a href="/admin/reserve">Перейти к резерву</a></div>';
        }

        echo '<div class="clear">&nbsp;</div></div>';

/*        
            $uri=db_query('select body_value from {field_data_body} where entity_id='.$_GET['nid']);
            $uri=$uri->fetchAssoc();
            if( isset($uri['body_value']) and strlen($uri['body_value']) ){
                $body='<div style="margin-left: 317px;"><div>Описание товара</div><div style="border: 1px solid #929191; margin-top: 19px; padding: 11px; overflow: auto; height: 157px;">'.$uri['body_value'].'</div></div>';
            }
*/        
        
    }
}

?>