<?php
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';

$mysessuid=0;
$rid=0;
$city=0;
$stopcache=0;
$skiprender=0;

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';

if( isset( $_GET['token'] ) and strlen($_GET['token']) ){
    $_GET['token']=str_replace('/', '', str_replace('.', '', strip_tags($_GET['token'])));
    if( file_exists('pdxcache/tokens/'.$_GET['token']) and (filectime('pdxcache/tokens/'.$_GET['token'])+61) > time() ){
        $datas=explode('_', $_GET['token']);
        if( isset($datas) and is_array($datas) and count($datas)==2 and is_numeric($datas[0]) ){
            unlink('pdxcache/tokens/'.$_GET['token']);
            drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
            global $user;
            if( $user->uid!=$datas[0] ){
//                module_invoke_all('user_logout', $account);
//                session_destroy();
                $user=user_load($datas[0]);
                $_SESSION['pdxuseruid']=$datas[0];
            }
            menu_execute_active_handler();
            exit();
//            drupal_goto('user');
        }
    }
}

drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
if(isset($_SESSION['pdxuseruid']) and $_SESSION['pdxuseruid']!=0){
    $mysessuid=$_SESSION['pdxuseruid'];

    if( !is_dir('curs/online') ){
        mkdir('curs/online');
    }
    $fp = fopen('curs/online/'.$mysessuid.'.txt', 'w'); fwrite($fp, ''); fclose($fp);

}

if( strpos($_SERVER['HTTP_HOST'], 'logmy')!==false ){
    $stopcache=1;
}else{



    if( isset($_SESSION['pdxuseruid']) and $_SESSION['pdxuseruid']!=0 ){}else{
        if( !isset( $_SESSION['limitmin'][date('i',time())] ) ){
            if( isset($_SESSION['limitmin']) ){
                unset($_SESSION['limitmin']);
            }
            $_SESSION['limitmin'][date('i',time())]=1;
        }else{
            $_SESSION['limitmin'][date('i',time())]++;
            if( $_SESSION['limitmin'][date('i',time())]>41 ){
                header( 'Location: '.$GLOBALS['base_url'].'/denied.html' );
                exit();
            }
        }
    }


if( isset($_SESSION['pdxuserrid']) and is_numeric($_SESSION['pdxuserrid']) ){
    switch($_SESSION['pdxuserrid']){
    case 3:
        $rid=3;
        break;
    case 4:
        $rid=4;
        break;
    case 8:
        $rid=8;
        break;
    case 2:
        $rid=2;
        break;
    case 6:
        $rid=2;
        break;
    }
}

if( defined('PDX_CITY_ID') and is_numeric(PDX_CITY_ID) ){
    $city=PDX_CITY_ID;
}


//if( (isset( $_COOKIE['has_js'] ) and !isset($_COOKIE['pdxuseruid'])) or (isset($_COOKIE['pdxuseruid']) and $_COOKIE['pdxuseruid']==0) ){
if( isset($_GET['adm']) ){
    $stopcache=1;
}
if( isset($_SESSION['messages']['status']) or isset($_SESSION['messages']['error']) or isset($_SESSION['messages']['warning']) ){
    $stopcache=1;
}

if( !$stopcache ){

            if( strpos($_SERVER['REQUEST_URI'],'?')===false ){}else{
                $tmpval=mb_substr($_SERVER['REQUEST_URI'], 1+mb_strpos($_SERVER['REQUEST_URI'],'?') );
                $_SERVER['REQUEST_URI']=mb_substr($_SERVER['REQUEST_URI'],0,mb_strpos($_SERVER['REQUEST_URI'],'?'));
                $newtmpval=array();
                $tmpval=explode('&',$tmpval);
                if( isset($tmpval) and is_array($tmpval) and count($tmpval) ){
                    foreach( $tmpval as $newtmp ){
                        $newtmp=explode('=',$newtmp);
                        if( isset($newtmp) and is_array($newtmp) and count($newtmp)==2 ){
                            switch($newtmp[0]){
                            case 'tid':
                            case 'k50id':
                            case 'retargeting_id':
                            case 'phrase_id':
                            case 'inline':
                            case 'skipmob':
                            case 'width':
                            case 'height':
                            case '_openstat':
                            case 'gclid':
                            case 'fb_ref':
                            case '_ga':
                                break;
                            default:
                                if( strpos($newtmp[0],'utm_')===false and strpos($newtmp[0],'clid')===false ){
                                    if( arg(0)=='find' and ($newtmp[0]=='s' or $newtmp[0]=='t') ){
                                    }else{
                                        $newtmpval[]=$newtmp[0].'='.$newtmp[1];
                                    }
                                }
                            }
                        }
                    }
                }
                if( isset($newtmpval) and is_array($newtmpval) and count($newtmpval) ){
                    $_SERVER['REQUEST_URI'].='?'.implode('&',$newtmpval);
                }
            }

    if( isset($_SERVER['REQUEST_URI']) and strlen($_SERVER['REQUEST_URI']) ){
        $url=str_replace('/','_',strip_tags($_SERVER['REQUEST_URI']));
        if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url) and filesize('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url) ){
            $skiprender=1;
            header('Content-Type: text/html; charset=utf-8');
            $tmpout=str_replace('var curCity=-1;', 'var curCity='.$city.';', str_replace('var curUid=-1;', 'var curUid='.$mysessuid.';',                 @file_get_contents('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url)));
            if( isset($_COOKIE['actionday']) and $_COOKIE['actionday']==date('j', time()) ){
                $tmpout=str_replace('id="actionmsg" class="regshow">', 'id="actionmsg">', $tmpout);
            }
            echo $tmpout;
        }
    }
}
}

if( !$skiprender ){
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
    menu_execute_active_handler();
}

//setrawcookie("actionday",date('z', time()), time()+31536000 );