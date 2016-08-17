<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);

$alllink=0;

$aout=array();
$aout[]='
<sitemap><loc>http://'.$_SERVER['HTTP_HOST'].'/sitemap_other.xml</loc><lastmod>'.date('Y-m-d', time()).'</lastmod></sitemap>';




/* --- sitemap_node --- */


$counter=0;
$counternum=0;
$arr=array();
$ress=db_query('select alias, source from {url_alias} where alias <> \'node\' and alias not like \'file/%\' and alias not like \'admin/%\'');
while( $res=$ress->fetchAssoc() ){
    if( strpos($res['alias'], 'help/')===false or $res['source']=='node/1673' or $res['source']=='node/135' ){}else{
        continue;
    }
    $arr[]['loc']=mb_strtolower($res['alias']);

    $counter++;
    if( !($counter%49998) ){
        if( isset($arr) and is_array($arr) and count($arr) ){
            cxml($arr, 'sitemap_node_'.$counternum, 'weekly', 0.3);
            $aout[]='
<sitemap><loc>http://'.$_SERVER['HTTP_HOST'].'/sitemap_node_'.$counternum.'.xml</loc><lastmod>'.date('Y-m-d', time()).'</lastmod></sitemap>';
            $counternum++;
        }
        $counter++;
        $arr=array();
    }

}
if( isset($arr) and is_array($arr) and count($arr) ){
    cxml($arr, 'sitemap_node_'.$counternum, 'weekly', 0.3);
    $aout[]='
<sitemap><loc>http://'.$_SERVER['HTTP_HOST'].'/sitemap_node_'.$counternum.'.xml</loc><lastmod>'.date('Y-m-d', time()).'</lastmod></sitemap>';
}

/* --- sitemap_other --- */
$arr=array();

$tmp=array();
$tmp['loc']='';
$tmp['priority']=1;
$tmp['changefreq']='weekly';
$arr[]=$tmp;

//$tmp=array();
//$tmp['loc']='news';
//$tmp['priority']=1;
//$tmp['changefreq']='weekly';
//$arr[]=$tmp;

$tmp=array();
$tmp['loc']='users/rents';
$tmp['priority']=1;
$tmp['changefreq']='weekly';
$arr[]=$tmp;

//$tmp=array();
//$tmp['loc']='a';
//$tmp['priority']=1;
//$tmp['changefreq']='monthly';
//$arr[]=$tmp;

//$tmp=array();
//$tmp['loc']='press';
//$tmp['priority']=1;
//$tmp['changefreq']='monthly';
//$arr[]=$tmp;

//$tmp=array();
//$tmp['loc']='i';
//$tmp['priority']=1;
//$tmp['changefreq']='monthly';
//$arr[]=$tmp;

//$tmp=array();
//$tmp['loc']='dev';
//$tmp['priority']=1;
//$tmp['changefreq']='monthly';
//$arr[]=$tmp;

$tmp=array();
$tmp['loc']='apply';
$tmp['priority']=1;
$tmp['changefreq']='monthly';
$arr[]=$tmp;

//$tmp=array();
//$tmp['loc']='rules';
//$tmp['priority']=1;
//$tmp['changefreq']='monthly';
//$arr[]=$tmp;

//$tmp=array();
//$tmp['loc']='apime';
//$tmp['priority']=1;
//$tmp['changefreq']='monthly';
//$arr[]=$tmp;

/*
$tmp=array();
$tmp['loc']='brands';
$tmp['priority']=1;
$tmp['changefreq']='weekly';
$arr[]=$tmp;
*/

$tmp=array();
$tmp['loc']='findmy';
$tmp['priority']=1;
$tmp['changefreq']='weekly';
$arr[]=$tmp;

$terms=db_query('select tid from {taxonomy_term_data} where vid=2 order by weight asc');
while( $term=$terms->fetchAssoc() ){
    $tmp=array();
    $tmp['loc']='findmy/'.$term['tid'];
    $tmp['priority']=1;
    $tmp['changefreq']='weekly';
    $arr[]=$tmp;

    $tmp=array();
    $tmp['loc']='catalog/'.$term['tid'];
    $tmp['priority']=1;
    $tmp['changefreq']='weekly';
    $arr[]=$tmp;

    $tids=db_query('select d.tid from {field_data_field_part} as f inner join {taxonomy_term_data} as d on f.entity_id=d.tid where f.entity_type=\'taxonomy_term\' and f.field_part_tid='.$term['tid'].' and d.vid=8');
    while( $tid=$tids->fetchAssoc() ){
        $tmp=array();
        $tmp['loc']='catalog/'.$term['tid'].'/'.$tid['tid'];
        $tmp['priority']=1;
        $tmp['changefreq']='weekly';
        $arr[]=$tmp;

        $tids2=db_query('select d.tid from {field_data_field_part} as f inner join {taxonomy_term_data} as d on f.entity_id=d.tid inner join {field_data_field_subpart} as s on ( d.tid=s.entity_id and s.entity_type=\'taxonomy_term\' ) where f.entity_type=\'taxonomy_term\' and f.field_part_tid='.$term['tid'].' and s.field_subpart_tid='.$tid['tid'].' and d.vid<>8');
        while( $tid2=$tids2->fetchAssoc() ){
            $tmp=array();
            $tmp['loc']='catalog/'.$term['tid'].'/'.$tid['tid'].'/'.$tid2['tid'];
            $tmp['priority']=1;
            $tmp['changefreq']='weekly';
            $arr[]=$tmp;
        }
        
    }

}

$terms = db_query('select tid from {taxonomy_term_data} where vid=10');
while($term=$terms->fetchAssoc()){
    $tmp=array();
    $tmp['loc']='catalog/'.$term['tid'];
    $tmp['priority']=1;
    $tmp['changefreq']='weekly';
    $arr[]=$tmp;
}



$ress=db_query('select DISTINCT u.uid from {users} as u left join {users_roles} as r on (u.uid=r.uid and (r.rid=3 or r.rid=4)) where u.status=1 and r.rid IS NULL');
while( $res=$ress->fetchAssoc() ){
    $tmp=array();
    $tmp['loc']='user/'.$res['uid'];
    $tmp['priority']=0.3;
    $tmp['changefreq']='weekly';
    $arr[$tmp['loc']]=$tmp;
}

if( isset($arr) and is_array($arr) and count($arr) ){
    cxml($arr, 'sitemap_other', 'monthly', 0.3);
}

//if( $alllink>0 ){
//    echo 'Страниц: '.$alllink;
//}


if( isset($aout) and is_array($aout) and count($aout) ){ 
    $out='<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';
    $out.=implode('', $aout);
    $out.='</sitemapindex>';

    $fp = fopen('sitemap.xml', 'w'); fwrite($fp, $out); fclose($fp);

}

function cxml($arr, $name, $changefreq, $priority){
    global $alllink;
    if( strlen($name) and isset($arr) and is_array($arr) and count($arr) ){
        $out='<?xml version="1.0" encoding="UTF-8" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach( $arr as $nid=>$val ){

            $prepval=str_replace('http://www.'.$_SERVER['HTTP_HOST'].'/', '', $val['loc']);
            $prepval=str_replace('https://www.'.$_SERVER['HTTP_HOST'].'/', '', $prepval);
            $prepval=str_replace('http://'.$_SERVER['HTTP_HOST'].'/', '', $prepval);
            $prepval=str_replace('https://'.$_SERVER['HTTP_HOST'].'/', '', $prepval);
            
            $alllink++;
            if( !isset($val['priority']) ){
                $val['priority']=$priority;
            }
            if( !isset($val['changefreq']) ){
                $val['changefreq']=$changefreq;
            }
            if( function_exists('pdx_mysafe') ){
                $val['loc']=pdx_mysafe($val['loc']);
            }
            $val['lastmod']='';
            $out.='
<url><loc>http://'.$_SERVER['HTTP_HOST'].'/'.$val['loc'].'</loc>'.$val['lastmod'].'<changefreq>'.$val['changefreq'].'</changefreq><priority>'.$val['priority'].'</priority></url>';
        }
        $out.='
</urlset>';
        $fp = fopen($name.'.xml', 'w'); fwrite($fp, $out); fclose($fp);
    }
}
?>