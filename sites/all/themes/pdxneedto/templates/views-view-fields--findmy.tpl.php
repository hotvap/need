<div id="findmy_nid_<?php echo $fields['nid']->content; ?>" class="findmy_item findmy_uid_<?php echo $fields['uid']->content; ?>">
<?php
echo '<div class="ttl admlnk pdxobj'.$fields['nid']->content.' pdxona pdxpp">';
//echo '<strong>F'.$fields['nid']->content.'</strong>. ';
if( isset( $fields['field_part']->content ) and strlen($fields['field_part']->content) ){
    $fields['field_part']->content=str_replace('catalog/', 'findmy/', $fields['field_part']->content);
    echo $fields['field_part']->content.' &gt; ';
}
echo $fields['title']->content;
echo '. <span class="is_a" onclick="showfindmy('.$fields['nid']->content.');">Подробнее &gt;&gt;</span>';
echo '</div>';
if( isset( $fields['field_str1']->content ) and strlen($fields['field_str1']->content) ){
    echo '<div class="more"><strong>Период аренды: </strong>'.$fields['field_str1']->content.'</div>';
}
echo '<div class="all"></div>';
//if( isset( $fields['field_str2']->content ) and strlen($fields['field_str2']->content) ){
//    echo '<div class="more"><strong>Готов заплатить: </strong>'.$fields['field_str2']->content.'</div>';
//}
?>
<div class="clear">&nbsp;</div></div>