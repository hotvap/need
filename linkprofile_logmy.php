<?php


if( isset( $_POST['uid'] ) and is_numeric($_POST['uid']) and $_POST['uid']>0 ){

    if( file_exists('pdxcache/user/'.$_POST['uid'].'_sm_logmy') and filesize('pdxcache/user/'.$_POST['uid'].'_sm_logmy') ){
        echo @file_get_contents('pdxcache/user/'.$_POST['uid'].'_sm_logmy');
        
    }else{
        
        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
        require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        
        global $user; if( $user->uid and $user->uid==$_POST['uid'] ){
            
            $isname='';
            $out='';

            
            $out.= '<script type="text/javascript"> ';

            $tmp='';
            $tmp.= '<div class="breadcrumbinno"><ul class="profileul">';
//            $tmp.= '<li class="us2"><a href="/user/'.$_POST['uid'].'/edit">Редактировать профиль</a></li>';
            $tmp.= '<li class="us3"><a href="/user/'.$_POST['uid'].'/ulogin" title="Привязать профили социальных сетей">Привязать<span class="mhide2"> соц. сети</span></a></li>';

            $tmp.= '<li class="us2"><a class="ico_msg';
            $num=db_query('select COUNT(i.mid) from {pm_index} as i inner join {pm_message} as m on i.mid=m.mid where i.deleted<>1 and i.type=\'user\' and i.is_new=1 and i.recipient='.$_POST['uid'].' and m.author <> '.$_POST['uid']);
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(i.mid)']) and is_numeric($num['COUNT(i.mid)']) and $num['COUNT(i.mid)']>0 ){
                $tmp.= ' ico_msg_new" title="Есть новые личные сообщения ('.$num['COUNT(i.mid)'].')';
            }else{
                $tmp.= '" title="Личные сообщения';
            }
            $tmp.= '" href="/messages"><span class="mhide">Сообщения</span>';
            $tmp.= ' <span class="count_active" title="Общее количество ваших сообщений">';
            $msgs=0;
            $num=db_query('select COUNT(i.mid) from {pm_index} as i inner join {pm_message} as m on i.mid=m.mid where i.deleted<>1 and i.type=\'user\' and i.recipient='.$_POST['uid'].' and m.author <> '.$_POST['uid']);
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(i.mid)']) and is_numeric($num['COUNT(i.mid)']) ){
                $msgs=$num['COUNT(i.mid)'];
            }
            $tmp.= $msgs;
            $tmp.= '</span></a>';
            $tmp.= '</li>';
            $tmp.= '<li class="us5"><a class="ico_money" href="/user/'.$_POST['uid'].'/points" title="Ваш баланс"><span class="mhide">Баланс</span>';

            $num=db_query('select points from {userpoints_total} where points>0 and uid='.$_POST['uid']);
            $num=$num->fetchAssoc();
            if( isset($num['points']) and is_numeric($num['points']) ){
                $tmp.= ' <span class="count_active">'.$num['points'].'</span>';
            }else{
                $tmp.= ' <span class="count_active">0</span>';
            }
//            $tmp.= ' <span class="addbalance">Пополнить</span>';
            
            $tmp.= '</a></li>';
            $tmp.= '</ul></div>';
            
            $out.=' 
                jQuery(\'.breadcrumbno_first\').html(\''.$tmp.'\');
            
        if( jQuery(\'body.logged-in\').length && curUid>0 ){
            if( jQuery(\'body.page-user-\'+curUid).length && !jQuery(\'body.page-user-edit\').length && !jQuery(\'body.page-user-ulogin\').length && !jQuery(\'body.page-user-points\').length ){
                jQuery(\'.us1 a\').addClass(\'umnuact\');
            }
        }
            
            ';
    

            if( isset($user->roles[3]) or isset($user->roles[4]) ){
                $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_delete} as fd2 on (n.uid=fd2.entity_id and fd2.entity_type=\'user\') inner join {field_data_field_moderate} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fd2.field_delete_value IS NULL or fd2.field_delete_value=0 ) and fb.field_moderate_value=1');
                $num=$num->fetchAssoc();
                if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                    $out.='jQuery(\'.adm_postmod\').append(\': '.$num['COUNT(n.nid)'].'\'); ';
                }

                $allnum=0;
                $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type=\'item\' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) and n.status=0');
                $num=$num->fetchAssoc();
                if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                    $allnum+=$num['COUNT(n.nid)'];
                }
                $num=db_query('select COUNT(n.nid) from {node} as n where n.type=\'findmy\' and n.status=0');
                $num=$num->fetchAssoc();
                if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                    $allnum+=$num['COUNT(n.nid)'];
                }
                if( $allnum>0 ){
                    $out.='jQuery(\'.adm_premod\').append(\': '.$allnum.'\'); ';
                }

            }
            
            
    
            $out.= '</script>';
            
    
            if( isset($out) and strlen($out) ){
                echo $out;
                $fp=fopen('pdxcache/user/'.$_POST['uid'].'_sm_logmy', "w");
                if($fp){
                    fwrite($fp, $out); fclose($fp);
                }
            }
        
        }

    }
    
    
}


?>