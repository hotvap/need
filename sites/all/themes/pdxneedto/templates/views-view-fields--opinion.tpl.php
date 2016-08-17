<div class="opinion_item admlnk pdxobj<?php echo $fields['nid']->content; ?> pdxona">
<?php
if(isset($fields['field_image']->content) and strlen($fields['field_image']->content)){
    echo '<div class="opinion_image">'.$fields['field_image']->content.'</div>';
}
echo '<div class="created">'.$fields['created']->content.'</div>';
if(isset($fields['field_fio']->content) and strlen($fields['field_fio']->content)){
    echo '<div class="fio">'.$fields['field_fio']->content.'</div>';
}
if(isset($fields['field_opinion']->content) and strlen($fields['field_opinion']->content)){
    echo nl2br($fields['field_opinion']->content);
}
?>
<div class="clear">&nbsp;</div></div>