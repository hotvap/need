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
            if($field=='nid'){ continue; }
//            if($field=='status'){ continue; }
        
         ?>
          <th class="<?php  echo 'f_'.$field.' '; if ($header_classes[$field]) { print $header_classes[$field]; } ?>">
            <?php print $label; ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php    
    foreach ($rows as $row_count => $row):
        
        $tid=array();
        $name=array();
        if( isset( $row['taxonomy_catalog'] ) and strlen($row['taxonomy_catalog']) ){
            $row['taxonomy_catalog']=explode('|d|',$row['taxonomy_catalog']);
            if( isset($row['taxonomy_catalog']) and is_array($row['taxonomy_catalog']) and count($row['taxonomy_catalog']) ){
                foreach($row['taxonomy_catalog'] as $rw){
                    $rw=explode('|pdx|',$rw);
                    if( isset($rw) and is_array($rw) and count($rw) ){
                        $name[]=$rw[0];
                        if(count($rw)>1){
                            $tid[]=$rw[1];
                        }
                    }
                }
            }
        }
        if( isset($name) and is_array($name) and count($name) ){
            $row['taxonomy_catalog']=implode(', ',$name);
        }
    
    ?>
      <tr <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
        <?php foreach ($row as $field => $content):
            if($field=='nid'){ continue; }
//            if($field=='status'){ continue; }
         ?>
          <td class="<?php echo 'f_'.$field.' '; if ($field_classes[$field][$row_count]) { print $field_classes[$field][$row_count]; } ?>" <?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php
            switch($field){
            case 'title':
                echo '<a class="stocktitle" id="stocktitle_'.$row['nid'].'" href="javascript: void(0);" onclick=" stockclick('.$row['nid'].'); ">';
                echo $content;
                echo '</a>';
                break;
            case 'stock':
                echo '<span';
                if( $content<0 ){
                    echo ' style="color: #a0a0a0;"';
                }
                echo ' id="stockis_'.$row['nid'].'">';
                echo $content;
                echo '</span>';
                break;
            case 'field_discount':
                if( isset($content) and strlen($content) ){
                    echo '-'.$content;
                }
                break;
            case 'nothing_1':
                echo '<input type="text" size="3" class="stockadd form-text" value="" id="stockadd_'.$row['nid'].'" /><a class="stockplus" href="javascript: void(0);" onclick=" stockadd('.$row['nid'].'); ">+</a>';
                break;
            case 'nothing':
                if( isset( $tid ) and is_array( $tid ) and count( $tid ) ){ 
                    $par=array();
                    foreach( $tid as $td ){
                        $parent='';
                        $parents=taxonomy_get_parents($td);
                        if( isset( $parents ) and is_array( $parents ) and count( $parents ) ){
                            foreach( $parents as $p ){
                                $parent=$p->name;
                            }
                        }
                        if( isset($parent) and strlen($parent) ){
                            $par[]=$parent;
                        }
                    }
                    if( isset($par) and is_array($par) and count($par) ){
                        $par=array_unique($par);
                        echo implode(', ', $par);
                    }
                }
                break;
            case 'status':
                echo '<input type="checkbox" class="stockstatus" id="stockstatus_'.$row['nid'].'" onchange=" var status=0; if( jQuery(this).attr(\'checked\')==true ){ status=1; } admchange('.$row['nid'].', \'node\', \'status\', status, \'\');"';
                if( $content=='True' ){
                    echo ' checked="checked"';
                }
                echo ' /> ';
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
