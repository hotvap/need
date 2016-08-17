<?php

if( isset($_POST['part']) and is_numeric($_POST['part']) and $_POST['part']>0 and isset($_POST['type']) and is_numeric($_POST['type']) and $_POST['type']>0 ){

    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
    
    
    echo ' <script type="text/javascript"> ';
    switch( $_POST['type'] ){
    case 1:
        echo 'jQuery(\'.form-item-field-subpart-und .form-radio\').parent().parent().hide();
        ';
        $outs=db_query('select entity_id from {field_data_field_part} where bundle=\'subpart\' and field_part_tid='.$_POST['part']);
        while( $out=$outs->fetchAssoc() ){
            echo 'jQuery(\'#edit-field-subpart-und-'.$out['entity_id'].'\').parent().parent().show(); ';
        }
        break;
    case 2:
        echo 'jQuery(\'.form-item-field-tags-und .form-radio\').parent().parent().hide();
        ';
        $outs=db_query('select entity_id from {field_data_field_part} where bundle=\'tags\' and field_part_tid='.$_POST['part']);
        while( $out=$outs->fetchAssoc() ){
            echo 'jQuery(\'#edit-field-tags-und-'.$out['entity_id'].'\').parent().parent().show(); ';
        }
        break;
    case 3:
        echo 'jQuery(\'.form-item-field-tags-und .form-radio\').parent().parent().hide();
        ';
        $outs=db_query('select entity_id from {field_data_field_subpart} where bundle=\'tags\' and field_subpart_tid='.$_POST['part']);
        while( $out=$outs->fetchAssoc() ){
            echo 'jQuery(\'#edit-field-tags-und-'.$out['entity_id'].'\').parent().parent().show(); ';
        }
        break;
    }
    
    echo ' </script> ';
}

?>