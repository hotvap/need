<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//echo '<pre>'.print_r($_REQUEST, true). '</pre>';
if( isset($_REQUEST['nid1']) and is_numeric($_REQUEST['nid1']) and isset($_REQUEST['nid2']) and is_numeric($_REQUEST['nid2']) and isset($_REQUEST['date1']) and is_numeric($_REQUEST['date1']) and isset($_REQUEST['date2']) and is_numeric($_REQUEST['date2']) and $_REQUEST['date1']!=$_REQUEST['date2'] ){

    global $user; if(isset($user->roles[3])){
        
            $hit=db_query('select field_sortweight_value from {field_data_field_sortweight} where entity_id='.$_REQUEST['nid1']);
            $hit=$hit->fetchAssoc();
            if(!isset($hit['field_sortweight_value'])){
//                db_query('insert into {field_data_field_sortweight} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, taxonomy_catalog_tid) values (\'node\',\''.$type['type'].'\',0,'.$_REQUEST['nid'].','.$_REQUEST['nid'].',\'und\',0,'.$_REQUEST['title'].')');
//                db_query('insert into {field_revision_taxonomy_catalog} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, taxonomy_catalog_tid) values (\'node\',\''.$type['type'].'\',0,'.$_REQUEST['nid'].','.$_REQUEST['nid'].',\'und\',0,'.$_REQUEST['title'].')');
            }else{
                db_query('update {field_data_field_sortweight} set field_sortweight_value='.$_REQUEST['date2'].' where entity_id='.$_REQUEST['nid1']);
                db_query('update {field_revision_field_sortweight} set field_sortweight_value='.$_REQUEST['date2'].' where entity_id='.$_REQUEST['nid1']);
            }
        
            $hit=db_query('select field_sortweight_value from {field_data_field_sortweight} where entity_id='.$_REQUEST['nid2']);
            $hit=$hit->fetchAssoc();
            if(!isset($hit['field_sortweight_value'])){
//                db_query('insert into {field_data_field_sortweight} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, taxonomy_catalog_tid) values (\'node\',\''.$type['type'].'\',0,'.$_REQUEST['nid'].','.$_REQUEST['nid'].',\'und\',0,'.$_REQUEST['title'].')');
//                db_query('insert into {field_revision_taxonomy_catalog} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, taxonomy_catalog_tid) values (\'node\',\''.$type['type'].'\',0,'.$_REQUEST['nid'].','.$_REQUEST['nid'].',\'und\',0,'.$_REQUEST['title'].')');
            }else{
                db_query('update {field_data_field_sortweight} set field_sortweight_value='.$_REQUEST['date1'].' where entity_id='.$_REQUEST['nid2']);
                db_query('update {field_revision_field_sortweight} set field_sortweight_value='.$_REQUEST['date1'].' where entity_id='.$_REQUEST['nid2']);
            }
        

        echo '<script type="text/javascript"> jQuery(\'#isproduct'.$_REQUEST['nid1'].'\').parent().parent().find(\'.isproductcounter\').html(\''.$_REQUEST['date2'].'\'); </script>';
        echo '<script type="text/javascript"> jQuery(\'#isproduct'.$_REQUEST['nid2'].'\').parent().parent().find(\'.isproductcounter\').html(\''.$_REQUEST['date1'].'\'); </script>';

        echo 'ok';
        cache_clear_all('*', 'cache', true);
        cache_clear_all('*', 'cache_entity_node', true);
        cache_clear_all('*', 'cache_field', true);
    }
}

?>