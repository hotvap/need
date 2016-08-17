<div class="news_item<?php

if( isset($fields['counter']->content) and is_numeric($fields['counter']->content) and $fields['counter']->content%2 ){
    echo ' news_item_odd';
}else{
    echo ' news_item_even';
}
?>">
<?php

if(isset($fields['created_1']->content) and is_numeric($fields['created_1']->content) and intval($fields['created_1']->content) ){
    echo '<div class="event_date">';
    echo '<span>'.date('j',$fields['created_1']->content).'</span>';
    echo pdxneedto_month_declination_ru(format_date($fields['created_1']->content,'custom','F'),date('n',$fields['created_1']->content));
    echo '</div>';
}

if( isset($fields['field_image']->content) and strlen($fields['field_image']->content) ){
    echo '<div class="image">'.$fields['field_image']->content.'</div>';
}
echo '<div class="event_right">';
echo '<h3 class="title admlnk pdxobj'. $fields['nid']->content.' pdxona pdxpp">';

echo $fields['title']->content;
echo '</h3>';
echo '<div class="body">'.$fields['body']->content.'</div>';

echo '</div>';
?>
<div class="clear">&nbsp;</div></div>