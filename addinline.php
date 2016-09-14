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