<?php

if( isset( $_POST['nid'] ) and is_numeric( $_POST['nid'] ) and $_POST['nid']>0 ){
    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
    
        global $user; if($user->uid){
            $res=db_query('select n.nid from {node} as n inner join {field_data_field_user1} as f on (n.nid=f.entity_id and f.entity_type=\'node\') where n.status=1 and n.type=\'deal\' and n.nid='.$_POST['nid'].' and f.field_user1_uid='.$user->uid);
            $res=$res->fetchAssoc();
            if( isset($res['nid']) and is_numeric($res['nid']) ){
    
                $hit=db_query('select field_delete_value from {field_data_field_delete} where bundle=\'deal\' and field_delete_value=1 and entity_id='.$_POST['nid']);
                $hit=$hit->fetchAssoc();
                if(!isset($hit['field_delete_value'])){

                    $hit=db_query('select field_agree1_value from {field_data_field_agree1} where bundle=\'deal\' and entity_id='.$_POST['nid']);
                    $hit=$hit->fetchAssoc();
                    if(!isset($hit['field_agree1_value'])){
                        db_query('insert into {field_data_field_agree1} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_agree1_value) values (\'node\',\'deal\',0,'.$_POST['nid'].','.$_POST['nid'].',\'und\',0,1)');
                    }else{
                        db_query('update {field_data_field_agree1} set field_agree1_value=1 where bundle=\'deal\' and entity_id='.$_POST['nid']);
                    }
                    
                    cache_clear_all('field:node:' . $_POST['nid'], 'cache_field', true);
    
                    $ismail=db_query('select u.mail, u.uid, n2.nid, n2.title from {node} as n inner join {field_data_field_user2} as f on (n.nid=f.entity_id and f.entity_type=\'node\') inner join {field_data_field_item} as i on (n.nid=i.entity_id and i.entity_type=\'node\') inner join {node} as n2 on (n2.nid=i.field_item_nid and i.entity_type=\'node\') inner join {users} as u on f.field_user2_uid=u.uid where n.status=1 and n.type=\'deal\' and n.nid='.$_POST['nid']);
                    $ismail=$ismail->fetchAssoc();
                    if( isset( $ismail['mail'] ) and strlen($ismail['mail']) and valid_email_address($ismail['mail']) and function_exists('pdxmail') ){
    
                        $msg='<p>Арендодатель товара "<a href="'.url('node/'.$ismail['nid'], array('absolute'=>true)).'">'.$ismail['title'].'</a>" согласился на сделку. В ближайшее время он свяжется с Вами (если еще не связался =) ).</p>';
                        $msg.='<p><a href="'.$GLOBALS['base_url'].'/node/'.$res['nid'].'">Подробнее...</a></p>';
                        
                        pdxmail($ismail['mail'], $msg,'Согласие на сделку на '.$_SERVER['HTTP_HOST']);
    
                        if( file_exists('pdxcache/user/'.$user->uid.'_sm_needto') ){
                            unlink('pdxcache/user/'.$user->uid.'_sm_needto');
                        }
                        if( file_exists('pdxcache/user/'.$ismail['uid'].'_sm_needto') ){
                            unlink('pdxcache/user/'.$ismail['uid'].'_sm_needto');
                        }
                        
                    }
                    
                    echo '<strong>Вы согласились на сделку</strong> =). Не забудьте связаться с арендатором по указанным контактным данным, чтобы обговорить детали сделки.';

                }else{
                    echo 'Данная сделка уже отменена =(';
                }
    
            }else{
                echo 'Вы не арендодатель в сделке =(';
            }
        }

}

?>