<?php

/**
 * @file
 * SimpleAds Image ad.
 * 
 * Avaialable variables
 * array $ad
 * array $settings
 * array $image_attributes
 * array $link_attributes
 * 
 */
?>
<div class="simplead-container image-ad <?php if (isset($css_attributes)): print $css_attributes; endif; ?>">
<?php
global $user; if(isset($user->roles[3])){
    if( mb_strrpos($ad['url'],'/')===false ){}else{
        $nid=mb_substr($ad['url'],1+mb_strrpos($ad['url'],'/'));
        if( isset($nid) and is_numeric($nid) ){
            echo '<div class="adm">';
            echo '<a href="/node/'.$nid.'/edit"><img alt="ред" src="/sites/all/libraries/img/adm_edit.png" /></a>';
            echo '</div>';
        }
    }
}
if( isset($ad['node']->title) and strlen($ad['node']->title) ){
    $ad['node']->title=str_replace('"','',$ad['node']->title);
    $ad['node']->title=str_replace("'",'',$ad['node']->title);
    $image_attributes['alt']=$ad['node']->title;
    $image_attributes['title']=$ad['node']->title;
}
?>
  <?php
  
  print l(theme('image', $image_attributes), $ad['url'], $link_attributes); ?>
</div>