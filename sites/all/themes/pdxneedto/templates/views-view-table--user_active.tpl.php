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
            if( $field=='field_premium' ){
                continue;
            }
         ?>
          <th class="<?php  echo $field.' '; if ($header_classes[$field]) { print $header_classes[$field]; } ?>">
            <?php print str_replace(' ', '&nbsp;',$label);
            
            ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $row_count => $row):
    $nd=0;
    if( isset($row['nid']) ){
        $nd=str_replace('N1','',strip_tags($row['nid']));
    }
    $ttl=str_replace('"','',str_replace("'",'',strip_tags($row['title'])));
    ?>
      <tr title="<?php echo $ttl; ?>" <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
        <?php foreach ($row as $field => $content):
            if( $field=='field_premium' ){
                continue;
            }
         ?>
          <td class="<?php echo $field.' '; if ($field_classes[$field][$row_count]) { print $field_classes[$field][$row_count]; } ?>" <?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php
            switch($field){
            case 'title':
                if( isset( $row['field_premium'] ) and is_numeric( $row['field_premium'] ) and $row['field_premium']>0 ){
                    echo '<img title="Premium-объявление" class="item_ispremium" alt="" src="/sites/all/themes/pdxneedto/img/trophy.png" />';
                }
                echo $content;
                break;
            case 'nothing':
                if( is_numeric($nd) and $nd>0 ){
?>
<script type="text/javascript"> 
var confirmtext<?php echo $nd; ?>='Вы действительно хотите поднять объявление "<?php echo $ttl; ?>" наверх? <span class="confirmyes" onclick=" bonus_ontop(<?php echo $nd; ?>, '+"'.bonus_ontop'"+'); jQuery('+"'.closeconfirm'"+').click(); ">&nbsp;</span>';
<?php if( isset( $row['field_premium'] ) and is_numeric( $row['field_premium'] ) and $row['field_premium']>0 ){ ?>
    var confirmtext2_<?php echo $nd; ?>='Вы действительно хотите продлить статус Premium для объявления "<?php echo $ttl; ?>"? <span class="confirmyes" onclick=" bonus_premium(<?php echo $nd; ?>, '+"'.bonus_premium'"+'); jQuery('+"'.closeconfirm'"+').click(); ">&nbsp;</span>';
<?php }else{ ?>
    var confirmtext2_<?php echo $nd; ?>='Вы действительно хотите повысить объявление "<?php echo $ttl; ?>" до Premium? <span class="confirmyes" onclick=" bonus_premium(<?php echo $nd; ?>, '+"'.bonus_premium'"+'); jQuery('+"'.closeconfirm'"+').click(); ">&nbsp;</span>';
<?php } ?>
 </script>
<?php
                    echo '<ul class="item_actions">';
                    echo '<li><a class="edit" href="/node/'.$nd.'/edit">редактировать</a></li>';
                    echo '<li><a class="clone" href="/node/add/item?clone='.$nd.'">клонировать</a><a target="_blank" href="http://'.PDX_URL_HELP.'/klonirovanie-obyavleniy.html" class="smhelp">&nbsp;</a></li>';
                    echo '<li><a class="bonus_ontop bonus_ontop_'.$nd.'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext'.$nd.'); jQuery(\'html, body\').animate({ scrollTop: jQuery(\'#confirmactionin\').offset().top }, 333); ">Поднять наверх!</a><a target="_blank" href="http://'.PDX_URL_HELP.'/podnyatie-obyavleniya-naverh.html" class="smhelp">&nbsp;</a></li>';
                    if( isset( $row['field_premium'] ) and is_numeric( $row['field_premium'] ) and $row['field_premium']>0 ){
                        echo '<li class="li_ispremium li_ispremium_'.$row['field_premium'].'"><a class="bonus_premium bonus_premium_'.$nd.'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext2_'.$nd.'); jQuery(\'html, body\').animate({ scrollTop: jQuery(\'#confirmactionin\').offset().top }, 333); ">Продлить Premium!</a><a target="_blank" href="http://'.PDX_URL_HELP.'/prisvoenie-obyavleniyu-statusa-premium.html" class="smhelp">&nbsp;</a></li>';
                    }else{
                        echo '<li><a class="bonus_premium bonus_premium_'.$nd.'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext2_'.$nd.'); jQuery(\'html, body\').animate({ scrollTop: jQuery(\'#confirmactionin\').offset().top }, 333); ">Сделать Premium!</a><a target="_blank" href="http://'.PDX_URL_HELP.'/prisvoenie-obyavleniyu-statusa-premium.html" class="smhelp">&nbsp;</a></li>';
                    }
                    echo '</ul>';
                }
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
