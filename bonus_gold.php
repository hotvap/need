<?php


    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    global $user; if( $user->uid ){
        if( defined('PDX_PRICE_GOLD') and is_numeric(PDX_PRICE_GOLD) ){
            $iscost=PDX_PRICE_GOLD;

                    $points=0;
                    $num=db_query('select points from {userpoints_total} where points>0 and uid='.$user->uid);
                    $num=$num->fetchAssoc();
                    if( isset($num['points']) and is_numeric($num['points']) ){
                        $points=$num['points'];
                    }   
                    if( $points>$iscost ){

                        $start=time()+777;
                        $isgold=db_query('select field_gold_value from {field_data_field_gold} where entity_type=\'user\' and entity_id='.$user->uid);
                        $isgold=$isgold->fetchAssoc();
                        if( isset($isgold['field_gold_value']) and is_numeric($isgold['field_gold_value']) and $isgold['field_gold_value']>$start ){
                            $start=$isgold['field_gold_value']+777;
                        }
                        $start+=2678400;

    
                        $params = array(
                            'uid' => $user->uid,
                            'points' => -$iscost,
                            'operation' => 'pdx_del_gold1',
                        );
                        userpoints_userpointsapi($params);
                        
                        if( isset( $isgold['field_gold_value'] ) ){
                            db_query('update {field_data_field_gold} set field_gold_value='.$start.' where bundle=\'user\' and entity_id='.$user->uid);
                        }else{
                            db_query('insert into {field_data_field_gold} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_gold_value) values (\'user\',\'user\',0,'.$user->uid.','.$user->uid.',\'und\',0,'.$start.')');
                        }
                        db_query('update {node} set sticky = 1 where uid='.$user->uid);
                        
                        if( !isset($user->roles[8])){
                            db_query('insert into {users_roles} (uid, rid) values ('.$user->uid.', 8)');
                        }
    
                        $pages=array();
                        $pages[]='_';
    
                        $path=path_load('user/'.$user->uid);
                        if( isset($path['alias']) and strlen($path['alias'])){
                            $url='/'.$path['alias'];
                        }else{
                            $url='/user/'.$user->uid;
                        }
                        $url=str_replace('//', '/', $url);
                        $pages[]=str_replace('/','_',$url);
                        
                        $nids=db_query('select nid, status from {node} where type=\'item\' and uid='.$user->uid);
                        while( $nid=$nids->fetchAssoc() ){

                            db_query('update {node_revision} set sticky = 1 where nid='.$nid['nid']);
                            if( $nid['status']==0 ){
                                $hit=db_query('select field_moderate_value from {field_data_field_moderate} where bundle=\'item\' and entity_id='.$nid['nid']);
                                $hit=$hit->fetchAssoc();
                                if(!isset($hit['field_moderate_value'])){
                                    db_query('insert into {field_data_field_moderate} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_moderate_value) values (\'node\',\'item\',0,'.$nid['nid'].','.$nid['nid'].',\'und\',0,1)');
                                }else{
                                    db_query('update {field_data_field_moderate} set field_moderate_value=1 where bundle=\'item\' and entity_id='.$nid['nid']);
                                }
                                db_query('update {node} set status = 1 where nid='.$nid['nid']);
                                db_query('update {node_revision} set status = 1 where nid='.$nid['nid']);

                            }

                            pdxcd('item', $nid['nid'], 1);

                            $path=path_load('node/'.$nid['nid']);
                            if( isset($path['alias']) and strlen($path['alias'])){
                                $url='/'.$path['alias'];
                            }else{
                                $url='/node/'.$nid['nid'];
                            }
                            $url=str_replace('//', '/', $url);
                            $pages[]=str_replace('/','_',$url);


                            $a1=$a8=array();
                            $cat=0;
                            $istaxs=db_query('select d.tid, d.vid from {taxonomy_index} as i inner join {taxonomy_term_data} as d on i.tid=d.tid where i.nid='.$nid['nid']);
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

                            cache_clear_all('field:node:' . $nid['nid'], 'cache_field', true);
                        }

            if( file_exists('pdxcache/user/'.$user->uid) ){
                unlink('pdxcache/user/'.$user->uid);
            }
            if( file_exists('pdxcache/user/'.$user->uid.'_sm') ){
                unlink('pdxcache/user/'.$user->uid.'_sm');
            }
            if( file_exists('pdxcache/user/'.$user->uid.'_item') ){
                unlink('pdxcache/user/'.$user->uid.'_item');
            }
            if( file_exists('pdxcache/user/'.$user->uid.'_o') ){
                unlink('pdxcache/user/'.$user->uid.'_o');
            }

                        
                        $pages[]='_users/rents';
                        if( function_exists('setrecall') ){
                            setrecall();
                        }
    
        if( function_exists('pdxclearmycache') ){
            pdxclearmycache($pages);
        }

                        cache_clear_all('field:user:' . $user->uid, 'cache_field', true);
                        unset($_SESSION['pdxuserrid']);
                        setrawcookie("pdxuserrid","", time()-1 );
                        
                        echo 'Gold до '.date('d.m.Y', $start).' . Продлить ещё?';  
    
                    }else{
                        echo '<script type="text/javascript"> ';
                        echo 'jQuery(\'#othermsg .cnt\').html(\'На Вашем счету недостаточно средств. Требуется еще '.($iscost-$points).' "нид". <span class="balancego" onclick=" jQuery(\'+"\'#countbalance\'"+\').val(10); jQuery(\'+"\'#addbalance\'"+\').show(); jQuery(\'+"\'html, body\'"+\').animate({ scrollTop: jQuery(\'+"\'#addbalance\'"+\').offset().top }, 333); ">Пополнить баланс</span>\');';
                        echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
                        echo '</script>';
                        echo 'Купить Gold-аккаунт!';
                    }

        }else{
            echo '<script type="text/javascript"> ';
            echo 'jQuery(\'#othermsg .cnt\').html(\'Стоимость услуги не найдена!\');';
            echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
            echo '</script>';
            echo 'Купить Gold-аккаунт!';
        }
        
    }else{
        echo '<script type="text/javascript"> ';
        echo 'jQuery(\'#othermsg .cnt\').html(\'Возникли проблемы с определением города!\');';
        echo 'jQuery(\'#othermsg\').addClass(\'regshow\');';
        echo '</script>';
        echo 'Купить Gold-аккаунт!';
    }
    



?>