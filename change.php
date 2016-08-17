<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_POST['nid']) and strlen($_POST['nid']) and isset($_POST['val']) and strlen($_POST['val']) and isset($_POST['type']) and strlen($_POST['type']) and isset($_POST['field']) and strlen($_POST['field']) ){
    global $user; 
    
    $_POST['nid']=explode('|px|', $_POST['nid']);
    if( isset($_POST['nid']) and is_array($_POST['nid']) and count($_POST['nid']) ){
        foreach( $_POST['nid'] as $nidid=>$nidval ){
            if( !is_numeric($nidval) ){
                unset($_POST['nid'][$nidid]);
            }
        }
    }
    if( isset($_POST['nid']) and is_array($_POST['nid']) and count($_POST['nid']) ){}else{
        exit();
    }
    
//    drupal_set_message('<pre>'.print_r($_POST, true). '</pre>');
    

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
        foreach( $_POST['nid'] as $nidid=>$nidval ){
        
        $_POST['field']=filter_xss(strip_tags($_POST['field']));


        switch($_POST['type']){
            case 'term':
                if( isset($_POST['typeval']) and strlen($_POST['typeval']) ){
                }else{
                    db_query('update {taxonomy_term_data} set '.$_POST['field'].'=:combo where tid='.$nidval, array(':combo'=>$_POST['val']) );
                    exit();
                }
                break;
        }
        
        $type=db_query('select type from {node} where nid='.$nidval);
        $type=$type->fetchAssoc();
        if( isset($type['type']) and strlen($type['type']) ){
            switch($_POST['type']){
            case 'node':
                if($_POST['field']=='alias'){
                    path_delete(array('source' => 'node/' . $nidval));
                    unset($path);
                    $path['alias'] = $_POST['val'];
                    $path['source'] = 'node/' . $nidval;
                    $path['language'] = LANGUAGE_NONE;
                    path_save($path);                    
                }else{
                    db_query('update {node} set '.$_POST['field'].'=:combo where nid='.$nidval, array(':combo'=>$_POST['val']) );
                    db_query('update {node_revision} set '.$_POST['field'].'=:combo where nid='.$nidval, array(':combo'=>$_POST['val']));
                }

                break;
            case 'fieldmulti':
                $vals=explode('|px|', $_POST['val']);
                if( isset($vals) and is_array($vals) and count($vals) ){
                    if($_POST['typeval']=='tid'){
                        $dels=db_query('select '.$_POST['field'].'_tid from {field_data_'.$_POST['field'].'} where bundle=\''.$type['type'].'\' and entity_id='.$nidval);
                        while($del=$dels->fetchAssoc()){
                            db_query('delete from {taxonomy_index} where tid='.$del[$_POST['field'].'_tid'].' and nid='.$nidval);
                        }
                    }
                    db_query('delete from {field_data_'.$_POST['field'].'} where entity_id='.$nidval);
                    db_query('delete from {field_revision_'.$_POST['field'].'} where entity_id='.$nidval);
                    $delta=0;
                    foreach( $vals as $v ){
                        db_query('insert into {field_data_'.$_POST['field'].'} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, '.$_POST['field'].'_'.$_POST['typeval'].') values (\'node\',\''.$type['type'].'\',0,'.$nidval.','.$nidval.',\'und\','.$delta.',:combo)', array(':combo'=>$v));
                        if($_POST['typeval']=='tid' and is_numeric($v) ){
                            $del=db_query('select tid from {taxonomy_index} where tid='.$v.' and nid='.$nidval);
                            $del=$del->fetchAssoc();
                            if( !isset($del['tid']) ){
                                db_query('insert into {taxonomy_index} (nid, tid, sticky, created) values ('.$nidval.', '.$v.', 0, '.time().')');
                            }
                        }

                        $delta++;
                    }

                }
                
                break;
            case 'field':
                if( isset($_POST['typeval']) and strlen($_POST['typeval']) and ( $_POST['typeval']=='value' or $_POST['typeval']=='tid' ) ){
                    if( isset($_POST['delta']) and is_numeric($_POST['delta']) ){
                        if($_POST['typeval']=='tid'){
                            $dels=db_query('select '.$_POST['field'].'_tid from {field_data_'.$_POST['field'].'} where delta='.$_POST['delta'].' and bundle=\''.$type['type'].'\' and entity_id='.$nidval);
                            while($del=$dels->fetchAssoc()){
                                db_query('delete from {taxonomy_index} where tid='.$del[$_POST['field'].'_tid'].' and nid='.$nidval);
                            }
                        }

                        if( $_POST['delta']==0 ){
                            $val=db_query('select entity_id from {field_data_'.$_POST['field'].'} where bundle=\''.$type['type'].'\' and delta='.$_POST['delta'].' and entity_id='.$nidval);
                            $val=$val->fetchAssoc();
                            if(!isset($val['entity_id'])){
                                db_query('insert into {field_data_'.$_POST['field'].'} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, '.$_POST['field'].'_'.$_POST['typeval'].') values (\'node\',\''.$type['type'].'\',0,'.$nidval.','.$nidval.',\'und\','.$_POST['delta'].',:combo)', array(':combo'=>$_POST['val']));
                            }else{
                                db_query('update {field_data_'.$_POST['field'].'} set '.$_POST['field'].'_'.$_POST['typeval'].'=:combo where entity_type=\'node\' and delta='.$_POST['delta'].' and entity_id='.$nidval, array(':combo'=>$_POST['val']));
                            }
                        }else{
                            db_query('update {field_data_'.$_POST['field'].'} set '.$_POST['field'].'_'.$_POST['typeval'].'=:combo where entity_type=\'node\' and delta='.$_POST['delta'].' and entity_id='.$nidval, array(':combo'=>$_POST['val']));
                        }

                    }else{
                        if($_POST['typeval']=='tid'){
                            $dels=db_query('select '.$_POST['field'].'_tid from {field_data_'.$_POST['field'].'} where bundle=\''.$type['type'].'\' and entity_id='.$nidval);
                            while($del=$dels->fetchAssoc()){
                                db_query('delete from {taxonomy_index} where tid='.$del[$_POST['field'].'_tid'].' and nid='.$nidval);
                            }
                        }

                        $val=db_query('select entity_id from {field_data_'.$_POST['field'].'} where bundle=\''.$type['type'].'\' and entity_id='.$nidval);
                        $val=$val->fetchAssoc();
                        if(!isset($val['entity_id'])){
                            db_query('insert into {field_data_'.$_POST['field'].'} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, '.$_POST['field'].'_'.$_POST['typeval'].') values (\'node\',\''.$type['type'].'\',0,'.$nidval.','.$nidval.',\'und\',0,:combo)', array(':combo'=>$_POST['val']));
                        }else{
                            db_query('update {field_data_'.$_POST['field'].'} set '.$_POST['field'].'_'.$_POST['typeval'].'=:combo where entity_type=\'node\' and delta=0 and entity_id='.$nidval, array(':combo'=>$_POST['val']));
                        }

                    }

                    if($_POST['typeval']=='tid' and is_numeric($_POST['val']) ){
                        $del=db_query('select tid from {taxonomy_index} where tid='.$_POST['val'].' and nid='.$nidval);
                        $del=$del->fetchAssoc();
                        if( !isset($del['tid']) ){
                            db_query('insert into {taxonomy_index} (nid, tid, sticky, created) values ('.$nidval.', '.$_POST['val'].', 0, '.time().')');
                        }
                    }
                    


                }
                break;
            }
        }
        
        cache_clear_all('field:node:' . $nidval, 'cache_field', true);
        pdxclearnodecaches( node_load($nidval) );
                
        }
    }
}

?>