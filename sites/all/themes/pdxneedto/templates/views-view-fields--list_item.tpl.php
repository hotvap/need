<div class="pub_item">
<?php
if(isset($fields['field_image']->content) and strlen($fields['field_image']->content)){
    echo '<div class="image">'.$fields['field_image']->content.'</div>';
}
echo '<div class="title admlnk pdxobj'.$fields['nid']->content.' pdxona pdxpp">';

    global $user; if(isset($user->roles[3]) or isset($user->roles[4]) and isset($fields['nid']->content) and is_numeric($fields['nid']->content) ){
        echo '<input style="position: absolute; right: 7px; top: 7px; z-index: 77;" class="form-text" size="2" type="text" value="';
        $cur=db_query('select field_sort_value from {field_data_field_sort} where entity_id='.$fields['nid']->content);
        $cur=$cur->fetchAssoc();
        if( isset($cur['field_sort_value']) and is_numeric($cur['field_sort_value']) ){
            echo $cur['field_sort_value'];
        }
        echo '" onchange=" if( jQuery(this).val() ){  admchange('.$fields['nid']->content.', \'field\', \'field_sort\', jQuery(this).val(), \'value\' ); }else{ admdel('.$fields['nid']->content.', \'field\', \'field_sort\', \'value\'); } " />';
    }

echo $fields['title']->content;
echo '</div>';
echo '<div class="body">'.$fields['body']->content.'</div>';
?>
<div class="clear">&nbsp;</div></div>