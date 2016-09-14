<?php

if( isset($_POST['nid']) and is_numeric($_POST['nid']) ){
    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    global $user; if( $user->uid ){
        if( defined('PDX_PRICE_TOP') and is_numeric(PDX_PRICE_TOP) ){
            $iscost=PDX_PRICE_TOP;
        
        
            $isset=db_query('select title, created from {node} where nid='.$_POST['nid'].' and uid='.$user->uid);
            $isset=$isset->fetchAssoc();
            if( isset($isset['title']) and strlen($isset['title']) ){
                if( time()-$isset['created'] > 10800 ){
                    $points=0;
                    $num=db_query('select points from {userpoints_total} where points>0 and uid='.$user->uid);
                    $num=$num->fetchAssoc();
                    if( isset($num['points']) and is_numeric($num['points']) ){
                        $points=$num['points'];
                    }   
                    if( $points>$iscost ){
    
                        $params = array(
                            'uid' => $user->uid,
                            'points' => -$iscost,
                            'operation' => 'pdx_del_top',
                        );
                        userpoints_userpointsapi($params);
                        db_query('update {node} set created = :combo where nid='.$_POST['nid'], array( ':combo'=> time()+777 ));
    
                        $pages=array();
                        $pages[]='_';
    
                        $path=path_load('node/'.$_POST['nid']);
                        if( isset($path['alias']) and strlen($path['alias'])){
                            $url='/'.$path['alias'];
                        }else{
                            $url='/node/'.$_POST['nid'];
                        }
                        $url=str_replace('//', '/', $url);
                        $pages[]=str_replace('/','_',$url);
    
    
                        $city=0;
                        if( defined('PDX_CITY_ID') and is_numeric(PDX_CITY_ID) ){
                            $city=PDX_CITY_ID;
                        }
                        
                            $a1=$a8=array();
                            $cat=0;
                            $istaxs=db_query('select d.tid, d.vid from {taxonomy_index} as i inner join {taxonomy_term_data} as d on i.tid=d.tid where i.nid='.$_POST['nid']);
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

                        
        if( function_exists('pdxclearmycache') ){
            pdxclearmycache($pages);
        }
    
                        echo 'готово =)';  
    
                    }else{
                        echo '<script type="text/javascript"> ';
                        echo 'jQuery(\'#othermsg .cnt\').html(\'На Вашем счету недостаточно средств. Требуется еще '.($iscost-$points).' "нид". <span class="balancego" onclick=" jQuery(\'+"\'#countbalance\'"+\').val(10); jQuery(\'+"\'#addbalance\'"+\').show(); jQuery(\'+"\'html, body\'"+\').animate({ scrollTop: jQuery(\'+"\'#addbalance\'"+\').offset().top }, 333); ">Пополнить баланс</span>\');';
                        echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
                        echo '</script>';
                        echo 'Поднять наверх!';
                    }
                }else{
                    echo '<script type="text/javascript"> ';
                    echo 'jQuery(\'#othermsg .cnt\').html(\'Вы недавно создали или только что уже подняли данное предложение. Подождите немножко. Поднимать предложения можно не чаще одного раза в 3 часа.\');';
                    echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
                    echo '</script>';
                    echo 'Поднять наверх!';
                }
            }else{
                echo '<script type="text/javascript"> ';
                echo 'jQuery(\'#othermsg .cnt\').html(\'Вы не являетесь автором!\');';
                echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
                echo '</script>';
                echo 'Поднять наверх!';
            }
            }else{
            echo '<script type="text/javascript"> ';
            echo 'jQuery(\'#othermsg .cnt\').html(\'Стоимость услуги не найдена!\');';
            echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
            echo '</script>';
            echo 'Поднять наверх!';
        }
        
    }else{
        echo '<script type="text/javascript"> ';
        echo 'jQuery(\'#othermsg .cnt\').html(\'Возникли проблемы с определением города!\');';
        echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
        echo '</script>';
        echo 'Поднять наверх!';
    }
    
}


?>