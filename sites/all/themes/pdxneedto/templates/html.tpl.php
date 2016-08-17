<?php
global $user;

$outis='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="'.$language->language.'" xml:lang="'.$language->language.'" dir="'.$language->dir.'" xmlns:fb="http://www.facebook.com/2008/fbml"><head><title>';
    if( arg(0)=='user' and is_string(arg(2)) ){
        $outis.= 'Профиль пользователя '.$_SERVER['HTTP_HOST'];
    }else{

        $pdxseo=array();
        $pdxseo_title='';
        $curpath=implode('/',arg());
    
        if($pdxseo=variable_get('pdxseo')){
            $pdxseo=unserialize($pdxseo);
            if(isset($pdxseo[urlencode($curpath)]['title']) and strlen($pdxseo[urlencode($curpath)]['title'])){
                $pdxseo_title=$pdxseo[urlencode($curpath)]['title'];
            }
        }
        if(strlen($pdxseo_title)){
            $outis.= $pdxseo_title;
        }else{
            $outis.= str_replace('&amp;','&',strip_tags(html_entity_decode($head_title)));
        }

    }
$outis.='</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link rel="alternate" type="application/rss+xml" title="Последние объявления по аренде в '.PDX_CITY_NAME2.'" href="http://d4zbhil5kxgyr.cloudfront.net/static/rss/'.PDX_CITY_ID.'.rss" />
';
$compress_stamp=0;
if( file_exists('pdxcache/compress_needto_timestamp.php') ){
    $compress_stamp=filectime('pdxcache/compress_needto_timestamp.php');
}
if ( isset($_SERVER['HTTP_ACCEPT_ENCODING']) and stripos($_SERVER['HTTP_ACCEPT_ENCODING'],'GZIP')!==false) $gz='gz'; else $gz=null;
$outis.='<link rel="stylesheet" type="text/css" href="/sites/all/themes/pdxneedto/min/needto.css'.$gz.'?r='.$compress_stamp.'" />';
if(isset($user->roles[3]) or isset($user->roles[4])){
    $outis.='<link rel="stylesheet" type="text/css" href="/sites/all/themes/pdxneedto/min/adm.css'.$gz.'?r='.$compress_stamp.'" />';
}
//<link rel="stylesheet" type="text/css" href="http://needtominsk.s3.amazonaws.com/static/needtome.css">
//<link href=\'https://fonts.googleapis.com/css?family=Cuprum:400&subset=latin,cyrillic\' rel=\'stylesheet\' type=\'text/css\'>
      ?>
	  <?php $outis.= $head; ?>
      <?php
      
      if(drupal_is_front_page()){
        if($pdxchangemeta=variable_get('pdxchangemeta')){
            $pdxchangemeta=unserialize($pdxchangemeta);
        }
        if( isset($pdxchangemeta) and strlen($pdxchangemeta) ){
            $outis.= $pdxchangemeta;
        }
      }
      
      ?>
      
	  <?php $outis.= $styles;
      
    $isadmproduct=0;
    if( function_exists('getisadm') ){
        $isadmproduct=getisadm();
    }
    
    if($isadmproduct){
        $outis.='<style type="text/css" media="all"> @import url("/sites/all/libraries/img/adm.css"); </style>';
        ?><?php }
      
      ?>
	  <?php

//window.dataLayer = window.dataLayer || [];
      
$outis.='<link href=\'https://fonts.googleapis.com/css?family=Open+Sans:400,300&subset=latin,cyrillic\' rel=\'stylesheet\' type=\'text/css\'>
</head><body class="pdxneedto';
if(arg(0)!='admin' and (arg(0)=='node' and (arg(1)!='add' and (is_numeric(arg(1)) and arg(2)!='edit')))) $outis.=' not-page-admin';
if( !isset($user->roles[3]) and !isset($user->roles[4]) ){
    $outis.=' user-not-admin';
}else{
    $outis.=' user-is-admin';
}
if( isset($_GET['pdxshow']) and is_numeric($_GET['pdxshow']) ){
    $outis.=' pdxshow'.$_GET['pdxshow'];
}
if( isset($_SESSION['pdxpdx_par_vid']) and is_numeric($_SESSION['pdxpdx_par_vid']) ){
    $outis.=' vid'.$_SESSION['pdxpdx_par_vid'];
}
$outis.=' ';
$outis.=$classes;
$outis.='">';
$outis.=$page_top; 
$outis.=$page; 
$outis.= '<script type="text/javascript"> var usN, usC=\'\'; </script>';
$outis.= $scripts;
$outis.=$page_bottom; 

$outis.='<script type="text/javascript" src="'.PDX_JSPATH.'/swiper.min.js"></script>';

$outis.='<script src="/sites/all/themes/pdxneedto/min/other.js'.$gz.'?r='.$compress_stamp.'"></script>';
if(isset($user->roles[3]) or isset($user->roles[4])){
    $outis.='<script src="/sites/all/themes/pdxneedto/min/adm.js'.$gz.'?r='.$compress_stamp.'"></script>';
}
if(arg(0)=='admin' and is_string(arg(1))){
    if(arg(1)=='structure' and is_string(arg(2)) and arg(2)=='taxonomy'){
    if( isset($_REQUEST['parent']) and is_numeric($_REQUEST['parent']) ){
        $outis.='<script type="text/javascript"> jQuery(document).ready(function($){';
        $outis.='if(jQuery("#edit-relations a.fieldset-title").length){ jQuery("#edit-relations a.fieldset-title").click(); } ';
        $outis.='if(jQuery("#edit-parent").length){ jQuery("#edit-parent").val('.$_REQUEST['parent'].'); } ';
        $outis.='jQuery("#edit-name").focus();';
        $outis.='}); </script>';
    }
    }
    if( isset($_GET['region']) and strlen($_GET['region']) ){
        $outis.='<script type="text/javascript"> jQuery(document).ready(function($){';
        $outis.='if(jQuery("select#edit-regions-pdxneedto").length){ jQuery("select#edit-regions-pdxneedto").val("'.$_GET['region'].'"); }';
        if( isset($_GET['page']) and strlen($_GET['page']) ){
            $_GET['page']=urldecode($_GET['page']);
            $outis.=' jQuery("#edit-visibility-1").attr("checked", true); ';
            $outis.=' jQuery("#edit-pages").val("'.$_GET['page'].'"); ';
            $outis.=' jQuery("#edit-info").val("Продвижение для '.$_GET['page'].'"); ';
        }
        $outis.='}); </script>';
    }
}

//if( !PDX_ISLOCAL ){
    $skipmap=0;
    $yesshare=0;
    if( isset($_SESSION['pdxpdx_node_type']) and strlen($_SESSION['pdxpdx_node_type']) ){
        if( $_SESSION['pdxpdx_node_type']=='item' or $_SESSION['pdxpdx_node_type']=='article' or $_SESSION['pdxpdx_node_type']=='raion' ){
            $yesshare=1;
        }
        if( $_SESSION['pdxpdx_node_type']=='page' or $_SESSION['pdxpdx_node_type']=='webform' or $_SESSION['pdxpdx_node_type']=='deal' ){
            $skipmap=1;
        }
    }elseif(arg(0)=='admin'){
        $skipmap=1;
    }elseif(arg(0)=='user' and is_numeric(arg(1)) and !is_string(arg(2))){
        $yesshare=1;
    }elseif( defined('PDXCAT_NAME') and arg(0)==PDXCAT_NAME ){
        $yesshare=1;
    }else{
        switch(arg(0)){
        case 'dev':
        case 'i':
        case 'a':
        case 'press':
        case 'apime':
            $yesshare=1;
            break;
        }
    }
    if( !$skipmap ){
        $outis.= '
    <script src="http://api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU" type="text/javascript"></script>
    ';
    }
    if( $yesshare ){
        $outis.= ' <script src="https://yastatic.net/share2/share.js" async="async"></script> ';
    }
//}
//$outis.='<script type="text/javascript" src="https://imgs.smartresponder.ru/52568378bec6f68117c48f2f786db466014ee5a0/"></script>';

$outis.='</body></html>';


if( isset($outis) and strlen($outis) ){
    $stopcache=0;

    if(isset($user->roles[3])){
        $rid=3;
    }elseif(isset($user->roles[4])){
        $rid=4;
    }elseif(isset($user->roles[8])){
        $rid=8;
    }elseif(isset($user->roles[2])){
        $rid=2;
    }elseif(isset($user->roles[6])){
        $rid=2;
    }else{
        $rid=0;
    }

$city=0;
if( defined('PDX_CITY_ID') and is_numeric(PDX_CITY_ID) ){
    $city=PDX_CITY_ID;
}

    if( isset($_SERVER['REQUEST_URI']) and strlen($_SERVER['REQUEST_URI']) ){

        if( strpos($_SERVER['REQUEST_URI'],'?')===false ){}else{
            $tmpval=mb_substr($_SERVER['REQUEST_URI'], 1+mb_strpos($_SERVER['REQUEST_URI'],'?') );
            $_SERVER['REQUEST_URI']=mb_substr($_SERVER['REQUEST_URI'],0,mb_strpos($_SERVER['REQUEST_URI'],'?'));
            $newtmpval=array();
            $tmpval=explode('&',$tmpval);
            if( isset($tmpval) and is_array($tmpval) and count($tmpval) ){
                foreach( $tmpval as $newtmp ){
                    if( $stopcache ){ break; }
                    $newtmp=explode('=',$newtmp);
                    if( isset($newtmp) and is_array($newtmp) and count($newtmp)==2 ){
                        switch($newtmp[0]){
                        case 'adm':
                            $stopcache=1;
                            break;
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
                            break;
                        default:
                            if( strpos($newtmp[0],'utm_')===false and strpos($newtmp[0],'clid')===false ){
                                $newtmpval[]=$newtmp[0].'='.$newtmp[1];
                            }
                        }
                    }
                }
            }
            if( isset($newtmpval) and is_array($newtmpval) and count($newtmpval) ){
                $_SERVER['REQUEST_URI'].='?'.implode('&',$newtmpval);
            }
        }
        if( arg(0)=='admin' ){
            $stopcache=1;
        }
        if( arg(0)=='node' and is_numeric(arg(1)) and is_string(arg(2)) ){
            $stopcache=1;
        }
        
        $url=str_replace('/','_',strip_tags($_SERVER['REQUEST_URI']));
        if( !is_dir('pdxcache/pages/'.$_SERVER['HTTP_HOST']) ){
            mkdir('pdxcache/pages/'.$_SERVER['HTTP_HOST']);
        }
        
        if( !$stopcache and mb_strpos($outis, 'id="mymessages"')===false and strpos($_SERVER['REQUEST_URI'], '/edit')===false ){
            $tmpoutis=str_replace('id="actionmsg">', 'id="actionmsg" class="regshow">', str_replace('var curCity='.PDX_CITY_ID.';', 'var curCity=-1;', str_replace('var curUid='.$user->uid.';', 'var curUid=-1;', str_replace('<body class="', '<body class="cached ', $outis))));
            switch($url){
            case '_':
//            case '_fav':
    //        case '_txt':
    //        case '_catalog':
    //        case '_action':
                $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                break;
            default:
                if( isset($_SESSION['pdxpdx_node_type']) and strlen($_SESSION['pdxpdx_node_type']) ){
                    switch( $_SESSION['pdxpdx_node_type'] ){
                    case 'page':
                    case 'news':
                    case 'article':
                    case 'item':
                    case 'raion':
                    case 'webform':
    //                case 'product':
                        $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                        break;
                    default:
    //                    if( strpos($_SESSION['pdxpdx_node_type'], 'product')===false ){}else{
    //                        $fp = fopen('pdxcache/pages/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
    //                    }
                    }
                }else{
                    switch(arg(0)){
                    case 'brands':
                    case 'findmy':
                    case 'dev':
                    case 'i':
                    case 'a':
                    case 'press':
                    case 'apime':
                    case 'fav':
                        $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                        break;
                    default:
                        if( arg(0)=='item' and is_string(arg(1)) and arg(1)=='viewed' ){
                            $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                        }elseif( arg(0)=='item' and is_string(arg(1)) and arg(1)=='rec' ){
                            $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                        }elseif( arg(0)=='news' or arg(0)=='taxonomy' or arg(0)==PDXCAT_NAME ){
                            $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                        }elseif( arg(0)=='user' and is_numeric(arg(1)) and !is_string(arg(2)) ){
                            $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                        }elseif( arg(0)=='users' and is_string(arg(1)) and arg(1)=='rents' ){
                            $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                        }elseif( arg(0)=='node' and is_string(arg(1)) and arg(1)=='add' and is_string(arg(2)) and arg(2)=='item' and $rid==0 ){
                            $fp = fopen('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$rid.'_'.$url, 'w'); fwrite($fp, $tmpoutis); fclose($fp);
                        }
                    }
                }
            }
        }
    }
    echo $outis;
}


?>