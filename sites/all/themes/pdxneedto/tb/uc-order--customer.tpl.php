<?php

/**
 * @file
 * This file is the default customer invoice template for Ubercart.
 *
 * Available variables:
 * - $products: An array of product objects in the order, with the following
 *   members:
 *   - title: The product title.
 *   - model: The product SKU.
 *   - qty: The quantity ordered.
 *   - total_price: The formatted total price for the quantity ordered.
 *   - individual_price: If quantity is more than 1, the formatted product
 *     price of a single item.
 *   - details: Any extra details about the product, such as attributes.
 * - $line_items: An array of line item arrays attached to the order, each with
 *   the following keys:
 *   - line_item_id: The type of line item (subtotal, shipping, etc.).
 *   - title: The line item display title.
 *   - formatted_amount: The formatted amount of the line item.
 * - $shippable: TRUE if the order is shippable.
 *
 * Tokens: All site, store and order tokens are also available as
 * variables, such as $site_logo, $store_name and $order_first_name.
 *
 * Display options:
 * - $op: 'view', 'print', 'checkout-mail' or 'admin-mail', depending on
 *   which variant of the invoice is being rendered.
 * - $business_header: TRUE if the invoice header should be displayed.
 * - $shipping_method: TRUE if shipping information should be displayed.
 * - $help_text: TRUE if the store help message should be displayed.
 * - $email_text: TRUE if the "do not reply to this email" message should
 *   be displayed.
 * - $store_footer: TRUE if the store URL should be displayed.
 * - $thank_you_message: TRUE if the 'thank you for your order' message
 *   should be displayed.
 *
 * @see template_preprocess_uc_order()
 */
?>
<p>Спасибо, Ваша заявка на пополнение баланса на сайте <?php echo $_SERVER['HTTP_HOST']; ?> принята.</p>
<div>&nbsp;</div>
<p><strong>Общая сумма заказа: </strong><?php echo $order_total; ?></p>
<p><strong>Способ оплаты: </strong><?php echo $order_payment_method; ?></p>
<div>&nbsp;</div>
<?php
switch( $order_payment_method ){
case 'Наличными':
    echo '<p>В ближайшее время с Вами свяжется наш менеджер для уточнения времени и места встречи.</p>';
    break;
default:
    echo '<p>После подтверждения оплаты, Ваш баланс будет пополнен.</p>';
}
?>