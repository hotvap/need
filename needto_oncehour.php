<?php

$out= @file_get_contents('pdxcache/needto_oncehour');
if( !isset($out) or !is_numeric($out) or (time()-$out)>3333 ){

    define('DRUPAL_ROOT', getcwd());
    
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    $fp = fopen('pdxcache/needto_oncehour', 'w');
    fwrite($fp, time());
    fclose($fp);
    
    $connect=1;
    $bucketName='needtominsk';

    if( PDX_CITY_ID==28 ){
        require_once DRUPAL_ROOT . '/sites/all/libraries/s3-php5-curl/S3.php';
        $s3=new S3(PDX_S3_1, PDX_S3_2);

        $change=0;
        //Обновление таксономии
        if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/tax') ){
            unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/tax');
            pdxcw('vars', 'tax', '', 1);
            $change=1;

            if( !$connect ){
                require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
                $connect=1;
            }
            
            //формирование файла разделов и подразделов
/*
            $out='<?xml version="1.0"?>
<partes>';
*/
            $aval=array();
            $nums=db_query('select tid, name from {taxonomy_term_data} where vid=2');
            while($num=$nums->fetchAssoc()){
                $atmp=array();
                $atmp['id']=$num['tid'];
                $atmp['name']=lxmldelit($num['name']);
                $atmp['link']='catalog/'.$num['tid'];
//                $out.='<part id="'.$num['tid'].'" name="'.lxmldelit($num['name']).'" link="catalog/'.$num['tid'].'">';
                    
                    $tids=db_query('select d.tid, d.name from {field_data_field_part} as f inner join {taxonomy_term_data} as d on f.entity_id=d.tid where f.entity_type=\'taxonomy_term\' and f.field_part_tid='.$num['tid'].' and d.vid=8 order by d.weight');
                    while( $tid=$tids->fetchAssoc() ){
                        $atmp2=array();
                        $atmp2['id']=$tid['tid'];
                        $atmp2['name']=lxmldelit($tid['name']);
                        $atmp2['link']='catalog/'.$num['tid'].'/'.$tid['tid'];
//                        $out.='<subpart id="'.$tid['tid'].'" name="'.lxmldelit($tid['name']).'" link="catalog/'.$num['tid'].'/'.$tid['tid'].'" />';
                        $atmp['subparts'][]=$atmp2;
                    }

//                $out.='</part>';
                $aval[]=$atmp;
            }
/*
            $atmp=array();
            $atmp['id']=0;
            $atmp['name']='К празднику';
            $atmp['link']='';
                $nums=db_query('select tid, name from {taxonomy_term_data} where vid=10');
                while($num=$nums->fetchAssoc()){
                    $atmp2=array();
                    $atmp2['id']=$num['tid'];
                    $atmp2['name']=lxmldelit($num['name']);
                    $atmp2['link']='catalog/'.$num['tid'];

                    $atmp['subparts'][]=$atmp2;
                }
                $aval[]=$atmp;
*/            
                if( isset($aval) and is_array($aval) and count($aval) ){ 
                    $zip = new ZipArchive();
                    $zip->open(DRUPAL_ROOT.'/curs/tmp/tax.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
                    $zip->addFromString("tax.dat", json_encode($aval));
                    $zip->close();
                    $s3->putObjectFile( DRUPAL_ROOT.'/curs/tmp/tax.zip' , $bucketName, 'mobile/tax.zip', S3::ACL_PUBLIC_READ);
                }
        }
    
        if( $change ){

            //формирование файла дат
            $out='{';
            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/tax') ){
                $out.='"tax": "'.filectime('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/tax').'"';
            }
            $out.='}';
            

            $s3->putObject($out, $bucketName, 'mobile/init.dat', S3::ACL_PUBLIC_READ);
    
        }
    }
    
    
    //обновление информации о количестве товаров в разделах
    if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/items2') ){
        unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/items2');
        if( !$connect ){
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
            $connect=1;
        }
        
        $atids=array();
        $countall=0;
        
        $tids=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=1 and n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 )');
        $tids=$tids->fetchAssoc();
        if( isset($tids['COUNT(n.nid)']) and is_numeric($tids['COUNT(n.nid)']) ){
            $countall=$tids['COUNT(n.nid)'];
        }
        
        $tids=db_query('select tid from {taxonomy_term_data} where vid IN (2, 10)');
        while( $tid=$tids->fetchAssoc() ){
            $num=db_query('select COUNT(n.nid) from {node} as n inner join {field_data_taxonomy_catalog} as t on (n.nid=t.entity_id and t.entity_type=\'node\') left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=1 and n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) and t.taxonomy_catalog_tid='.$tid['tid']);
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>0 ){
                $atids[$tid['tid']]=$num['COUNT(n.nid)'];
            }else{
                $atids[$tid['tid']]=0;
            }
        }
        
        $tids=db_query('select tid from {taxonomy_term_data} where vid IN (8)');
        while( $tid=$tids->fetchAssoc() ){
            $num=db_query('select COUNT(n.nid) from {node} as n inner join {field_data_field_subpart} as p on (n.nid=p.entity_id and p.entity_type=\'node\') left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=1 and n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) and p.field_subpart_tid='.$tid['tid']);
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>0 ){
                $atids[$tid['tid']]=$num['COUNT(n.nid)'];
            }else{
                $atids[$tid['tid']]=0;
            }
        }


        $tids=db_query('select tid from {taxonomy_term_data} where vid IN (1)');
        while( $tid=$tids->fetchAssoc() ){
            $num=db_query('select COUNT(n.nid) from {node} as n inner join {field_data_field_tags} as p on (n.nid=p.entity_id and p.entity_type=\'node\') left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=1 and n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) and p.field_tags_tid='.$tid['tid']);
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>0 ){
                $atids[$tid['tid']]=$num['COUNT(n.nid)'];
            }else{
                $atids[$tid['tid']]=0;
            }
        }

    
        if( isset($atids) and is_array($atids) and count($atids) ){
            $out='  jQuery(document).ready(function($){  jQuery(\'.status_count_item\').html('.$countall.'); window.setInterval(\'shmyc()\', 777);  }); function shmyc(){ ';
            foreach( $atids as $tid=>$count ){
                $out.=' shmycin('.$tid.', '.$count.'); ';
            }
            $out.=' } ';
            if( isset( $out ) and strlen( $out ) ){
                $fp = fopen('js/a_'.PDX_CITY_ID.'.js', 'w'); fwrite($fp, $out); fclose($fp);
                if( !isset($s3) ){
                    require_once DRUPAL_ROOT . '/sites/all/libraries/s3-php5-curl/S3.php';
                    $s3=new S3(PDX_S3_1, PDX_S3_2);
                }
                $s3->putObject($out, $bucketName, 'curs/a_'.PDX_CITY_ID.'.js', S3::ACL_PUBLIC_READ);
                $s3->putObject('jQuery(\'#mcounter'.PDX_CITY_ID.'\').html('.$countall.');', $bucketName, 'curs/al_'.PDX_CITY_ID.'.js', S3::ACL_PUBLIC_READ);
            }
            
        }
    

        
        
    }

    //генерация RSS
    if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/allpubs') ){
        unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/allpubs');
        if( !$connect ){
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
            $connect=1;
        }
        
        $out='<?xml version="1.0"?>
<rss version="2.0">
<channel>
<title>Аренда полезных вещей в '.PDX_CITY_NAME2.'</title>
<link>http://'.$_SERVER['HTTP_HOST'].'</link>
<description>Всё, что можно взять в аренду в '.PDX_CITY_NAME2.', вы найдете у нас! А также на нашей площадке вы можете сдавать в аренду собственные вещи, которые вам в данный момент не нужны. Зарабатывая на них дополнительные деньги.</description>
<language>ru</language>
<pubDate>'.date('r', time()).'</pubDate>
<lastBuildDate>'.date('r', time()).'</lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>needto.me</generator>
';
        
        $nums=db_query('select n.nid, n.title, n.type, b.body_value, n.changed, i.field_image_fid from {node} as n left join {field_data_body} as b on n.nid=b.entity_id left join {field_data_field_image} as i on (n.nid=i.entity_id and i.delta=0) where n.status=1 and n.type IN (\'item\', \'news\', \'findmy\') order by n.changed desc limit 0, 33');
        while( $num=$nums->fetchAssoc() ){
            $desc='';
            $out.='<item>';
            switch( $num['type'] ){
            case 'news':
                if( isset( $num['body_value'] ) and strlen( $num['body_value'] ) ){
                    $desc=strip_tags($num['body_value']);
                }
                if( strlen($desc) and $desc>127 ){
                    $desc=mb_substr($desc, 0, 123).'...';
                }
                $out.='<title>'.lxmldelit($num['title']).'</title><link>http://needto.me/news.html</link><description><![CDATA['.lxmldelit($desc).']]></description><pubDate>'.date('r', $num['changed']).'</pubDate><guid>http://'.$_SERVER['HTTP_HOST'].'#'.$num['nid'].'</guid>';
/*
                if( isset( $num['field_image_fid'] ) and is_numeric( $num['field_image_fid'] ) ){
                    $file = file_load($num['field_image_fid']);
                    if( isset( $file->uri ) and strlen( $file->uri ) ){
                        $img=image_style_url('news_preview', $file->uri);
                    }
                    if( isset($img) and strlen($img) ){
                        $out.='<image><url>'.$img.'</url><title>'.lxmldelit($num['title']).'</title></image>';
                    }
                }
*/
                break;
            case 'item':
                if( isset( $num['body_value'] ) and strlen( $num['body_value'] ) ){
                    $desc=strip_tags($num['body_value']);
                }
                if( strlen($desc) and $desc>127 ){
                    $desc=mb_substr($desc, 0, 123).'...';
                }
                $out.='<title>'.lxmldelit($num['title']).'</title><link>http://'.$_SERVER['HTTP_HOST'].'/item/'.$num['nid'].'</link><description><![CDATA['.lxmldelit($desc).']]></description><pubDate>'.date('r', $num['changed']).'</pubDate><guid>http://'.$_SERVER['HTTP_HOST'].'#'.$num['nid'].'</guid>';
/*
                if( isset( $num['field_image_fid'] ) and is_numeric( $num['field_image_fid'] ) ){
                    $file = file_load($num['field_image_fid']);
                    if( isset( $file->uri ) and strlen( $file->uri ) ){
                        $img=image_style_url('news_preview', $file->uri);
                    }
                    if( isset($img) and strlen($img) ){
                        $out.='<image><url>'.$img.'</url><title>'.lxmldelit($num['title']).'</title></image>';
                    }
                }
*/
                break;
            case 'findmy':
                if( isset( $num['body_value'] ) and strlen( $num['body_value'] ) ){
                    $desc=strip_tags($num['body_value']);
                }
                if( strlen($desc) and $desc>127 ){
                    $desc=mb_substr($desc, 0, 123).'...';
                }
                $out.='<title>'.lxmldelit($num['title']).'</title><link>http://'.$_SERVER['HTTP_HOST'].'/findmy</link><description><![CDATA['.lxmldelit($desc).']]></description><pubDate>'.date('r', $num['changed']).'</pubDate><guid>http://'.$_SERVER['HTTP_HOST'].'#'.$num['nid'].'</guid>';
/*
                if( isset( $num['field_image_fid'] ) and is_numeric( $num['field_image_fid'] ) ){
                    $file = file_load($num['field_image_fid']);
                    if( isset( $file->uri ) and strlen( $file->uri ) ){
                        $img=image_style_url('news_preview', $file->uri);
                    }
                    if( isset($img) and strlen($img) ){
                        $out.='<image><url>'.$img.'</url><title>'.lxmldelit($num['title']).'</title></image>';
                    }
                }
*/
                break;
            }
            $out.='</item>';
        }
        
        $out.='
</channel>
</rss>';

        if( !isset($s3) ){
            require_once DRUPAL_ROOT . '/sites/all/libraries/s3-php5-curl/S3.php';
            $s3=new S3(PDX_S3_1, PDX_S3_2);
        }
        $s3->putObject($out, $bucketName, 'static/rss/'.PDX_CITY_ID.'.rss', S3::ACL_PUBLIC_READ);


        
        
    }
    

    

    
}


function lxmldelit($str){
    $str=str_replace('"','&quot;',$str);
    $str=str_replace('©','&copy;',$str);
    $str=str_replace('®','&reg;',$str);
    $str=str_replace('™','&trade;',$str);
    $str=str_replace('?','&euro;',$str);
    $str=str_replace('Ј','&pound;',$str);
    $str=str_replace('„','&bdquo;',$str);
    $str=str_replace('“','&ldquo;',$str);
    $str=str_replace('«','&laquo;',$str);
    $str=str_replace('»','&raquo;',$str);
    $str=str_replace('>','&gt;',$str);
    $str=str_replace('<','&lt;',$str);
    $str=str_replace('?','&ge;',$str);
    $str=str_replace('?','&le;',$str);
    $str=str_replace('?','&asymp;',$str);
    $str=str_replace('?','&ne;',$str);
    $str=str_replace('?','&equiv;',$str);
    $str=str_replace('§','&sect;',$str);
    $str=str_replace('&','&amp;',$str);
    $str=str_replace('?','&infin;',$str);

    return $str;
}

?>