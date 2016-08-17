<?php


if( isset($_POST['nid']) and is_numeric($_POST['nid']) and $_POST['nid']>0 ){

    define('DRUPAL_ROOT', getcwd());
    
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

    global $user; if( $user->uid ){ 
        
        $nids=array();
        $nums=db_query('select n.nid, n.created from {node} as n inner join {field_data_field_item} as i on (n.nid=i.entity_id and i.entity_type=\'node\') inner join {field_data_field_user1} as u1 on (n.nid=u1.entity_id and u1.entity_type=\'node\') inner join {field_data_field_user2} as u2 on (n.nid=u2.entity_id and u2.entity_type=\'node\') where n.type=\'deal\' and n.status=1 and (u1.field_user1_uid='.$user->uid.' or u2.field_user2_uid='.$user->uid.') and i.field_item_nid='.$_POST['nid']);
        while( $num=$nums->fetchAssoc() ){
            $nids[$num['created']]=$num['nid'];
        }
        if( isset($nids) and is_array($nids) and count($nids) ){
            ksort($nids);
            echo '<ul>';
            foreach( $nids as $date=>$nid ){
                echo '<li class="';
                $status='завершена';
                $status2='is_yes';
                
                $isset=db_query('select n.nid from {node} as n inner join {field_data_field_delete} as f on (n.nid=f.entity_id and f.entity_type=\'node\') where f.field_delete_value=1 and n.type=\'deal\' and n.nid='.$nid);
                $isset=$isset->fetchAssoc();
                if( isset( $isset['nid'] ) and is_numeric($isset['nid']) ){
                    $status='отказ';
                    $status2='is_no';
                }else{
                    $isset=db_query('select n.nid from {node} as n inner join {field_data_field_agree1} as f1 on (n.nid=f1.entity_id and f1.entity_type=\'node\') inner join {field_data_field_agree2} as f2 on (n.nid=f2.entity_id and f2.entity_type=\'node\') where f1.field_agree1_value=1 and f2.field_agree2_value=1 and n.type=\'deal\' and n.nid='.$nid);
                    $isset=$isset->fetchAssoc();
                    if( isset( $isset['nid'] ) and is_numeric($isset['nid']) ){
                        $isset=db_query('select n.nid from {node} as n inner join {field_data_field_end1} as f1 on (n.nid=f1.entity_id and f1.entity_type=\'node\') inner join {field_data_field_end2} as f2 on (n.nid=f2.entity_id and f2.entity_type=\'node\') where f1.field_end1_value=1 and f2.field_end2_value=1 and n.type=\'deal\' and n.nid='.$nid);
                        $isset=$isset->fetchAssoc();
                        if( isset( $isset['nid'] ) and is_numeric($isset['nid']) ){
                        }else{
                            $status='ждем завершения';
                        }
                    }else{
                        $status='ждем согласия';
                        $status2='is_wait';
                    }
                }
                
                echo $status2;
                echo '">';
                echo date('j', $date) .' '. mb_strtolower(pdxneedto_month_declination_ru(format_date($date,'custom','F'),date('n',$date))) .' '. date('Y', $date).' года';
                echo '<br />';
                echo '<strong>'.$status.'</strong>. <a target="_blank" href="/node/'.$nid.'">Подробнее...</a>';
                
                echo '</li>';
            }
            echo '</ul>';
        }else{
            echo 'Пока еще не было сделок =(';
        }
        
    }else{
        echo 'Требуется авторизация!';
    }

}

?>