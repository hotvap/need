<?php

if( isset($_POST['nid']) and is_numeric($_POST['nid']) and $_POST['nid']>0 ){
    define('DRUPAL_ROOT', getcwd());
    require_once DRUPAL_ROOT . '/sites/all/themes/pdxneedto/my.inc';
    require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
    
    global $user; if( $user->uid ){
        $isset=db_query('select n.status, n.uid, n.title, d.field_delete_value, b.field_block_value from {node} as n left join {field_data_field_delete} as d on (n.nid=d.entity_id and d.entity_type=\'node\') left join {field_data_field_block} as b on (n.nid=b.entity_id and b.entity_type=\'node\') where n.type=\'item\' and n.nid='.$_POST['nid']);
        $isset=$isset->fetchAssoc();
        if( isset($isset['uid']) ){
            if( $isset['uid']!=$user->uid ){
                if( $isset['status']==1 ){
                    if( isset( $isset['field_delete_value'] ) and $isset['field_delete_value']==1 ){
                        echo 'Данное объявление удалено автором :-(';
                    }else{
                        if( isset( $isset['field_block_value'] ) and $isset['field_block_value']==1 ){
                            echo 'Данное объявление заблокировано модератором :-(';
                        }else{
                            /* --------------------------------- */
                            
        $edit = new stdClass();
        $edit->type = 'deal';
        node_object_prepare($edit);
        $edit->title = html_entity_decode('Сделка');
        $edit->language = LANGUAGE_NONE;
        $edit->field_agree2[$edit->language][0]['value'] = 1;
        $edit->field_item[$edit->language][0]['nid'] = $_POST['nid'];
        $edit->field_user1[$edit->language][0]['uid'] = $isset['uid'];
        $edit->field_user2[$edit->language][0]['uid'] = $user->uid;
        if( isset($_POST['count']) and is_numeric($_POST['count']) and $_POST['count']>0 ){
            $edit->field_period_num[$edit->language][0]['value'] = $_POST['count'];
        }
        if( isset($_POST['deliv']) and is_numeric($_POST['deliv']) and $_POST['deliv']>0 ){
            $edit->field_deliveris[$edit->language][0]['value'] = $_POST['deliv'];
        }
        if( isset($_POST['ed']) and is_numeric($_POST['ed']) and $_POST['ed']>0 ){
            $edit->field_period_ed[$edit->language][0]['value'] = $_POST['ed'];
        }
        if( isset($_POST['zalog']) and strlen($_POST['zalog']) ){
            $_POST['zalog']=filter_xss(strip_tags($_POST['zalog']));
            $edit->field_str1[$edit->language][0]['value'] = $_POST['zalog'];
        }
        if( isset($_POST['date']) and strlen($_POST['date']) ){
            $eventdate=strtotime($_POST['date']);
            if( isset($eventdate) and is_numeric($eventdate) and $eventdate>0 ){
                $edit->field_datend[$edit->language][0]['value'] = date('Y-m-d 00:00:00', $eventdate);
            }
        }

        if($node = node_submit($edit)) {
            node_save($node);
            if($node->nid){

                if( function_exists('pdxcreate_dirty') ){
                    pdxcreate_dirty('admin');
                }

                $ismail=db_query('select u.mail, f.field_name_value from {users} as u left join {field_data_field_name} as f on (u.uid=f.entity_id and f.entity_type=\'user\') where u.uid='.$isset['uid']);
                $ismail=$ismail->fetchAssoc();
                if( isset($ismail['mail']) and strlen($ismail['mail']) and valid_email_address($ismail['mail']) and function_exists('pdxmail') ){
                    $path=path_load('node/'.$_POST['nid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$_POST['nid'];        
                    $usname='';
                    if( isset($ismail['field_name_value']) and strlen($ismail['field_name_value']) ){
                        $usname=', '.$ismail['field_name_value'];
                    }
                    $msg='<p>Здравствуй'.$usname.'.</p>';
                    $msg.='<p>Поступила заявка на аренду товара "'.$isset['title'].'". Ссылка на товар: '.$GLOBALS['base_url'].'/'.$path.'</p>';
                    if( isset($_POST['count']) and is_numeric($_POST['count']) and $_POST['count']>0 and isset($_POST['ed']) and is_numeric($_POST['ed']) and $_POST['ed']>0 ){
                        $edstr='';
                        switch($_POST['ed']){
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
                            $msg.='<p><strong>Период аренды:</strong> '.$_POST['count'].$edstr.' </p>';
                        }
                    }
                    if( isset($eventdate) and is_numeric($eventdate) and $eventdate>0 ){
                        $msg.='<p><strong>Предполагаемая дата начала аренды:</strong> '.date('j', $eventdate).' '.mb_strtolower(pdxneedto_month_declination_ru(format_date($eventdate,'custom','F'),date('n',$eventdate))).' '.date('Y', $eventdate).' года</p>';
                    }
                    $msg.='<p>&nbsp;</p><p>Контакты арендатора:</p>';
                    $path=path_load('user/'.$user->uid); if(strlen($path['alias'])) $path=$path['alias']; else $path='user/'.$user->uid;
                    $msg.='<p><strong>Профиль пользователя:</strong> '.$GLOBALS['base_url'].'/'.$path.'</p>';
                    $out=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.$user->uid);
                    $out=$out->fetchAssoc();
                    if( isset($out['field_name_value']) and strlen($out['field_name_value']) ){
                        $msg.='<p><strong>Имя:</strong> '.$out['field_name_value'].'</p>';
                    }
                    $msg.='<p><strong>Email:</strong> '.$user->mail.'</p>';
            
                    $msg.='<p><strong>Телефоны:</strong>';
                    $outs=db_query('select field_phones_value from {field_data_field_phones} where entity_type=\'user\' and entity_id='.$user->uid);
                    while( $out=$outs->fetchAssoc() ){
                        $msg.='<div>'.$out['field_phones_value'];
                        if( function_exists('pdxclassphone') ){
                            $phonesuf=pdxclassphone($out['field_phones_value']);
                            if( isset($phonesuf) and strlen($phonesuf) ){
                                $msg.=' ('.$phonesuf.')';
                            }
                        }
                        $msg.='</div>';
                    }
                    $msg.='</p>';
                    
                    $out=db_query('select field_skype_value from {field_data_field_skype} where entity_type=\'user\' and entity_id='.$user->uid);
                    $out=$out->fetchAssoc();
                    if( isset($out['field_skype_value']) and strlen($out['field_skype_value']) ){
                        $msg.='<p><strong>Skype:</strong> '.$out['field_skype_value'].'</p>';
                    }
                    $msg.='<p>&nbsp;</p>';
                    $msg.='<p>Пожалуйста, подтверди или откажись от сделки на данной странице: '.$GLOBALS['base_url'].'/node/'.$node->nid.'. После чего не забудь связаться с арендатором.</p>';
                    $msg.='<p>&nbsp;</p>';
                    $msg.='<p>C уважением, '.$_SERVER['HTTP_HOST'].'.</p>';
                    
                    pdxmail($ismail['mail'], $msg, 'Новая заявка на прокат товара '.$_SERVER['HTTP_HOST']);
                    
                }
                echo '<div class="rentsuccess">Спасибо, арендодателю отправлено предложение о сделке, и ваши контакты. В ближайшее время он с вами свяжется.</div><br /><div>Текущее состояние сделки вы всегда можете увидеть в разделе <a href="/user/deals">Мои сделки</a></div>';
                echo '<script type="text/javascript"> 
                if( jQuery(\'.us6 .count_active\').length ){
                    var curd=jQuery(\'.us6 .count_active\').html().replace(\' \', \'\');
                    if( curd>=0 ){
                        jQuery(\'.us6 .count_active\').html(++curd);
                    }
                }
                </script>';

                if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$user->uid.'_sm_needto') ){
                    unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$user->uid.'_sm_needto');
                }
                if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$isset['uid'].'_sm_needto') ){
                    unlink('pdxcache/'.$_SERVER['HTTP_HOST'].'/user/'.$isset['uid'].'_sm_needto');
                }
                
            }
        }
                            /* --------------------------------- */
                        }
                    }
                }else{
                    echo 'Данное объявление на модерации :-(';
                }
            }else{
                echo 'Нельзя заключать сделки с самим собой ;-)';
            }
        }else{
            echo 'Такого объявления не найдено :-(';
        }
    }else{
        echo 'Для заключения сделки необходимо зарегистрироваться на сайте.';
    }

}else{
    echo 'Проблемка :-( Попробуйте еще раз';
}


?>