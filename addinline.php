<?php

if( isset( $_REQUEST['id'] ) and is_numeric($_REQUEST['id']) ){
    define('DRUPAL_ROOT', getcwd());
    
    switch($_REQUEST['id']){
    case 1:
        break;
    case 2:
        break;
    case 3: // рекомендуемые объявления
        if( isset( $_REQUEST['city'] ) and is_numeric($_REQUEST['city']) ){
            $connect=0;
            if( !file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec') or !filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec') ){
                require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                if( function_exists('setrecall') ){
                    setrecall();
                }
                $connect=1;
            }
            
            $anids=array();
            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec') and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec') ){
                $new= unserialize(file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec'));
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
                echo '<div class="postblockover"><div class="block-title"><div class="title">Рекомендуем <a class="sm" href="http://'.$_SERVER['HTTP_HOST'].'/item/rec">показать еще</a></div></div><div class="content"><div class="view view-rec-new view-id-rec_new view-display-id-item"><div class="view-content">';
                echo implode('', $anids);
                echo '</div></div></div></div>';
                echo '<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';
            }
        }
        break;
    case 4: //новые объявления
        if( isset( $_REQUEST['city'] ) and is_numeric($_REQUEST['city']) ){
            $connect=0;
            if( !file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_new') or !filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_new') ){
                require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                if( function_exists('setlastnew') ){
                    setlastnew();
                }
                $connect=1;
            }
            
            $anids=array();
            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_new') and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_new') ){
                $new= unserialize(file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_new'));
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
                echo '<div class="postblockover"><div class="block-title"><div class="title">Новые предложения</div></div><div class="content"><div class="view view-rec-new view-id-rec_new view-display-id-item"><div class="view-content">';
                echo implode('', $anids);
                echo '</div></div></div></div>';
                echo '<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';
            }
        }
        break;
    case 5: //просмотренные объявления
        if( isset( $_REQUEST['city'] ) and is_numeric($_REQUEST['city']) ){
            $connect=0;
            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
            
            
            $new= array();
            if( isset( $_COOKIE['needRelated'] ) and strlen( $_COOKIE['needRelated'] ) ){
                $new=explode('_', $_COOKIE['needRelated']);
            }
            $anids=array();
                if( isset($new) and is_array($new) and count($new) ){
                    foreach( $new as $n ){
                        if( isset($n) and is_numeric($n) and $n>0 ){}else{
                            continue;
                        }
                        if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) ){
                            $anids[]='<div class="views-row">'.file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n).'</div>';
                        }else{
                            if( !$connect ){
                                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                                $connect=1;
                            }
                            if( function_exists('pdxgetmyitem') ){
                                $anids[]='<div class="views-row">'.pdxgetmyitem($n).'</div>';
                            }
                        }
                    }
                }
            echo '<div class="postblockover"><div class="content"><div class="view view-rec-new view-id-rec_new view-display-id-item"><div class="view-content">';
            if( isset($anids) and is_array($anids) and count($anids) ){
                echo '<div style="padding-bottom: 21px;"><a href="javascript: void(0);" onclick=" clearrelated(this, 1); ">Удалить историю просмотренных объявлений</a></div>';
//                $anids=array_reverse($anids);
                echo implode('', $anids);
                echo '<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';
            }else{
                echo 'Сведений о просмотренных вами предложениях не сохранилось =(';
            }
            echo '</div></div></div></div>';
        }
        break;
    case 9: //рекомендуемые объявления в разделах каталога
        if( isset( $_REQUEST['city'] ) and is_numeric($_REQUEST['city']) and isset($_REQUEST['data']) and strlen($_REQUEST['data']) ){
            $_REQUEST['data']=str_replace('cat_', '', $_REQUEST['data']);
            $_REQUEST['data']=explode('_', $_REQUEST['data']);
            if( isset($_REQUEST['data']) and is_array($_REQUEST['data']) and count($_REQUEST['data'])==4 and is_numeric($_REQUEST['data'][0]) and $_REQUEST['data'][0]>0 and is_numeric($_REQUEST['data'][1]) and is_numeric($_REQUEST['data'][2]) and is_numeric($_REQUEST['data'][3]) ){
                $anids=array();
                require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                
                $sqlinner='';
                $sqlwhere='';
                if( $_REQUEST['data'][2]>0 ){
                    $sqlinner.=' inner join {field_data_field_tags} as ft on (n.nid=ft.entity_id and ft.entity_type=\'node\')';
                    $sqlwhere.=' and ft.field_tags_tid='.$_REQUEST['data'][2];
                }elseif( $_REQUEST['data'][1]>0 ){
                    $sqlinner.=' inner join {field_data_field_subpart} as fs on (n.nid=fs.entity_id and fs.entity_type=\'node\')';
                    $sqlwhere.=' and fs.field_subpart_tid='.$_REQUEST['data'][1];
                }
                if( $_REQUEST['data'][3]>0 ){
                    $sqlinner.=' inner join {field_data_field_celebration} as fc on (n.nid=fc.entity_id and fc.entity_type=\'node\')';
                    $sqlwhere.=' and fc.field_celebration_tid='.$_REQUEST['data'][3];
                }
                $sql='select n.nid from {node} as n inner join {field_data_taxonomy_catalog} as t on (n.nid=t.entity_id and t.entity_type=\'node\') left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\')'.$sqlinner.' where t.taxonomy_catalog_tid='.$_REQUEST['data'][0].' and n.status=1 and n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) '.$sqlwhere.' order by RAND() limit 0, 4';
                $ares=db_query($sql);
                while( $are=$ares->fetchAssoc() ){
                    if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$are['nid']) and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$are['nid']) ){
                        $anids[]='<div class="views-row">'.file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$are['nid']).'</div>';
                    }else{
                        if( function_exists('pdxgetmyitem') ){
                            $anids[]='<div class="views-row">'.pdxgetmyitem($are['nid']).'</div>';
                        }
                    }
                }
                
                if( isset($anids) and is_array($anids) and count($anids) ){
                    echo '<div class="block_rec_items_catis">';
                    echo implode('', $anids);
                    echo '</div>';
                    echo '<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';
                }
                
            }
        }
        break;
    case 6: //рекомендуемые объявления
        if( isset( $_REQUEST['city'] ) and is_numeric($_REQUEST['city']) ){
            $connect=0;
            if( !file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec') or !filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec') ){
                require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                if( function_exists('setrecall') ){
                    setrecall();
                }
                $connect=1;
            }
            $anids=array();
            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec') and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec') ){
                $new= unserialize(file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/vars/'.$_REQUEST['city'].'_items_rec'));
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
                        if( count($anids)>=50 ){
                            break;
                        }
                    }
                }
            }
            
            echo '<div class="postblockover"><div class="content"><div class="view view-rec-new view-id-rec_new view-display-id-item"><div class="view-content">';
            if( isset($anids) and is_array($anids) and count($anids) ){
                shuffle($anids);
                echo implode('', $anids);
                echo '<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';
            }else{
                echo 'Мы пока не можем ничего Вам порекомендовать =(';
            }
            echo '</div></div></div></div>';
        }
        break;            
    case 7: //Избранное
        if( isset( $_REQUEST['city'] ) and is_numeric($_REQUEST['city']) ){
            $connect=0;

            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
            
            
            $new= array();
            if( is_dir('pdxcache/udata/fav/node/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST']) ){
                $aflag=scandir('pdxcache/udata/fav/node/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST']);
                if( isset($aflag) and is_array($aflag) and count($aflag) ){
                    foreach( $aflag as $file ){
                        if( is_numeric($file) ){
                            $new[]=$file;
                        }
                    }
                    $flag_node = count($aflag)-2;
                    if( $flag_node<0 ){
                        $flag_node=0;
                    }
                }
            }
            
            $anids=array();
                if( isset($new) and is_array($new) and count($new) ){
                    foreach( $new as $n ){
                        if( isset($n) and is_numeric($n) and $n>0 ){}else{
                            continue;
                        }
                        if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) ){
                            $anids[]='<div class="views-row">'.file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n).'</div>';
                        }else{
                            if( !$connect ){
                                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                                $connect=1;
                            }
                            if( function_exists('pdxgetmyitem') ){
                                $anids[]='<div class="views-row">'.pdxgetmyitem($n).'</div>';
                            }
                        }
                    }
                }
            
            echo '<div class="postblockover"><div class="content"><div class="view view-rec-new view-id-rec_new view-display-id-item"><div class="view-content">';
            if( isset($anids) and is_array($anids) and count($anids) ){
                echo '<div style="padding-bottom: 21px;"><a href="javascript: void(0);" onclick=" clearrelated(this, 2); ">Очистить Избранное</a></div>';
                echo implode('', $anids);
                echo '<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';
            }else{
                echo '<div>В Избранном нет ни одного объявления. Чтобы добавить объявление в Избранное, кликните левой кнопкой мыши по звездочке на карточке объявления, либо на его странице.</div>';
            }
            echo '</div></div></div></div>';
        }
        break;  
    case 8: //Подписан на пользователей
        if( isset( $_REQUEST['city'] ) and is_numeric($_REQUEST['city']) ){
            $connect=0;

            require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
            
            
            $new= array();
            if( is_dir('pdxcache/udata/fav/user/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST']) ){
                $aflag=scandir('pdxcache/udata/fav/user/'.$_SESSION['pdxuseruid'].'/'.$_SERVER['HTTP_HOST']);
                if( isset($aflag) and is_array($aflag) and count($aflag) ){
                    foreach( $aflag as $file ){
                        if( is_numeric($file) ){
                            $new[]=$file;
                        }
                    }
                    $flag_node = count($aflag)-2;
                    if( $flag_node<0 ){
                        $flag_node=0;
                    }
                }
            }
            
            $anids=array();
                if( isset($new) and is_array($new) and count($new) ){
                    foreach( $new as $n ){
                        if( isset($n) and is_numeric($n) and $n>0 ){}else{
                            continue;
                        }
                        if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$n.'_item') and filesize('pdxcache/'.$_SERVER['HTTP_HOST'].'/item/'.$n) ){
                            $anids[]='<div class="views-row">'.file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$n.'_item').'</div>';
                        }else{
                            if( !$connect ){
                                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                                $connect=1;
                            }
                            if( function_exists('pdxgetmyuser') ){
                                $anids[]='<div class="views-row">'.pdxgetmyuser($n).'</div>';
                            }
                        }
                    }
                }
            
            echo '<div class="postblockover"><div class="content"><div class="view view-rec-new view-id-rec_new view-display-id-item"><div class="view-content">';
            if( isset($anids) and is_array($anids) and count($anids) ){
                echo '<div style="padding-bottom: 21px;"><a href="javascript: void(0);" onclick=" clearrelated(this, 3); ">Очистить Избранное</a></div>';
                echo implode('', $anids);
                echo '<script type="text/javascript"> preparecurrency(); addadmuser(); updateswiperitem(); </script>';
            }else{
                echo '<div>В Избранном нет ни одного участника. Чтобы добавить участника в Избранное, кликните левой кнопкой мыши по звездочке на карточке участника, либо на странице его профиля.</div>';
            }
            echo '</div></div></div></div>';
        }
        break;  
    }


}



?>