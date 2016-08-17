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
        if($field=='cid'){ continue; }
         ?>
          <th class="<?php  echo $field.' '; if ($header_classes[$field]) { print $header_classes[$field]; } ?>">
            <?php print $label; ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $row_count => $row): ?>
      <tr <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
        <?php foreach ($row as $field => $content):
        if($field=='cid'){ continue; }
         ?>
          <td class="<?php echo $field.' '; if ($field_classes[$field][$row_count]) { print $field_classes[$field][$row_count]; } ?>" <?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php
            switch($field){
            case 'created':
                echo '<span id="pe_'.$row['cid'].'_cmt'.$field.'"></span><input type="text" size="11" value="';
                if(isset($content) and strlen($content)){
                    echo str_replace('"','\"',$content);
                }
                echo '" id="e_'.$row['cid'].'_cmt'.$field.'" onchange=\'edittitle('.$row['cid'].',"cmt'.$field.'")\' />';
                break;
            case 'comment_body':
                echo '<span id="pe_'.$row['cid'].'_cmtbody"></span><textarea style="width:100%;" rows="7" id="e_'.$row['cid'].'_cmtbody" onchange=\'edittitle('.$row['cid'].',"cmtbody")\'>'.$content.'</textarea>';
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
