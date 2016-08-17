<?php


if( isset( $_POST['uid'] ) and is_numeric($_POST['uid']) and $_POST['uid']>0 ){

    if( file_exists('pdxcache/user/'.$_POST['uid'].'_sm') and filesize('pdxcache/user/'.$_POST['uid'].'_sm') ){
        echo @file_get_contents('pdxcache/user/'.$_POST['uid'].'_sm');
        
    }else{
        
        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
        require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        
        global $user; if( $user->uid and $user->uid==$_POST['uid'] ){
            
            $isname='';
            $out='<a href="/user/'.$_POST['uid'].'">';
            
    //        $correct_title=db_query('select n.field_name_value, p.field_items_prokat_value from {field_data_field_name} as n left join {field_data_field_items_prokat} as p on (n.entity_id=p.entity_id and p.entity_type=\'user\') where n.entity_type=\'user\' and n.entity_id='.$_POST['uid']);
            $correct_title=db_query('select n.field_name_value from {field_data_field_name} as n where n.entity_type=\'user\' and n.entity_id='.$_POST['uid']);
            $correct_title=$correct_title->fetchAssoc();
            if( isset($correct_title['field_name_value']) and strlen($correct_title['field_name_value']) ){
                $correct_title['field_name_value']=filter_xss(strip_tags($correct_title['field_name_value']));
                $isname=str_replace("'", '', $correct_title['field_name_value']);
                if( mb_strlen($correct_title['field_name_value'])>17 ){
                    $correct_title['field_name_value']=mb_substr($correct_title['field_name_value'],0,15).'..';
                }
                $out.=$correct_title['field_name_value'];
            }
            $out.='</a> <a title="Редактировать профиль" href="/user/'.$_POST['uid'].'/edit"><img alt="" src="/sites/all/libraries/img/adm_edit.png" /></a> ';
            if(isset($user->roles[6])){ 
//                $num=db_query('select created from {users} where uid='.$user->uid);
                $out.=' <a title="Подтвердите свой e-mail, чтобы учетная запись не была удалена через указанный промежуток времени." class="user_untrast user_untrast_';
                $out.=$user->created;
                $out.='" href="/user/'.$user->uid.'/edit"> ???</a> ';
            }
            

            
            $out.= '<script type="text/javascript"> ';
            if( isset($isname) and strlen($isname) ){
                $out.= 'usN=\''.$isname.'\'; ';
                //jQuery(\'#webform-client-form-1673 .webform-component--name .form-text\').val(usN);
            }

            if(isset($user->roles[6])){
                $out.='
                var ndTime = jQuery(\'.user_untrast\').attr(\'class\').replace(\'user_untrast user_untrast_\', \'\');
                if( ndTime>0 && curd>ndTime ){
                    ndTime=31-parseInt(( parseInt(curd)-parseInt(ndTime))/86400);
                    if( ndTime>0 ){
                        jQuery(\'.user_untrast\').html(\'≈\'+pdxnumfoot(ndTime, \'день\', \'дней\', \'дня\')+\'!\');
                    }else{
                        jQuery(\'.user_untrast\').html(\'≈0 дней!\');
                    }
                }
                
                ';
            }
            
            $tmp='';

            $num=db_query('select field_delete_value from {field_data_field_delete} where entity_type=\'user\' and entity_id='.$_POST['uid']);
            $num=$num->fetchAssoc();
            if( isset($num['field_delete_value']) and $num['field_delete_value']==1 ){
                $out.= ' jQuery(\'#othermsg .cnt\').html(\'Ваша учетная запись удалена! <a href="/user/'.$_POST['uid'].'/edit">Восстановите учетную запись</a>, если хотите, чтобы ваши данные снова появились на сайте\'); jQuery(\'#othermsg\').addClass(\'regshow\'); ';
            }
    
            if( isset($user->roles[3]) or isset($user->roles[4]) ){

                $num=db_query('select COUNT(n.txn_id) from {userpoints_txn} as n where n.description<>\'Бонус за регистрацию\' and n.time_stamp > '. (time()-604800));
                $num=$num->fetchAssoc();
                if( isset($num['COUNT(n.txn_id)']) and $num['COUNT(n.txn_id)']>=0 ){
                    $out.='jQuery(\'.adm_balance\').append(\': '.$num['COUNT(n.txn_id)'].'\'); ';
                }

            }
            
            
    
            $out.= '</script>';
            
    
            if( isset($out) and strlen($out) ){
                echo $out;
                $fp=fopen('pdxcache/user/'.$_POST['uid'].'_sm', "w");
                if($fp){
                    fwrite($fp, $out); fclose($fp);
                }
            }
        
        }

    }
    
    
}


?>