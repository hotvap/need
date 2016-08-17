<?php


if( isset($_GET['nid']) and is_numeric($_GET['nid']) and isset($_GET['p']) and is_numeric($_GET['p']) and isset($_GET['b']) and is_numeric($_GET['b']) ){
    define('DRUPAL_ROOT', getcwd());
    
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    if( function_exists('pdxgetuseritems') ){
        pdxgetuseritems($_GET['nid'], $_GET['p'], $_GET['b']);
    }
    
    echo '<script type="text/javascript"> jQuery(\'html, body\').animate({ scrollTop: jQuery("#itemofuser_'.$_GET['nid'].'").offset().top }, 333); checkTimeProkat(); preparecurrency(); updateswiperitem(); </script>';
}

?>