<?php

if( isset( $_GET['cols'] ) and strlen( $_GET['cols'] ) and isset( $_GET['type'] ) and is_numeric( $_GET['type'] ) ){
    $_GET['cols']=trim(strip_tags(urldecode($_GET['cols'])));
    if( isset( $_GET['cols'] ) and strlen( $_GET['cols'] ) ){

        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
    
        if(isset($_SESSION['pdxuseruid']) and is_numeric($_SESSION['pdxuseruid']) and $_SESSION['pdxuseruid']>0){
            $suf='';
            if( $_GET['type']>1 ){
                $suf=$_GET['type'];
            }
            
            if( !is_dir('pdxcache/settings') ){
                mkdir('pdxcache/settings');
            }
            if( !is_dir('pdxcache/settings/colorder'.$suf) ){
                mkdir('pdxcache/settings/colorder'.$suf);
            }
            $fp = fopen('pdxcache/settings/colorder'.$suf.'/'.$_SESSION['pdxuseruid'], 'w');
            fwrite($fp, $_GET['cols']); fclose($fp);
            
            echo '<script type="text/javascript"> jQuery(\'.savemyorder\').hide(111); </script>';
            
        }
    }
}

?>