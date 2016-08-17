<?php
$outis='';

if(file_exists('pdxcache/blocks/filterbrand') and filectime('pdxcache/blocks/filterbrand')>(time()-186400) ){
    echo @file_get_contents('pdxcache/blocks/filterbrand');
}else{

define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    $terms = taxonomy_get_tree(4);
    if($terms){
        $outis.='<ul>';
        $abr=array();
        foreach($terms as $term){
            $abr[$term->name]=$term->tid;
        }
        if( isset($abr) and is_array($abr) and count($abr) ){
            ksort($abr);
            foreach( $abr as $name=>$tid ){
//                $outis.='<li><a class="isbrand'.$tid.'" href=" javascript: void(0); " onclick=" jQuery(\'.selectbrand span\').html(\''.$name.'\'); jQuery(\'.selectbrand span\').addClass(\'select\'); jQuery(\'.showbrand\').hide(); ">'.$name.'</a></li>';
                $outis.='<li><a class="isbrand'.$tid.'" href=" javascript: void(0); " onclick=" jQuery(\'.selectbrand span\').html(\''.$name.'\'); jQuery(\'#search_brand\').val('.$tid.'); jQuery(\'.selectbrand span\').addClass(\'select\'); jQuery(\'.showbrand\').hide(); ">'.$name.'</a></li>';
            }
        }
        $outis.='</ul>';
    }
    
    $outis.='<script type="text/javascript"> jQuery(\'.showbrand\').show(); </script>';

    if( isset($outis) and strlen($outis) ){
        echo $outis;
        $fp=fopen('pdxcache/blocks/filterbrand', "w");
        if($fp){
            fwrite($fp, $outis); fclose($fp);
        }
    
    }

}


?>