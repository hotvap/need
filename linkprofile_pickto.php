<?php


if( isset( $_POST['uid'] ) and is_numeric($_POST['uid']) and $_POST['uid']>0 ){

    if( file_exists('pdxcache/user/'.$_POST['uid'].'_sm_pickto') and filesize('pdxcache/user/'.$_POST['uid'].'_sm_pickto') ){
        echo @file_get_contents('pdxcache/user/'.$_POST['uid'].'_sm_pickto');
        
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
            $tmp.= '<li class="us4 item_on_all"><a href="/user/item/active">Встречи</a></li>';

            $tmp.= '<li class="us6"><a class="deals_ok" href="/user/deals" title="Заявки на участие в ваших встречах"><span class="mhide2">Заявки</span> <span class="count_active">';

            $iswaitmsg='';
            $iswaitnid=$iswaitcount1=$iswaitcount2=0;
            $num=db_query('select COUNT(n.nid) from {node} as n inner join {field_data_field_item} as i on (n.nid=i.entity_id and i.entity_type=\'node\') inner join {node} as n2 on n2.nid=i.field_item_nid where n.type=\'deal\' and n.status=1 and n.uid='.$_POST['uid']);
            $num=$num->fetchAssoc();
            if( isset( $num['COUNT(n.nid)'] ) and $num['COUNT(n.nid)']>0 ){
                $iswaitcount1=$num['COUNT(n.nid)'];
                $tmp.= $num['COUNT(n.nid)'];
            }else{
                $tmp.= '0';
            }
            $tmp.='</span></a> <a title="Ваши заявки на участие во встречах" href="/user/deals/you"><span class="count_mod">';
            $num=db_query('select COUNT(n.nid) from {node} as n inner join {field_data_field_item} as i on (n.nid=i.entity_id and i.entity_type=\'node\') inner join {node} as n2 on n2.nid=i.field_item_nid where n.type=\'deal\' and n.status=1 and n2.uid='.$_POST['uid']);
            $num=$num->fetchAssoc();
            if( isset( $num['COUNT(n.nid)'] ) and $num['COUNT(n.nid)']>0 ){
                $iswaitcount2=$num['COUNT(n.nid)'];
                $tmp.= $num['COUNT(n.nid)'];
            }else{
                $tmp.= '0';
            }
            if( $iswaitcount1>0 or $iswaitcount2>0 ){
                $iswait=0;
                $thiswait=db_query('select COUNT(n.nid), n.nid from {node} as n inner join {field_data_field_item} as i on (n.nid=i.entity_id and i.entity_type=\'node\') inner join {node} as n2 on n2.nid=i.field_item_nid left join {field_data_field_agree1} as a on (n.nid=a.entity_id and a.entity_type=\'node\') where n.type=\'deal\' and n.status=1 and n2.uid='.$_POST['uid'].' and (a.field_agree1_value IS NULL or a.field_agree1_value=0)');
                $thiswait=$thiswait->fetchAssoc();
                if( $thiswait['nid'] and is_numeric($thiswait['nid']) ){
                    $iswait=1;
                    $iswaitmsg='Имеются предложения составить компанию, требующие вашего подтверждения или отклонения ('.$thiswait['COUNT(n.nid)'].') <a class="balancego" href="'.url('node/'.$thiswait['nid'], array('absolute'=>true)).'">Перейти к предложению</a>';
                    $iswaitnid=$thiswait['nid'];
                }
                
            }            
            
            $tmp.= '</span></a></li>';
            
            
            $tmp.= '<li class="us2"><a class="ico_msg';
            
            $tmp.= '" title="Личные сообщения';
            $tmp.= '" href="/pm"><span class="mhide">Сообщения</span>';
            $tmp.= ' <span class="count_active" title="Общее количество ваших сообщений">';
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
            $tmp.= '</a>  <span class="addbalance" title="Пополнить баланс">&nbsp;</span>';
            
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
        jQuery(\'body.page-user-'.$_POST['uid'].' .myuserlink\').show();
            
            ';

            if( isset($iswaitmsg) and strlen($iswaitmsg) and isset($iswaitnid) and is_numeric($iswaitnid) ){
                $out.= 'if( !jQuery(\'#node-'.$iswaitnid.'\').length && !jQuery(\'body.page-pm\').length && !jQuery(\'body.page-user-deals\').length ){ jQuery(\'#othermsg .cnt\').html(\''.$iswaitmsg.'\'); jQuery(\'#othermsg\').addClass(\'regshow\'); }';
            }    
            
            
            $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=1 and n.type=\'item\' and n.uid='.$_POST['uid'].' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 )');
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                $out.='jQuery(\'.item_on_active\').append(\' <span title="Количество ваших активных встреч">( '.$num['COUNT(n.nid)'].' )</span>\'); ';
                $out.='jQuery(\'.item_on_all\').append(\' <span title="Количество ваших активных встреч"> <a class="count_active" href="/user/item/active">'.$num['COUNT(n.nid)'].'</a></span>\'); ';
            }
            $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.status=0 and n.type=\'item\' and n.uid='.$_POST['uid'].' and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 )');
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                $out.='jQuery(\'.item_on_prokat\').append(\' <span title="Количество ваших встреч на модерации">( '.$num['COUNT(n.nid)'].' )</span>\'); ';
                $out.='jQuery(\'.item_on_all\').append(\'<span title="Количество ваших встреч на модерации"> <a class="count_mod" href="/user/item/mod">'.$num['COUNT(n.nid)'].'</a></span>\'); ';
            }
            $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type=\'item\' and n.uid='.$_POST['uid'].' and (fd.field_delete_value=1 or fb.field_block_value=1) ');
            $num=$num->fetchAssoc();
            if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                $out.='jQuery(\'.item_on_del\').append(\' <span title="Количество ваших завершенных встреч">( '.$num['COUNT(n.nid)'].' )</span>\'); ';
                $out.='jQuery(\'.item_on_all\').append(\'<span title="Количество ваших завершенных встреч"> <a class="count_del" href="/user/item/del">'.$num['COUNT(n.nid)'].'</a> </span>\'); ';
            }            

            $flag_node='0';
            if( is_dir('pdxcache/udata/fav/node/'.$user->uid.'/'.$_SERVER['HTTP_HOST']) ){
                $aflag=scandir('pdxcache/udata/fav/node/'.$user->uid.'/'.$_SERVER['HTTP_HOST']);
                $isfav=array();
                if( isset($aflag) and is_array($aflag) and count($aflag) ){
                    foreach( $aflag as $file ){
                        if( is_numeric($file) ){
                            $out.=' favmenode.push('.$file.'); ';
                        }
                    }
                    $flag_node = count($aflag)-2;
                    if( $flag_node<0 ){
                        $flag_node=0;
                    }
                }
            }
            $out.=' window.setTimeout(" favmecheck(\'node\'); ", 999);';
            $flag_user='0';
            if( is_dir('pdxcache/udata/fav/user/'.$user->uid.'/'.$_SERVER['HTTP_HOST']) ){
                $aflag=scandir('pdxcache/udata/fav/user/'.$user->uid.'/'.$_SERVER['HTTP_HOST']);
                if( isset($aflag) and is_array($aflag) and count($aflag) ){
                    foreach( $aflag as $file ){
                        if( is_numeric($file) ){
                            $out.=' favmeuser.push('.$file.'); ';
                        }
                    }
                    $flag_user = count($aflag)-2;
                    if( $flag_user<0 ){
                        $flag_user=0;
                    }
                }
            }
            $out.=' window.setTimeout(" favmecheck(\'user\'); ", 999);';
            
            $out.='jQuery(\'.item_on_fav\').append(\' <span class="favnodecount" title="Избранные предложения">'.$flag_node.'</span>\'); ';
            $out.='jQuery(\'.item_on_fav_user\').append(\' <span class="favusercount" title="Предложения пользователей, на которых вы подписаны">'.$flag_user.'</span>\'); ';
            
            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/settins/fav_auto/autodelevents_'.$user->uid) ){
                $out.=' jQuery(\'#autodelevents\').attr(\'checked\', \'checked\'); ';
            }
            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/settins/fav_event/eventmeevents_'.$user->uid) ){
                $out.=' jQuery(\'#eventmeevents\').attr(\'checked\', \'checked\'); ';
            }
            if( file_exists('pdxcache/'.$_SERVER['HTTP_HOST'].'/settins/fav_sms/smsmeevents_'.$user->uid) ){
                $out.=' jQuery(\'#smsmeevents\').attr(\'checked\', \'checked\'); ';
            }

            
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
                            var confirmtext1=\'Вы действительно хотите поднять данную встречу наверх? <span class="confirmyes" onclick=" bonus_ontop(nnid, \'+"\'.bonus_ontop\'"+\'); jQuery(\'+"\'.closeconfirm\'"+\').click(); ">&nbsp;</span>\';
                            jQuery(\'.breadcrumbno_two ul\').append(\'<li><a class="bonus_ontop bonus_ontop_\'+nnid+\'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext1); "><span class="mhide">Поднять </span>наверх!</a><a target="_blank" href="http://'.PDX_URL_HELP.'/podnyatie-obyavleniya-naverh.html" class="smhelp">&nbsp;</a></li>\');
                        }
                    }
                }
                
                if( nnid>0 ){
                    if( jQuery(\'#content .node_premium\').length ){
                        var confirmtext2=\'Вы действительно хотите продлить статус Premium для данной встречи? <span class="confirmyes" onclick=" bonus_premium(nnid, \'+"\'.bonus_premium\'"+\'); jQuery(\'+"\'.closeconfirm\'"+\').click(); ">&nbsp;</span>\';
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
                        jQuery(\'.breadcrumbno_two ul\').append(\'<li><a class="bonus_premium bonus_premium_\'+nnid+\'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext2); ">\'+ttl+\'</a><a target="_blank" href="http://'.PDX_URL_HELP.'/prisvoenie-obyavleniyu-statusa-premium.html" class="smhelp">&nbsp;</a></li><li class="acta"><a href="/node/add/item?clone=\'+nnid+\'">Клон<span class="mhide2">ировать</span><span class="mhide"> встречу</span></a><a target="_blank" href="http://'.PDX_URL_HELP.'/klonirovanie-obyavleniy.html" class="smhelp">&nbsp;</a></li><li class="acta"><a href="/node/\'+nnid+\'/edit">Ред<span class="mhide2">актировать</span><span class="mhide"> встречу</span></a></li>\');
                    }else{
                        var confirmtext2=\'Вы действительно хотите сделать данную встречу Premium? <span class="confirmyes" onclick=" bonus_premium(nnid, \'+"\'.bonus_premium\'"+\'); jQuery(\'+"\'.closeconfirm\'"+\').click(); ">&nbsp;</span>\';
                        jQuery(\'.breadcrumbno_two ul\').append(\'<li><a class="bonus_premium bonus_premium_\'+nnid+\'" href="javascript: void(0);" onclick=" confirmbonus(confirmtext2); "><span class="mhide2">Сделать </span><span class="mhide">встречу </span>Premium!</a><a target="_blank" href="http://'.PDX_URL_HELP.'/prisvoenie-obyavleniyu-statusa-premium.html" class="smhelp">&nbsp;</a></li><li class="acta"><a href="/node/add/item?clone=\'+nnid+\'">Клон<span class="mhide2">ировать</span><span class="mhide"> встречу</span></a><a target="_blank" href="http://'.PDX_URL_HELP.'/klonirovanie-obyavleniy.html" class="smhelp">&nbsp;</a></li><li class="acta"><a href="/node/\'+nnid+\'/edit">Ред<span class="mhide2">актировать</span><span class="mhide"> встречу</span></a></li>\');
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
                $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_delete} as fd2 on (n.uid=fd2.entity_id and fd2.entity_type=\'user\') inner join {field_data_field_moderate} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type IN (\'item\', \'event\') and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fd2.field_delete_value IS NULL or fd2.field_delete_value=0 ) and fb.field_moderate_value=1');
                $num=$num->fetchAssoc();
                if( isset($num['COUNT(n.nid)']) and $num['COUNT(n.nid)']>=0 ){
                    $out.='jQuery(\'.adm_postmod\').append(\': '.$num['COUNT(n.nid)'].'\'); ';
                }

                $allnum=0;
                $num=db_query('select COUNT(n.nid) from {node} as n left join {field_data_field_delete} as fd on (n.nid=fd.entity_id and fd.entity_type=\'node\') left join {field_data_field_block} as fb on (n.nid=fb.entity_id and fb.entity_type=\'node\') where n.type IN (\'item\', \'event\') and ( fd.field_delete_value IS NULL or fd.field_delete_value=0 ) and ( fb.field_block_value IS NULL or fb.field_block_value=0 ) and n.status=0');
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
            
            $out.=' var ismy_field_interes=new Array(); ';
            $isouts=array();
            $nums=db_query('select field_interes_value from {field_data_field_interes} where entity_type=\'user\' and entity_id='.$_POST['uid']);
            while( $num=$nums->fetchAssoc() ){
                $isouts[]=$num['field_interes_value'];
            }
            if( isset($isouts) and is_array($isouts) and count($isouts) ){
                foreach($isouts as $isout ){
                    $out.=' ismy_field_interes.push('.$isout.'); ';
                }
            }
            
            $out.=' var ismy_field_movie=new Array(); ';
            $isouts=array();
            $nums=db_query('select field_movie_value from {field_data_field_movie} where entity_type=\'user\' and entity_id='.$_POST['uid']);
            while( $num=$nums->fetchAssoc() ){
                $isouts[]=$num['field_movie_value'];
            }
            if( isset($isouts) and is_array($isouts) and count($isouts) ){
                foreach($isouts as $isout ){
                    $out.=' ismy_field_movie.push('.$isout.'); ';
                }
            }
            
            $out.=' var ismy_field_music=new Array(); ';
            $isouts=array();
            $nums=db_query('select field_music_value from {field_data_field_music} where entity_type=\'user\' and entity_id='.$_POST['uid']);
            while( $num=$nums->fetchAssoc() ){
                $isouts[]=$num['field_music_value'];
            }
            if( isset($isouts) and is_array($isouts) and count($isouts) ){
                foreach($isouts as $isout ){
                    $out.=' ismy_field_music.push('.$isout.'); ';
                }
            }
            
    
            $out.= '</script>';
            
            $apm=array();
            if( file_exists('pdxcache/pm/'.$user->uid.'/allow') ){
                $data=file_get_contents('pdxcache/pm/'.$user->uid.'/allow');
                if( $data ){
                    $data=unserialize($data);
                    if( isset($data) and is_array($data) and count($data) ){
                        $apm=$data;
                    }
                }
            }
            if( isset($apm) and is_array($apm) and count($apm) ){
                foreach( $apm as $ap=>$data ){
                    if( file_exists('pdxcache/pm/'.$user->uid.'/from/'.$ap) and file_exists('pdxcache/pm/'.$user->uid.'/to/'.$ap) ){
                    }else{
                        unset($apm[$ap]);
                    }
                }
            }
            
            if( isset($apm) and is_array($apm) and count($apm) ){
                $chatimgs=array();
                
                $img=PDX_IMGPATH.'/img/nouser.png';
                $num=db_query('select field_ava_fid from {field_data_field_ava} where entity_type=\'user\' and entity_id='.$user->uid);
                $num=$num->fetchAssoc();
                if( isset($num['field_ava_fid']) and is_numeric($num['field_ava_fid']) ){
                    $uri = file_load($num['field_ava_fid'])->uri;
                    $uri = image_style_url('user', $uri);
                    if(isset($uri) and strlen($uri)){
                        $img=$uri;
                    }
                }else{
                    $num=db_query('select field_sex_value from {field_data_field_sex} where entity_type=\'user\' and entity_id='.$user->uid);
                    $num=$num->fetchAssoc();
                    if( isset($num['field_sex_value']) and is_numeric($num['field_sex_value']) ){
                        if( $num['field_sex_value']==1 ){
                            $img=PDX_IMGPATH.'/img/nouser1.png';
                        }elseif( $num['field_sex_value']==0 ){
                            $img=PDX_IMGPATH.'/img/nouser2.png';
                        }
                    }
                }
                $chatimgs[$user->uid]=$img;
                
                
                $out.= '<div id="mychat" onclick="stpr(event || window.event);">';
                $out.= '<div id="mychat_body">';
                foreach( $apm as $ap=>$data ){
                    if( file_exists('pdxcache/pm/'.$user->uid.'/from/'.$ap) and file_exists('pdxcache/pm/'.$user->uid.'/to/'.$ap) ){
                    }else{
                        continue;
                    }
                    $who='';
                    $num=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.$ap);
                    $num=$num->fetchAssoc();
                    if( isset($num['field_name_value']) and strlen($num['field_name_value']) ){
                        $who=$num['field_name_value'];
                    }
                    
                    $out.= '<div class="mychat_item mychat_item_'.$ap.'"><div class="mychat_item2" onclick=" chatme('.$ap.'); "><span class="name" style="display: none;">'.$who.'</span>';
                    
                    $img=PDX_IMGPATH.'/img/nouser.png';
                    $num=db_query('select field_ava_fid from {field_data_field_ava} where entity_type=\'user\' and entity_id='.$ap);
                    $num=$num->fetchAssoc();
                    if( isset($num['field_ava_fid']) and is_numeric($num['field_ava_fid']) ){
                        $uri = file_load($num['field_ava_fid'])->uri;
                        $uri = image_style_url('user', $uri);
                        if(isset($uri) and strlen($uri)){
                            $img=$uri;
                        }
                    }else{
                        $num=db_query('select field_sex_value from {field_data_field_sex} where entity_type=\'user\' and entity_id='.$ap);
                        $num=$num->fetchAssoc();
                        if( isset($num['field_sex_value']) and is_numeric($num['field_sex_value']) ){
                            if( $num['field_sex_value']==1 ){
                                $img=PDX_IMGPATH.'/img/nouser1.png';
                            }elseif( $num['field_sex_value']==0 ){
                                $img=PDX_IMGPATH.'/img/nouser2.png';
                            }
                        }
                    }
                    $out.= '<img alt="" src="'.$img.'" />';
                    $chatimgs[$ap]=$img;
                    
                    $out.= '<div class="chat_fortitle" style="display: none;"><div class="ttl"><a href="'.url('user/'.$user->uid, array('absolute'=>true)).'">'.$who.'</a></div><div class="more">';
                    $ismytitle='';
                    if( isset($data['item']) and is_numeric($data['item']) ){
                        $num=db_query('select title from {node} where nid='.$data['item']);
                        $num=$num->fetchAssoc();
                        if( isset($num['title']) and strlen($num['title']) ){
                            $ismytitle=$num['title'];
                        }
                    }
                    switch( $data['type'] ){
                        case 'deal':
                            $out.= 'Приглашен на ';
                            if( isset($ismytitle) and strlen($ismytitle) ){
                                $out.= 'встречу <a href="'.url('node/'.$data['item'], array('absolute'=>true)).'">'.$ismytitle.'</a>';
                            }else{
                                $out.= '<a href="'.url('node/'.$data['item'], array('absolute'=>true)).'">встречу</a>';
                            }
                            if( isset($data['deal']) and is_numeric($data['deal']) ){
                                $out.= '. Смотреть <a href="'.url('node/'.$data['deal'], array('absolute'=>true)).'">приглашение</a>.';
                            }
                            break;
                        case 'item':
                            $out.= 'Автор ';
                            if( isset($ismytitle) and strlen($ismytitle) ){
                                $out.= 'встречи <a href="'.url('node/'.$data['item'], array('absolute'=>true)).'">'.$ismytitle.'</a>';
                            }else{
                                $out.= '<a href="'.url('node/'.$data['item'], array('absolute'=>true)).'">встречи</a>';
                            }
                            if( isset($data['deal']) and is_numeric($data['deal']) ){
                                $out.= '. Смотреть <a href="'.url('node/'.$data['deal'], array('absolute'=>true)).'">вашу заявку</a>.';
                            }
                            break;
                    }
                    $out.= '</div></div>';
                    $out.= '<span title="offline" class="ustat ustat_'.$ap.'"></span></div></div>';
                }
                $out.= '</div>';
                $out.= '<div class="chatop';
                $out.= '" onclick="chatop();">&nbsp;</div>';
                $out.= '<div id="chatimgs" style="display: none;">';
                if( isset($chatimgs) and is_array($chatimgs) and count($chatimgs) ){
                    foreach($chatimgs as $tmpid=>$tmpval ){
                        $out.= '<div id="ichatimg_'.$tmpid.'">'.$tmpval.'</div>';
                    }
                }
                $out.= '</div>';
                $out.= '</div><script type="text/javascript"> chatstatus(); checkonline(); var chatmecook = getCookie(\'chatme\'); if( chatmecook && chatmecook>0 ){ chatme(chatmecook); } </script>';
            }
            $out.= '<div id="chat_noti" onclick="stpr(event || window.event);">';
            $out.= '</div>';
/*
            $out.=' <script type="text/javascript"> 
            
            
            var sns = new AWS.SNS({accessKeyId: \'AKIAINQMGXGXRZBOOHLA\', secretAccessKey: \'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\', region: \'us-east-1\'});
            var params = {
              Name: \'pickto_\'+curCity+\'_'.$user->uid.'\'
            };
            sns.createTopic(params, function(err, data) {
              if (err){}else{
                myTopic=data.TopicArn;
                sns.subscribe( {Protocol: \'http\', TopicArn: myTopic, Endpoint: \'http://sns.us-east-1.amazonaws.com\' }, function(err, data) {
                  if (err){}else{
                    window.alert(data.SubscriptionArn);
                  }
                });
              } 
            });
            </script> ';
*/            
// window.setInterval(\'chatstat()\', 1111);
            $out.=' <script type="text/javascript"> window.setTimeout(\'chatstat(); notiWait();\', 2333); window.setInterval(\'checkonline()\', 333333);  </script> ';
            
    
            if( isset($out) and strlen($out) ){
                echo $out;
                $fp=fopen('pdxcache/user/'.$_POST['uid'].'_sm_pickto', "w");
                if($fp){
                    fwrite($fp, $out); fclose($fp);
                }
            }
        
        }

    }
}


?>