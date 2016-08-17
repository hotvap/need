<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_GET['nid']) and is_numeric($_GET['nid']) ){
    global $user; if( isset($user->roles[3]) or isset($user->roles[4]) or isset($user->roles[5]) ){
        
        $img='';
        $body='';
        
            $uri=db_query('select uc_product_image_fid from {field_data_uc_product_image} where delta=0 and entity_id='.$_GET['nid']);
            $uri=$uri->fetchAssoc();
            if( isset($uri['uc_product_image_fid']) and is_numeric($uri['uc_product_image_fid']) ){
                $uri = file_load($uri['uc_product_image_fid'])->uri;
            //    $uri = file_create_url($uri);
                $uri = image_style_url('mystock', $uri);
                if( isset($uri) and strlen($uri) ){
                    $img='<div style="width: 273px; float: left; text-align: right;"><img style="border: 1px solid #929191;" alt="" src="'.$uri.'" /></div>';
                }
                
            }
            
            $uri=db_query('select body_value from {field_data_body} where entity_id='.$_GET['nid']);
            $uri=$uri->fetchAssoc();
            if( isset($uri['body_value']) and strlen($uri['body_value']) ){
                $body='<div style="margin-left: 317px;"><div>Описание товара</div><div style="border: 1px solid #929191; margin-top: 19px; padding: 11px; overflow: auto; height: 157px;">'.$uri['body_value'].'</div></div>';
            }
        
        echo $img.$body;
        
    }
}

?>