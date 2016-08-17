<?php


if( isset( $_POST['uid'] ) and is_numeric($_POST['uid']) and $_POST['uid']>0 ){

    if( file_exists('pdxcache/user/'.$_POST['uid'].'_sm_needto') and filesize('pdxcache/user/'.$_POST['uid'].'_sm_needto') ){
        echo @file_get_contents('pdxcache/user/'.$_POST['uid'].'_sm_needto');
        
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
            $tmp.= '<li class="us4 item_on_all"><a href="/user/item/active">Объяв<span class="mhide">ления</span></a></li>';

            $tmp.= '<li class="us7"><a class="ico_findmy" href="/user/findmy">Ищу<span class="mhide"> вещь</span></a>';
            $tmp.= ' <span class="count_active" title="Ваши заявки на поиск вещи">';
            $msgs=0;
            $num=db_query('select COUNT(nid) from {node} where status=1 and type=\'findmy\' and uid='.$_POST['uid']);
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(nid)']) and is_numeric($num['COUNT(nid)']) ){
                $tmp.= $num['COUNT(nid)'];
            }
            $tmp.= '</span>';
            $tmp.= '</li>';

            $tmp.= '<li class="us6"><a class="deals_ok" href="/user/deals" title="Оформленные вами сделки"><span class="mhide">Сделки</span> <span class="count_active">0</span></a></li>';
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
                $tmp.= ' <span class="count_active" title="Ваши средства">'.$num['points'].'</span>';
            }else{
                $tmp.= ' <span class="count_active">0</span>';
            }
            $tmp.= '</a> <span class="addbalance" title="Пополнить баланс">&nbsp;</span>';
            
            $tmp.= '</li>';
            $tmp.= '</ul></div>';
            
            $out.=' 
                jQuery(\'.breadcrumbno_first\').html(\''.$tmp.'\');
                jQuery(\'.addbalance\').bind(\'click\', 
                    function(){
                        jQuery(\'#countbalance\').val(30); jQuery(\'#addbalance\').show(); jQuery(this).hide();
                    }
                );
            
        if( jQuery(\'body.logged-in\').length && curUid>0 ){
            if( jQuery(\'body.page-user-\'+curUid).length && !jQuery(\'body.page-user-edit\').length && !jQuery(\'body.page-user-ulogin\').length && !jQuery(\'body.page-user-points\').length ){
                jQuery(\'.us1 a\').addClass(\'umnuact\');
            }
        }
            
            ';
    

            $num=db_query('select COUNT(n.nid) from {node} as n inner join {field_data_field_user1} as u1 on (n.nid=u1.entity_id and u1.entity_type=\'node\') inner join {field_data_field_user2} as u2 on (n.nid=u2.entity_id and u2.entity_type=\'node\') left join {field_data_field_delete} as d on (n.nid=d.entity_id and d.entity_type=\'node\') where n.type=\'deal\' and n.status=1 and (u1.field_user1_uid='.$_POST['uid'].' or u2.field_user2_uid='.$_POST['uid'].') and (d.field_delete_value IS NULL or d.field_delete_value=0)');
            $num=$num->fetchAssoc();
            if( isset( $num['COUNT(n.nid)'] ) and $num['COUNT(n.nid)']>0 ){
                $iswait=0;
                $iswaitmsg='';
                $iswaitnid=0;
                $thiswait=db_query('select n.nid from {node} as n inner join {field_data_field_user1} as u1 on (n.nid=u1.entity_id and u1.entity_type=\'node\') left join {field_data_field_agree1} as a on (n.nid=a.entity_id and a.entity_type=\'node\') left join {field_data_field_delete} as d on (n.nid=d.entity_id and d.entity_type=\'node\') where n.type=\'deal\' and n.status=1 and u1.field_user1_uid='.$_POST['uid'].' and (a.field_agree1_value IS NULL or a.field_agree1_value=0) and (d.field_delete_value IS NULL or d.field_delete_value=0)');
                $thiswait=$thiswait->fetchAssoc();
                if( $thiswait['nid'] and is_numeric($thiswait['nid']) ){
                    $iswait=1;
                    $path=path_load('node/'.$thiswait['nid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$thiswait['nid'];
                    $iswaitmsg='Имеются сделки, требующие вашего подтверждения как арендодателя! <a class="balancego" href="/'.$path.'">Подробнее...</a>';
                    $iswaitnid=$thiswait['nid'];
                }
                $thiswait=db_query('select n.nid from {node} as n inner join {field_data_field_user2} as u1 on (n.nid=u1.entity_id and u1.entity_type=\'node\') left join {field_data_field_agree2} as a on (n.nid=a.entity_id and a.entity_type=\'node\') left join {field_data_field_delete} as d on (n.nid=d.entity_id and d.entity_type=\'node\') where n.type=\'deal\' and n.status=1 and u1.field_user2_uid='.$_POST['uid'].' and (a.field_agree2_value IS NULL or a.field_agree2_value=0) and (d.field_delete_value IS NULL or d.field_delete_value=0)');
                $thiswait=$thiswait->fetchAssoc();
                if( $thiswait['nid'] and is_numeric($thiswait['nid']) ){
                    $iswait=1;
                    $path=path_load('node/'.$thiswait['nid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$thiswait['nid'];
                    $iswaitmsg='Имеются сделки, требующие вашего подтверждения как арендатора! <a class="balancego" href="/'.$path.'">Подробнее...</a>';
                    $iswaitnid=$thiswait['nid'];
                }
                $thiswait=db_query('select n.nid from {node} as n inner join {field_data_field_user2} as u1 on (n.nid=u1.entity_id and u1.entity_type=\'node\') left join {field_data_field_end2} as a on (n.nid=a.entity_id and a.entity_type=\'node\') inner join {field_data_field_end1} as a1 on (n.nid=a1.entity_id and a1.entity_type=\'node\') left join {field_data_field_delete} as d on (n.nid=d.entity_id and d.entity_type=\'node\') where n.type=\'deal\' and n.status=1 and u1.field_user2_uid='.$_POST['uid'].' and (a.field_end2_value IS NULL or a.field_end2_value=0) and (d.field_delete_value IS NULL or d.field_delete_value=0) and a1.field_end1_value=1');
                $thiswait=$thiswait->fetchAssoc();
                if( $thiswait['nid'] and is_numeric($thiswait['nid']) ){
                    $iswait=1;
                    $path=path_load('node/'.$thiswait['nid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$thiswait['nid'];
                    $iswaitmsg='Имеются сделки, требующие вашего завершения как арендатора! <a class="balancego" href="/'.$path.'">Подробнее...</a>';
                    $iswaitnid=$thiswait['nid'];
                }
                $thiswait=db_query('select n.nid from {node} as n inner join {field_data_field_user1} as u1 on (n.nid=u1.entity_id and u1.entity_type=\'node\') left join {field_data_field_end1} as a on (n.nid=a.entity_id and a.entity_type=\'node\') inner join {field_data_field_end2} as a1 on (n.nid=a1.entity_id and a1.entity_type=\'node\') left join {field_data_field_delete} as d on (n.nid=d.entity_id and d.entity_type=\'node\') where n.type=\'deal\' and n.status=1 and u1.field_user1_uid='.$_POST['uid'].' and (a.field_end1_value IS NULL or a.field_end1_value=0) and (d.field_delete_value IS NULL or d.field_delete_value=0) and a1.field_end2_value=1');
                $thiswait=$thiswait->fetchAssoc();
                if( $thiswait['nid'] and is_numeric($thiswait['nid']) ){
                    $iswait=1;
                    $path=path_load('node/'.$thiswait['nid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$thiswait['nid'];
                    $iswaitmsg='Имеются сделки, требующие вашего завершения как арендодателя! <a class="balancego" href="/'.$path.'">Подробнее...</a>';
                    $iswaitnid=$thiswait['nid'];
                }
                
                if( $iswait ){
                    $out.= 'jQuery(\'.us6 a\').addClass(\'deals_wait\');';
                }
                $out.= 'jQuery(\'.us6 .count_active\').html(\''.$num['COUNT(n.nid)'].'\');';
                if( isset($iswaitmsg) and strlen($iswaitmsg) and isset($iswaitnid) and is_numeric($iswaitnid) ){
                    $out.= 'if( !jQuery(\'#node-'.$iswaitnid.'\').length && !jQuery(\'body.page-user-deals\').length ){ jQuery(\'#othermsg .cnt\').html(\''.$iswaitmsg.'\'); jQuery(\'#othermsg\').addClass(\'regshow\'); }';
                }    
                
            }
    
            $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=1 and n.type=\'item\' and n.uid='.$_POST['uid'].' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 )');
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                $out.='jQuery(\'.item_on_active\').append(\' <span title="Количество ваших активных объявлений">( '.$num['COUNT(n.nid)'].' )</span>\'); ';
                $out.='jQuery(\'.item_on_all\').append(\' <span title="Количество ваших активных объявлений"> <a class="count_active" href="/user/item/active">'.$num['COUNT(n.nid)'].'</a></span>\'); ';
            }
            $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=0 and n.type=\'item\' and n.uid='.$_POST['uid'].' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 )');
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                $out.='jQuery(\'.item_on_prokat\').append(\' <span title="Количество ваших объявлений на модерации">( '.$num['COUNT(n.nid)'].' )</span>\'); ';
                $out.='jQuery(\'.item_on_all\').append(\'<span title="Количество ваших объявлений на модерации"> <a class="count_mod" href="/user/item/mod">'.$num['COUNT(n.nid)'].'</a></span>\'); ';
            }
            $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type=\'item\' and n.uid='.$_POST['uid'].' and (fd.field_delete_value=1 or fb.field_block_value=1) ');
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                $out.='jQuery(\'.item_on_del\').append(\' <span title="Количество ваших удаленных объявлений">( '.$num['COUNT(n.nid)'].' )</span>\'); ';
                $out.='jQuery(\'.item_on_all\').append(\'<span title="Количество ваших удаленных объявлений"> <a class="count_del" href="/user/item/del">'.$num['COUNT(n.nid)'].'</a> </span>\'); ';
            }
            
            $flag_node='0';
            if( is_dir('pdxcache/udata/fav/node/'.$user->uid.'/'.$_SERVER['HTTP_HOST']) ){
                $aflag=scandir('pdxcache/udata/fav/node/'.$user->uid.'/'.$_SERVER['HTTP_HOST']);
                if( isset($aflag) and is_array($aflag) and count($aflag) ){
                    foreach( $aflag as $file ){
                        if( is_numeric($file) ){
                            $out.=' window.setTimeout(" favbuild(\'node\', '.$file.'); ", 999);';
                        }
                    }
                    $flag_node = count($aflag)-2;
                    if( $flag_node<0 ){
                        $flag_node=0;
                    }
                }
            }
            $flag_user='0';
            if( is_dir('pdxcache/udata/fav/user/'.$user->uid.'/'.$_SERVER['HTTP_HOST']) ){
                $aflag=scandir('pdxcache/udata/fav/user/'.$user->uid.'/'.$_SERVER['HTTP_HOST']);
                if( isset($aflag) and is_array($aflag) and count($aflag) ){
                    foreach( $aflag as $file ){
                        if( is_numeric($file) ){
                            $out.=' window.setTimeout(" favbuild(\'user\', '.$file.'); ", 999);';
                        }
                    }
                    $flag_user = count($aflag)-2;
                    if( $flag_user<0 ){
                        $flag_user=0;
                    }
                }
            }
            
            $out.='jQuery(\'.item_on_fav\').append(\' <span><span class="favnodecount" title="Избранные объявления">'.$flag_node.'</span>/<a class="favusercount" href="/fav/users" title="Избранные пользователи">'.$flag_user.'</a></span>\'); ';
            
            $out.='

            var dt = new Date();
            dt=parseInt(dt.getTime()/1000);

            if( jQuery(\'#node_for_time\').length && jQuery(\'.breadcrumbno_two\').length && jQuery(\'.node_uid_\'+curUid).length ){
                jQuery(\'.breadcrumbno_two\').show();
                
                var nnid=jQuery(\'#content div.node-item\').attr(\'id\').replace(\'node-\', \'\');
                
                var ndTime = jQuery(\'#node_for_time\').attr(\'class\').replace(\'node-container node_time_\', \'\');
                if( ndTime>0 ){
                    var dt = new Date();
                    dt=parseInt(dt.getTime()/1000);
                    if( dt>0 && (dt-ndTime)>133333 ){
                        if( nnid>0 ){
                            var confirmtext1=\'Вы действительно хотите поднять данное объявление наверх? <span class="confirmyes" onclick=" bonus_ontop(nnid, \'+"\'.bonus_ontop\'"+\'); jQuery(\'+"\'.closeconfirm\'"+\').click(); ">&nbsp;</span>\';
                            jQuery(\'.breadcrumbno_two ul\').append(\'<li><a class="bonus_ontop bonus_ontop_\'+nnid+\'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext1); "><span class="mhide">Поднять </span>наверх!</a><a target="_blank" href="http://'.PDX_URL_HELP.'/podnyatie-obyavleniya-naverh.html" class="smhelp">&nbsp;</a></li>\');
                        }
                    }
                }
                
                if( nnid>0 ){
                    if( jQuery(\'#content .node_premium\').length ){
                        var confirmtext2=\'Вы действительно хотите продлить статус Premium для данного объявления? <span class="confirmyes" onclick=" bonus_premium(nnid, \'+"\'.bonus_premium\'"+\'); jQuery(\'+"\'.closeconfirm\'"+\').click(); ">&nbsp;</span>\';
                        var ttl=\'Продлить Premium!\';
                        if( jQuery(\'.node_premium_ico\').length ){
                            var prem=jQuery(\'.node_premium_ico\').attr(\'class\').replace(\'node_premium_ico node_premium_\', \'\');
                            if( prem>0 ){
                                if( dt>0 && dt<prem ){
                                    prem=parseInt((prem-dt)/86400);
                                    prem++;
                                    ttl=\'Premium \'+dtPre+\' \'+pdxnumfoot(prem, \'день\', \'дней\', \'дня\')+\'. Продлить?\';
                                }
                            }
                        }
                        jQuery(\'.breadcrumbno_two ul\').append(\'<li><a class="bonus_premium bonus_premium_\'+nnid+\'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext2); ">\'+ttl+\'</a><a target="_blank" href="http://'.PDX_URL_HELP.'/prisvoenie-obyavleniyu-statusa-premium.html" class="smhelp">&nbsp;</a></li><li class="acta"><a href="/node/add/item?clone=\'+nnid+\'">Клон<span class="mhide2">ировать</span><span class="mhide"> объявление</span></a><a target="_blank" href="http://'.PDX_URL_HELP.'/klonirovanie-obyavleniy.html" class="smhelp">&nbsp;</a></li><li class="acta"><a href="/node/\'+nnid+\'/edit">Ред<span class="mhide2">актировать</span><span class="mhide"> объявление</span></a></li>\');
                    }else{
                        var confirmtext2=\'Вы действительно хотите сделать данное объявление Premium? <span class="confirmyes" onclick=" bonus_premium(nnid, \'+"\'.bonus_premium\'"+\'); jQuery(\'+"\'.closeconfirm\'"+\').click(); ">&nbsp;</span>\';
                        jQuery(\'.breadcrumbno_two ul\').append(\'<li><a class="bonus_premium bonus_premium_\'+nnid+\'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext2); "><span class="mhide2">Сделать </span><span class="mhide">объявление </span>Premium!</a><a target="_blank" href="http://'.PDX_URL_HELP.'/prisvoenie-obyavleniyu-statusa-premium.html" class="smhelp">&nbsp;</a></li><li class="acta"><a href="/node/add/item?clone=\'+nnid+\'">Клон<span class="mhide2">ировать</span><span class="mhide"> объявление</span></a><a target="_blank" href="http://'.PDX_URL_HELP.'/klonirovanie-obyavleniy.html" class="smhelp">&nbsp;</a></li><li class="acta"><a href="/node/\'+nnid+\'/edit">Ред<span class="mhide2">актировать</span><span class="mhide"> объявление</span></a></li>\');
                    }
                }
            }
            ';
            
            $isgold=db_query('select field_gold_value from {field_data_field_gold} where entity_type=\'user\' and entity_id='.$_POST['uid']);
            $isgold=$isgold->fetchAssoc();
            if( isset($isgold['field_gold_value']) and is_numeric($isgold['field_gold_value']) and $isgold['field_gold_value']>time() ){
                $out.= '
                var bonus='.$isgold['field_gold_value'].';
                if( dt>0 && dt<bonus ){
                    bonus=parseInt((bonus-dt)/86400);
                    bonus++;
                    jQuery(\'.bonus_gold\').html(\'≈ \'+pdxnumfoot(bonus, \'день\', \'дней\', \'дня\')+\'. Продлить?\');
                }
                ';
            }
    
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

                $num=db_query('select COUNT(o.order_id) from {uc_orders} as o where order_status NOT IN (\'abandoned\', \'in_checkout\', \'completed\')');
                $num=$num->fetchAssoc();
                if( isset($num['COUNT(o.order_id)']) and $num['COUNT(o.order_id)']>=0 ){
                    $out.='jQuery(\'.adm_orders\').append(\': '.$num['COUNT(o.order_id)'].'\'); ';
                }

                $num=db_query('select COUNT(n.nid) from {node} as n where n.status=1 and n.type=\'deal\'');
                $num=$num->fetchAssoc();
                if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                    $out.='jQuery(\'.adm_deals\').append(\': '.$num['COUNT(n.nid)'].'\'); ';
                }

/*
                $num=db_query('select COUNT(n.sid) from {webform_submissions} as n where n.submitted > '. (time()-604800));
                $num=$num->fetchAssoc();
                if( isset($num['COUNT(n.sid)']) and $num['COUNT(n.sid)']>=0 ){
                    $out.='jQuery(\'.adm_webforms\').append(\': '.$num['COUNT(n.sid)'].'\'); ';
                }
*/
                
            }
            
            
    
            $out.= '</script>';
            
    
            if( isset($out) and strlen($out) ){
                echo $out;
                $fp=fopen('pdxcache/user/'.$_POST['uid'].'_sm_needto', "w");
                if($fp){
                    fwrite($fp, $out); fclose($fp);
                }
            }
        
        }

    }
    
    
}


?>