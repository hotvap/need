<?php

if( isset( $_GET['id'] ) and is_numeric( $_GET['id'] ) and isset( $_GET['type'] ) and strlen( $_GET['type'] ) and ( $_GET['type']=='node' or $_GET['type']=='user' ) ){
    $flagtype='fav';
    $flagtitlein='В Избранное';
    $flagtitleout='Из Избранного';
    if($_GET['type']=='user'){
        $flagtype='users';
        $flagtitlein='В Избранные пользователи';
        $flagtitleout='Из Избранных пользователей';
    }

    define('DRUPAL_ROOT', getcwd());
    
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
    
    if(isset($_SESSION['pdxuseruid']) and $_SESSION['pdxuseruid']>0){
        
        if( !is_dir('pdxcache/udata') ){
            mkdir('pdxcache/udata');
        }
        if( !is_dir('pdxcache/udata/fav') ){
            mkdir('pdxcache/udata/fav');
        }
        if( !is_dir('pdxcache/udata/fav/'.$_GET['type']) ){
            mkdir('pdxcache/udata/fav/'.$_GET['type']);
        }
        if( !is_dir('pdxcache/udata/fav/'.$_GET['type'].'/'.$_SESSION['pdxuseruid']) ){
            mkdir('pdxcache/udata/fav/'.$_GET['type'].'/'.$_SESSION['pdxuseruid']);
        }
        if( !is_dir('pdxcache/udata/fav/'.$_GET['type'].'/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST']) ){
            mkdir('pdxcache/udata/fav/'.$_GET['type'].'/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST']);
        }
        
        $flagcnt = count(scandir('pdxcache/udata/fav/'.$_GET['type'].'/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST']))-2;
        if( $flagcnt<0 ){
            $flagcnt=0;
        }
        echo '<script type="text/javascript"> ';
        echo ' jQuery(\'.fav_'.$_GET['type'].'_'.$_GET['id'].' .fv\').removeClass(\'favproc\');';
            
        if( file_exists('pdxcache/udata/fav/'.$_GET['type'].'/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST'].'/'.$_GET['id']) ){
            unlink('pdxcache/udata/fav/'.$_GET['type'].'/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST'].'/'.$_GET['id']);
            echo ' jQuery(\'.fav_'.$_GET['type'].'_'.$_GET['id'].' .fv\').removeClass(\'favout\');';
            echo ' jQuery(\'.fav_'.$_GET['type'].'_'.$_GET['id'].' .fv\').attr(\'title\', \''.$flagtitlein.'\');';
            $flagcnt--;
        }else{
            $fp = fopen('pdxcache/udata/fav/'.$_GET['type'].'/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST'].'/'.$_GET['id'], 'w');
            fwrite($fp, '');
            fclose($fp);
            
            echo ' jQuery(\'.fav_'.$_GET['type'].'_'.$_GET['id'].' .fv\').addClass(\'favout\');';
            echo ' jQuery(\'.fav_'.$_GET['type'].'_'.$_GET['id'].' .fv\').attr(\'title\', \''.$flagtitleout.'\');';
            $flagcnt++;
        }
        if( file_exists('pdxcache/user/'.$_SESSION['pdxuseruid'].'_sm_needto') ){
            unlink('pdxcache/user/'.$_SESSION['pdxuseruid'].'_sm_needto');
        }
        
            
        echo ' jQuery(\'.fav'.$_GET['type'].'count\').html(\''.$flagcnt.'\');';
        echo '</script>';
    }else{
        echo 'Для доступа к данной возможности зарегистрируйтесь или войдите на сайт';
        echo '<script type="text/javascript"> jQuery(\'.fav_'.$_GET['type'].'_'.$_GET['id'].' .fv\').removeClass(\'favproc\'); </script>';
    }

}


?>