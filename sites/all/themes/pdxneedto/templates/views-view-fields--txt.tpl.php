<div class="pub_item">
<?php
echo '<div class="title admlnk pdxobj'.$fields['nid']->content.' pdxona pdxpp">';
echo $fields['title']->content;
echo '</div>';
if(isset($fields['field_image']->content) and strlen($fields['field_image']->content)){
    echo '<div class="image">'.$fields['field_image']->content.'</div>';
}
echo $fields['body']->content;
if(isset($fields['view_node']->content) and strlen($fields['view_node']->content)){
    echo '<div class="alnk">'.$fields['view_node']->content.'</div>';
}
?>
<div class="clear">&nbsp;</div></div>