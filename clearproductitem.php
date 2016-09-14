<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    global $user; if(isset($user->roles[3]) or isset($user->roles[4])){
        
        $pages=array();

$city=0;
if( defined('PDX_CITY_ID') and is_numeric(PDX_CITY_ID) ){
    $city=PDX_CITY_ID;
}

        if( isset( $_GET['url'] ) and strlen( $_GET['url'] ) ){
            $_GET['url']=filter_xss(strip_tags(urldecode($_GET['url'])));

            $_GET['url']=str_replace('//', '/', $_GET['url']);
            $pages[]=str_replace('/','_',$_GET['url']);
                    
        if( function_exists('pdxclearmycache') ){
            pdxclearmycache($pages);
        }

            
            exit();
        }

        
    if( !is_dir('pdxcache/pages/'.$_SERVER['HTTP_HOST']) ){
        mkdir('pdxcache/pages/'.$_SERVER['HTTP_HOST']);
    }
        
        $name='';
        $path='';
        $path2='';
        if( isset($_GET['type']) ){
            switch($_GET['type']){
            case 100: //анонимный кеш
                
                if( function_exists('pdxupdadv') ){
                    pdxupdadv();
                }
                    
                $path='pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/';
                @file_get_contents($GLOBALS['base_url'].'/compress.php');
                cache_clear_all();
                break;
            case 99: //объявления
                $path='pdxcache/'.$_SERVER['HTTP_HOST'].'/item/';
                break;
            case 98: //пользователи
                $path='pdxcache/'.$_SERVER['HTTP_HOST'].'/user/';
                break;
            case 101: //анонимный кеш, только базовые
                $nids=db_query('select nid from {node} where type=\'page\'');
                while( $nid=$nids->fetchAssoc() ){
                    $path=path_load('node/'.$nid['nid']);
                    if( isset($path['alias']) and strlen($path['alias'])){
                        $url='/'.$path['alias'];
                    }else{
                        $url='/node/'.$nid['nid'];
                    }
                
                    $url=str_replace('//', '/', $url);
                    $pages[]=str_replace('/','_',$url);
                    
                    if( isset($pages) and is_array($pages) and count($pages) ){
                        foreach($pages as $pattern){
                            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$pattern) ){
                                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$pattern);
                            }
                        }
                    }
                    
                    
                }
                exit();
                break;
            case 103: //анонимный кеш, только товары
                $nids=db_query('select nid from {uc_products}');
                while( $nid=$nids->fetchAssoc() ){
                    $path=path_load('node/'.$nid['nid']);
                    if( isset($path['alias']) and strlen($path['alias'])){
                        $url='/'.$path['alias'];
                    }else{
                        $url='/node/'.$nid['nid'];
                    }
                
                    $url=str_replace('//', '/', $url);
                    $pages[]=str_replace('/','_',$url);
                    
                    if( isset($pages) and is_array($pages) and count($pages) ){
                        foreach($pages as $pattern){
                            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$pattern) ){
                                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$pattern);
                            }
                        }
                    }
                    
                    
                }
                exit();
                break;
            case 102: //анонимный кеш, только страницы каталога
                $terms = taxonomy_get_tree(2);
                if($terms){
                    foreach($terms as $term){
                        $path=path_load('catalog/'.$term->tid);
                        if(strlen($path['alias'])) $url='/'.$path['alias']; else $url='/catalog/'.$term->tid;
                        $url=str_replace('//', '/', $url);
                        $pages[]=str_replace('/','_',$url);

                        if( isset($pages) and is_array($pages) and count($pages) ){
                            foreach($pages as $pattern){
                                if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$pattern) ){
                                    unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$pattern);
                                }
                            }
                        }

                        $files=scandir('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/');
                        if( isset($files) and is_array($files) and count($files) ){
                            foreach( $files as $file ){
                                if( strpos($file, 'sort_by=')===false ){}else{
                                    unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$file);
                                }
                            }
                        }
                    }
                }
                
                $nids=db_query('select nid from {node} where type=\'page\'');
                while( $nid=$nids->fetchAssoc() ){
                    $path=path_load('node/'.$nid['nid']);
                    if( isset($path['alias']) and strlen($path['alias'])){
                        $url='/'.$path['alias'];
                    }else{
                        $url='/node/'.$nid['nid'];
                    }
                
                    $url=str_replace('//', '/', $url);
                    $pages[]=str_replace('/','_',$url);
                    
                    if( isset($pages) and is_array($pages) and count($pages) ){
                        foreach($pages as $pattern){
                            if( file_exists('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$pattern) ){
                                unlink('pdxcache/pages/'.$_SERVER['HTTP_HOST'].'/'.$pattern);
                            }
                        }
                    }
                    
                    
                }
                exit();
                break;
            }
        }
        
        if( !strlen($name) ){
            DelDir($path);
            mkdir($path);
            if( isset($path2) and strlen($path2) ){
                DelDir($path2);
                mkdir($path2);
            }
            if( isset($path3) and strlen($path3) ){
                DelDir($path3);
                mkdir($path3);
            }
            if( isset($path4) and strlen($path4) ){
                DelDir($path4);
                mkdir($path4);
            }
            if( isset($path5) and strlen($path5) ){
                DelDir($path5);
                mkdir($path5);
            }
        }else{
            $files=scandir($path);
            if( isset($files) and is_array($files) and count($files) ){
                foreach( $files as $file ){
                    if( strpos($file,$name)===false ){}else{
                        unlink($path.'/'.$file);
                    }
                }
            }
        }
        echo 'ok';
        
    }



function DelDir($dir)  
{ 
    //если не открыть директорию 
    if (!$dd = opendir($dir)) return false; 
     
    //читаем директорию в цикле 
    while (false !== ($obj = readdir($dd))) 
    { 
        //пропускаем системные каталоги 
        if($obj=='.' || $obj=='..') continue; 
         
        //пробуем удалить объект, если это не удается, то применяем функцию к этому объекту вновь 
        if (!@unlink($dir.'/'.$obj)) DelDir($dir.'/'.$obj); 
    } 
    closedir($dd); 
     
        //удаляем пустую директорию 
        @rmdir($dir); 
}

?>