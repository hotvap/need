<?php

/**
 * @file
 * This file is the default admin notification template for Ubercart.
 */

//echo '<pre>'.print_r(get_defined_vars(), true).'</pre>';
//$uc_addresses_billing_ucxf_email
/*
$result = db_query("SELECT * FROM {uc_orders} WHERE order_id = ".$order_id);
$result = $result->fetchAssoc();
if($result){
    echo '<div>Адрес: '.$result['delivery_street1'].'</div>';
    echo '<div>Адрес2: '.$result['delivery_street2'].'</div>';
    echo '<div>Город: '.$result['delivery_city'].'</div>';
    echo '<div>Почтовый индекс: '.$result['delivery_postal_code'].'</div>';
    if(isset($result['delivery_zone']) and intval($result['delivery_zone'])){
        $zonename=db_query("SELECT zone_name FROM {uc_zones} WHERE zone_id = ".$result['delivery_zone']);
        $zonename=$zonename->fetchAssoc();
        if(isset($zonename['zone_name'])){
            echo '<div>Регион: '.$zonename['zone_name'].'</div>';
        }
    }
}
*/

if(isset($order_payment_method) and strlen($order_payment_method)){
    echo '<div>Способ оплаты заказа: '.$order_payment_method.'</div>';
}
if(isset($new_username) and isset($new_password)){
    echo '<div>Новое имя пользователя заказа, если применяется: '.$new_username.'</div>';
    echo '<div>Новый пароль заказа, если применяется: '.$new_password.'</div>';
}
if(isset($order_uid) and strlen($order_uid)){
    echo '<div>ID пользователя заказа: '.$order_uid.'</div>';
}
if(isset($order_link) and strlen($order_link)){
    echo '<div>Ссылка на заказ, использующая ID заказа: '.$order_link.' ('.$order_url.')</div>';
}
if(isset($order_admin_link) and strlen($order_admin_link)){
    echo '<div>Ссылка страницы просмотра заказа для администратора: '.$order_admin_link.' ('.$order_admin_url.')</div>';
}
if(isset($order_billing_phone) and strlen($order_billing_phone)){
    echo '<br /><div>Телефон: '.$order_billing_phone.'</div>';
}
?>

<p>
<?php print t('Order number:'); ?> <?php print $order_admin_link; ?><br />
<?php print t('Customer:'); ?> <?php print $order_first_name; ?> <?php print $order_last_name; ?> - <?php print $order_email; ?><br />
<?php print t('Order total:'); ?> <?php print $order_total; ?><br />
</p>

<p>
<?php print t('Products:'); ?><br />
<?php foreach ($products as $product): ?>
- <?php print $product->qty; ?> x <?php print $product->title; ?> - <?php print $product->total_price; ?><br />
&nbsp;&nbsp;<?php print t('SKU'); ?>: <?php print $product->model; ?><br />
    <?php if (!empty($product->data['attributes'])): ?>
    <?php foreach ($product->data['attributes'] as $attribute => $option): ?>
    &nbsp;&nbsp;<?php print t('@attribute: @options', array('@attribute' => $attribute, '@options' => implode(', ', (array)$option))); ?><br />
    <?php endforeach; ?>
    <?php endif; ?>
<br />
<?php endforeach; ?>
</p>

<p>
<?php print t('Order comments:'); ?><br />
<?php print $order_comments; ?>
</p>
