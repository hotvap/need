<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_POST['cat']) and is_numeric($_POST['cat']) and isset($_POST['brand']) and is_numeric($_POST['brand']) and isset($_POST['tags']) and is_numeric($_POST['tags']) and isset($_POST['str']) and isset($_POST['ispath']) and strlen($_POST['ispath']) ){
    $_POST['ispath']=filter_xss(strip_tags($_POST['ispath']));
    $celebration=0;
    if( isset($_POST['celebration']) and is_numeric($_POST['celebration']) ){
        $celebration=$_POST['celebration'];
    }

        $filter=array();
//            $_POST['str']=urldecode($_POST['str']);
        if(strlen($_POST['str'])){
            $_POST['str']=filter_xss(strip_tags($_POST['str']));
            $_POST['str']=str_replace('&amp;','&',$_POST['str']);
        }


            if( function_exists('pdxfilterparse') ){
                $filter=pdxfilterparse($_POST['str']);
            }

    if( function_exists('pdxcat_filter') ){

        echo pdxcat_filter($_POST['ispath'], $_POST['cat'], $_POST['brand'], $_POST['tags'], $filter, $celebration);
    }
    
//    echo '<script type="text/javascript"> jQuery(\'html, body\').animate({ scrollTop: jQuery(".mycatalogmore").offset().top }, 333); </script>';
}

?>