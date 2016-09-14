<?php

if( isset( $_POST['nid'] ) and is_numeric( $_POST['nid'] ) and $_POST['nid']>0 and isset( $_POST['rat'] ) and is_numeric( $_POST['rat'] ) and $_POST['rat']>0 and $_POST['rat']<6 and isset( $_POST['dt'] ) and strlen( $_POST['dt'] ) ){
    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
    
    $_POST['dt']=urldecode($_POST['dt']);
    $_POST['dt']=trim(filter_xss(strip_tags($_POST['dt'])));
    if( strlen($_POST['dt']) ){
    
        global $user; if($user->uid){
            $res=db_query('select n.nid from {node} as n inner join {field_data_field_user2} as f on (n.nid=f.entity_id and f.entity_type=\'node\') where n.status=1 and n.type=\'deal\' and n.nid='.$_POST['nid'].' and f.field_user2_uid='.$user->uid);
            $res=$res->fetchAssoc();
            if( isset($res['nid']) and is_numeric($res['nid']) ){
    
                $hit=db_query('select field_delete_value from {field_data_field_delete} where bundle=\'deal\' and field_delete_value=1 and entity_id='.$_POST['nid']);
                $hit=$hit->fetchAssoc();
                if(!isset($hit['field_delete_value'])){


                    $hit=db_query('select field_reason1_value from {field_data_field_reason1} where bundle=\'deal\' and entity_id='.$_POST['nid']);
                    $hit=$hit->fetchAssoc();
                    if(!isset($hit['field_reason1_value'])){
                        db_query('insert into {field_data_field_reason1} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_reason1_value) values (\'node\',\'deal\',0,'.$_POST['nid'].','.$_POST['nid'].',\'und\',0,:combo)', array(':combo'=>$_POST['dt']));
                    }else{
                        db_query('update {field_data_field_reason1} set field_reason1_value=:combo where bundle=\'deal\' and entity_id='.$_POST['nid'], array(':combo'=>$_POST['dt']));
                    }

                    $hit=db_query('select field_rat1_value from {field_data_field_rat1} where bundle=\'deal\' and entity_id='.$_POST['nid']);
                    $hit=$hit->fetchAssoc();
                    if(!isset($hit['field_rat1_value'])){
                        db_query('insert into {field_data_field_rat1} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_rat1_value) values (\'node\',\'deal\',0,'.$_POST['nid'].','.$_POST['nid'].',\'und\',0,:combo)', array(':combo'=>$_POST['rat']));
                    }else{
                        db_query('update {field_data_field_rat1} set field_rat1_value=:combo where bundle=\'deal\' and entity_id='.$_POST['nid'], array(':combo'=>$_POST['rat']));
                    }

                    $hit=db_query('select field_end2_value from {field_data_field_end2} where bundle=\'deal\' and entity_id='.$_POST['nid']);
                    $hit=$hit->fetchAssoc();
                    if(!isset($hit['field_end2_value'])){
                        db_query('insert into {field_data_field_end2} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_end2_value) values (\'node\',\'deal\',0,'.$_POST['nid'].','.$_POST['nid'].',\'und\',0,:combo)', array(':combo'=>1));
                    }else{
                        db_query('update {field_data_field_end2} set field_end2_value=:combo where bundle=\'deal\' and entity_id='.$_POST['nid'], array(':combo'=>1));
                    }
                                        
                    cache_clear_all('field:node:' . $_POST['nid'], 'cache_field', true);
    
                    $ismail=db_query('select u.mail, u.uid, n2.nid, n2.title from {node} as n inner join {field_data_field_user1} as f on (n.nid=f.entity_id and f.entity_type=\'node\') inner join {field_data_field_item} as i on (n.nid=i.entity_id and i.entity_type=\'node\') inner join {node} as n2 on (n2.nid=i.field_item_nid and i.entity_type=\'node\') inner join {users} as u on f.field_user1_uid=u.uid where n.status=1 and n.type=\'deal\' and n.nid='.$_POST['nid']);
                    $ismail=$ismail->fetchAssoc();
                    if( isset( $ismail['mail'] ) and strlen($ismail['mail']) and valid_email_address($ismail['mail']) and function_exists('pdxmail') ){
    
                        $msg='<p>Арендатор объявил о завершении сделки по товару "<a href="'.url('node/'.$ismail['nid'], array('absolute'=>true)).'">'.$ismail['title'].'</a>". О Вас был оставлен следующий отзыв:</p>';
                        $msg.='<p>'.nl2br($_POST['dt']).'</p>';
                        $msg.='<p>Сотрудничество с Вами оценено в '.$_POST['rat'].' бала(ов)</p>';
                        $msg.='<p><a href="'.$GLOBALS['base_url'].'/node/'.$res['nid'].'">Подробнее...</a></p>';
                        
                        pdxmail($ismail['mail'], $msg,'Завершение сделки на '.$_SERVER['HTTP_HOST']);

                        if( function_exists('pdxupdateuserrat') ){
                            pdxupdateuserrat($ismail['uid'], 'field_rat1');
                            pdxupdateuserrat($user->uid, 'field_rat2', 'field_userrat2', 'user2');
                        }
    
                        if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$user->uid.'_sm_needto') ){
                            unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$user->uid.'_sm_needto');
                        }
                        if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$ismail['uid'].'_sm_needto') ){
                            unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$ismail['uid'].'_sm_needto');
                        }
                        
                    }

                    $hit=db_query('select field_end1_value from {field_data_field_end1} where bundle=\'deal\' and field_end1_value=1 and entity_id='.$_POST['nid']);
                    $hit=$hit->fetchAssoc();
                    if(!isset($hit['field_end1_value'])){
                        echo 'Спасибо, Вы завершили сделку. Ждем завершения сделки от арендодателя.';
                    }else{
                        echo 'Сделка официально завершена. Спасибо за то, что пользуетесь нашим сервисом =)';

                        if( isset($ismail['nid']) and is_numeric($ismail['nid']) ){
                            $pages=array();
                            $path=path_load('node/'.$ismail['nid']);
                            if( isset($path['alias']) and strlen($path['alias'])){
                                $url='/'.$path['alias'];
                            }else{
                                $url='/node/'.$ismail['nid'];
                            }
                            $url=str_replace('//', '/', $url);
                            $pages[]=str_replace('/','_',$url);
                        
        if( function_exists('pdxclearmycache') ){
            pdxclearmycache($pages);
        }
                        

                        }


                    }
                }else{
                    echo 'Данная сделка уже отменена =(';
                }
                
            }else{
                echo 'Вы не арендатор в сделке =(';
            }
        }
    }else{
        echo 'Нет текста =(';
    }
}

?>