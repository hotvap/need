<?php
if(isset($fields['uc_product_image']->content) and strlen($fields['uc_product_image']->content)){
    if( strpos($fields['uc_product_image']->content,'|pdx|')===false ){
        echo $fields['uc_product_image']->content;
    }else{
        $img=explode('|pdx|',$fields['uc_product_image']->content);
        if( isset($img) and is_array($img) and count($img)==2 ){
            $img[1]=str_replace('<img ','<img id="preview-large"',$img[1]);
            $uri = file_load($img[0])->uri;
            $uri = file_create_url($uri);
            //$uri = image_style_url('catalog_links_preview', $uri);

            echo '<a href="'.$uri.'" rel="preload-selectors-small:false;smoothing:false;fps:40;zoom-fade:true;show-title:false;loading-msg:Загрузка...;zoom-width:287;zoom-height:298;zoom-distance:10" class="MagicZoom" title="">'.$img[1].'</a><span style="display: inline;" class="DivZoomIco"></span>';
        }else{
            echo $fields['uc_product_image']->content;
        }
    }
}
?>
