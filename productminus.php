<?php

define('DRUPAL_ROOT', getcwd());



require_once DRUPAL_ROOT . '/includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);



if( isset($_GET['key']) and is_numeric($_GET['key']) and isset($_GET['count']) and is_numeric($_GET['count']) ){



    $countcart=uc_cart_get_total_qty();

    if(isset($countcart) and is_numeric($countcart) and $countcart>0 ){

        $total=0;

    

        $display_items = entity_view('uc_cart_item', uc_cart_get_contents(), 'cart');

        if( isset($display_items['uc_cart_item'][$_GET['key']]) and is_array($display_items['uc_cart_item'][$_GET['key']]) ){



//            $display_items['uc_cart_item'][$_GET['key']]['#entity']->data['updated'] = TRUE;

            module_invoke($display_items['uc_cart_item'][$_GET['key']]['#entity']->data['module'], 'uc_update_cart_item', $display_items['uc_cart_item'][$_GET['key']]['#entity']->nid, $display_items['uc_cart_item'][$_GET['key']]['#entity']->data, $_GET['count']);

            uc_cart_get_contents(NULL, 'rebuild');

            echo '<img alt="" src="/misc/throbber.gif" />

            <script type="text/javascript"> jQuery(document).ready(function($){ 

//                jQuery(\'table.mycartshow .content\').load(Drupal.settings.basePath + \'mycart.php\');

                window.location.reload(true);

            }); </script>

            ';

            //uc_cart_update_item_object((object)$display_items['uc_cart_item']);

            //uc_cart_update_item($display_items['uc_cart_item'][$_GET['key']]);

//            echo '<pre>'.print_r($display_items['uc_cart_item'][$_GET['key']], true). '</pre>';

        }

    }



}



?>