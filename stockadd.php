<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset($_POST['nid']) and is_numeric($_POST['nid']) and isset($_POST['val']) and is_numeric($_POST['val']) ){
    global $user; if( isset($user->roles[3]) or isset($user->roles[4]) or isset($user->roles[5]) ){
        
$model=db_query('select model from {uc_products} where nid='.$_POST['nid']);
$model=$model->fetchAssoc();

if(module_exists('uc_stock') and isset($model['model']) and strlen($model['model']) ){
    
    $curstock=0;
    $isstock=db_query('select stock from {uc_product_stock} where nid='.$_POST['nid']);
    $isstock=$isstock->fetchAssoc();
    if( isset($isstock['stock']) and is_numeric($isstock['stock']) ){
        $curstock=$isstock['stock'];
    }
    if( isset($_POST['val']) and is_numeric($_POST['val']) and ( isset($user->roles[3]) or $_POST['val']>0 ) ){
        $curstock+=$_POST['val'];
    }
    if( isset($curstock) and is_numeric($curstock) ){
        $hit=db_query('select field_stock_value from {field_data_field_stock} where entity_id='.$_POST['nid']);
        $hit=$hit->fetchAssoc();
        if(!isset($hit['field_stock_value'])){
            db_query('insert into {field_data_field_stock} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_stock_value) values (\'node\',\'product\',0,'.$_POST['nid'].','.$_POST['nid'].',\'und\',0,'.$curstock.')');
            db_query('insert into {field_revision_field_stock} (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_stock_value) values (\'node\',\'product\',0,'.$_POST['nid'].','.$_POST['nid'].',\'und\',0,'.$curstock.')');
        }else{
            db_query('update {field_data_field_stock} set field_stock_value='.$curstock.' where bundle=\'product\' and entity_id='.$_POST['nid']);
            db_query('update {field_revision_field_stock} set field_stock_value='.$curstock.' where bundle=\'product\' and entity_id='.$_POST['nid']);
        }

        $hit=db_query('select stock from {uc_product_stock} where nid='.$_POST['nid']);
        $hit=$hit->fetchAssoc();
        if(!isset($hit['stock'])){
            db_query('insert into {uc_product_stock} (sku, nid, active, stock, threshold) values (:combo, '.$_POST['nid'].', 1, '.$curstock.', 0)', array(':combo'=>$model['model']));
        }else{
            db_query('update {uc_product_stock} set stock='.$curstock.' where nid='.$_POST['nid']);
            db_query('update {uc_product_stock} set active=1 where nid='.$_POST['nid']);
        }
        db_query('delete from {field_data_field_stockadd} where entity_id='.$_POST['nid']);
        db_query('delete from {field_revision_field_stockadd} where entity_id='.$_POST['nid']);

        cache_clear_all('field:node:' . $_POST['nid'], 'cache_field', true);
        
        echo $curstock;
        echo '<script type="text/javascript"> jQuery("#stockadd_'.$_POST['nid'].'").val(""); </script>';
    }
}
 
 
    }
}

?>