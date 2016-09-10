<?php // $Id$
if( defined('DRUPAL_ROOT') ){}else{
    define('DRUPAL_ROOT', getcwd());
}
if( file_exists( DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my_'.$_SERVER['HTTP_HOST'].'.inc' ) ){
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my_'.$_SERVER['HTTP_HOST'].'.inc';
}else{
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my_mn.needto.me.inc';
}

define('PDX_S3_1', 'AKIAJZBFGP6M7K354GTQ');
define('PDX_S3_2', 'n3G35KKdug3H4IhYqDS5Y9WtzGgsZfqKneRufqFH');
define('PDX_URL_HELP', 'help.needto.me');
define('PDX_PROFILE', serialize(array( 'field_apply3'=>1, 'field_bday'=>1, 'field_gold'=>1, 'field_name'=>1, 'field_notemail'=>1, 'field_phones'=>1, 'field_sex'=>1, 'field_skype'=>1, 'field_url_fb'=>1, 'field_url_in'=>1, 'field_url_site'=>1, 'field_url_vk'=>1, 'field_url_yt'=>1, 'field_who'=>1, 'field_company_name'=>1, 'mail'=>1, 'name'=>1, 'uid'=>1, 'created'=>1, 'access'=>1, 'login'=>1, 'status'=>1, 'init'=>1, 'roles'=>1, 'data'=>1 )) );

function pdxmail($mail, $msg, $theme){
    $sitemail='needtome@yandex.ru';
        
    $header="Content-type: text/html; charset=\"utf-8\"";
    $header.="From: \"needto.me\" <".$sitemail.">";
    $header.='Subject: "'.$theme.'"';
    $header.="Content-type: text/html; charset=\"utf-8\"";
    
    if( strpos($_SERVER['HTTP_HOST'], 'needto')===false ){
    }else{
        if( file_exists('html/sub/template.htm') ){
            $data=file_get_contents('html/sub/template.htm');
            if( $data ){
                $msg=str_replace('{{MyText}}', $msg, $data);
            }
        }
    }
        
    mail($mail,$theme, $msg, $header, '-f'.$sitemail);
}
function pdxclearmycache($pages, $city=0){
    if( $city==0 ){
        if( defined('PDX_CITY_ID') and is_numeric(PDX_CITY_ID) ){
            $city=PDX_CITY_ID;
        }
    }
    if( !is_dir('pdxcache/pages/'.$_SERVER['HTTP_HOST']) ){
        mkdir('pdxcache/pages/'.$_SERVER['HTTP_HOST']);
    }

    if( isset($pages) and is_array($pages) and count($pages) ){
        foreach($pages as $pattern){
            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$pattern) ){
                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_'.$pattern);
            }
            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_0_'.$pattern) ){
                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_0_'.$pattern);
            }
            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_3_'.$pattern) ){
                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_3_'.$pattern);
            }
            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_4_'.$pattern) ){
                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_4_'.$pattern);
            }
            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_8_'.$pattern) ){
                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_8_'.$pattern);
            }
            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_2_'.$pattern) ){
                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$city.'_2_'.$pattern);
            }
        }
    }

}
