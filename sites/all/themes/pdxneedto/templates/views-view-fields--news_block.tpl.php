<div class="newsblock_item">
<?php
if(isset($fields['field_image']->content) and strlen($fields['field_image']->content)){
    echo '<div class="image">'.$fields['field_image']->content.'</div>';
}
echo '<div class="newsblock_body">';
if(isset($fields['created']->content) and strlen($fields['created']->content)){
    echo '<div class="created">'.$fields['created']->content.'</div>';
}
echo '<div class="title admlnk pdxobj'.$fields['nid']->content.' pdxona pdxpp">';

echo $fields['title']->content.'</div>';
echo '</div>';

?>
<div class="clear">&nbsp;</div></div>