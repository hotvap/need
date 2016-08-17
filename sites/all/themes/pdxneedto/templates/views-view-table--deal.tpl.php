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
        $iscont=0;
        switch($field){
        case 'nid':
            $iscont=1;
        }
        if( $iscont ) continue;
         ?>
          <th class="<?php  echo $field.' '; if ($header_classes[$field]) { print $header_classes[$field]; } ?>">
            <?php print $label; ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $row_count => $row):
    $nd=node_load($row['nid']);
    ?>
      <tr title="<?php echo str_replace('"','',strip_tags($row['field_item'])); ?>" <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
        <?php foreach ($row as $field => $content):
        $iscont=0;
        switch($field){
        case 'nid':
            $iscont=1;
        }
        if( $iscont ) continue;
         ?>
          <td class="<?php echo $field.' '; if ($field_classes[$field][$row_count]) { print $field_classes[$field][$row_count]; } ?>" <?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php
            switch($field){
            case 'nothing_1':
                if( isset( $nd->field_datend['und'][0]['value'] ) and strlen( $nd->field_datend['und'][0]['value'] ) ){
                    $eventdate=strtotime($nd->field_datend['und'][0]['value']);
                    if( isset($eventdate) and is_numeric($eventdate) and $eventdate>0 ){
                        echo '<div><strong>Начало: </strong>'.date('Y.m.d', $eventdate).'</div>';
                    }
                }
                if( isset( $nd->field_period_ed['und'][0]['value'] ) and is_numeric( $nd->field_period_ed['und'][0]['value'] ) and isset( $nd->field_period_num['und'][0]['value'] ) and is_numeric( $nd->field_period_num['und'][0]['value'] ) ){
                    $edstr='';
                    switch($nd->field_period_ed['und'][0]['value']){
                    case 1:
                        $edstr=' час(а)';
                        break;
                    case 2:
                        $edstr=' день(я)';
                        break;
                    case 3:
                        $edstr=' неделя(и)';
                        break;
                    case 4:
                        $edstr=' месяц(а)';
                        break;
                    }
                    if( isset($edstr) and strlen($edstr) ){
                        echo '<div><strong>Период: </strong>'.$nd->field_period_num['und'][0]['value'].' '.$edstr.'</div>';
                    }
                }
                if( isset( $nd->field_deliveris['und'][0]['value'] ) and strlen( $nd->field_deliveris['und'][0]['value'] ) ){
                    $edstr='';
                    switch($nd->field_deliveris['und'][0]['value']){
                    case 1:
                        $edstr='самовывоз';
                        break;
                    case 2:
                        $edstr='доставка по городу';
                        break;
                    case 3:
                        $edstr='доставка вне города';
                        break;
                    }
                    if( isset($edstr) and strlen($edstr) ){
                        echo '<div><strong>Доставка: </strong>'.$edstr.'</div>';
                    }
                }
                break;
            case 'nothing':
                if( isset( $nd->field_delete['und'][0]['value'] ) and $nd->field_delete['und'][0]['value']==1 ){
                    echo '<div class="lineico canceled">Сделка отменена...</div>';
                }else{
                    if( !isset( $nd->field_agree1['und'][0]['value'] ) or $nd->field_agree1['und'][0]['value']==0 ){
                        if( isset( $row['field_user2'] ) and strlen( $row['field_user2'] ) ){
                            echo '<div class="lineico waiting">Ждем Вашего <br />согласия...</div>';
                        }else{
                            echo '<div class="lineico waiting">Ждем согласия <br />арендодателя...</div>';
                        }
                    }elseif( !isset( $nd->field_agree2['und'][0]['value'] ) or $nd->field_agree2['und'][0]['value']==0 ){
                        if( isset( $row['field_user1'] ) and strlen( $row['field_user1'] ) ){
                            echo '<div class="lineico waiting">Ждем Вашего <br />согласия...</div>';
                        }else{
                            echo '<div class="lineico waiting">Ждем согласия <br />арендатора...</div>';
                        }
                    }else{
                        $outstr='<div class="lineico inprocess">Сделка в процессе</div>';
                        if( isset( $nd->field_end1['und'][0]['value'] ) and $nd->field_end1['und'][0]['value']==1 ){
                            if( isset( $nd->field_end2['und'][0]['value'] ) and $nd->field_end2['und'][0]['value']==1 ){
                                $outstr='<div class="lineico end">Сделка завершена</div>';
                            }else{
                                if( isset( $row['field_user1'] ) and strlen( $row['field_user1'] ) ){
                                    $outstr= '<div class="lineico waiting2">Ждем завершения <br />сделки от Вас...</div>';
                                }else{
                                    $outstr= '<div class="lineico waiting2">Ждем завершения сделки <br />от арендатора...</div>';
                                }
                            }
                        }else{
                            if( isset( $nd->field_end2['und'][0]['value'] ) and $nd->field_end2['und'][0]['value']==1 ){
                                if( isset( $row['field_user1'] ) and strlen( $row['field_user1'] ) ){
                                    $outstr= '<div class="lineico waiting2">Ждем завершения сделки <br />от арендатора...</div>';
                                }else{
                                    $outstr= '<div class="lineico waiting2">Ждем завершения <br />сделки от Вас...</div>';
                                }
                            }                        
                        }
                        echo $outstr;
                    }
                }
                break;
            case 'field_user2':
            case 'field_user1':
                $content=strip_tags($content);
                if( isset( $content ) and is_numeric( $content ) ){
                    $val='Неизвестно';

                    $out=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.$content);
                    $out=$out->fetchAssoc();
                    if( isset($out['field_name_value']) and strlen($out['field_name_value']) ){
                        $val=$out['field_name_value'];
                    }
                    
                    $path=path_load('user/'.$content); if(strlen($path['alias'])) $path=$path['alias']; else $path='user/'.$content;
                    echo '<a href="/'.$path.'">'.$val.'</a>';

                }
                break;
            case 'created':
                $content=strip_tags($content);
                if( is_numeric($content) and $content>0 ){
                    if( date('Y.m.d', $content)==date('Y.m.d', time()) ){
                        echo 'сегодня';
                    }elseif( date('Y.m.d', $content)==date('Y.m.d', time()-86400) ){
                        echo 'вчера';
                    }else{
                        echo '<span class="isdate">'.date('j', $content).' '. pdxneedto_month_declination_ru(format_date($content,'custom','F'),date('n',$content)).'</span>';
                    }
                }else{
                    echo $content;
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
