<?php

if( isset($_GET['nid']) and is_numeric($_GET['nid']) and isset($_GET['qty']) and is_numeric($_GET['qty']) and $_GET['qty']>9 and $_GET['qty']<10000 ){
    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    session_start();

    $p = array(
        'nid' => $_GET['nid'],
        'qty' => $_GET['qty'],
        'data' => array(),
    );
    $msg=FALSE;
    
        if ($p['nid'] > 0 && $p['qty'] > 0) {
          $node = node_load($p['nid']);
          if (is_array($node->products)) {
            foreach ($node->products as $nid => $product) {
              $p['data']['products'][$nid] = array(
                'nid' => $nid,
                'qty' => $product->qty,
              );
            }
          }
        }else{
          echo 'При формировании заказа произошла ошибка =(';
          exit();
        }

          uc_cart_get_contents(NULL, 'rebuild');
          uc_cart_add_item($p['nid'], $p['qty'], $p['data'] + module_invoke_all('uc_add_to_cart_data', $p), uc_cart_get_id(), $msg, FALSE, FALSE);
          uc_cart_get_contents(NULL, 'rebuild');
          
          echo '<div class="msgwait">Переходим на страницу выбора способа оплаты...</div>';
          echo '<script type="text/javascript"> window.location.replace(\'/cart/checkout\'); </script>';

}


?>