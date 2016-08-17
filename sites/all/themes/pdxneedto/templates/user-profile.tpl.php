<?php

/**
 * @file
 * Default theme implementation to present all user profile data.
 *
 * This template is used when viewing a registered member's profile page,
 * e.g., example.com/user/123. 123 being the users ID.
 *
 * Use render($user_profile) to print all profile items, or print a subset
 * such as render($user_profile['user_picture']). Always call
 * render($user_profile) at the end in order to print all remaining items. If
 * the item is a category, it will contain all its profile items. By default,
 * $user_profile['summary'] is provided, which contains data on the user's
 * history. Other data can be included by modules. $user_profile['user_picture']
 * is available for showing the account picture.
 *
 * Available variables:
 *   - $user_profile: An array of profile items. Use render() to print them.
 *   - Field variables: for each field instance attached to the user a
 *     corresponding variable is defined; e.g., $account->field_example has a
 *     variable $field_example defined. When needing to access a field's raw
 *     values, developers/themers are strongly encouraged to use these
 *     variables. Otherwise they will have to explicitly specify the desired
 *     field language, e.g. $account->field_example['en'], thus overriding any
 *     language negotiation rule that was previously applied.
 *
 * @see user-profile-category.tpl.php
 *   Where the html is handled for the group.
 * @see user-profile-item.tpl.php
 *   Where the html is handled for each item in the group.
 * @see template_preprocess_user_profile()
 *
 * @ingroup themeable
 */
?>
<div class="profile"<?php print $attributes; ?>>
  <?php

if( arg(0)=='user' and is_numeric(arg(1)) ){
$usr=prepuser(arg(1));
if( isset($usr->field_delete['und'][0]['value']) and $usr->field_delete['und'][0]['value']==1 ){
    echo '<div class="inmod">Извините, данная учетная запись<br />временно удалена пользователем</div>';
}else{

    $companyname=$usr->field_name['und'][0]['value'];
    if( isset($usr->field_who['und'][0]['value']) and $usr->field_who['und'][0]['value']==2 ){
        if( isset($usr->field_company_name['und'][0]['value']) and strlen($usr->field_company_name['und'][0]['value']) ){
            $companyname=$usr->field_company_name['und'][0]['value'];
        }
    }

    echo '<div class="user_prim admlnk pdxobj'.$usr->uid.' pdxou pdxpp">';


    if( isset( $usr->created ) and is_numeric( $usr->created ) ){
        echo '<div class="user_item_ln"><strong>Дата регистрации: </strong><span class="datereg datereg_'.$usr->created.'">';
        echo date('j',$usr->created).' '.pdxneedto_month_declination_ru(format_date($usr->created,'custom','F'),date('n',$usr->created)).' '.date('Y',$usr->created).' г.';
        echo '</span> <span class="datereg_ago_is">≈ <span class="datereg_ago">???</span> назад</span></div>';
    }
    if( isset( $usr->access ) and is_numeric( $usr->access ) ){
        echo '<div class="user_item_ln"><strong>Последнее посещение: </strong><span class="dateac dateac_'.$usr->access.'">';
        echo date('j',$usr->access).' '.pdxneedto_month_declination_ru(format_date($usr->access,'custom','F'),date('n',$usr->access)).' '.date('Y',$usr->access).' г.';
        echo '</span> <span class="datereg_ago_is">≈ <span class="datereg_ago">???</span> назад</span></div>';
    }
    echo '</div>';



    $merit=array();
    if( isset( $usr->field_merit['und'] ) and is_array( $usr->field_merit['und'] ) and count( $usr->field_merit['und'] ) ){
        foreach( $usr->field_merit['und'] as $val ){
            switch( $val['value'] ){
            case 1: //Контент-менеджер
                $merit[]='<div class="merit_item merit_item_1"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Контент-менеджер <br />сайта</div>';
                break;
            case 2: //Администратор
                $merit[]='<div class="merit_item merit_item_2"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Администратор <br />этого города</div>';
                break;
            case 3: //1 год с нами
                $merit[]='<div class="merit_item merit_item_3"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Более года <br />с нами</div>';
                break;
            case 4: //2 года с нами!
                $merit[]='<div class="merit_item merit_item_4"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Более 2 лет <br />с нами!</div>';
                break;
            case 5: //3 года с нами!!
                $merit[]='<div class="merit_item merit_item_5"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Более 3 лет <br />с нами!!</div>';
                break;
            case 6: //5 лет с нами!!!
                $merit[]='<div class="merit_item merit_item_6"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Более 5 лет <br />с нами!!!</div>';
                break;
            case 7: //Пополнял баланс
                $merit[]='<div class="merit_item merit_item_7"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Пополнял <br />свой баланс</div>';
                break;
            case 8: //Заключал сделки
                $merit[]='<div class="merit_item merit_item_8"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Официально <br />заключал сделки</div>';
                break;
            case 9: //Более 10 сделок
                $merit[]='<div class="merit_item merit_item_9"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Более 10 <br />сделок!</div>';
                break;
            case 10: //Ведет наш менеджер
                $merit[]='<div class="merit_item merit_item_10"><img alt="" src="/sites/all/themes/pdxneedto/img/medal.png" />Аккаунт ведет <br />наш менеджер</div>';
                break;
            }
        }
    }
    if( isset($merit) and is_array($merit) and count($merit) ){
        echo '<div class="meritis"><a target="_blank" href="http://'.PDX_URL_HELP.'/zaslugi-polzovatelya.html" class="smhelp">&nbsp;</a>';
        echo implode('', $merit);
        echo '<div class="clear">&nbsp;</div></div>';
    }
    
//if( isset($user_profile['user_picture']) ){
//    unset($user_profile['user_picture']);
//}
//  print render($user_profile);


if( isset($companyname) and strlen($companyname) and $companyname!=$usr->field_name['und'][0]['value'] ){
    echo '<div class="user_profile_company">Компания <span>'.$companyname.'</span></div>';
    if( isset($usr->field_company_logo['und'][0]['uri']) and strlen($usr->field_company_logo['und'][0]['uri']) ){
        $uri = image_style_url('news_full', $usr->field_company_logo['und'][0]['uri']);
        if(isset($uri) and strlen($uri)){
            echo '<div class="user_profile_left"><img alt="" src="'.$uri.'" /></div>';
        }
    }
}
if( isset($usr->field_about['und'][0]['value']) and strlen($usr->field_about['und'][0]['value']) ){
    echo '<div class="user_about">';

    $ismytext=$usr->field_about['und'][0]['value'];
    if( mb_strpos($ismytext, '<p>')===false and mb_strpos($ismytext, '<li>')===false and mb_strpos($ismytext, '<div>')===false ){
        $ismytext=nl2br($ismytext);
    }
    $ismytext=str_replace('<a ', '<noindex><a rel="nofollow" target="_blank" ', $ismytext);
    $ismytext=str_replace('</a>', '</a></noindex>', $ismytext);
    echo $ismytext;

    echo '</div>';
}
if( isset($usr->field_org_info['und'][0]['value']) and strlen($usr->field_org_info['und'][0]['value']) ){
    echo '<div class="user_profile_block user_profile_time"><div class="item_block_ttl">Реквизиты компании:</div>';
    echo nl2br($usr->field_org_info['und'][0]['value']);
    echo '</div>';
}
if( isset($usr->field_rent_apply6['und'][0]['value']) and strlen($usr->field_rent_apply6['und'][0]['value']) ){
    echo '<div class="user_profile_block user_profile_time"><div class="item_block_ttl">Условия аренды:</div>';

    $ismytext=$usr->field_rent_apply6['und'][0]['value'];
    if( mb_strpos($ismytext, '<p>')===false and mb_strpos($ismytext, '<li>')===false and mb_strpos($ismytext, '<div>')===false ){
        $ismytext=nl2br($ismytext);
    }
    $ismytext=str_replace('<a ', '<noindex><a rel="nofollow" target="_blank" ', $ismytext);
    $ismytext=str_replace('</a>', '</a></noindex>', $ismytext);
    echo $ismytext;

    echo '</div>';
}
if( isset($usr->field_time['und'][0]['value']) and strlen($usr->field_time['und'][0]['value']) ){
    echo '<div class="user_profile_block user_profile_time"><div class="item_block_ttl">Время работы:</div>';
    echo nl2br($usr->field_time['und'][0]['value']);
    echo '</div>';
}




$numcomment=db_query('SELECT COUNT(r.field_rat2_value) FROM {field_data_field_rat2} as r inner join {field_data_field_user2} as i on (r.entity_id=i.entity_id and i.entity_type=\'node\') inner join {node} as n on n.nid=r.entity_id WHERE n.status=1 and r.field_rat2_value>0 and r.entity_type=\'node\' and i.field_user2_uid = '.arg(1));
$numcomment=$numcomment->fetchAssoc();
if( isset($numcomment['COUNT(r.field_rat2_value)']) and is_numeric($numcomment['COUNT(r.field_rat2_value)']) and $numcomment['COUNT(r.field_rat2_value)']>0 ){
    echo '<div id="comments_user1" class="item_block">';
    echo '<div class="item_block_ttl">Отзывы об арендаторе ('.$numcomment['COUNT(r.field_rat2_value)'].'):';

        $cmt=db_query('select field_userrat2_value from {field_data_field_userrat2} where entity_type=\'user\' and entity_id='.arg(1));
        $cmt=$cmt->fetchAssoc();
        if( isset($cmt['field_userrat2_value']) and is_numeric($cmt['field_userrat2_value']) and $cmt['field_userrat2_value']>0 ){
                $cmt['field_userrat2_value']=intval($cmt['field_userrat2_value']/10);
                echo '<div class="user_rat" title="Общий рейтинг арендатора">';
                if( $cmt['field_userrat2_value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat2_value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }

                echo '</div>';
        }
    echo '</div>';
    
    $cmts=db_query('SELECT r.field_rat2_value, e.field_reason2_value, u.uid, u.picture, ur.field_userrat_value, un.field_name_value, n2.nid, n2.title FROM {field_data_field_rat2} as r inner join {field_data_field_item} as i on (r.entity_id=i.entity_id and i.entity_type=\'node\') inner join {node} as n2 on i.field_item_nid=n2.nid inner join {field_data_field_reason2} as e on (r.entity_id=e.entity_id and e.entity_type=\'node\') inner join {field_data_field_user2} as u1 on (r.entity_id=u1.entity_id and u1.entity_type=\'node\') inner join {field_data_field_user1} as u2 on (r.entity_id=u2.entity_id and u2.entity_type=\'node\') inner join {users} as u on u2.field_user1_uid=u.uid inner join {field_data_field_userrat} as ur on (u.uid=ur.entity_id and ur.entity_type=\'user\') inner join {field_data_field_name} as un on (u.uid=un.entity_id and un.entity_type=\'user\') inner join {node} as n on n.nid=r.entity_id WHERE n.status=1 and r.field_rat2_value>0 and r.entity_type=\'node\' and u1.field_user2_uid = '.arg(1).' order by n.created desc limit 0,3');
    while( $cmt=$cmts->fetchAssoc() ){

        echo '<div class="cmt_item">';

        echo '<div class="cmt_item_left">';

/*
        if( isset($cmt['field_userrat_value']) and is_numeric($cmt['field_userrat_value']) and $cmt['field_userrat_value']>0 ){
                $cmt['field_userrat_value']=intval($cmt['field_userrat_value']/10);
                echo '<div class="user_rat">';
                if( $cmt['field_userrat_value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                echo '</div>';
            
        }
*/
        
        echo '<div class="cmt_item_name"';
        if( isset($cmt['picture']) and is_numeric($cmt['picture']) and $cmt['picture']>0 ){
            $uri = file_load($cmt['picture'])->uri;
            if( isset($uri) and strlen($uri) ){
                $uri = image_style_url('user', $uri);
                echo ' style="background: transparent url(\''.$uri.'\') no-repeat left top;"';
            }
        }else{
            echo ' style="background: transparent url(\'/sites/all/themes/pdxneedto/img/nophoto.png\') no-repeat left top;"';
        }
        echo '><a href="/user/'.$cmt['uid'].'">';
        echo $cmt['field_name_value'];
        echo '</a></div>';
        
        echo '</div>';

        echo '<div class="cmt_item_right">';
        if( isset($cmt['field_rat2_value']) and is_numeric($cmt['field_rat2_value']) and $cmt['field_rat2_value']>0 ){
                $cmt['field_rat2_value']=$cmt['field_rat2_value']*2;
                echo '<div class="user_rat">';
                if( $cmt['field_rat2_value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat2_value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                echo '</div>';
        }
        
        echo '<div class="cmt_desc">';
        $path=path_load('node/'.$cmt['nid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$cmt['nid'];
        echo '<a target="_blank" href="/'.$path.'">'.$cmt['title'].'</a><br />';
        if( isset($cmt['field_reason2_value']) and strlen($cmt['field_reason2_value']) ){
            echo nl2br($cmt['field_reason2_value']);
        }
        echo '</div>';

        echo '</div>';
        
        echo '<div class="clear">&nbsp;</div></div>';

    }
        
    echo '</div>';
}





$numcomment=db_query('SELECT COUNT(r.field_rat1_value) FROM {field_data_field_rat1} as r inner join {field_data_field_user1} as i on (r.entity_id=i.entity_id and i.entity_type=\'node\') inner join {node} as n on n.nid=r.entity_id WHERE n.status=1 and r.field_rat1_value>0 and r.entity_type=\'node\' and i.field_user1_uid = '.arg(1));
$numcomment=$numcomment->fetchAssoc();
if( isset($numcomment['COUNT(r.field_rat1_value)']) and is_numeric($numcomment['COUNT(r.field_rat1_value)']) and $numcomment['COUNT(r.field_rat1_value)']>0 ){
    echo '<div id="comments_user1" class="item_block">';
    echo '<div class="item_block_ttl">Отзывы об арендодателе ('.$numcomment['COUNT(r.field_rat1_value)'].'):';

        $cmt=db_query('select field_userrat_value from {field_data_field_userrat} where entity_type=\'user\' and entity_id='.arg(1));
        $cmt=$cmt->fetchAssoc();
        if( isset($cmt['field_userrat_value']) and is_numeric($cmt['field_userrat_value']) and $cmt['field_userrat_value']>0 ){
                $cmt['field_userrat_value']=intval($cmt['field_userrat_value']/10);
                echo '<div class="user_rat" title="Общий рейтинг арендатора">';
                if( $cmt['field_userrat_value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }

                echo '</div>';
        }
    echo '</div>';
    
    $cmts=db_query('SELECT r.field_rat1_value, e.field_reason1_value, u.uid, u.picture, ur.field_userrat2_value, un.field_name_value, n2.nid, n2.title FROM {field_data_field_rat1} as r inner join {field_data_field_item} as i on (r.entity_id=i.entity_id and i.entity_type=\'node\') inner join {node} as n2 on i.field_item_nid=n2.nid inner join {field_data_field_reason1} as e on (r.entity_id=e.entity_id and e.entity_type=\'node\') inner join {field_data_field_user1} as u1 on (r.entity_id=u1.entity_id and u1.entity_type=\'node\') inner join {field_data_field_user2} as u2 on (r.entity_id=u2.entity_id and u2.entity_type=\'node\') inner join {users} as u on u2.field_user2_uid=u.uid inner join {field_data_field_userrat2} as ur on (u.uid=ur.entity_id and ur.entity_type=\'user\') inner join {field_data_field_name} as un on (u.uid=un.entity_id and un.entity_type=\'user\') inner join {node} as n on n.nid=r.entity_id WHERE n.status=1 and r.field_rat1_value>0 and r.entity_type=\'node\' and u1.field_user1_uid = '.arg(1).' order by n.created desc limit 0,3');
    while( $cmt=$cmts->fetchAssoc() ){

        echo '<div class="cmt_item">';

        echo '<div class="cmt_item_left">';

/*
        if( isset($cmt['field_userrat_value']) and is_numeric($cmt['field_userrat_value']) and $cmt['field_userrat_value']>0 ){
                $cmt['field_userrat_value']=intval($cmt['field_userrat_value']/10);
                echo '<div class="user_rat">';
                if( $cmt['field_userrat_value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_userrat_value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                echo '</div>';
            
        }
*/
        
        echo '<div class="cmt_item_name"';
        if( isset($cmt['picture']) and is_numeric($cmt['picture']) and $cmt['picture']>0 ){
            $uri = file_load($cmt['picture'])->uri;
            if( isset($uri) and strlen($uri) ){
                $uri = image_style_url('user', $uri);
                echo ' style="background: transparent url(\''.$uri.'\') no-repeat left top;"';
            }
        }else{
            echo ' style="background: transparent url(\'/sites/all/themes/pdxneedto/img/nophoto.png\') no-repeat left top;"';
        }
        echo '><a href="/user/'.$cmt['uid'].'">';
        echo $cmt['field_name_value'];
        echo '</a></div>';
        
        echo '</div>';

        echo '<div class="cmt_item_right">';
        if( isset($cmt['field_rat1_value']) and is_numeric($cmt['field_rat1_value']) and $cmt['field_rat1_value']>0 ){
                $cmt['field_rat1_value']=$cmt['field_rat1_value']*2;
                echo '<div class="user_rat">';
                if( $cmt['field_rat1_value']>=1 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=2 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=3 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=4 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=5 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=6 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=7 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=8 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']>=9 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                if( $cmt['field_rat1_value']==10 ){ echo '<div class="ratis raty">&nbsp;</div>';
                }else{ echo '<div class="ratis ratn">&nbsp;</div>'; }
    
                echo '</div>';
        }
        
        echo '<div class="cmt_desc">';
        $path=path_load('node/'.$cmt['nid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$cmt['nid'];
        echo '<a target="_blank" href="/'.$path.'">'.$cmt['title'].'</a><br />';
        if( isset($cmt['field_reason1_value']) and strlen($cmt['field_reason1_value']) ){
            echo nl2br($cmt['field_reason1_value']);
        }
        echo '</div>';

        echo '</div>';
        
        echo '<div class="clear">&nbsp;</div></div>';

    }
        
    echo '</div>';
}

if( function_exists('pdxgetshare') ){
    $soc_title=str_replace('"', '', 'Возьми в аренду от арендодателя '.$companyname.' в '.PDX_CITY_NAME2);
    if( mb_strlen($soc_title)>127 ){
        $soc_title=mb_substr($soc_title,0,123).'...';
    }
    $soc_desc='';
    if( isset($usr->field_about['und'][0]['value']) and strlen($usr->field_about['und'][0]['value']) ){
        $soc_desc=str_replace('"', '', strip_tags($usr->field_about['und'][0]['value']));
        if( mb_strlen($soc_desc)>127 ){
            $soc_desc=mb_substr($soc_desc,0,123).'...';
        }
    }
    $soc_img='';
    if(isset($usr->picture->uri) and strlen($usr->picture->uri)){
        $uri = image_style_url('user', $usr->picture->uri);
        if(isset($uri) and strlen($uri)){
            $soc_img= $uri;
        }
    }
    
    echo pdxgetshare($soc_title, $soc_desc, $soc_img, url('user/'.$usr->uid, array('absolute'=>TRUE)));
}
?>

<div class="clear">&nbsp;</div>

<?php


    if( function_exists('pdxgetuseritems') ){
        pdxgetuseritems(arg(1), 1, 0);
    }
}
}

?>
</div>
