<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_POST['nid']) and is_numeric($_POST['nid']) and isset($_POST['type']) and strlen($_POST['type']) and isset($_POST['field']) and strlen($_POST['field']) ){
    global $user;
    $alowed=0;
    if( isset($user->roles[3]) or isset($user->roles[4]) ){
        $alowed=1;
    }else{
        if( $user->uid>0 ){
            $isyes=db_query('select uid from {node} where nid='.$_POST['nid'].' and uid='.$user->uid);
            $isyes=$isyes->fetchAssoc();
            if( isset($isyes['uid']) and is_numeric($isyes['uid']) ){
                $alowed=1;
            }
        }
    }
    if( $alowed ){
        
        switch($_POST['type']){
        case 'node':
            if($_POST['field']=='alias'){
                path_delete(array('source' => 'node/' . $_POST['nid']));
            }
            break;
        case 'field':
            if( isset($_POST['typeval']) and $_POST['typeval']=='tid'){
                $dels=db_query('select '.$_POST['field'].'_tid from {field_data_'.$_POST['field'].'} where entity_type=\'node\' and entity_id='.$_POST['nid']);
                while($del=$dels->fetchAssoc()){
                    db_query('delete from {taxonomy_index} where tid='.$del[$_POST['field'].'_tid'].' and nid='.$_POST['nid']);
                }
            }
            
            if( isset($_POST['delta']) and is_numeric($_POST['delta']) ){

                $node=node_load($_POST['nid']);
                if( isset($node->{$_POST['field']}['und']) and is_array($node->{$_POST['field']}['und']) and isset( $node->{$_POST['field']}['und'][$_POST['delta']] ) ){
                    unset($node->{$_POST['field']}['und'][$_POST['delta']]);
                }
        
                $field_info = field_info_field($_POST['field']);
                field_sql_storage_field_storage_write('node', $node, 'update', array($field_info['id']));
                
            }else{
                db_query('delete from {field_data_'.$_POST['field'].'} where entity_id='.$_POST['nid']);
                db_query('delete from {field_revision_'.$_POST['field'].'} where entity_id='.$_POST['nid']);
            }
            
            break;
        case 'product':
            switch($_POST['field']){
            case 'cost1':
            case 'cost2':
            case 'cost3':
                db_query('delete from {field_data_field_sign} where entity_id='.$_POST['nid']);
                db_query('delete from {field_revision_field_sign} where entity_id='.$_POST['nid']);
                db_query('update {uc_products} set cost=0 where nid='.$_POST['nid']);
                break;
            default:
                db_query('update {uc_products} set '.$_POST['field'].'=0 where nid='.$_POST['nid']);
            }
            break;
        }
        
        cache_clear_all('field:node:' . $_POST['nid'], 'cache_field', true);
        pdxclearnodecaches( node_load($_POST['nid']) );
    }
}

?>