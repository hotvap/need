<?php

global $user;

?>
<table <?php if ($classes) { print 'class="'. $classes . '" '; } ?><?php print $attributes; ?>>
   <?php if (!empty($title) || !empty($caption)) : ?>
     <caption><?php if(!empty($caption)){ print $caption; } if(!empty($title)){ print $title; } ?></caption>
  <?php endif; ?>
  <?php if (!empty($header)) : ?>
    <thead>
      <tr>
        <?php foreach ($header as $field => $label):
        if( $field=='field_order_delivery' ){
            continue;
        }elseif( $field=='primary_email' ){
            continue;
        }elseif( $field=='name' ){
            continue;
        }elseif( $field=='billing_street1' ){
            continue;
        }elseif( $field=='billing_street2' ){
            continue;
        }elseif( $field=='billing_city' ){
            continue;
        }elseif( $field=='billing_company' ){
            continue;
        }elseif( $field=='billing_postal_code' ){
            continue;
        }elseif( $field=='country_name' ){
            continue;
        }elseif( $field=='billing_phone' ){
            continue;
        }elseif( $field=='zone_name' ){
            continue;
        }elseif( $field=='value' ){
            continue;
        }
//        if(isset($user->roles[6])){ 
//            if( $field=='order_status' ){
//                continue;
//            }
//        }
        
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
      <tr class="roworder_<?php echo $row['order_id']; ?> statusis<?php
      if( isset($row['order_status']) and strlen($row['order_status']) ){
        switch($row['order_status']){
        case 'Оплачен':
        case 'Завершен':
        case 'Платеж принят':
        case 'PayPal ожидание':
            echo '1';
            break;
        case 'Ждет оплаты':
        case 'Ожидание оплаты':
            echo '2';
            break;
        case 'Новый':
            echo '3';
            break;
        }
        
      }
      echo ' ';
      if ($row_classes[$row_count]) { print implode(' ', $row_classes[$row_count]);  } ?>">
        <?php foreach ($row as $field => $content):
        if( $field=='field_order_delivery' ){
            continue;
        }elseif( $field=='primary_email' ){
            continue;
        }elseif( $field=='name' ){
            continue;
        }elseif( $field=='billing_street1' ){
            continue;
        }elseif( $field=='billing_street2' ){
            continue;
        }elseif( $field=='billing_city' ){
            continue;
        }elseif( $field=='billing_company' ){
            continue;
        }elseif( $field=='billing_postal_code' ){
            continue;
        }elseif( $field=='country_name' ){
            continue;
        }elseif( $field=='billing_phone' ){
            continue;
        }elseif( $field=='zone_name' ){
            continue;
        }elseif( $field=='value' ){
            continue;
        }
//        if(isset($user->roles[6])){ 
//            if( $field=='order_status' ){
//                continue;
//            }
//        }
        ?>
          <td class="<?php echo $field.'_'.$row['order_id'].' '. $field.' '; if ($field_classes[$field][$row_count]) { print $field_classes[$field][$row_count]; } ?>" <?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php
            switch($field){
            case 'order_status':
            if(isset($user->roles[6])){
                switch($content){
                case 'Оплачен':
                case 'Завершен':
                case 'Платеж принят':
                case 'PayPal ожидание':
                    echo '<a class="order_cancel" onclick=" admchange2('.$row['order_id'].', \'order\', \'status_cancel\', \'ok\', \'value\' ); " href="javascript: void(0);">x</a>';
                    break;
                case 'В доставке':
                    echo '<a class="order_ok" onclick=" admchange2('.$row['order_id'].', \'order\', \'status_ok\', \'ok\', \'value\' ); " href="javascript: void(0);">ok</a>';
                    break;
                }
            }else{
                echo '<select onchange=" admchange('.$row['order_id'].', \'order\', \'status\', jQuery(this).val(), \'value\' ); ">';
                $vals=db_query('select order_status_id, title from {uc_order_statuses} order by weight asc');
                while( $val=$vals->fetchAssoc() ){
                    echo '<option value="'.$val['order_status_id'].'"';
                    if( $val['title']==$content ){
                        echo ' selected="selected"';
                    }
                    echo '>'.$val['title'].'</option>';
                }
                echo '</select>';
            }
                break;
            case 'payment_method':
                if( isset($row['field_order_delivery']) and strlen($row['field_order_delivery']) ){
                    print '<div>'.$row['field_order_delivery'].'</div>';
                }
                if( isset($content) and strlen($content) ){
                    print '<div>'.$content.'</div>';
                }
                break;
            case 'billing_full_name':
                if( isset($row['name']) and strlen($row['name']) ){
                    print '<p><strong>Логин:</strong> '.$row['name'].'</p>';
                }
                if( isset($row['primary_email']) and strlen($row['primary_email']) ){
                    print '<p>'.$row['primary_email'].'</p>';
                }
                if( isset($row['billing_phone']) and strlen($row['billing_phone']) ){
                    print '<p><strong>Телефон:</strong> ';
                    if( isset($row['value']) and strlen($row['value']) ){
                        echo $row['value'].' ';
                    }
                    echo $row['billing_phone'].'</p>';
                }
                $street='';
                if( isset($row['billing_company']) and strlen($row['billing_company']) ){
                    if( strlen($street) ){
                        $street.=', ';
                    }
                    $street.=$row['billing_company'];
                }
/*
                if( isset($row['zone_name']) and strlen($row['zone_name']) ){
                    if( strlen($street) ){
                        $street.=', ';
                    }
                    $street.=$row['zone_name'];
                }
                if( isset($row['country_name']) and strlen($row['country_name']) ){
                    if( strlen($street) ){
                        $street.=', ';
                    }
                    $street.=$row['country_name'];
                }
*/
                if( isset($row['billing_city']) and strlen($row['billing_city']) ){
                    if( strlen($street) ){
                        $street.=', ';
                    }
                    $street.=$row['billing_city'];
                }
                if( isset($row['billing_street1']) and strlen($row['billing_street1']) ){
                    if( strlen($street) ){
                        $street.=', ';
                    }
                    $street.=$row['billing_street1'];
                }
                if( isset($row['billing_street2']) and strlen($row['billing_street2']) ){
                    if( strlen($street) ){
                        $street.=', ';
                    }
                    $street.=$row['billing_street2'];
                }
                if( isset($row['billing_postal_code']) and strlen($row['billing_postal_code']) ){
                    if( strlen($street) ){
                        $street.=', ';
                    }
                    $street.=$row['billing_postal_code'];
                }
                if( isset($street) and strlen($street) ){
                    print '<p><strong>Адрес:</strong> '.$street.'</p>';
                }

                if( isset($content) and strlen($content) ){
                    print '<p><strong>ФИО:</strong> '.$content.'</p>';
                }
                break;
            case 'field_order_vk':
                if( isset($content) and is_numeric($content) and $content==1 ){
                    echo '<img alt="" src="/sites/all/libraries/img/adm/order_vk.png" />';
                }
                break;
            case 'field_order_track':
                if( isset($content) and is_numeric($content) and $content==1 ){
                    echo '<img alt="" src="/sites/all/libraries/img/adm/order_t.png" />';
                }
                break;
            case 'product_count':
                $isneg=0;
                echo '<ul class="product_count">';
                if( isset($row['order_id']) and is_numeric($row['order_id']) ){
                    $nids=db_query('select u.title, u.model, u.nid, u.price, s.stock from {uc_order_products} as u left join {uc_product_stock} as s on u.model=s.sku where u.order_id='.$row['order_id']);
                    while( $nid=$nids->fetchAssoc() ){
                        echo '<li><a href="/node/'.$nid['nid'].'"><strong>'.$nid['model'].'</strong> '.$nid['title'];
                        if( isset($nid['stock']) ){
                            echo ' <span>('.$nid['stock'].')</span>';
                            if( $nid['stock']<=0 ){
                                $isneg=1;
                            }
                        }
                        echo '</a><span>'.uc_currency_format($nid['price']).'</span></li>';
                    }
                }
                echo '</ul>';
                if( $isneg ){
                    echo '<script type="text/javascript"> jQuery(document).ready(function($){ 
                        jQuery(\'.roworder_'.$row['order_id'].'\').addClass(\'orderisneg\');
                    }); </script>';
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
