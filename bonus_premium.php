<?php

if( isset($_POST['nid']) and is_numeric($_POST['nid']) ){
    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    global $user; if( $user->uid ){
        if( defined('PDX_PRICE_PREMIUM') and is_numeric(PDX_PRICE_PREMIUM) ){
            $iscost=PDX_PRICE_PREMIUM;
        
        
            $isset=db_query('select n.title, f.field_premium_value from {node} as n left join {field_data_field_premium} as f on ( n.nid=f.entity_id and f.entity_type=\'node\' ) where n.nid='.$_POST['nid'].' and n.uid='.$user->uid);
            $isset=$isset->fetchAssoc();
            if( isset($isset['title']) and strlen($isset['title']) ){
                
                    $points=0;
                    $num=db_query('select points from {userpoints_total} where points>0 and uid='.$user->uid);
                    $num=$num->fetchAssoc();
                    if( isset($num['points']) and is_numeric($num['points']) ){
                        $points=$num['points'];
                    }   
                    if( $points>$iscost ){

                        $start=time()+777;
                        if( isset( $isset['field_premium_value'] ) and is_numeric( $isset['field_premium_value'] ) and $isset['field_premium_value']>$start ){
                            $start=$isset['field_premium_value']+777;
                        }
                        $start+=1209600;

    
                        $params = array(
                            'uid' => $user->uid,
                            'points' => -$iscost,
                            'operation' => 'pdx_del_premium',
                        );
                        userpoints_userpointsapi($params);
                        
                        if( isset( $isset['field_premium_value'] ) ){
                            db_query('update {field_data_field_premium} set field_premium_value='.$start.' where bundle=\'item\' and entity_id='.$_POST['nid']);
                        }else{
                            db_query('insert into {field_data_field_premium} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_premium_value) values (\'node\',\'item\',0,'.$_POST['nid'].','.$_POST['nid'].',\'und\',0,'.$start.')');
                        }
                        db_query('update {node} set sticky = 1 where nid='.$_POST['nid']);
                        db_query('update {node_revision} set sticky = 1 where nid='.$_POST['nid']);
    
                        $pages=array();
//                        $pages[]='_';
    
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
                        
                        
                        if( function_exists('setrecall') ){
                            setrecall();
                        }
    
        if( function_exists('pdxclearmycache') ){
            pdxclearmycache($pages);
        }

                        cache_clear_all('field:node:' . $_POST['nid'], 'cache_field', true);
                        
                        echo 'Premium до '.date('d.m.Y', $start).' . Продлить ещё?';  
    
                    }else{
                        echo '<script type="text/javascript"> ';
                        echo 'jQuery(\'#othermsg .cnt\').html(\'На Вашем счету недостаточно средств. Требуется еще '.($iscost-$points).' "нид". <span class="balancego" onclick=" jQuery(\'+"\'#countbalance\'"+\').val(30); jQuery(\'+"\'#addbalance\'"+\').show(); jQuery(\'+"\'html, body\'"+\').animate({ scrollTop: jQuery(\'+"\'#addbalance\'"+\').offset().top }, 333); ">Пополнить баланс</span>\');';
                        echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
                        echo '</script>';
                        echo 'Сделать Premium!';
                    }
            }else{
                echo '<script type="text/javascript"> ';
                echo 'jQuery(\'#othermsg .cnt\').html(\'Вы не являетесь автором!\');';
                echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
                echo '</script>';
                echo 'Сделать Premium!';
            }
        }else{
            echo '<script type="text/javascript"> ';
            echo 'jQuery(\'#othermsg .cnt\').html(\'Стоимость услуги не найдена!\');';
            echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
            echo '</script>';
            echo 'Сделать Premium!';
        }
        
    }else{
        echo '<script type="text/javascript"> ';
        echo 'jQuery(\'#othermsg .cnt\').html(\'Возникли проблемы с определением города!\');';
        echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
        echo '</script>';
        echo 'Сделать Premium!';
    }
    
}


?>