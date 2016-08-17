<?php

/**
 * @file
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $caption: The caption for this table. May be empty.
 * - $header_classes: An array of header classes keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $classes: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * - $field_classes: An array of classes to apply to each field, indexed by
 *   field id, then row number. This matches the index in $rows.
 * @ingroup views_templates
 */
?>
<table <?php if ($classes) { print 'class="'. $classes . '" '; } ?><?php print $attributes; ?>>
   <?php if (!empty($title) || !empty($caption)) : ?>
     <caption><?php print $caption . $title; ?></caption>
  <?php endif; ?>
  <?php if (!empty($header)) : ?>
    <thead>
      <tr>
        <?php foreach ($header as $field => $label):
        if( $field=='nid' ){ continue; }
         ?>
          <th class="<?php  echo $field.' '; if ($header_classes[$field]) { print $header_classes[$field]; } ?>">
            <?php print str_replace(' ', '&nbsp;',$label);
            switch($field){
            case 'field_price_hour':
            case 'field_price_day':
            case 'field_price_week':
            case 'field_price_month':
            case 'field_rent_price':
            case 'field_delivery1_price':
                if( defined('PDX_CITY_CUR') ){
                    echo ',&nbsp;'.PDX_CITY_CUR;
                }
                break;
            }
            
            ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $row_count => $row): ?>
      <tr title="<?php echo str_replace('"','',strip_tags($row['title'])); ?>" <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
        <?php foreach ($row as $field => $content):
        if( $field=='nid' ){ continue; }
         ?>
          <td class="<?php echo $field.' '; if ($field_classes[$field][$row_count]) { print $field_classes[$field][$row_count]; } ?>" <?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php
            switch($field){
            case 'field_price_hour':
            case 'field_price_day':
            case 'field_price_week':
            case 'field_price_month':
            case 'field_delivery1_price':
            case 'field_rent_price':
                echo '<input size="7" class="form-text" type="text" id="" value="';
                if( isset($content) and strlen($content) ){
                    $content=strip_tags($content);
                    if( is_numeric($content) and $content>0 ){
                        echo $content;
                    }
                }
                echo '" onchange=" if( jQuery(this).val() ){ admchangedelta('.$row['nid'].', \'field\', \''.$field.'\', jQuery(this).val().replaceAll(\' \', \'\'), \'value\', 0); }else{ admdeldelta('.$row['nid'].', \'field\', \''.$field.'\', jQuery(this).val().replaceAll(\' \', \'\'), 0); } " />';
                break;
            default:
                print $content;
            }
            
            ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
