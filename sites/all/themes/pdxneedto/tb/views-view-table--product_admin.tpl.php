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
        <?php foreach ($header as $field => $label): ?>
          <th <?php if ($header_classes[$field]) { print 'class="'. $header_classes[$field] . '" '; } ?>>
            <?php print $label; ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $row_count => $row): ?>
      <tr <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
        <?php foreach ($row as $field => $content): ?>
          <td <?php if ($field_classes[$field][$row_count]) { print 'class="'. $field_classes[$field][$row_count] . '" '; } ?><?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php
            switch($field){
            case 'title':
                echo '<span id="pe_'.$row['nid'].'_'.$field.'"></span><input type="text" size="21" value="';
                if(isset($content) and strlen($content)){
                    echo str_replace('"','\"',$content);
                }
                echo '" id="e_'.$row['nid'].'_'.$field.'" onchange=\'edittitle('.$row['nid'].',"'.$field.'")\' />';
                break;
            case 'model':
                echo '<span id="pe_'.$row['nid'].'_'.$field.'"></span><input type="text" size="7" value="';
                if(isset($content) and strlen($content)){
                    echo str_replace('"','\"',$content);
                }
                echo '" id="e_'.$row['nid'].'_'.$field.'" onchange=\'edittitle('.$row['nid'].',"'.$field.'")\' />';
                break;
            case 'sell_price':
                echo '<span id="pe_'.$row['nid'].'_'.$field.'"></span><input type="text" size="4" value="';
                if(isset($content) and strlen($content)){
                    echo str_replace('"','\"',$content);
                }
                echo '" id="e_'.$row['nid'].'_'.$field.'" onchange=\'edittitle('.$row['nid'].',"'.$field.'")\' />';
                break;
            case 'cost':
                echo '<span id="pe_'.$row['nid'].'_'.$field.'"></span><input type="text" size="4" value="';
                if(isset($content) and strlen($content)){
                    echo str_replace('"','\"',$content);
                }
                echo '" id="e_'.$row['nid'].'_'.$field.'" onchange=\'edittitle('.$row['nid'].',"'.$field.'")\' />';
                break;
            case 'taxonomy_catalog':
                echo '<span id="pe_'.$row['nid'].'_'.$field.'"></span>';
                echo '<select id="e_'.$row['nid'].'_'.$field.'" onchange=\'edittitle('.$row['nid'].',"'.$field.'")\'>';
                echo '<option value="">выберите</option>';
                
                $terms = taxonomy_get_tree(2);
                if($terms){
                    foreach($terms as $term){
                        echo '<option value="'.$term->tid.'"';
                        if( isset($content) and $content==$term->tid ){
                            echo ' selected="selected"';
                        }
                        echo '>'.$term->name.'</option>';
                    }
                }

                echo '</select>';
                break;
            case 'field_brand':
                echo '<span id="pe_'.$row['nid'].'_'.$field.'"></span>';
                echo '<select id="e_'.$row['nid'].'_'.$field.'" onchange=\'edittitle('.$row['nid'].',"'.$field.'")\'>';
                echo '<option value="">выберите</option>';
                
                $terms = taxonomy_get_tree(5);
                if($terms){
                    foreach($terms as $term){
                        echo '<option value="'.$term->tid.'"';
                        if( isset($content) and $content==$term->tid ){
                            echo ' selected="selected"';
                        }
                        echo '>'.$term->name.'</option>';
                    }
                }

                echo '</select>';
                break;
            case 'field_hit':
                echo '<div class="foradmin_hit foradmin_hit'.$row['nid'].'">';
                if(strip_tags($content)==0){
                    echo '<a href="javascript: void(0);" onclick="foradmin_hit('.$row['nid'].');">Сделать хитом</a>';
                }else{
                    echo '<a href="javascript: void(0);" onclick="foradmin_hit('.$row['nid'].');">Убрать из хитов</a>';
                }
                echo '</div>';
                break;
            case 'sticky':
                echo '<div class="foradmin_sticky foradmin_sticky'.$row['nid'].'">';
                if(strip_tags($content)==0){
                    echo '<a href="javascript: void(0);" onclick="foradmin_sticky('.$row['nid'].');">Закрепить</a>';
                }else{
                    echo '<a href="javascript: void(0);" onclick="foradmin_sticky('.$row['nid'].');">Открепить</a>';
                }
                echo '</div>';
                break;
            case 'status':
                echo '<div class="foradmin_status foradmin_status'.$row['nid'].'">';
                if(strip_tags($content)==0){
                    echo '<a href="javascript: void(0);" onclick="foradmin_status('.$row['nid'].');">Опубликовать</a>';
                }else{
                    echo '<a href="javascript: void(0);" onclick="foradmin_status('.$row['nid'].');">Снять с публикации</a>';
                }
                echo '</div>';
                break;
//            case 'nothing_1': //параметры
//                break;
            case 'nothing_2': //опции
                $aids=db_query('select a.aid, a.name from {uc_product_attributes} as p inner join {uc_attributes} as a on p.aid=a.aid where p.nid='.$row['nid']);
                while($aid=$aids->fetchAssoc()){
                    echo '<div class="option_line"><strong>'.$aid['name'].': </strong>';
                    $aoids=array();
                    $oids=db_query('select o.oid, o.name, p.price from {uc_product_options} as p inner join {uc_attribute_options} as o on p.oid=o.oid where p.nid='.$row['nid'].' and o.aid='.$aid['aid']);
                    while($oid=$oids->fetchAssoc()){
                        if( $oid['price']>0 ){
                            $aoids[]=$oid['name'].' ('.round($oid['price'], 2).')';
                        }else{
                            $aoids[]=$oid['name'];
                        }
                    }
                    if( isset($aoids) and is_array($aoids) and count($aoids) ){
                        echo implode(', ', $aoids);
                    }
                    
                    echo '</div>';
                }
                echo '<a target="_blank" href="/updateattr.php?nid='.$row['nid'].'">редактировать</a>';
                
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
