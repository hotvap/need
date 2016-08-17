<?php

if( isset($_GET['cat']) and is_numeric($_GET['cat']) and isset($_GET['p']) and is_numeric($_GET['p']) and isset($_GET['b']) and is_numeric($_GET['b']) and isset($_GET['t']) and is_numeric($_GET['t']) and isset($_GET['sort']) and strlen($_GET['sort']) and isset($_GET['path']) and strlen($_GET['path']) ){

    $celebration=0;
    if( isset($_GET['celebration']) and is_numeric($_GET['celebration']) ){
        $celebration=$_GET['celebration'];
    }

    $pt='?';
    
    $_GET['path']=urldecode($_GET['path']);
    $_GET['path']=str_replace('..', '', str_replace(chr(0), '', strip_tags($_GET['path'])));
    $_GET['path']=str_replace('"','',str_replace('\'','',$_GET['path']));
    
    $strpath=str_replace('/', '_', $_GET['path']);

    if( isset($_GET['str']) and strlen($_GET['str']) ){
        $_GET['str']=urldecode($_GET['str']);
        $_GET['str']=str_replace('..', '', str_replace(chr(0), '', strip_tags($_GET['str'])));
        $_GET['str']=str_replace('&amp;','&',$_GET['str']);
        $pt='?'.str_replace('[to]','_to',str_replace('[from]','_from',$_GET['str']));
    }
    if( isset($_GET['sort']) and strlen($_GET['sort']) ){
        $_GET['sort']=str_replace('..', '', str_replace(chr(0), '', strip_tags($_GET['sort'])));
        $_GET['sort']=str_replace('"','',str_replace('\'','',$_GET['sort']));
        $pt.='&sort_by='.$_GET['sort'];
    }
    $pt=str_replace('?&', '?', $pt);
    $strpt=str_replace('[', '', str_replace(']', '', str_replace('field_', '', str_replace('/', '_', $pt))));
    
    if( !is_dir('pdxcache/filterstr') ){
        mkdir('pdxcache/filterstr');
    }
    if( !is_dir('pdxcache/filterstr/'.$strpath) ){
        mkdir('pdxcache/filterstr/'.$strpath);
    }
    
    if( 1==0 and file_exists('pdxcache/filterstr/'.$strpath.'/'.$_GET['p'].'_'.$strpt) and filesize('pdxcache/filterstr/'.$strpath.'/'.$_GET['p'].'_'.$strpt)>0 and (time()-filectime('pdxcache/filterstr/'.$strpath.'/'.$_GET['p'].'_'.$strpt) < 333333 ) ){
        echo file_get_contents('pdxcache/filterstr/'.$strpath.'/'.$_GET['p'].'_'.$strpt);
    }else{

        define('DRUPAL_ROOT', getcwd());
        
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
        require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        
        $out='';
        
        if( function_exists('pdxcat_catalog') ){
            
            $rep=0;
            if(isset($_GET['replace']) and is_numeric($_GET['replace']) and $_GET['replace']==1 ){
                $rep=1;
            }
            
            $filter=array();
            if( isset($_GET['str']) and strlen($_GET['str']) ){
                if( function_exists('pdxfilterparse') ){
                    $filter=pdxfilterparse($_GET['str']);
                }
            }
            
            $out.= pdxcat_catalog($_GET['path'], $_GET['cat'], $_GET['b'], $_GET['t'], $filter, $_GET['sort'], $_GET['p'], 1, $rep, 0, $celebration);
            if( $rep ){
                $out.= '<div class="mycatalogmore"></div>';
            }
            
            $out.= '<script type="text/javascript">
';

            if( strlen($pt)>1 ){
                $out.= '
    history.pushState( null, null, "'.$pt.'" );
    ';
            }else{
                if( strpos($_GET['path'], PDXCAT_NAME.'/')===false ){}else{
                    $pth=path_load($_GET['path']); if(strlen($pth['alias'])) $pth=$pth['alias']; else $pth=$_GET['path'];
                }
                $out.= '
    history.pushState( null, null, "/'.$pth.'" );
    ';
            }
            $out.= '
    </script>';
            
        }
        
        if( isset( $out ) and strlen($out) ){
            echo $out;
            if ($fp = fopen('pdxcache/filterstr/'.$strpath.'/'.$_GET['p'].'_'.$strpt, 'w')) {
                fwrite($fp, $out );
                fclose($fp);
            }
        }
        
    
    }
    
}

?>