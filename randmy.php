<?php
    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
    
    if( isset( $_GET['city'] ) and is_numeric( $_GET['city'] ) and $_GET['city']>0 ){
        $myop=0;
        if( isset( $_GET['op'] ) and is_numeric($_GET['op']) and isset( $_GET['nid'] ) and is_numeric($_GET['nid']) ){
            $myop=$_GET['op'];
        }
        $num=db_query('select n.nid from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=1 and n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) order by RAND() limit 0,1');
        $num=$num->fetchAssoc();
        
        if( isset($num['nid']) and is_numeric( $num['nid'] ) ){
            header("Location: ".$GLOBALS['base_url']."/node/".$num['nid']);
        }
    
    }
    echo 'Извините, не удалось опознать Ваш город =(';


?>

