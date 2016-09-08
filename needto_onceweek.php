<?php

$out= @file_get_contents('pdxcache/'.$_SERVER['HTTP_HOST'].'/needto_onceweek');
if( !isset($out) or !is_numeric($out) or (time()-$out)>600000 ){

    define('DRUPAL_ROOT', getcwd());
    
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);

    $fp = fopen('pdxcache/'.$_SERVER['HTTP_HOST'].'/needto_onceweek', 'w');
    fwrite($fp, time());
    fclose($fp);

    $pages=array();
    $city=0;
    if( defined('PDX_CITY_ID') and is_numeric(PDX_CITY_ID) ){
        $city=PDX_CITY_ID;
    }


/* ------------- Заслуги пользователей */
$uids=db_query('select uid, created, mail from {users} where status=1');
while( $uid=$uids->fetchAssoc() ){
    db_query('delete from {field_data_field_merit} where entity_type=\'user\' and entity_id='.$uid['uid']);
    db_query('delete from {field_revision_field_merit} where entity_type=\'user\' and entity_id='.$uid['uid']);

    $ins=array();
    
    //2|Администратор
    $isset=db_query('select uid from {users_roles} where rid=3 and uid='.$uid['uid']);
    $isset=$isset->fetchAssoc();
    if( isset($isset['uid']) and is_numeric($isset['uid']) ){
        $ins[]=2;
    }else{
        //1|Контент-менеджер
        $isset=db_query('select uid from {users_roles} where rid=4 and uid='.$uid['uid']);
        $isset=$isset->fetchAssoc();
        if( isset($isset['uid']) and is_numeric($isset['uid']) ){
            $ins[]=1;
        }else{
            if( strpos( $uid['mail'], '@needto.me' )===false ){}else{
                //10|Ведет наш менеджер    
                $ins[]=10;
            }
        }
    }
    
    if( (time() - $uid['created'])>157680000 ){
        //6|5 лет с нами!!!
        $ins[]=6;
    }elseif( (time() - $uid['created'])>94608000 ){
        //5|3 года с нами!!
        $ins[]=5;
    }elseif( (time() - $uid['created'])>63072000 ){
        //4|2 года с нами!
        $ins[]=4;
    }elseif( (time() - $uid['created'])>31536000 ){
        //3|1 год с нами
        $ins[]=3;
    }
    
    $isset=db_query('select order_id from {uc_orders} where order_status IN (\'completed\', \'payment_received\') and uid='.$uid['uid'].' limit 0,1');
    $isset=$isset->fetchAssoc();
    if( isset($isset['order_id']) and is_numeric($isset['order_id']) ){
        //7|Пополнял баланс
        $ins[]=7;
    }
    
    $isset=db_query('select COUNT(n.nid) from {node} as n inner join {field_data_field_end1} as e1 on (n.nid=e1.entity_id and e1.entity_type=\'node\') inner join {field_data_field_end2} as e2 on (n.nid=e2.entity_id and e2.entity_type=\'node\') inner join {field_data_field_user2} as u2 on (n.nid=u2.entity_id and u2.entity_type=\'node\') inner join {field_data_field_user1} as u1 on (n.nid=u1.entity_id and u1.entity_type=\'node\') where n.type=\'deal\' and n.status=1 and e1.field_end1_value=1 and e2.field_end2_value=1 and (u1.field_user1_uid='.$uid['uid'].' or u2.field_user2_uid='.$uid['uid'].')');
    $isset=$isset->fetchAssoc();
    if( isset($isset['COUNT(n.nid)']) and is_numeric($isset['COUNT(n.nid)']) and $isset['COUNT(n.nid)']>0 ){
        if( $isset['COUNT(n.nid)']>10 ){
            //9|Более 10 сделок
            $ins[]=9;
        }else{
            //8|Заключал сделки
            $ins[]=8;
        }
    }
    
    if( isset($ins) and is_array($ins) and count($ins) ){
        $delta=0;
        foreach( $ins as $in ){
            db_query('insert into {field_data_field_merit} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_merit_value) values (\'user\',\'user\',0,'.$uid['uid'].','.$uid['uid'].',\'und\','.$delta.',:combo)', array(':combo'=>$in));
            $delta++;
        }
    }
    
//    cache_clear_all('field:user:' . $uid['uid'], 'cache_field', true);
    db_query('delete from {cache_field} where cid=:combo', array(':combo'=>'field:user:' . $uid['uid']));
    if( file_exists('sites/default/files/.ht.filecache/cache_field-field user '.$uid['uid']) ){
        unlink('sites/default/files/.ht.filecache/cache_field-field user '.$uid['uid']);
    }
    if( file_exists('pdxcache/user/'.$uid['uid'].'_o') ){
        unlink('pdxcache/user/'.$uid['uid'].'_o');
    }

    $pages[]='_user_'.$uid['uid'];

}


        if( function_exists('pdxclearmycache') ){
            pdxclearmycache($pages);
        }




    
}


?>