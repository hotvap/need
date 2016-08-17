<div class="product_item<?php
if( isset($fields['nid']->content) and is_numeric($fields['nid']->content) ){
    echo ' product_item_'.$fields['nid']->content;
}
?>">
<?php
echo '<div class="title admlnk pdxobj'.$fields['nid']->content.' pdxona pdxpp">';
echo $fields['title']->content;
echo '</div>';
if(isset($fields['uc_product_image']->content) and strlen($fields['uc_product_image']->content)){
    echo '<div class="image">'.$fields['uc_product_image']->content.'</div>';
}
if(isset($fields['sell_price']->content) and strlen($fields['sell_price']->content)){
    echo '<div class="sell_price">'.$fields['sell_price']->content.' руб</div>';
}
if(isset($fields['discounted_price']->content) and strlen($fields['discounted_price']->content)){
    echo '<div class="discounted_price">'.$fields['discounted_price']->content.'</div>';
}
if(isset($fields['addtocartlink']->content) and strlen($fields['addtocartlink']->content)){
    echo '<div class="addtocartlink">'.$fields['addtocartlink']->content.'</div>';
}
if(isset($fields['ops']->content) and strlen($fields['ops']->content)){
    echo '<div class="ops">'.$fields['ops']->content.'</div>';
}

global $user; if(isset($user->roles[3])){
    if(isset($fields['nid']->content) and is_numeric($fields['nid']->content)){
        echo '<div class="foradmin_publ foradmin_publ'.$fields['nid']->content.'"><a href="javascript: void(0);" onclick="foradmin_publ('.$fields['nid']->content.');">Депубликовать</a></div>';
        echo '<div class="foradmin_hit foradmin_hit'.$fields['nid']->content.'"><a href="javascript: void(0);" onclick="foradmin_hit('.$fields['nid']->content.');">Сделать хитом/Убрать из хитов</a></div>';
    }
    echo '<input style="position: absolute; right: 7px; top: 7px; z-index: 77;" class="form-text" size="2" type="text" value="';
    $cur=db_query('select field_sort_value from {field_data_field_sort} where entity_id='.$fields['nid']->content);
    $cur=$cur->fetchAssoc();
    if( isset($cur['field_sort_value']) and is_numeric($cur['field_sort_value']) ){
        echo $cur['field_sort_value'];
    }
    echo '" onchange=" if( jQuery(this).val() ){  admchange('.$fields['nid']->content.', \'field\', \'field_sort\', jQuery(this).val(), \'value\' ); }else{ admdel('.$fields['nid']->content.', \'field\', \'field_sort\', \'value\'); } " />';
}
?>
<div class="clear">&nbsp;</div></div>