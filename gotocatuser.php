<?php

if( isset($_GET['p']) and is_numeric($_GET['p']) and isset($_GET['sort']) and strlen($_GET['sort']) ){

    $pt='?';
    
    $strpath='users/rents';
    $strpath=str_replace('/', '_', $strpath);

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
        
        if( function_exists('pdxcat_catalog_user') ){
            
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
            
            $out.= pdxcat_catalog_user($filter, $_GET['sort'], $_GET['p'], 1, $rep, 0);
            if( $rep ){
                $out.= '<div class="mycatalogmore"></div>';
            }
            
            $out.= '<script type="text/javascript">
';
/*
            $out.='jQuery(\'#map_canvas_profiles\').remove(); ';
            if( isset($filter['field_rn'][0]) and is_numeric($filter['field_rn'][0]) and $filter['field_rn'][0]>0 ){
                $out.=' jQuery(\'#floatis\').after(\'<div id="map_canvas_profiles" style="width: 100%; height: 311px; display: none;"></div><div id="map_canvas_profiles_cnt" style="display: none;"><div class="maps_raion">'.implode('|', $filter['field_rn']).'</div></div>\'); jQuery.post(Drupal.settings.basePath + \'showmapall.php\', { tid: jQuery(\'.maps_raion\').html(), cat: 0, city: curCity }, function( data ) { jQuery(\'#map_canvas_profiles_cnt\').html(data); } ); ';
            }elseif( isset($filter['field_parts'][0]) and is_numeric($filter['field_parts'][0]) and $filter['field_parts'][0]>0 ){
                $out.=' jQuery(\'#floatis\').after(\'<div id="map_canvas_profiles" style="width: 100%; height: 311px; display: none;"></div><div id="map_canvas_profiles_cnt" style="display: none;"><div class="maps_cat">'.implode('|', $filter['field_parts']).'</div></div>\'); jQuery.post(Drupal.settings.basePath + \'showmapall.php\', { tid: 0, cat: jQuery(\'.maps_cat\').html(), city: curCity }, function( data ) { jQuery(\'#map_canvas_profiles_cnt\').html(data); } ); ';                
            }
*/

            if( strlen($pt)>1 ){
                $out.= '
    history.pushState( null, null, "'.$pt.'" );
    ';
            }else{
                $pth=PDXCAT_USERNAME;
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