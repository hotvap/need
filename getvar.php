<?php

define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

global $user; if(isset($user->roles[3])){
    


echo '<div>';
    $sql= 'select * from pdxvariable where name LIKE \'additional_settings__%\' or name LIKE \'ant_%\' or name LIKE \'comment_%\' or name LIKE \'custom_breadcrumbs_show_form_table_%\' or name LIKE \'field_bundle_settings_%\' or name LIKE \'field_sql_norevisions%\' or name LIKE \'formblock_%\' or name LIKE \'language_content_type_%\' or name LIKE \'menu_options_%\' or name LIKE \'menu_parent_%\' or name LIKE \'node_options_%\' or name LIKE \'node_preview_%\' or name LIKE \'node_rank_%\' or name LIKE \'node_submitted_%\' or name LIKE \'pathauto_node_%\' or name LIKE \'pathauto_taxonomy_term_%\' or name LIKE \'save_continue_%\' or name LIKE \'uc_address_%\' or name LIKE \'uc_cap_%\' or name LIKE \'uc_cart_%\' or name LIKE \'uc_coupon_%\' or name LIKE \'uc_field_%\' or name LIKE \'uc_image_%\' or name LIKE \'uc_pane_%\' or name LIKE \'uc_payment_%\' or name LIKE \'uc_product_%\' or name LIKE \'var_%\' or name LIKE \'pm_%\' or name LIKE \'privatemsg_%\' or name LIKE \'date_format_%\' ';
    echo '<textarea rows="3" cols="151" onclick=" this.select(); ">'.$sql.'</textarea>';
    
    echo '<textarea rows="31" cols="151" onclick=" this.select(); ">';

    $vars=db_query($sql);
    while( $var=$vars->fetchAssoc() ){
//        if( strpos($var['name'], 'additional_settings__')===false and strpos($var['name'], 'ant_')===false and strpos($var['name'], 'comment_')===false and strpos($var['name'], 'custom_breadcrumbs_show_form_table_')===false and strpos($var['name'], 'field_bundle_settings_')===false and strpos($var['name'], 'field_sql_norevisions')===false and strpos($var['name'], 'formblock_')===false and strpos($var['name'], 'language_content_type_')===false and strpos($var['name'], 'menu_options_')===false and strpos($var['name'], 'menu_parent_')===false and strpos($var['name'], 'node_options_')===false and strpos($var['name'], 'node_preview_')===false and strpos($var['name'], 'node_rank_')===false and strpos($var['name'], 'node_submitted_')===false and strpos($var['name'], 'pathauto_node_')===false and strpos($var['name'], 'pathauto_taxonomy_term_')===false and strpos($var['name'], 'save_continue_')===false and strpos($var['name'], 'uc_address_')===false and strpos($var['name'], 'uc_cap_')===false and strpos($var['name'], 'uc_cart_')===false and strpos($var['name'], 'uc_coupon_')===false and strpos($var['name'], 'uc_field_')===false and strpos($var['name'], 'uc_image_')===false and strpos($var['name'], 'uc_pane_')===false and strpos($var['name'], 'uc_payment_')===false and strpos($var['name'], 'uc_product_')===false and strpos($var['name'], 'var_')===false ){ }else{
            echo  'delete from pdxvariable where name="'.$var['name'].'";
';
/*
            $var['value']=unserialize($var['value']);
            $var['value']=serialize($var['value']);
            echo  'insert into pdxvariable (name, value) VALUES ("'.$var['name'].'", \''.$var['value'].'\');
';
*/
//        }
    }

    echo '</textarea>';
echo '</div>';
    
}

?>