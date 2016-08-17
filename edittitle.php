<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_REQUEST['nid']) and is_numeric($_REQUEST['nid']) and isset($_REQUEST['a']) and strlen($_REQUEST['a']) and isset($_REQUEST['title']) and strlen($_REQUEST['title']) ){

    global $user; if(isset($user->roles[3])){
        switch($_REQUEST['a']){
        case 'taxonomy_catalog': //title
            if(is_numeric($_REQUEST['title'])){
                $type=db_query('select type from {node} where nid='.$_REQUEST['nid']);
                $type=$type->fetchAssoc();
                if( isset($type['type']) and strlen($type['type']) ){
            $hit=db_query('select taxonomy_catalog_tid from {field_data_taxonomy_catalog} where bundle=\''.$type['type'].'\' and entity_id='.$_REQUEST['nid']);
            $hit=$hit->fetchAssoc();
            if(!isset($hit['taxonomy_catalog_tid'])){
                db_query('insert into {field_data_taxonomy_catalog} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, taxonomy_catalog_tid) values (\'node\',\''.$type['type'].'\',0,'.$_REQUEST['nid'].','.$_REQUEST['nid'].',\'und\',0,'.$_REQUEST['title'].')');
                db_query('insert into {field_revision_taxonomy_catalog} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, taxonomy_catalog_tid) values (\'node\',\''.$type['type'].'\',0,'.$_REQUEST['nid'].','.$_REQUEST['nid'].',\'und\',0,'.$_REQUEST['title'].')');
            }else{
                db_query('update {field_data_taxonomy_catalog} set taxonomy_catalog_tid='.$_REQUEST['title'].' where bundle=\''.$type['type'].'\' and entity_id='.$_REQUEST['nid']);
                db_query('update {field_revision_taxonomy_catalog} set taxonomy_catalog_tid='.$_REQUEST['title'].' where bundle=\''.$type['type'].'\' and entity_id='.$_REQUEST['nid']);
            }
                }
            }
            break;
        case 'created': //title
            if(is_numeric($_REQUEST['title'])){
                db_query('update {node} set created='.$_REQUEST['title'].' where nid='.$_REQUEST['nid']);
            }
            break;
        case 'cmtcreated': //title
            if(strlen($_REQUEST['title'])){
                $date = date_create_from_format('Y.m.d', $_REQUEST['title']);
                $date = date_format($date, 'U');
                if( isset($date) and is_numeric($date) and $date>0 ){
                    db_query('update {comment} set created='.$date.' where cid='.$_REQUEST['nid']);
                }
            }
            break;
        case 'body': //title
            if(strlen($_REQUEST['title'])){
                $type=db_query('select type from {node} where nid='.$_REQUEST['nid']);
                $type=$type->fetchAssoc();
                if( isset($type['type']) and strlen($type['type']) ){
            $hit=db_query('select body_value from {field_data_body} where bundle=\''.$type['type'].'\' and entity_id='.$_REQUEST['nid']);
            $hit=$hit->fetchAssoc();
            if(!isset($hit['body_value'])){
                db_query('insert into {field_data_body} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, body_value, body_summary, body_format) values (\'node\',\''.$type['type'].'\',0,'.$_REQUEST['nid'].','.$_REQUEST['nid'].',\'und\',0,:combo, \'\', NULL)', array(':combo' => $_REQUEST['title'] ));
                db_query('insert into {field_revision_body} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, body_value, body_summary, body_format) values (\'node\',\''.$type['type'].'\',0,'.$_REQUEST['nid'].','.$_REQUEST['nid'].',\'und\',0,:combo, \'\', NULL)', array(':combo' => $_REQUEST['title'] ));
            }else{
                db_query('update {field_data_body} set body_value=:combo where bundle=\''.$type['type'].'\' and entity_id='.$_REQUEST['nid'], array(':combo' => $_REQUEST['title'] ));
                db_query('update {field_revision_body} set body_value=:combo where bundle=\''.$type['type'].'\' and entity_id='.$_REQUEST['nid'], array(':combo' => $_REQUEST['title'] ));
            }
                }
            }
            break;
        case 'cmtbody': //title
            if(strlen($_REQUEST['title'])){
            $hit=db_query('select comment_body_value from {field_data_comment_body} where entity_id='.$_REQUEST['nid']);
            $hit=$hit->fetchAssoc();
            if(!isset($hit['comment_body_value'])){
            }else{
                db_query('update {field_data_comment_body} set comment_body_value=:combo where entity_id='.$_REQUEST['nid'], array(':combo' => $_REQUEST['title'] ));
                db_query('update {field_revision_comment_body} set comment_body_value=:combo where entity_id='.$_REQUEST['nid'], array(':combo' => $_REQUEST['title'] ));
            }
            }
            break;
        case 'title': //title
            if(strlen($_REQUEST['title'])){
                db_query('update {node} set title=\''.$_REQUEST['title'].'\' where nid='.$_REQUEST['nid']);
                db_query('update {node_revision} set title=\''.$_REQUEST['title'].'\' where nid='.$_REQUEST['nid']);
            }
            break;
        case 'sell_price': //price
            $hit=db_query('select sell_price from {uc_products} where nid='.$_REQUEST['nid']);
            $hit=$hit->fetchAssoc();
            if( !isset($hit['sell_price']) ){}else{
                db_query('update {uc_products} set sell_price='.str_replace("'","\'",$_REQUEST['title']).' where nid='.$_REQUEST['nid']);
            }
            break;
        case 'model': //price
            if( isset($_GET['aid']) and is_numeric($_GET['aid']) and isset($_GET['oid']) and is_numeric($_GET['oid']) ){
                $hit=db_query('select nid, model from {uc_product_adjustments} where nid='.$_GET['nid'].' and combination=:combo', array(':combo' => serialize(array($_GET['aid']=>$_GET['oid']))) );
                $hit=$hit->fetchAssoc();
                if( !isset($hit['nid']) ){

                    db_query('delete from {uc_product_stock} where sku=\''.str_replace("'","\'",$_GET['title']).'\'');
                    db_query('insert into {uc_product_stock} (sku, nid, active, stock, threshold) values (\''.str_replace("'","\'",$_GET['title']).'\', '.$_GET['nid'].', 1, 7777, 3)');

                    db_query('insert into {uc_product_adjustments} (nid, combination, model) values ('.$_GET['nid'].', :combo, \''.str_replace("'","\'",$_GET['title']).'\')', array(':combo' => serialize(array($_GET['aid']=>$_GET['oid']))) );
                }else{

                    db_query('delete from {uc_product_stock} where sku=\''.$hit['model'].'\'');
                    db_query('delete from {uc_product_stock} where sku=\''.str_replace("'","\'",$_GET['title']).'\'');
                    db_query('insert into {uc_product_stock} (sku, nid, active, stock, threshold) values (\''.str_replace("'","\'",$_GET['title']).'\', '.$_GET['nid'].', 1, 7777, 3)');

                    db_query('update {uc_product_adjustments} set model=\''.str_replace("'","\'",$_GET['title']).'\' where nid='.$_GET['nid'].' and combination=:combo', array(':combo' => serialize(array($_GET['aid']=>$_GET['oid']))) );


                }
            }else{
                $hit=db_query('select model from {uc_products} where nid='.$_GET['nid']);
                $hit=$hit->fetchAssoc();
                if( !isset($hit['model']) ){}else{
                    db_query('delete from {uc_product_stock} where sku=\''.$hit['model'].'\'');
                    db_query('delete from {uc_product_stock} where sku=\''.str_replace("'","\'",$_GET['title']).'\'');
                    db_query('update {uc_products} set model='.str_replace("'","\'",$_GET['title']).' where nid='.$_GET['nid']);
                    db_query('insert into {uc_product_stock} (sku, nid, active, stock, threshold) values (\''.str_replace("'","\'",$_GET['title']).'\', '.$_GET['nid'].', 1, 7777, 3)');
                    
                }
            }
            break;
        case 'cost': //cost
            $hit=db_query('select cost from {uc_products} where nid='.$_REQUEST['nid']);
            $hit=$hit->fetchAssoc();
            if( !isset($hit['cost']) ){}else{
                db_query('update {uc_products} set cost='.str_replace("'","\'",$_REQUEST['title']).' where nid='.$_REQUEST['nid']);
            }
            break;
        
        }
        echo 'ok';
//        cache_clear_all('*', 'cache', true);
//        cache_clear_all('*', 'cache_entity_node', true);
//        cache_clear_all('*', 'cache_field', true);

        cache_clear_all('field:node:' . $_REQUEST['nid'], 'cache_field', true);
    }
}

?>