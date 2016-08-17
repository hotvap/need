<div class="slide_item admlnk pdxobj<?php echo $fields['nid']->content; ?> pdxona">
<?php
if(isset($fields['field_image2']->content)){
    if(isset($fields['field_url']->content) and strlen($fields['field_url']->content)){
        if( strpos($fields['field_url']->content,'http://')===false and strpos($fields['field_url']->content,'https://')===false ){
            if( mb_substr($fields['field_url']->content,0,1)=='/' ){}else{
                $fields['field_url']->content='http://'.$fields['field_url']->content;
            }
        }
        echo '<a href="'.$fields['field_url']->content.'">';
    }
    echo '<div class="image">'.$fields['field_image2']->content.'</div>';
    if(isset($fields['field_url']->content) and strlen($fields['field_url']->content)){
        echo '</a>';
    }
}
echo '<div class="forslider">&nbsp;</div>';

    if(isset($fields['title']->content) and strlen($fields['title']->content)){
        echo '<div class="title">';
        echo $fields['title']->content;
        echo '</div>';
    }
    if(isset($fields['body']->content) and strlen($fields['body']->content)){
        echo '<div class="body">';
        echo $fields['body']->content;
        echo '</div>';
    }

?>
<div class="clear">&nbsp;</div></div>