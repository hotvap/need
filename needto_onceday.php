<?php

$out= @file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/needto_onceday');
if( !isset($out) or !is_numeric($out) or (time()-$out)>77777 ){

    define('DRUPAL_ROOT', getcwd());
    
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    $fp = fopen('pdxcache/'.$_SERVER['HTTP_HOST'].'/needto_onceday', 'w');
    fwrite($fp, time());
    fclose($fp);

    switch(date('N')){
        case 2:
        case 4:
        case 6:
            db_query('truncate table {watchdog}');
            break;
    }

    if( date('N')==3 ){
        $randme=db_query('select u.uid, u.mail, f.field_name_value from {users} as u left join {field_data_field_name} as f on (u.uid=f.entity_id and f.entity_type=\'user\') where u.uid>0 and u.status=1 and u.mail NOT LIKE \'%needto.me%\' order by RAND() limit 0,1');
        $randme=$randme->fetchAssoc();
        if( isset($randme['uid']) and is_numeric($randme['uid']) ){
            $params = array(
                'uid' => $randme['uid'],
                'points' => 7,
                'operation' => 'pdx_lucky',
            );
            userpoints_userpointsapi($params);
            $usname='';
            if( isset($randme['field_name_value']) and strlen($randme['field_name_value']) ){
                $usname=', '.$randme['field_name_value'];
            }
            pdxmail($randme['mail'], '<p>Здравствуй'.$usname.'.</p><p>Ты получил подарок от сайта '.$_SERVER['HTTP_HOST'].': 7 "нид" на баланс твоего аккаунта. Счастливого дня =)</p>', 'Подарок от '.$_SERVER['HTTP_HOST']);
        }
    }

    $pages=array();
    $city=0;
    if( defined('PDX_CITY_ID') and is_numeric(PDX_CITY_ID) ){
        $city=PDX_CITY_ID;
    }

/* ------------- удаление устаревших Нужна вешь */
    $isyes=0;
    $nots=db_query('select nid from {node} where type=\'findmy\' and uid=0 and changed<'.(time()-604800));
    while( $not=$nots->fetchAssoc() ){
        node_delete($not['nid']);
        $isyes++;
    }
    $nots=db_query('select nid from {node} where type=\'findmy\' and uid>0 and created<'.(time()-2678400));
    while( $not=$nots->fetchAssoc() ){
        node_delete($not['nid']);
        $isyes++;
    }
    if($isyes>0){
        $pages[]='_findmy';
        $terms=db_query('select tid from {taxonomy_term_data} where vid=2 order by weight asc');
        while( $term=$terms->fetchAssoc() ){
            $pages[]='_findmy_'.$term['tid'];
        }
    }

/* ------------- PREMIUM */
    $isyes=0;
    $nots=db_query('select f.field_premium_value, f.entity_id, n.uid, n.title from {field_data_field_premium} as f inner join {node} as n on n.nid=f.entity_id where f.bundle=\'item\'');
    while( $not=$nots->fetchAssoc() ){
        if( is_numeric( $not['field_premium_value'] ) and time()>$not['field_premium_value'] ){
            db_query('delete from {field_data_field_premium} where bundle=\'item\' and entity_id='.$not['entity_id']);

            $nidurl='';
            $path=path_load('node/'.$not['entity_id']);
            if( isset($path['alias']) and strlen($path['alias'])){
                $nidurl=$path['alias'];
            }else{
                $nidurl='node/'.$not['entity_id'];
            }
            
            $num=db_query('select field_gold_value from {field_data_field_gold} where entity_type=\'user\' and entity_id='.$not['uid']);
            $num=$num->fetchAssoc();
            if( !isset($num['field_gold_value']) or !is_numeric( $num['field_gold_value'] ) or time()<$num['field_gold_value'] ){
                db_query('update {node} set sticky=0 where nid='.$not['entity_id']);
                db_query('update {node_revision} set sticky=0 where nid='.$not['entity_id']);
                $isyes++;
                
                $url='/'.$nidurl;

                            $url=str_replace('//', '/', $url);
                            $pages[]=str_replace('/','_',$url);

                            $a1=$a8=array();
                            $cat=0;
                            $istaxs=db_query('select d.tid, d.vid from {taxonomy_index} as i inner join {taxonomy_term_data} as d on i.tid=d.tid where i.nid='.$not['entity_id']);
                            while( $istax=$istaxs->fetchAssoc() ){
                                switch($istax['vid']){
                                case 4:
                                case 6:
                                    $path=path_load('taxonomy/term/'.$istax['tid']);
                                    if( isset($path['alias']) and strlen($path['alias'])){
                                        $url='/'.$path['alias'];
                                    }else{
                                        $url='/taxonomy/term/'.$istax['tid'];
                                    }
                                    $url=str_replace('//', '/', $url);
                                    $pages[]=str_replace('/','_',$url);
                                    break;
                                case 2:
                                    $cat=$istax['tid'];
                                case 10:
                                    $pages[]='_catalog_'.$istax['tid'];
                                    break;
                                case 1:
                                    $a1[]=$istax['tid'];
                                    break;
                                case 8:
                                    $a8[]=$istax['tid'];
                                    break;
                                }
                            }
                            if( $cat>0 ){
                                if( isset($a8) and is_array($a8) and count($a8) ){
                                    foreach( $a8 as $path1 ){
                                        $pages[]='_catalog_'.$cat.'_'.$path1;
                                        if( isset($a1) and is_array($a1) and count($a1) ){
                                            foreach( $a1 as $path2 ){
                                                $pages[]='_catalog_'.$cat.'_'.$path1.'_'.$path2;
                                            }
                                        }
                                    }
                                }
                            }

                pdxcd('item', $not['entity_id'], 1);
                cache_clear_all('field:node:'.$not['entity_id'], 'cache_field', true);

            }
            

                $ismail=db_query('select u.mail, f.field_name_value from {users} as u left join {field_data_field_name} as f on (u.uid=f.entity_id and f.entity_type=\'user\') where u.uid='.$not['uid']);
                $ismail=$ismail->fetchAssoc();
                if( isset($ismail['mail']) and strlen($ismail['mail']) and valid_email_address($ismail['mail']) and function_exists('pdxmail') ){
                    $usname='';
                    if( isset($ismail['field_name_value']) and strlen($ismail['field_name_value']) ){
                        $usname=', '.$ismail['field_name_value'];
                    }
                    $msg='<p>Здравствуй'.$usname.'.</p>';
                    $msg.='<p>Срок действия статуса Premium для объявления <a href="'.$GLOBALS['base_url'].'/'.$nidurl.'">"'.$not['title'].'"</a> завершен. Команда сайта '.$_SERVER['HTTP_HOST'].' благодарит вас за сотрудничество.</p>';
                    
                    pdxmail($ismail['mail'], $msg, 'Срок действия Premium-статуса окончен');
                    
                }


            
        }
    }
    if( $isyes>0 ){
        if( function_exists('setrecall') ){
            setrecall();
        }
    }




/* ------------- GOLD */
    $isyes=0;
    $nots=db_query('select f.field_gold_value, f.entity_id, u.mail, n.field_name_value from {field_data_field_gold} as f inner join {users} as u on f.entity_id=u.uid inner join {field_data_field_name} as n on (f.entity_id=n.entity_id and n.entity_type=\'user\') where f.entity_type=\'user\'');
    while( $not=$nots->fetchAssoc() ){
        if( is_numeric( $not['field_gold_value'] ) and time()>$not['field_gold_value'] ){
            db_query('delete from {field_data_field_gold} where entity_type=\'user\' and entity_id='.$not['entity_id']);
            
            $nums=db_query('select n.nid, f.field_premium_value from {node} as n left join {field_data_field_premium} as f on (n.nid=f.entity_id and f.entity_type=\'node\') where n.type=\'item\' and n.uid='.$not['entity_id']);
            while( $num=$nums->fetchAssoc() ){
                if( !isset($num['field_premium_value']) or !is_numeric( $num['field_premium_value'] ) or time()<$num['field_premium_value'] ){
                    db_query('update {node} set sticky=0 where nid='.$num['nid']);
                    db_query('update {node_revision} set sticky=0 where nid='.$num['nid']);
                    $isyes++;

                            $path=path_load('node/'.$num['nid']);
                            if( isset($path['alias']) and strlen($path['alias'])){
                                $url='/'.$path['alias'];
                            }else{
                                $url='/node/'.$num['nid'];
                            }
                            $url=str_replace('//', '/', $url);
                            $pages[]=str_replace('/','_',$url);


                            $a1=$a8=array();
                            $cat=0;
                            $istaxs=db_query('select d.tid, d.vid from {taxonomy_index} as i inner join {taxonomy_term_data} as d on i.tid=d.tid where i.nid='.$num['nid']);
                            while( $istax=$istaxs->fetchAssoc() ){
                                switch($istax['vid']){
                                case 4:
                                case 6:
                                    $path=path_load('taxonomy/term/'.$istax['tid']);
                                    if( isset($path['alias']) and strlen($path['alias'])){
                                        $url='/'.$path['alias'];
                                    }else{
                                        $url='/taxonomy/term/'.$istax['tid'];
                                    }
                                    $url=str_replace('//', '/', $url);
                                    $pages[]=str_replace('/','_',$url);
                                    break;
                                case 2:
                                    $cat=$istax['tid'];
                                case 10:
                                    $pages[]='_catalog_'.$istax['tid'];
                                    break;
                                case 1:
                                    $a1[]=$istax['tid'];
                                    break;
                                case 8:
                                    $a8[]=$istax['tid'];
                                    break;
                                }
                            }
                            if( $cat>0 ){
                                if( isset($a8) and is_array($a8) and count($a8) ){
                                    foreach( $a8 as $path1 ){
                                        $pages[]='_catalog_'.$cat.'_'.$path1;
                                        if( isset($a1) and is_array($a1) and count($a1) ){
                                            foreach( $a1 as $path2 ){
                                                $pages[]='_catalog_'.$cat.'_'.$path1.'_'.$path2;
                                            }
                                        }
                                    }
                                }
                            }

                }
                cache_clear_all('field:node:'.$num['nid'], 'cache_field', true);
            }
            db_query('delete from {users_roles} where rid=8 and uid='.$not['entity_id']);
            if( function_exists('clearusercaches') ){
                clearusercaches($not['entity_id']);
            }
            
            
                if( isset($not['mail']) and strlen($not['mail']) and valid_email_address($not['mail']) and function_exists('pdxmail') ){
                            $path=path_load('user/'.$not['entity_id']);
                            if( isset($path['alias']) and strlen($path['alias'])){
                                $nidurl=$path['alias'];
                            }else{
                                $nidurl='user/'.$not['entity_id'];
                            }
                    
                    $usname='';
                    if( isset($not['field_name_value']) and strlen($not['field_name_value']) ){
                        $usname=', '.$not['field_name_value'];
                    }
                    $msg='<p>Здравствуй'.$usname.'.</p>';
                    $msg.='<p>Срок действия Gold-аккаунта для профиля <a href="'.$GLOBALS['base_url'].'/'.$nidurl.'">"'.$not['field_name'].'"</a> истёк. Команда сайта '.$_SERVER['HTTP_HOST'].' благодарит тебя за сотрудничество.</p>';
                    
                    pdxmail($ismail['mail'], $msg, 'Срок действия Gold-аккаунта окончен');
                    
                }


            
        }
    }
    if( $isyes>0 ){
        if( function_exists('setrecall') ){
            setrecall();
        }
    }

        if( function_exists('pdxclearmycache') ){
            pdxclearmycache($pages);
        }



    //генерация архива объявлений для программы
    $anids=array();
    $nids=db_query('select n.nid, n.title, fs.field_subpart_tid, f1.field_price_hour_value, f2.field_price_day_value, f3.field_price_week_value, f4.field_price_month_value, fi.field_image_fid, f5.field_cur_value, f6.field_price_min_value, b.tid, b.name from {node} as n inner join {field_data_field_subpart} as fs on (n.nid=fs.entity_id and fs.entity_type=\'node\') left join {field_data_field_cur} as f5 on (n.nid=f5.entity_id and f5.entity_type=\'node\') left join {field_data_field_price_hour} as f1 on (n.nid=f1.entity_id and f1.entity_type=\'node\') left join {field_data_field_price_day} as f2 on (n.nid=f2.entity_id and f2.entity_type=\'node\') left join {field_data_field_price_week} as f3 on (n.nid=f3.entity_id and f3.entity_type=\'node\') left join {field_data_field_price_month} as f4 on (n.nid=f4.entity_id and f4.entity_type=\'node\') left join {field_data_field_price_min} as f6 on (n.nid=f6.entity_id and f6.entity_type=\'node\') left join {field_data_field_image} as fi on (n.nid=fi.entity_id and fi.entity_type=\'node\' and fi.delta=0) left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') left join {field_data_field_brand} as f7 on (n.nid=f7.entity_id and f7.entity_type=\'node\') left join {taxonomy_term_data} as b on b.tid=f7.field_brand_tid where n.status=1 and n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 )');
    while( $nid=$nids->fetchAssoc() ){
        $pr=array();
        $pr['id']=$nid['nid'];
        $pr['title']=$nid['title'];
        $pr['link']=url('node/'.$nid['nid']);
        if( isset( $nid['field_cur_value'] ) and is_numeric($nid['field_cur_value']) ){
            $pr['cur']=$nid['field_cur_value'];
        }else{
            $pr['cur']=1;
        }

        
       if( isset( $nid['tid'] ) and is_numeric($nid['tid']) ){
            $pr['brandid']=$nid['tid'];
            $pr['brandname']=$nid['name'];
        }

        if( isset($nid['field_price_min_value']) and is_numeric($nid['field_price_min_value']) ){
            $suffix= 'часов';
            $tmpis=$nid['field_price_min_value'];
            if( $tmpis>23 ){
                $tmpis=round($tmpis/24, 0);
                $suffix= 'дней';
                if( $tmpis==1 ){
                    $suffix= 'дня';
                }
            }
            if( $tmpis>29 ){
                $tmpis=round($tmpis/30, 0);
                $suffix= 'месяца';
            }
            $pr['min']='от '.$tmpis.' '.$suffix;
        }
        
    $price1=$price2=$price4=$price3=$yesprice=$pricelen=0;
    
    if( isset($nid['field_price_month_value']) and is_numeric($nid['field_price_month_value']) ){
        $price4=$nid['field_price_month_value'];
        $price3=round($nid['field_price_month_value']/4, 2);
        $price2=round($nid['field_price_month_value']/30, 2);
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($nid['field_price_week_value']) and is_numeric($nid['field_price_week_value']) ){
        $price3=$nid['field_price_week_value'];
        $price2=round($nid['field_price_week_value']/7, 2);
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($nid['field_price_day_value']) and is_numeric($nid['field_price_day_value']) ){
        $price2=$nid['field_price_day_value'];
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        if( !isset($price3) or !is_numeric($price3) or $price3<1 ){
            $price3=$price2*7;
        }
        $price1=round($price2/24, 2);
        $yesprice=1;
    }
    if( isset($nid['field_price_hour_value']) and is_numeric($nid['field_price_hour_value']) ){
        $price1=$nid['field_price_hour_value'];
        if( !isset($price2) or !is_numeric($price2) or $price2<1 ){
            $price2=$price1*24;
        }
        if( !isset($price4) or !is_numeric($price4) or $price4<1 ){
            $price4=$price2*30;
        }
        if( !isset($price3) or !is_numeric($price3) or $price3<1 ){
            $price3=$price2*7;
        }
        $yesprice=1;
    }
    if( isset($nid['field_price_min_value']) and is_numeric($nid['field_price_min_value']) ){
        if( $nid['field_price_min_value']>23 ){
            $price1=0;
            if( $nid['field_price_min_value']>167 ){
                $price2=0;
            }
        }
    }



        if( isset( $price1 ) and is_numeric($price1) ){
            $price1=ceil($price1);
            $pr['price1']=$price1;
        }else{
            $pr['price1']=0;
        }
        if( isset( $price2 ) and is_numeric($price2) ){
            $price2=ceil($price2);
            $pr['price2']=$price2;
        }else{
            $pr['price2']=0;
        }
        if( isset( $price3 ) and is_numeric($price3) ){
            $price3=ceil($price3);
            $pr['price3']=$price3;
        }else{
            $pr['price3']=0;
        }
        if( isset( $price4 ) and is_numeric($price4) ){
            $price4=ceil($price4);
            $pr['price4']=$price4;
        }else{
            $pr['price4']=0;
        }
        if( isset( $nid['field_image_fid'] ) and is_numeric($nid['field_image_fid']) ){
            $file = file_load($nid['field_image_fid']);
            if( isset( $file->uri ) and strlen( $file->uri ) ){
                $pr['image1']=image_style_url('inlinesearch', $file->uri);
                $pr['image2']=image_style_url('original', $file->uri);
                $pr['image3']=image_style_url('news_preview', $file->uri);
            }
//            $style= image_style_load('product_full');
        }
        
        $anids[$nid['field_subpart_tid']][]=$pr;
    }
    if( isset($anids) and is_array($anids) and count($anids) ){
        echo '<pre>'.print_r($anids, true). '</pre>';
        require_once DRUPAL_ROOT . '/sites/all/libraries/s3-php5-curl/S3.php';
        $s3=new S3(PDX_S3_1, PDX_S3_2);
        $bucketName='needtominsk';
        
        $zip = new ZipArchive();
        $zip->open(DRUPAL_ROOT.'/curs/tmp/'.PDX_CITY_ID.'_items.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString(PDX_CITY_ID.'_items.dat', json_encode($anids));
        $zip->close();
        
        $s3->putObjectFile( DRUPAL_ROOT.'/curs/tmp/'.PDX_CITY_ID.'_items.zip' , $bucketName, 'mobile/'.PDX_CITY_ID.'_items.zip', S3::ACL_PUBLIC_READ);
        
        
    }



    
}


?>