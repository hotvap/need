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