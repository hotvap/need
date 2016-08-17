<?php

$out= @file_get_contents('pdxcache/needto_oncemin');
if( !isset($out) or !is_numeric($out) or (time()-$out)>291 ){

    define('DRUPAL_ROOT', getcwd());
    
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';

    $fp = fopen('pdxcache/needto_oncemin', 'w');
    fwrite($fp, time());
    fclose($fp);
    
    $connect=0;
    $bucketName='needtominsk';


    //рекомендуемые объявления
    if( !file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_rec') or !filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_rec') ){
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        if( function_exists('setrecall') ){
            setrecall();
        }
        $connect=1;
    }

    $anids=array();
    if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_rec') and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_rec') ){
        $new= unserialize(file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_rec'));
        if( isset($new) and is_array($new) and count($new) ){
            shuffle($new);
            foreach( $new as $n ){
                if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) ){
                    $anids[]='<div class="views-row">'.file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n).'</div>';
                }else{
                    if( !$connect ){
                        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                        $connect=1;
                    }
                    if( function_exists('pdxgetmyitem') ){
                        $anids[]='<div class="views-row">'.pdxgetmyitem($n).'</div>';
                    }
                }
                if( count($anids)>=4 ){
                    break;
                }
            }
        }
    }
    if( isset($anids) and is_array($anids) and count($anids) ){
        $out='';
        $out.='<div class="postblockover"><div class="block-title"><div class="title">Рекомендуем <a class="sm" href="http://'.$_SERVER['HTTP_HOST'].'/item/rec">показать еще</a></div></div><div class="content"><div class="view view-rec-new view-id-rec_new view-display-id-item"><div class="view-content">';
        $out.=implode('', $anids);
        $out.='</div></div></div></div>';
        $out.='<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';

        $fp = fopen('html/rec.htm', 'w'); fwrite($fp, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru" dir="ltr"><head><title>Аренда вещей без проблем</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body id="start">'.$out.'</body></html>'); fclose($fp);
    }

    //Новые объявления
    if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/new') ){
        unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/new');

        if( !file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_new') or !filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_new') ){
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
            if( function_exists('setlastnew') ){
                setlastnew();
            }
            $connect=1;
        }

        $anids=array();
        if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_new') and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_new') ){
                $new= unserialize(file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.PDX_CITY_ID.'_items_new'));
                if( isset($new) and is_array($new) and count($new) ){
                    foreach( $new as $n ){
                        if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) ){
                            $anids[]='<div class="views-row">'.file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n).'</div>';
                        }else{
                            if( !$connect ){
                                require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                                $connect=1;
                            }
                            if( function_exists('pdxgetmyitem') ){
                                $anids[]='<div class="views-row">'.pdxgetmyitem($n).'</div>';
                            }
                        }
                    }
                }
        }


                    if( isset($anids) and is_array($anids) and count($anids) ){
                        $out='';
                        $out.='<div class="postblockover"><div class="block-title"><div class="title">Новые предложения</div></div><div class="content"><div class="view view-rec-new view-id-rec_new view-display-id-item"><div class="view-content">';
                        $out.=implode('', $anids);
                        $out.='</div></div></div></div>';
                        $out.='<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';

                        $fp = fopen('html/new.htm', 'w'); fwrite($fp, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru" dir="ltr"><head><title>Аренда вещей без проблем</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body id="start">'.$out.'</body></html>'); fclose($fp);

                    }

    }
    
    
    //постинг в твиттер
    if( defined('PDX_TWITTER1') and strlen(PDX_TWITTER1) and defined('PDX_TWITTER2') and strlen(PDX_TWITTER2) and defined('PDX_TWITTER3') and strlen(PDX_TWITTER3) and defined('PDX_TWITTER4') and strlen(PDX_TWITTER4) and is_dir('pdxcache/social/twitter') ){
        $files=scandir('pdxcache/social/twitter');
        if( isset($files) and is_array($files) and count($files) ){
            require_once (DRUPAL_ROOT . '/sites/all/libraries/codebird/src/codebird.php');
            \Codebird\Codebird::setConsumerKey(PDX_TWITTER1, PDX_TWITTER2);
            $cb = \Codebird\Codebird::getInstance();
            $cb->setToken(PDX_TWITTER3, PDX_TWITTER4);
 
            $counter=3;
            foreach( $files as $file ){
                if( strpos($file, '|||')===false ){ continue; }
                
                $message=file_get_contents('pdxcache/social/twitter/'.$file);
                if( isset($message) and strlen($message) ){
                    $params = array(
                      'status' => $message
                    );
                    
                    $tmpfile=explode('|||', $file);
                    if( is_array($tmpfile) and is_numeric($tmpfile[0]) ){
                        if( isset($tmpfile[2]) and strlen($tmpfile[2]) ){
                            $imget = file_get_contents(urldecode($tmpfile[2]));
                            if($imget){
                                $fp = fopen(DRUPAL_ROOT . '/curs/'.$tmpfile[0].'.jpg', "w");
                                if($fp){
                                    fwrite($fp, $imget);
                                    fclose($fp);
                                }
                            }
                            $media_files=$media_ids=array();
                            if( file_exists(DRUPAL_ROOT . '/curs/'.$tmpfile[0].'.jpg') ){
                                $media_files[]=DRUPAL_ROOT . '/curs/'.$tmpfile[0].'.jpg';
                                foreach ($media_files as $fl) {
                                    $reply = $cb->media_upload(array(
                                        'media' => $fl
                                    ));
                                    $media_ids[] = $reply->media_id_string;
                                }
                                if( isset($media_ids) and is_array($media_ids) and count($media_ids) ){ 
                                    $params['media_ids']=implode(',', $media_ids);
                                }
                            }
                        }
                    }
                    
                    $reply = $cb->statuses_update($params);
                    if( file_exists(DRUPAL_ROOT . '/curs/'.$tmpfile[0].'.jpg') ){
                        unlink(DRUPAL_ROOT . '/curs/'.$tmpfile[0].'.jpg');
                    }
                }
                unlink('pdxcache/social/twitter/'.$file);
                
                if($counter--<1){
                    break;
                }
            }
        }
    }
    
    
    //Вставлено, удалено, обновлено объявление
    if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/items') ){
        unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/items');
        if( !$connect ){
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
            $connect=1;
        }
        if( function_exists('setlastnew') ){
            setlastnew();
        }
        if( function_exists('setrecall') ){
            setrecall();
        }
    }

    //Добавлен/удален пользователь
    if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/user') ){
        unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/user');
        if( !$connect ){
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
            $connect=1;
        }

        if( defined('PDX_CITY_ID') and is_numeric(PDX_CITY_ID) ){
            $outscr='';
            $num=db_query('select COUNT(u.uid) from {users} as u left join {field_data_field_delete} as fd on (u.uid=fd.entity_id and fd.entity_type=\'user\') where u.status=1 and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 )');
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(u.uid)']) and $num['COUNT(u.uid)']>1 ){
                $num=$num['COUNT(u.uid)'];
            }else{
                $num=0;
            }
//            $outscr.=' jQuery(document).ready(function($){ ';
            $outscr.= ' jQuery(\'.status_count_users, #muser'.PDX_CITY_ID.'\').html('.$num.'); ';
//            $outscr.=' }); ';
            $fp = fopen('js/b_'.PDX_CITY_ID.'.js', 'w'); fwrite($fp, $outscr); fclose($fp);
            if( !isset($s3) ){
                require_once DRUPAL_ROOT . '/sites/all/libraries/s3-php5-curl/S3.php';
                $s3=new S3('AKIAJZBFGP6M7K354GTQ', 'n3G35KKdug3H4IhYqDS5Y9WtzGgsZfqKneRufqFH');
            }
            $s3->putObject($outscr, $bucketName, 'curs/b_'.PDX_CITY_ID.'.js', S3::ACL_PUBLIC_READ);

        }

    }
    
    //требуется обновление инфы пользователя
    if( is_dir('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/users') ){
        $files=scandir('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/users');
        if( isset($files) and is_array($files) and count($files) ){
            if( !$connect ){
                require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                $connect=1;
            }
            foreach( $files as $file ){
                if( is_numeric($file) and $file>0 ){
                    unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/users/'.$file);
                    if( function_exists('updateuseritem') ){
                        updateuseritem($file);
                    }
                    if( function_exists('updateuseritemimgs') ){
                        updateuseritemimgs($file);
                    }
                    if( file_exists('pdxcache/user/'.$file.'_sm_needto') ){
                        unlink('pdxcache/user/'.$file.'_sm_needto');
                    }
                }
            }
        }
    }
    //Требуется очистка кешей админов
    if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/admin') ){
        unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/dirty/admin');
        if( !$connect ){
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
            $connect=1;
        }

        $uids=db_query('select u.uid from {users} as u inner join {users_roles} as r on u.uid=r.uid where r.rid=3 or r.rid=4');
        while( $uid=$uids->fetchAssoc() ){
            if( file_exists('pdxcache/user/'.$uid['uid'].'_sm_needto') ){
                unlink('pdxcache/user/'.$uid['uid'].'_sm_needto');
            }
        }

    }
    
    //отправка писем о публикации товара
    if( is_dir('pdxcache/'.$_SERVER['HTTP_HOST'].'/mail/1') ){
        $emails=scandir('pdxcache/'.$_SERVER['HTTP_HOST'].'/mail/1');
        if( isset($emails) and is_array($emails) and count($emails) ){
            foreach( $emails as $email ){
                if( strpos($email, '@')===false ){}else{
                    $nids=scandir('pdxcache/'.$_SERVER['HTTP_HOST'].'/mail/1/'.$email);
                    if( isset($nids) and is_array($nids) and count($nids) ){
                        $anids=array();
                        foreach( $nids as $nid ){
                            if( isset( $nid ) and is_numeric($nid) and $nid>0 ){
                                $data=file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/mail/1/'.$email.'/'.$nid);
                                if( isset($data) and strlen($data) ){
                                    $anids[]='<a href="http://'.$_SERVER['HTTP_HOST'].'/item/'.$nid.'">'.$data.'</a>';
                                }
                                unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/mail/1/'.$email.'/'.$nid);
                            }
                        }
                        if( isset($anids) and is_array($anids) and count($anids) ){
                            if( count($anids)>1 ){
                                $text='<div>Поздравляем, ваши объявления:</div><ul>';
                                foreach( $anids as $anid ){
                                    $text.='<li>'.$anid.'</li>';
                                }
                                $text.='</ul><div>Прошли модерацию и опубликованы на сайте '.$_SERVER['HTTP_HOST'].'</div>';
                                pdxmail($email, $text, 'Ваши объявления ('.count($anids).' шт.) опубликованы на сайте '.$_SERVER['HTTP_HOST']);
                            }else{
                                pdxmail($email, 'Поздравляем, ваше объявление "'.$anids[0].'" прошло модерацию и опубликовано на сайте =)', 'Объявление опубликовано на сайте '.$_SERVER['HTTP_HOST'], 'Ваше объявление опубликовано на сайте '.$_SERVER['HTTP_HOST']);
                            }
                        }
                    }
                }
            }
        }
    }

    //проверка онлайн-статуса пользователей
    if( is_dir('curs/online') ){
        $files=scandir('curs/online');
        if( isset($files) and is_array($files) and count($files) ){
            $istime=time();
            foreach( $files as $file ){
                if( strpos($file, '.txt')===false ){}else{
                    if( filectime('curs/online/'.$file)<($istime-777) ){
                        unlink('curs/online/'.$file);
                    }
                }
            }
        }
    }
    //удаление токенов
    if( is_dir('pdxcache/tokens') ){
        $files=scandir('pdxcache/tokens');
        if( isset($files) and is_array($files) and count($files) ){
            $istime=time();
            foreach( $files as $file ){
                if( strlen($file)>4 ){
                    if( filectime('pdxcache/tokens/'.$file)<($istime-77) ){
                        unlink('pdxcache/tokens/'.$file);
                    }
                }
            }
        }
    }

    
    //перенос файлов на s3
    if( file_exists('pdxcache/s3/wait') ){
        $files=scandir('pdxcache/s3/wait');
        if( isset($files) and is_array($files) and count($files) ){
            if( !is_dir('pdxcache/s3/ok') ){
                mkdir('pdxcache/s3/ok');
            }
            if( !isset($s3) ){
                require_once DRUPAL_ROOT . '/sites/all/libraries/s3-php5-curl/S3.php';
                $s3=new S3('AKIAJZBFGP6M7K354GTQ', 'n3G35KKdug3H4IhYqDS5Y9WtzGgsZfqKneRufqFH');
            }
/*
            if( !$connect ){
                require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
                $connect=1;
            }
*/
            $stopme=0;
            foreach( $files as $file ){
                if( strlen($file)>5 ){
                    $file2=urldecode($file);
                    if( !file_exists(DRUPAL_ROOT.'/sites/default/files/styles/'.$file2) ){
                        unlink('pdxcache/s3/wait/'.$file);
                        continue;
                    }

                    if( ++$stopme>21 ){
                        break;
                    }
                    //$s3->putObject($out, $bucketName, 'static/needtome/'.PDX_CITY_ID.'/'.$file2, S3::ACL_PUBLIC_READ);
                    $s3->putObjectFile( DRUPAL_ROOT.'/sites/default/files/styles/'.$file2 , $bucketName, 'static/needtome/'.PDX_CITY_ID.'/'.$file2, S3::ACL_PUBLIC_READ);
                    $fp = fopen('pdxcache/s3/ok/'.$file, 'w');
                    fwrite($fp, 'http://d4zbhil5kxgyr.cloudfront.net/static/needtome/'.PDX_CITY_ID.'/'.$file2);
                    fclose($fp);
                    unlink('pdxcache/s3/wait/'.$file);
                }
            }
        }
    }
    
}


?>