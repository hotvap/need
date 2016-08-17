<div class="vacancy_item">
<?php
if(isset($fields['field_image']->content) and strlen($fields['field_image']->content)){
    echo '<div class="img">'.$fields['field_image']->content.'</div>';
}
echo '<div class="title admlnk pdxobj'.$fields['nid']->content.' pdxona pdxpp">'.$fields['title']->content;
echo '</div>';
echo '<div class="body">'.$fields['body']->content;
echo '</div>';

?>
<div class="clear">&nbsp;</div></div>