<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_GET['nid']) and is_numeric($_GET['nid']) and isset($_GET['a']) and is_numeric($_GET['a']) ){
    global $user; if( isset($user->roles[3]) or isset($user->roles[4]) ){
        switch($_GET['a']){
        case 0: //public
            db_query('update {node} set status=1 where nid='.$_GET['nid']);
            db_query('update {node_revision} set status=1 where nid='.$_GET['nid']);
            if( function_exists('pdxcreate_dirty') ){
                pdxcreate_dirty('admin');
            }
            break;
        case 2: //sticky
            $hit=db_query('select sticky from {node} where nid='.$_GET['nid']);
            $hit=$hit->fetchAssoc();
            if( isset($hit['sticky']) and $hit['sticky']==0){
                db_query('update {node} set sticky=1 where nid='.$_GET['nid']);
                db_query('update {node_revision} set sticky=1 where nid='.$_GET['nid']);
            }else{
                db_query('update {node} set sticky=0 where nid='.$_GET['nid']);
                db_query('update {node_revision} set sticky=0 where nid='.$_GET['nid']);
            }
            break;
        case 1: //hit
            $hit=db_query('select field_hit_value from {field_data_field_hit} where bundle=\'product\' and entity_id='.$_GET['nid']);
            $hit=$hit->fetchAssoc();
            if(!isset($hit['field_hit_value'])){
                db_query('insert into {field_data_field_hit} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_hit_value) values (\'node\',\'product\',0,'.$_GET['nid'].','.$_GET['nid'].',\'und\',0,1)');
                db_query('insert into {field_revision_field_hit} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_hit_value) values (\'node\',\'product\',0,'.$_GET['nid'].','.$_GET['nid'].',\'und\',0,1)');
            }else{
                if($hit['field_hit_value']==0){
                    db_query('update {field_data_field_hit} set field_hit_value=1 where bundle=\'product\' and entity_id='.$_GET['nid']);
                    db_query('update {field_revision_field_hit} set field_hit_value=1 where bundle=\'product\' and entity_id='.$_GET['nid']);
                }else{
                    db_query('update {field_data_field_hit} set field_hit_value=0 where bundle=\'product\' and entity_id='.$_GET['nid']);
                    db_query('update {field_revision_field_hit} set field_hit_value=0 where bundle=\'product\' and entity_id='.$_GET['nid']);
                }
            }
        
        }
        echo 'ok';
        cache_clear_all('field:node:' . $_GET['nid'], 'cache_field', true);
        $node=node_load($_GET['nid']);
        pdxclearnodecaches($node);
        setlastnew();


    }
}

?>