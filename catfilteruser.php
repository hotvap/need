<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_POST['str']) ){

        $filter=array();
//            $_POST['str']=urldecode($_POST['str']);
        if( strlen($_POST['str']) ){
            $_POST['str']=filter_xss(strip_tags($_POST['str']));
            $_POST['str']=str_replace('&amp;','&',$_POST['str']);
        }


            if( function_exists('pdxfilterparse') ){
                $filter=pdxfilterparse($_POST['str']);
            }

    if( function_exists('pdxcat_filter_user') ){

        echo pdxcat_filter_user($filter);
    }
    
//    echo '<script type="text/javascript"> jQuery(\'html, body\').animate({ scrollTop: jQuery(".mycatalogmore").offset().top }, 333); </script>';
}

?>