<?php

    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    
$login='u9295';
$domain='needto';
switch( PDX_CITY_ID ){
case 27:
    $login='u12801';
    break;
}

$mydir='/home/'.$login.'/f/'.$domain.'/tmp';
if( is_dir($mydir) ){
    $files=scandir($mydir);
    if( isset($files) and is_array($files) and count($files) ){
        foreach($files as $file){
            if( strlen($file)>5 and is_file($mydir.'/'.$file) ){
                $curtime=filectime($mydir.'/'.$file);
                if($curtime>0 and (time()-$curtime)>211400){
                    unlink($mydir.'/'.$file);
                }
            }
        }
    } 
}

$mydir='/home/'.$login.'/f/kra/tmp';
if( is_dir($mydir) ){
    $files=scandir($mydir);
    if( isset($files) and is_array($files) and count($files) ){
        foreach($files as $file){
            if( strlen($file)>5 and is_file($mydir.'/'.$file) ){
                $curtime=filectime($mydir.'/'.$file);
                if($curtime>0 and (time()-$curtime)>211400){
                    unlink($mydir.'/'.$file);
                }
            }
        }
    } 
}

$mydir='/home/'.$login.'/f/soonmy/tmp';
if( is_dir($mydir) ){
    $files=scandir($mydir);
    if( isset($files) and is_array($files) and count($files) ){
        foreach($files as $file){
            if( strlen($file)>5 and is_file($mydir.'/'.$file) ){
                $curtime=filectime($mydir.'/'.$file);
                if($curtime>0 and (time()-$curtime)>211400){
                    unlink($mydir.'/'.$file);
                }
            }
        }
    } 
}


$mydir='mcache/';
if( is_dir($mydir) ){
    $files=scandir($mydir);
    if( isset($files) and is_array($files) and count($files) ){
        foreach($files as $file){
            if( strlen($file)>5 and is_dir($mydir.$file) ){

    $files2=scandir($mydir.$file);
    if( isset($files2) and is_array($files2) and count($files2) ){
        foreach($files2 as $file2){
            if( strlen($file2)>5 and is_file($mydir.$file.'/'.$file2) ){
                
                $curtime=filectime($mydir.$file.'/'.$file2);
                if($curtime>0 and (time()-$curtime)>518400){
                    unlink($mydir.$file.'/'.$file2);
                }
            }
        }
    } 

                
            }
        }
    } 
}


?>