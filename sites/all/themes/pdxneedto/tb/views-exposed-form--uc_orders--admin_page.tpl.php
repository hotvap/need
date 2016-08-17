<?php
/**
 * @file views-exposed-form.tpl.php
 *
 * This template handles the layout of the views exposed filter form.
 *
 * Variables available:
 * - $widgets: An array of exposed form widgets. Each widget contains:
 * - $widget->label: The visible label to print. May be optional.
 * - $widget->operator: The operator for the widget. May be optional.
 * - $widget->widget: The widget itself.
 * - $sort_by: The select box to sort the view using an exposed form.
 * - $sort_order: The select box with the ASC, DESC options to define order. May be optional.
 * - $items_per_page: The select box with the available items per page. May be optional.
 * - $offset: A textfield to define the offset of the view. May be optional.
 * - $reset_button: A button to reset the exposed filter applied. May be optional.
 * - $button: The submit button for the form.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($q)): ?>
  <?php
    // This ensures that, if clean URLs are off, the 'q' is added first so that
    // it shows up first in the URL.
    print $q;
  ?>
<?php endif; ?>
<?php

if( isset($widgets['filter-order_status']) ){
    echo '<div style="float: left; padding-left: 55px; white-space: nowrap;">';
    echo '<div style=" padding-bottom: 7px; "><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-order-status option\').each( function(){ jQuery(this).removeAttr(\'selected\') } ); jQuery(\'#edit-submit-uc-orders\').click(); "><strong>все заказы</strong></a></div>';
    echo '<div style=" padding: 5px 0 7px 61px; background: transparent url(\'/sites/all/libraries/img/adm/status_new.png\') no-repeat left center; "><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-order-status option\').each( function(){ jQuery(this).removeAttr(\'selected\') } ); jQuery(\'#edit-order-status option[value=pending]\').attr(\'selected\', \'selected\'); jQuery(\'#edit-submit-uc-orders\').click(); ">новые</a></div>';
    echo '<div style=" padding: 5px 0 7px 61px; background: transparent url(\'/sites/all/libraries/img/adm/status_driving.png\') no-repeat left center; "><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-order-status option\').each( function(){ jQuery(this).removeAttr(\'selected\') } ); jQuery(\'#edit-order-status option[value=driving]\').attr(\'selected\', \'selected\'); jQuery(\'#edit-submit-uc-orders\').click(); ">в доставке</a></div>';
    echo '<div style=" padding: 5px 0 7px 61px; background: transparent url(\'/sites/all/libraries/img/adm/status_wait.png\') no-repeat left center; "><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-order-status option\').each( function(){ jQuery(this).removeAttr(\'selected\') } ); jQuery(\'#edit-order-status option[value=processing]\').attr(\'selected\', \'selected\'); jQuery(\'#edit-submit-uc-orders\').click(); ">ждут оплаты</a></div>';
    echo '<div style=" padding: 5px 0 7px 61px; background: transparent url(\'/sites/all/libraries/img/adm/status_complete.png\') no-repeat left center; "><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-order-status option\').each( function(){ jQuery(this).removeAttr(\'selected\') } ); jQuery(\'#edit-order-status option[value=payment_received]\').attr(\'selected\', \'selected\'); jQuery(\'#edit-order-status option[value=completed]\').attr(\'selected\', \'selected\'); jQuery(\'#edit-submit-uc-orders\').click(); ">оплачены</a></div>';
    echo '<div style=" padding: 5px 0 7px 61px; "><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-order-status option\').each( function(){ jQuery(this).removeAttr(\'selected\') } ); jQuery(\'#edit-order-status option[value=canceled]\').attr(\'selected\', \'selected\'); jQuery(\'#edit-order-status option[value=abandoned]\').attr(\'selected\', \'selected\'); jQuery(\'#edit-order-status option[value=defect]\').attr(\'selected\', \'selected\'); jQuery(\'#edit-submit-uc-orders\').click(); ">отменены</a></div>';
    echo '</div>';
    echo '<div style="display: none;">';
    echo $widgets['filter-order_status']->widget;
    echo '</div>';
    unset($widgets['filter-order_status']);
}
if( isset($widgets['filter-date_filter']) ){
    echo '<div style="float: left; padding-left: 55px; white-space: nowrap;">';

    echo '<div style="padding-bottom: 7px;"><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-date-filter-min-datepicker-popup-0\').val(\''.date('m/d/Y', time()).'\'); jQuery(\'#edit-date-filter-max-datepicker-popup-0\').val(\''.date('m/d/Y', time() ).'\'); jQuery(\'#edit-submit-uc-orders\').click(); ">за сегодня</a></div>';
    echo '<div style="padding-bottom: 7px;"><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-date-filter-min-datepicker-popup-0\').val(\''.date('m/d/Y', (time()-86400)).'\'); jQuery(\'#edit-date-filter-max-datepicker-popup-0\').val(\''.date('m/d/Y', (time()-86400) ).'\'); jQuery(\'#edit-submit-uc-orders\').click(); ">за вчера</a></div>';
    echo '<div style="padding-bottom: 7px;"><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-date-filter-min-datepicker-popup-0\').val(\''.date('m/d/Y', (time()-604800) ).'\'); jQuery(\'#edit-date-filter-max-datepicker-popup-0\').val(\''.date('m/d/Y', time()).'\'); jQuery(\'#edit-submit-uc-orders\').click(); ">за неделю</a></div>';
    echo '<div style="padding-bottom: 7px;"><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-date-filter-min-datepicker-popup-0\').val(\''.date('m/d/Y', (time()-2678400) ).'\'); jQuery(\'#edit-date-filter-max-datepicker-popup-0\').val(\''.date('m/d/Y', time()).'\'); jQuery(\'#edit-submit-uc-orders\').click(); ">за месяц</a></div>';
    echo '<div style="padding-bottom: 7px;"><a href=" javascript: void(0); " onclick=" jQuery(\'#edit-date-filter-min-datepicker-popup-0\').val(\'\'); jQuery(\'#edit-date-filter-max-datepicker-popup-0\').val(\'\'); jQuery(\'#edit-submit-uc-orders\').click(); ">за все время</a></div>';

    echo '</div>';
    echo '<div style="float: left; padding-left: 18px;">';
    echo $widgets['filter-date_filter']->widget;
    echo '</div>';
    unset($widgets['filter-date_filter']);
}

    echo '<div style="float: left; padding-left: 55px; width: 239px;">';
    if( isset($widgets['filter-order_id']) ){
        echo '<div>№ заказа</div>';
        echo $widgets['filter-order_id']->widget;
        unset($widgets['filter-order_id']);
    }

    if( isset($widgets['filter-field_order_role_value']) ){
        echo '<select onchange=" if(jQuery(this).val()){ jQuery(\'#edit-field-order-role-value\').val(jQuery(this).val()); }else{ jQuery(\'#edit-field-order-role-value\').val(\'\'); } jQuery(\'#edit-submit-uc-orders\').click(); "><option value="">Роль создателя</option>';
        $vals=db_query('select DISTINCT field_order_role_value from {field_data_field_order_role}');
        while($val=$vals->fetchAssoc()){
            if( !strlen($val['field_order_role_value']) ){ continue; }
            echo '<option value="'.$val['field_order_role_value'].'"';
            if( isset( $_GET['field_order_role_value'] ) and $_GET['field_order_role_value']==$val['field_order_role_value'] ){
                echo ' selected="selected"';
            }
            echo '>'.$val['field_order_role_value'].'</option>';
        }
        echo '</select>';
        echo '<div style="display: none;">';
        echo $widgets['filter-field_order_role_value']->widget;
        echo '</div>';
        unset($widgets['filter-field_order_role_value']);
    }

    if( isset($widgets['filter-payment_method']) ){
        echo str_replace( '<select ', '<select onchange=" jQuery(\'#edit-submit-uc-orders\').click(); " ', str_replace( '- Все -','Способ оплаты', $widgets['filter-payment_method']->widget));
        unset($widgets['filter-payment_method']);
    }

    if( isset($widgets['filter-field_order_delivery_value']) ){
        echo '<select onchange=" if(jQuery(this).val()){ jQuery(\'#edit-field-order-delivery-value\').val(jQuery(this).val()); }else{ jQuery(\'#edit-field-order-delivery-value\').val(\'\'); } jQuery(\'#edit-submit-uc-orders\').click(); "><option value="">Способ доставки</option>';
        $vals=db_query('select DISTINCT field_order_delivery_value from {field_data_field_order_delivery}');
        while($val=$vals->fetchAssoc()){
            if( !strlen($val['field_order_delivery_value']) ){ continue; }
            echo '<option value="'.$val['field_order_delivery_value'].'"';
            if( isset( $_GET['field_order_delivery_value'] ) and $_GET['field_order_delivery_value']==$val['field_order_delivery_value'] ){
                echo ' selected="selected"';
            }
            echo '>'.$val['field_order_delivery_value'].'</option>';
        }
        echo '</select>';
        echo '<div style="display: none;">';
        echo $widgets['filter-field_order_delivery_value']->widget;
        echo '</div>';
        unset($widgets['filter-field_order_delivery_value']);
    }

    echo '</div>';


?>
<div class="views-exposed-form">
  <div class="views-exposed-widgets clearfix">
    <?php foreach ($widgets as $id => $widget): 
//    echo $id.'<br />';
    
    ?>
      <div id="<?php print $widget->id; ?>-wrapper" class="views-exposed-widget views-widget-<?php print $id; ?>">
        <?php if (!empty($widget->label)): ?>
          <label for="<?php print $widget->id; ?>">
            <?php print $widget->label; ?>
          </label>
        <?php endif; ?>
        <?php if (!empty($widget->operator)): ?>
          <div class="views-operator">
            <?php print $widget->operator; ?>
          </div>
        <?php endif; ?>
        <div class="views-widget">
          <?php print $widget->widget; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if (!empty($sort_by)): ?>
      <div class="views-exposed-widget views-widget-sort-by">
        <?php print $sort_by; ?>
      </div>
      <div class="views-exposed-widget views-widget-sort-order">
        <?php print $sort_order; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($items_per_page)): ?>
      <div class="views-exposed-widget views-widget-per-page">
        <?php print $items_per_page; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($offset)): ?>
      <div class="views-exposed-widget views-widget-offset">
        <?php print $offset; ?>
      </div>
    <?php endif; ?>
    <div class="views-exposed-widget views-submit-button">
      <?php print $button; ?>
    </div>
    <?php if (!empty($reset_button)): ?>
      <div class="views-exposed-widget views-reset-button">
        <?php print $reset_button; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<div class="clear">&nbsp;</div>