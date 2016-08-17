<?php
global $user;
$isadm=0;
if(isset($user->roles[3]) or isset($user->roles[4]) ){ 
    $isadm=1;
}

?>
<div id="floatis">

<div id="menublock"><div id="menublockis">
<div class="mnu_profile"><ul class="first">
<?php
if( $user->uid ){ 
    echo '<li id="linkprofile"><a href="/user">Профиль</a></li>';
    echo '<li><a href="/user/logout">Выйти</a></li>';
    echo '</ul><ul>';
    echo '<li class="item_on_fav"><a href="/fav">Избранное</a></li>';
    echo '<li><a class="bonus_gold" href="javascript: void(0);" onclick=" confirmbonus(confirmtext3); ">Хочу Gold-аккаунт!</a><a target="_blank" href="http://'.PDX_URL_HELP.'/pokupka-gold-akkaunta.html" class="smhelp">&nbsp;</a></li>';
}else{
    echo '<li><a href="javascript:void(0);" onclick="onbasic(this, 2);"><span class="thr" style="display: none;"></span>Зарегистрироваться</a> | <a href="javascript:void(0);" onclick="onbasic(this, 1);"><span class="thr" style="display: none;"></span>Войти</a> </li>';
}
?>
</ul>
<?php if($page['undercontent']){ print render($page['undercontent']); } ?>
</div>
<div class="clear clear0">&nbsp;</div></div>

</div>

<?php

    if( $user->uid ){
        echo '<div class="breadcrumbno_first breadcrumbno">';
        echo '</div>';
        if( arg(0)=='user' and is_string(arg(1)) and arg(1)=='item' and is_string(arg(2)) ){
            echo '<div class="breadcrumbno"><div class="breadcrumbinno"><ul>';

            echo '<li class="item_on_prokat">';
            if( arg(2)=='mod' ){
                echo 'На мод<span class="mhide">ерации</span>';
            }else{
                echo '<a href="/user/item/mod';
                echo '">На мод<span class="mhide">ерации</span></a>';
            }
            echo '</li>';

            echo '<li class="item_on_active">';
            if( arg(2)=='active' ){
                echo 'Актив<span class="mhide2">ные</span><span class="mhide"> объявления</span>';
            }else{
                echo '<a href="/user/item/active';
                echo '">Актив<span class="mhide2">ные</span><span class="mhide"> объявления</span></a>';
            }
            echo '</li>';

            echo '<li class="item_on_del">';
            if( arg(2)=='del' ){
                echo 'Удал<span class="mhide2">енные</span><span class="mhide"> объявления</span>';
            }else{
                echo '<a href="/user/item/del';
                echo '">Удал<span class="mhide2">енные</span><span class="mhide"> объявления</span></a>';
            }
            echo '</li>';

            echo '<li>';
            if( arg(2)=='nomod' ){
                echo 'Масс<span class="mhide">овое</span> ред<span class="mhide">актирование</span>';
            }else{
                echo '<a href="/user/item/nomod';
                echo '">Масс<span class="mhide">овое</span> <span class="mhide">редактирование</span></a>';
            }
            echo '</li>';
    
    
            echo '</ul></div></div>';
        }elseif( isset($_SESSION['pdxpdx_node_type']) and $_SESSION['pdxpdx_node_type']=='item' ){
            echo '<div class="breadcrumbno_two breadcrumbno" style="display: none;"><div class="breadcrumbinno"><ul>';
            echo '</ul></div></div>';
        }

    }

?>
<?php
if( $user->uid ){
    $buy=db_query('select u.sell_price, u.nid from {uc_products} as u inner join {node} as n on n.nid=u.nid where n.status=1');
    $buy=$buy->fetchAssoc();
    if( isset($buy['sell_price']) and is_numeric($buy['sell_price']) ){
?>
    <div id="confirmaction" style="display: none;"><div id="confirmactionin"><div id="confirmactioncnt"></div><img class="closeconfirm" onclick=" jQuery(this).parent().parent().hide(); jQuery('#confirmactioncnt').html(''); " alt="x" src="<?php echo PDX_IMGPATH; ?>/img/ico_close.png" /><div class="clear">&nbsp;</div></div></div>
    <div id="addbalance" style="display: none;"><div id="addbalancein">
    <div id="addbalancecnt"><input type="hidden" value="<?php echo intval($buy['sell_price']); ?>" id="nid_price" />
    Пополнить баланс на <input id="countbalance" type="text" value="30" class="form-text" size="3" onfocus=" buyInterval=window.setInterval('buyCheck();', 1333); " onblur=" window.clearInterval(buyInterval); " /> "нид" за<br /> <strong class="balanceresult"><?php echo number_format(30*$buy['sell_price'], 0, '', ' '); ?></strong> <?php
if( defined('PDX_CITY_CUR') ){
    echo PDX_CITY_CUR;
}
    ?> <span class="balancego" onclick=" balancego(<?php echo $buy['nid']; ?>); ">Далее &gt;&gt;</span>
    </div><div class="balance_prim"><a target="blank" class="helpme" href="http://<?php echo PDX_URL_HELP; ?>/kak-mozhno-popolnit-balans-polzovatelya.html" title="Открыть справку в новом окне">&nbsp;</a>* минимум 30 "нид", максимум 10 000 "нид"</div>
    <img class="closebalance" onclick=" jQuery(this).parent().parent().hide(); jQuery('.addbalance').show(); " alt="x" src="<?php echo PDX_IMGPATH; ?>/img/ico_close.png" /><div class="clear">&nbsp;</div></div></div>
<?php
    }
}
?>
<header id="header"><div id="headerin">
<div id="region_topbar" class="region_item">

<?php
if( $user->uid ){
    echo '<section id="block-block-58" class="block"><div class="content"><div class="pre_add_product"><a class="add_product" href=" javascript: void(0); " onclick=" addsel();  ">Сдать в аренду</a><span class="ft">Это бесплатно:)</span></div></div></section>';
}else{
    echo '<section id="block-block-59" class="block"><div class="content"><div class="pre_add_product"><a class="add_product" href=" javascript: void(0); " onclick=" stpr(event || window.event); jQuery(\'#forreg\').addClass(\'regshow\'); return false;  ">Сдать в аренду</a><span class="ft">Это бесплатно:)</span></div></div></section>';
}
?>


<section id="block-block-60" class="block"><div class="content">
<div id="inline_ajax_search_container"><form id="inline-ajax-search-form" method="get" accept-charset="UTF-8" action="/find/"><div>
<input alt="" class="custom-search-button form-submit" id="edit-submit" src="<?php echo PDX_IMGPATH; ?>/img/ico_find.png" type="image" />
<input name="s" id="inline_ajax_search" placeholder="Поиск вещи" type="text" /><input type="hidden" name="brand" value="All" id="search_brand" />
</div></form>
<div id="inline_ajax_search_results_pre" style="display: none;" onclick=" stpr(event || window.event); "><div id="inline_ajax_search_results"></div></div>
</div><div class="showbrand" style="display: none;" onclick="stpr(event || window.event);"></div>
</div></section>

</div>
<div style="display: none;" class="linemesm">&nbsp;</div>
<div class="smline1">
<?php if($logo){ print '<div id="logo-container">';
echo $imagelogo.'</div>'; } ?>
<?php
if( function_exists('pdxgetcitys') and defined('PDX_CITY_NAME') ){
    echo pdxgetcitys();
}

?>
</div><div style="display: none;" class="linemesm2">&nbsp;</div>
<div id="curpanel"><span class="curs"></span><span class="curs2"></span><span class="weat"></span></div>

<div class="clear">&nbsp;</div></div></header>
<div id="catmenu"><div class="catmenuis">
<?php

$terms=db_query('select tid, name from {taxonomy_term_data} where vid=2 order by weight asc');
$podterm=0;
$path="";
if($terms){
//    global $user;
//    $pdxshowhelp=variable_get('pdxshowhelp', 0);
    
    echo '<ul class="menu">';
    $item=0;

    while($term = $terms->fetchAssoc() ){

        $item++;
        
        echo '<li class="li'.$item.' admlnk pdxobj'.$term['tid'].' pdxot pdxpi';
        if(arg(0)=='catalog' and is_numeric(arg(1)) ){
            if( $term['tid']==arg(1) ){
                echo ' active';
            }elseif( isset($_SESSION['pdxpdx_par_part']) and is_numeric($_SESSION['pdxpdx_par_part']) and $_SESSION['pdxpdx_par_part']==$term['tid'] ){
                echo ' active';
            }
        }elseif( isset($_SESSION['pdxpdx_node_tid']) and is_numeric($_SESSION['pdxpdx_node_tid']) and $_SESSION['pdxpdx_node_tid']==$term['tid'] ){
            echo ' active';
        }
        echo '">';

        echo '<a class="level1 mtid'.$term['tid'].'" href="/'.PDXCAT_NAME.'/'.$term['tid'].'"  onclick=" stpr(event || window.event); ">'.$term['name'].'</a>';
        echo '</li>';


    }
    echo '<li class="celebration';
    if( isset($_SESSION['pdxpdx_par_vid']) and $_SESSION['pdxpdx_par_vid']==10 ){
        echo ' active';
    }
    echo '"><a class="level1 mtid0" href="javascript: void(0);" onclick=" stpr(event || window.event); ">Праздники</a></li>';

    echo '<li class="findmy';
    if( arg(0)=='findmy' ){
        echo ' active';
    }
    echo '"><a class="level1" href="/findmy" title="Подать заявку на поиск вещи к аренде">Ищу вещь!</a></li>';
    echo '</ul>';
}
?>

</div>
</div>
<div class="catsubmenu menu" style="display: none;" onclick=" stpr(event || window.event); "></div>
</div>
<?php

/*
if( arg(0)=='user' and is_numeric(arg(1)) and !is_string(arg(2)) ){
    echo '<div id="showmeuser'.arg(1).'" class="showmeuser"></div>';
}elseif( arg(0)=='users' and is_string(arg(1)) and arg(1)=='rents' ){
    if( isset( $_GET['field_rn'][0] ) and is_numeric( $_GET['field_rn'][0] ) ){
        echo '<div id=\'map_canvas_profiles\' style=\'width: 100%; height: 311px; display: none;\'></div><div id="map_canvas_profiles_cnt" style="display: none;"><div class="maps_raion">'.implode('|', $_GET['field_rn']).'</div></div>';
    }elseif( isset( $_GET['field_parts'][0] ) and is_numeric( $_GET['field_parts'][0] ) ){
        echo '<div id=\'map_canvas_profiles\' style=\'width: 100%; height: 311px; display: none;\'></div><div id="map_canvas_profiles_cnt" style="display: none;"><div class="maps_cat">'.implode('|', $_GET['field_parts']).'</div></div>';
    }
}
*/

if( drupal_is_front_page() ){
    echo '<div class="swiper-container swiper-container-front"><div class="swiper-pretitle"><div class="swiper_title_block"><div class="swiper-title">Аренда нужных вещей</div><div class="swiper_text"><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/text24.png" /></div></div></div><div class="swiper-wrapper">';
    $scount=0;

/*
    echo '<div class="swiper-slide swiper-slide-manual" style=" background: #e188b6; "><div class="swiper-slide-in swiper-slide-in-a"><div class="swiper_button">
    <ul class="swiper_q">
    <li>Нет денег на покупку?</li>
    <li>Приехал в отпуск, а в арендуемой квартире НЕТ...?</li>
    </ul>
    <div class="swiper_a"><img alt="" src="'.PDX_IMGPATH.'/img/nophoto.png" />Не мучайся! Возьми в аренду!</div>
    </div><img class="swiper-img" alt="" src="'.PDX_IMGPATH.'/img/a/1.jpg" /></div></div>';
    $scount++;

    echo '<div class="swiper-slide swiper-slide-manual" style=" background: #39cbc6; "><div class="swiper-slide-in swiper-slide-in-a"><div class="swiper_button">
    <ul class="swiper_q">
    <li>Нет денег на покупку?</li>
    <li>Приехал в отпуск, а в арендуемой квартире НЕТ...?</li>
    </ul>
    <div class="swiper_a"><img alt="" src="'.PDX_IMGPATH.'/img/nophoto.png" />Не мучайся! Возьми в аренду!</div>
    </div><img class="swiper-img" alt="" src="'.PDX_IMGPATH.'/img/a/2.jpg" /></div></div>';
    $scount++;

    echo '<div class="swiper-slide swiper-slide-manual" style=" background: #fabf21; "><div class="swiper-slide-in swiper-slide-in-a"><div class="swiper_button">
    <ul class="swiper_q">
    <li>Нет денег на покупку?</li>
    <li>Приехал в отпуск, а в арендуемой квартире НЕТ...?</li>
    </ul>
    <div class="swiper_a"><img alt="" src="'.PDX_IMGPATH.'/img/nophoto.png" />Не мучайся! Возьми в аренду!</div>
    </div><img class="swiper-img" alt="" src="'.PDX_IMGPATH.'/img/a/3.jpg" /></div></div>';
    $scount++;
*/

    echo '<div class="swiper-slide swiper-slide-manual" style=" background: #62d3cf; "><div class="swiper-slide-in swiper-slide-in-a"><div class="swiper_button">
    <ul class="swiper_q">
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />Предлагайте товары к аренде</li>
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />Берите товары в аренду</li>
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />Общайтесь</li>
    </ul>
    </div><img class="swiper-img lazy" alt="" data-original="'.PDX_IMGPATH.'/img/a/a1.png" /></div></div>';
    $scount++;

    echo '<div class="swiper-slide swiper-slide-manual" style=" background: #fabf21; "><div class="swiper-slide-in swiper-slide-in-a"><div class="swiper_button">
    <ul class="swiper_q">
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />Зачем покупать? Возьми в аренду!</li>
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />Путешествуй налегке! Все нужное ты сможешь взять у нас</li>
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />Аренда – это дешево!</li>
    </ul>
    </div><img class="swiper-img lazy" alt="" data-original="'.PDX_IMGPATH.'/img/a/a2.png" /></div></div>';
    $scount++;

    echo '<div class="swiper-slide swiper-slide-manual" style=" background: #57c57e; "><div class="swiper-slide-in swiper-slide-in-a"><div class="swiper_button">
    <ul class="swiper_q">
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />Сомневаешься? Возьми в аренду и попробуй!</li>
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />Нет денег на покупку? Возьми в аренду</li>
    <li><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/yes2.png" />В отпуске нет привычной вещи? Не отказывай себе в удобстве!</li>
    </ul>
    </div><img class="swiper-img lazy" alt="" data-original="'.PDX_IMGPATH.'/img/a/a3.png" /></div></div>';
    $scount++;

    echo '</div><div class="swiper-pagination swiper-pagination-front"></div></div>';
}
?>
<?php
if( arg(0)=='node' and is_string(arg(1)) and arg(1)=='add' and is_string(arg(2)) ){}else{
    if( arg(0)=='fav' ){
        echo '<div class="breadcrumbno"><div class="breadcrumbinno">';
        if( is_string(arg(1)) ){
            if( arg(1)=='users' ){
                echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/izbrannye-polzovateli.html" title="Открыть справку в новом окне">&nbsp;</a>';
            }
        }else{
            echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/izbrannye-obyavleniya.html" title="Открыть справку в новом окне">&nbsp;</a>';
        }
        echo '<ul>';
        echo '<li>';
        if( !is_string(arg(1)) ){
            echo 'Избранные<span class="mhide"> предложения</span>';
        }else{
            echo '<a href="/fav">Избранные<span class="mhide"> предложения</span></a>';
        }
        echo '</li>';

        if( $user->uid ){
            
            echo '<li>';
            if( is_string(arg(1)) and arg(1)=='users' ){
                echo 'Избранные пользователи';
            }else{
                echo '<a href="/fav/users">Избранные пользователи</a>';
            }
            echo '</li>';
        }


        echo '</ul></div></div>';
    }else{
        if($breadcrumb){
            echo '<div id="breadcrumb"><div id="breadcrumbin" xmlns:v="http://rdf.data-vocabulary.org/#">';

            switch( arg(0) ){
            case 'messages':
                echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/otpravka-lichnyh-soobshcheniy.html" title="Открыть справку в новом окне">&nbsp;</a>';
                break;
            case 'findmy':
                echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/podacha-zayavki-na-arendu-veshchi-kotoroy-net-v-kataloge.html" title="Открыть справку в новом окне">&nbsp;</a>';
                break;
            case 'catalog':
                if( is_numeric(arg(1)) ){
                    echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/poisk-nuzhnogo-obyavleniya.html" title="Открыть справку в новом окне">&nbsp;</a>';
                }
                break;
            case 'find':
                echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/poisk-nuzhnogo-obyavleniya.html" title="Открыть справку в новом окне">&nbsp;</a>';
                break;
            case 'node':
                if( is_string(arg(1)) and arg(1)=='add' and is_string(arg(2)) and arg(2)=='item' ){
                    echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/podacha-obyavleniy-s-predlozheniem-o-prokate.html" title="Открыть справку в новом окне">&nbsp;</a>';
                }elseif( isset($_SESSION['pdxpdx_node_type']) and $_SESSION['pdxpdx_node_type']=='item' ){
                    echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/prosmotr-obyavleniy.html" title="Открыть справку в новом окне">&nbsp;</a>';
                }
                break;
            case 'cart':
                if( is_string(arg(1)) and arg(1)=='checkout' and (!is_string(arg(2)) or arg(2)!='complete') ){
                    echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/kak-mozhno-popolnit-balans-polzovatelya.html" title="Открыть справку в новом окне">&nbsp;</a>';
                }
                break;
            }
            if( arg(0)=='item' and is_string(arg(1)) and arg(1)=='viewed' ){}else{
                echo '<span id="related" style="display: none;"><a class="lnk" href="/item/viewed">Просмотренные</a><a target="_blank" href="http://'.PDX_URL_HELP.'/prosmotr-obyavleniy.html#help_viewer" class="smhelp">&nbsp;</a></span> ';
            }
            echo str_replace('>'.t('Home').'<', '>'.PDX_CITY_NAME.'<', $breadcrumb).'</div></div></div>'; }
    }
}

if( isset($_SESSION['pdxpdx_node_type']) and strlen($_SESSION['pdxpdx_node_type']) and $_SESSION['pdxpdx_node_type']=='item' and isset($_SESSION['pdxpdx_node_uid']) and is_numeric($_SESSION['pdxpdx_node_uid']) and !is_string(arg(2)) ){
    if( file_exists('api/other/slogan') ){
        $outslogan=file('api/other/slogan');
        if( isset($outslogan) and is_array($outslogan) and count($outslogan) ){
        echo '<div class="slogan"><div class="sloganin"><span class="slin"><span class="slinqout">«</span>'. trim(str_replace("\r", '',str_replace("\n", '', filter_xss($outslogan[mt_rand(0, count($outslogan)-1)])))) .'»</span></div></div>';
        }
    }
}

?>
<div id="block_center">
<div id="center_content"<?php
$isproductscheme=0;
if( isset($_SESSION['pdxpdx_node_type']) and strlen($_SESSION['pdxpdx_node_type']) and function_exists('uc_product_is_product') and uc_product_is_product($_SESSION['pdxpdx_node_type']) ){
    $isproductscheme=1;
    echo ' itemscope itemtype="http://data-vocabulary.org/Product"';
}
?>>

<?php
$showtab=0;
if( arg(0)!='user' and (isset($user->roles[3]) or isset($user->roles[4])) ){
    $showtab=1;
}
if( arg(0)=='messages' ){
    $showtab=1;
}
if ( $showtab and $tabs): ?><div class="tab-container"><?php print render($tabs); ?></div><?php endif; ?>

<?php

    if( $isadm ){
        echo '<div class="admplace admplace_left"><div class="admplacein"><a title="Очистить кеш данной страницы" href="/clearproductitem.php?url='.urlencode(filter_xss(strip_tags($_SERVER['REQUEST_URI']))).'"><img src="/sites/all/libraries/img/adm_clear.png" /></a></div></div>';
    }

if ($title and isset($_SESSION['pdxpdx_node_type']) and $_SESSION['pdxpdx_node_type']=='item' ){
echo '<div id="site_title"><h1 id="page-title" class="page-title-item">'.html_entity_decode($title).'</h1></div>';
}

print render($title_prefix);
if ($title and !drupal_is_front_page() and ( !isset($_SESSION['pdxpdx_node_type']) or ($_SESSION['pdxpdx_node_type']!='item' and $_SESSION['pdxpdx_node_type']!='deal') ) ){
echo '<div id="site_title">';

    if( function_exists('pdx_change_title') ){
        $tmptitle=pdx_change_title($title);
        if( strlen($tmptitle) ){
            $title=$tmptitle;
        }
    }
    
    if( arg(0)=='cart' and is_string(arg(1)) and arg(1)=='checkout' and (!is_string(arg(2)) or arg(2)!='complete') ){
        print '<h1 id="page-title">Пополнение баланса на ';
        
        $display_items = entity_view('uc_cart_item', uc_cart_get_contents(), 'cart');
        foreach (element_children($display_items['uc_cart_item']) as $key) {
            if( isset($display_items['uc_cart_item'][$key]['#entity']->qty) ){
                echo $display_items['uc_cart_item'][$key]['#entity']->qty.' "нид" ('.number_format($display_items['uc_cart_item'][$key]['#entity']->qty*$display_items['uc_cart_item'][$key]['#entity']->sell_price, 0, '', ' ');
                if( defined( 'PDX_CITY_CUR' ) ){
                    echo ' '.PDX_CITY_CUR;
                }
                echo ')';
                break;
            }
        }
        
        print '</h1>';
        
        if( $user->uid ){
            $isset=db_query('select order_id, order_total from {uc_orders} where order_status IN (\'pending\') and uid='.$user->uid.' order by created desc limit 0,1');
            $isset=$isset->fetchAssoc();
            if( isset( $isset['order_total'] ) and is_numeric($isset['order_total']) ){
                echo '<div class="wrnblock">';
                echo 'Своего завершения еще ожидает ваша заявка на пополнение баланса на сумму <strong>'.number_format($isset['order_total'], 0, '', ' ').'</strong>';
                if( defined( 'PDX_CITY_CUR' ) ){
                    echo ' '.PDX_CITY_CUR;
                }
                echo '<br />Вы уверены, что хотите создать еще одну заявку?';
                echo '</div>';
            }
        }
        echo '<div class="wrnblock">Во время Beta-тестирования (пока на логотипе отображается строчка «БЕТА-версия») пополнение баланса любыми способами не поддерживается. Но вы можете пополнить баланс своего аккаунта бесплатно, <a href="http://'.PDX_URL_HELP.'/kak-poluchit-nidy-besplatno.html">воспользовавшись одним из доступных способов</a>.</div>';
                
    }elseif( arg(0)=='user' ){
        if( is_numeric(arg(1)) ){
            if( is_string(arg(2)) ){
                if( ( arg(2)=='edit' or arg(2)=='ulogin' ) ){
                    echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/zapolnenie-svoego-profilya.html" title="Открыть справку в новом окне">&nbsp;</a>';
                }elseif( arg(2)=='points' ){
                    echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/o-nidah-i-balanse-polzovatelya.html" title="Открыть справку в новом окне">&nbsp;</a>';
                }
            }
            print '<h1 id="page-title" class="text_user_'.arg(1).'">Пользователь <span>';
            if( isset($_SESSION['pdxpdx_user_name']) and strlen($_SESSION['pdxpdx_user_name']) ){
                echo $_SESSION['pdxpdx_user_name'];
                drupal_set_title($_SESSION['pdxpdx_user_name'].': анкета пользователя');
            }
            print '</span>';
            if( is_string(arg(2)) ){
                switch(arg(2)){
                case 'edit':
                    echo ': редактирование профиля';
                    break;
                case 'ulogin':
                    echo ': привязка соц. сетей';
                    break;
                case 'points':
                    echo ': баланс';
                    break;
                }
            }
            print '</h1>';
            
            if( $user->uid ){
                $path=path_load('user/'.$user->uid); if(strlen($path['alias'])) $path=$path['alias']; else $path='user/'.$user->uid;
                echo '<div class="myuserlink">Ссылка на Ваш профиль: <input type="text" value="'.$GLOBALS['base_url'].'/'.$path.'" size="23" class="form-text" onclick=" jQuery(this).select(); " /></div>';
            }
        }elseif( is_string(arg(1)) ){
            if( arg(1)=='item' ){
                echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/podacha-obyavleniy-s-predlozheniem-o-prokate.html" title="Открыть справку в новом окне">&nbsp;</a>';
                print '<h1 id="page-title">'.$title.'</h1>';
            }elseif( arg(1)=='findmy' ){
                echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/podacha-zayavki-na-arendu-veshchi-kotoroy-net-v-kataloge.html" title="Открыть справку в новом окне">&nbsp;</a>';
                print '<h1 id="page-title">'.$title.'</h1>';
            }
        }
    }else{
        
        if( arg(0)=='node' and is_string(arg(1)) and arg(1)=='add' and is_string(arg(2)) and arg(2)=='item' ){
            echo '<a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/podacha-obyavleniy-s-predlozheniem-o-prokate.html" title="Открыть справку в новом окне">&nbsp;</a>';
        }


        
        $correct_title='';
        $pdxseo=array();
        $pdxseo_title='';
        $curpath=implode('/',arg());
    
        if($pdxseo=variable_get('pdxseo')){
            $pdxseo=unserialize($pdxseo);
            if(isset($pdxseo[urlencode($curpath)]['h1']) and strlen($pdxseo[urlencode($curpath)]['h1'])){
                $pdxseo_title=$pdxseo[urlencode($curpath)]['h1'];
            }
        }
        if(strlen($pdxseo_title)){
            if($pdxseo_title!='none') $correct_title=$pdxseo_title;
    
        }else{
            $correct_title=strip_tags(htmlspecialchars_decode($title));
        }
        
        if( strlen($correct_title)){
            if( arg(0)=='taxonomy' and is_string(arg(1)) and arg(1)=='term' and is_numeric(arg(2)) ){
                if( isset($_SESSION['pdxpdx_par_vid']) and $_SESSION['pdxpdx_par_vid']==6 ){
                    if( defined('PDX_CITY_NAME') ){
                        $correct_title='<span>'.PDX_CITY_NAME.':</span> '.$correct_title.'. ТОП-100 арендодателей';
                    }
                }
            }
            if(isset($user->roles[3]) and isset($_SESSION['pdxpdx_node_type']) and isset($_SESSION['pdxpdx_node_nid']) and is_numeric($_SESSION['pdxpdx_node_nid']) ){
                echo '<div>';
                echo '<div class="edittitle" style="display: none;"><span id="pe_'.$_SESSION['pdxpdx_node_nid'].'_title"></span><input size="23" maxlength="27"  type="text" class="form-text" id="e_'.$_SESSION['pdxpdx_node_nid'].'_title" onchange=\'edittitle('.$_SESSION['pdxpdx_node_nid'].',"title")\' value="';
                echo strip_tags(htmlspecialchars_decode($title));
                echo '" /></div>';
                
                print '<h1 id="page-title"';
                if( isset($isproductscheme) and $isproductscheme==1 ){
                    echo ' itemprop="name"';
                }
                echo '>';
                echo $correct_title;
                echo '<a href="javascript:void(0);" onclick=" jQuery(this).parent().hide(); jQuery(this).parent().parent().find(\'.edittitle\').show(); "><img alt="ред" title="Редактировать" src="/sites/all/libraries/img/adm_edit.png" /></a>';
                print '</h1>';
                echo '</div>';
            }else{
                if( arg(0)=='node' and is_string(arg(1)) and arg(1)=='add' and is_string(arg(2)) and arg(2)=='item' ){
                    if( isset($_GET['part']) and is_numeric( $_GET['part'] ) and $_GET['part']>0 ){
                        $name=db_query('select name from {taxonomy_term_data} where tid='.$_GET['part']);
                        $name=$name->fetchAssoc();
                        if( isset($name['name']) and strlen($name['name']) ){
                            $correct_title='Добавить предложение о сдаче в аренду в раздел <span>'.$name['name'].'</span>';
                        }
                    }elseif( isset($_GET['clone']) and is_numeric( $_GET['clone'] ) and $_GET['clone']>0 ){
                        $name=db_query('select d.name from {field_data_taxonomy_catalog} as t inner join {taxonomy_term_data} as d on t.taxonomy_catalog_tid=d.tid where t.entity_type=\'node\' and t.entity_id='.$_GET['clone'].' limit 0,1');
                        $name=$name->fetchAssoc();
                        if( isset($name['name']) and strlen($name['name']) ){
                            $correct_title='Добавить предложение о сдаче в аренду в раздел <span>'.$name['name'].'</span>';
                        }
                    }
                }
                
                print '<h1';
                if( isset($isproductscheme) and $isproductscheme==1 ){
                    echo ' itemprop="name"';
                }
                echo ' id="page-title">';

                if( isset($_SESSION['pdxpdx_par_tid']) and is_numeric($_SESSION['pdxpdx_par_tid']) and isset($_SESSION['pdxpdx_par_vid']) ){
                    if($_SESSION['pdxpdx_par_vid']==2 ){
                        echo '<img class="ttlimg lazy" alt="" data-original="http://d4zbhil5kxgyr.cloudfront.net/static/part/i'.arg(1).'.png" />';
                    }elseif( $_SESSION['pdxpdx_par_vid']==8 or $_SESSION['pdxpdx_par_vid']==1){
                        echo '<img class="ttlimg lazy" alt="" data-original="http://d4zbhil5kxgyr.cloudfront.net/static/part/i'.arg(2).'.png" />';
                    }
                }

                if( mb_strpos($correct_title, ': ')===false ){}else{
                    $correct_title='<span>'.str_replace(': ', ':</span> ', $correct_title);
                }
                if( is_string(arg(1)) and ((arg(0)=='users' and arg(1)=='rents') or (arg(0)=='taxonomy' and arg(1)=='term' )) and isset( $_GET['cat'] ) and is_numeric($_GET['cat']) ){
                    $name=db_query('select name from {taxonomy_term_data} where tid='.$_GET['cat']);
                    $name=$name->fetchAssoc();
                    if( isset($name['name']) and strlen($name['name']) ){
                        $correct_title.=', '.$name['name'];
                    }
                }
                echo $correct_title;
                print '</h1>';
            }
        }
    
    }

echo '</div>';
}
print render($title_suffix);

if($page['sidebar_first']){ print render($page['sidebar_first']); } ?>
<?php if($page['sidebar_second']){ print render($page['sidebar_second']); } ?>

<div id="main_content"><div id="main_content_post" class="<?php if($page['sidebar_first']){ echo ' main_content_post_left'; } ?><?php if($page['sidebar_second']){ echo ' main_content_post_right'; } ?>">

<?php

if( $user->uid and arg(0)=='messages' and !is_string(arg(1)) ){
    $out='';

    $anid=array();
    $nums=db_query('select f1.field_user1_uid as uid1, f2.field_user2_uid as uid2, u1.field_name_value as name1, u2.field_name_value as name2, n1.field_company_name_value as company1, n2.field_company_name_value as company2 from {field_data_field_user1} as f1 inner join {field_data_field_user2} as f2 on (f1.entity_id=f2.entity_id and f2.bundle=\'deal\') inner join {field_data_field_name} as u1 on (f1.field_user1_uid=u1.entity_id and u1.entity_type=\'user\') inner join {field_data_field_name} as u2 on (f2.field_user2_uid=u2.entity_id and u2.entity_type=\'user\') left join {field_data_field_company_name} as n1 on (f1.field_user1_uid=n1.entity_id and n1.entity_type=\'user\') left join {field_data_field_company_name} as n2 on (f2.field_user2_uid=n2.entity_id and n2.entity_type=\'user\') where f1.bundle=\'deal\' and (f1.field_user1_uid='.$user->uid.' or f2.field_user2_uid='.$user->uid.')');
    while( $num=$nums->fetchAssoc() ){
        if( $num['uid1']!=$user->uid ){
            if( isset( $num['company1'] ) and strlen($num['company1']) and $num['company1']!=$num['name1'] ){
                $num['name1'].=' ('.$num['company1'].')';
            }
            $anid[$num['uid1']]=$num['name1'];
        }
        if( $num['uid2']!=$user->uid ){
            if( isset( $num['company2'] ) and strlen($num['company2']) and $num['company2']!=$num['name2'] ){
                $num['name2'].=' ('.$num['company2'].')';
            }
            $anid[$num['uid2']]=$num['name2'];
        }
    }
    if( isset($anid) and is_array($anid) and count($anid) ){
        $out.= '<select onchange=" if(jQuery(this).val() && jQuery(this).val()>0){ window.location.replace(\'/messages/new?to=\'+jQuery(this).val()); } "><option value="0">Написать пользователю из сделок</option>';
        foreach( $anid as $uid=>$name ){
            $out.= '<option value="'.$uid.'">'.$name.'</option>';
        }
        $out.= '</select>';
    }

    $anid=array();
    $nums=db_query('select DISTINCT i.recipient as uid1, u1.field_name_value as name1, n1.field_company_name_value as company1 from {pm_index} as i inner join {field_data_field_name} as u1 on (i.recipient=u1.entity_id and u1.entity_type=\'user\') left join {field_data_field_company_name} as n1 on (u1.entity_id=n1.entity_id and n1.entity_type=\'user\') where i.type=\'user\' and i.recipient<>'.$user->uid);
    while( $num=$nums->fetchAssoc() ){
        if( isset( $num['company1'] ) and strlen($num['company1']) and $num['company1']!=$num['name1'] ){
            $num['name1'].=' ('.$num['company1'].')';
        }
        $anid[$num['uid1']]=$num['name1'];
    }
    if( isset($anid) and is_array($anid) and count($anid) ){
        $out.= '<select onchange=" if(jQuery(this).val() && jQuery(this).val()>0){ window.location.replace(\'/messages/new?to=\'+jQuery(this).val()); } "><option value="0">Написать пользователю из сообщений</option>';
        foreach( $anid as $uid=>$name ){
            $out.= '<option value="'.$uid.'">'.$name.'</option>';
        }
        $out.= '</select>';
    }

    if( isset($out) and strlen($out) ){
        echo '<div class="region_item" id="region_overnode"><section id="block-block-67" class="block"><div class="content"><div><strong>Ваши контакты:</strong></div>';
        echo $out;
        echo '</div></section></div>';
    }

}

?>


<?php
if(isset($messages) and strlen($messages)){
    print '<div id="mymessages">'.$messages.'<span onclick=" jQuery(\'#mymessages\').removeClass(\'mymessagesshow\'); " class="wndclose">&nbsp;</span></div>';
}
?>
    <?php print render($page['help']); ?>
    <?php
    if(isset($user->roles[3]) or isset($user->roles[4])){ 
        if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif;
    }
     ?>
    <div id="content">
    <?php

    if( arg(0)=='admin' and is_string(arg(1)) and arg(1)=='structure' and is_string(arg(2)) and arg(2)=='block' ){
        echo '<div><a target="_blank" href="/admin/structure/block/demo/pdxneedto">Смотреть расположение регионов в текущей теме</a></div>';
    }
    print render($page['content']);
    
    if( defined('PDXCAT_NAME') and arg(0)==PDXCAT_NAME ){
        
        if( isset($_SESSION['pdxpdx_par_vid']) and is_numeric($_SESSION['pdxpdx_par_vid']) and $_SESSION['pdxpdx_par_vid']>0 ){
        
        echo '<div class="term_desc_is term_desc_'.$_SESSION['pdxpdx_par_vid'].'">';
        $term=db_query('select description from {taxonomy_term_data} where tid='.$_SESSION['pdxpdx_par_tid']);
        $term=$term->fetchAssoc();
        if(isset($term['description']) and strlen($term['description'])){
            echo '<div class="term_desc admlnk pdxobj'.$_SESSION['pdxpdx_par_tid'].' pdxot">'.$term['description'].'</div>';
        }else{
            echo '<div class="admlnk pdxobj'.$_SESSION['pdxpdx_par_tid'].' pdxot pdxptl"></div>';
        }
        echo '</div>';
        if( function_exists('pdxgetshare') ){
            $soc_title=str_replace('"', '', pdx_change_title(drupal_get_title()));
            if( !strlen($soc_title) ){
                $soc_title=str_replace('"', '', 'Возьми в аренду: '.drupal_get_title());
            }
            if( mb_strlen($soc_title)>127 ){
                $soc_title=mb_substr($soc_title,0,123).'...';
            }
            $soc_desc='';
            if( isset($term['description']) and strlen($term['description']) ){
                $soc_desc=str_replace('"', '', strip_tags($term['description']));
                if( mb_strlen($soc_desc)>127 ){
                    $soc_desc=mb_substr($soc_desc,0,123).'...';
                }
            }else{
                $soc_desc= 'Каталог полезных вещей по выбранной тематике, которые вы можете взять в аренду.';
            }
            
            echo pdxgetshare($soc_title, $soc_desc, 'http://d4zbhil5kxgyr.cloudfront.net/static/ipart/i'. $_SESSION['pdxpdx_par_tid']. '.jpg');
        }
        
 ?>
<div class="clear">&nbsp;</div>
<?php
        }else{
            if( function_exists('pdxgetshare') ){
                echo pdxgetshare('Каталог вещей, доступных в аренду в '.PDX_CITY_NAME2, 'Каталог полезных вещей, которые вы можете взять в аренду в '.PDX_CITY_NAME2);
            }
 ?>
<div class="clear">&nbsp;</div>
<?php
            
        }
    }

if( drupal_is_front_page() ){
    echo '<section id="block_rec_items" class="block">';
    echo '</section>';
    echo '<section id="block_new_items" class="block">';
    echo '</section>';
    echo '<section id="block_rec_parts" class="block"><div class="block-title"><div class="title">Популярные разделы <a href="/'.PDXCAT_NAME.'">Каталога</a></div><div class="content">';
    $amass=array();
    $amass[2][67]='Игрушки';
    $amass[2][80]='Гуляем с малышом';
    $amass[13][108]='Карнавальные костюмы';
    $amass[13][107]='Для свадьбы';
    $amass[3][106]='Туризм';
    $amass[3][104]='Кардио-тренажеры';
    $amass[3][70]='Силовые тренажеры';
    $amass[3][75]='Активный отдых';
    $amass[1][983]='Здоровье';
    $amass[1][73]='Ремонт и строительство';
    $amass[12][74]='Бытовая техника';
    $amass[12][112]='Игровые приставки';
    $amass[12][71]='Фото и видео';
    $amass[869][870]='Легковые автомобили';
    $amass[869][873]='Лимузины';
    $amass[869][874]='Грузовые авто';

    if( isset($amass) and is_array($amass) and count($amass) ){
        foreach( $amass as $part1 => $am ){
            if( isset($am) and is_array($am) and count($am) ){
                foreach( $am as $part2 => $ttl ){
                    $path=path_load(PDXCAT_NAME.'/'.$part1.'/'.$part2);
                    if(strlen($path['alias'])) $path=$path['alias']; else $path=PDXCAT_NAME.'/'.$part1.'/'.$part2;
                    echo '<div class="headcatis headcatis'.$part2.'"><a class="aheadcatis" href="/'.$path.'"><span class="image"><img alt="" class="lazy" data-original="http://d4zbhil5kxgyr.cloudfront.net/static/part/i'.$part2.'.png" /></span><span class="ttl partis'.$part2.'">'.$ttl.'</span></a></div>';
                }
            }
        }
    }
    echo '</div></div>';
    echo '</section>';
}    
    
    ?>
    </div>
    <?php
    if( !$user->uid and arg(0)=='node' and is_string(arg(1)) and arg(1)=='add' ){
        if(  is_string(arg(2)) and arg(2)=='item' ){
            echo '<div class="itemnotlogin"><p>...Но это не беда. <a href="javascript:void(0);" onclick="onbasic(this, 1);"><span class="thr" style="display: none;"></span>Войдите</a> на сайт, или <a href="javascript:void(0);" onclick="onbasic(this, 2);"><span class="thr" style="display: none;"></span>зарегистрируйтесь</a> на сайте обычным способом. Или через вашу любимую социальную сеть!</p><div id="ulogin2" x-ulogin-params="display=small&fields=first_name,last_name,email,photo&optional=&providers=vkontakte,facebook,youtube,mailru,twitter,google,yandex,odnoklassniki,livejournal,flickr,vimeo,webmoney,foursquare,tumblr&redirect_uri='.urlencode($GLOBALS['base_url'].'/ulogin?destination='.implode('/', arg())).'"></div><div>&nbsp;</div><p>И вы легко сможете добавить свое объявление!</p>';
            echo '</div>';
        }
    }
    ?>
    <?php print $feed_icons ?>
<div class="clear">&nbsp;</div></div></div>

<div class="clear">&nbsp;</div></div>
</div>

<?php


//if( drupal_is_front_page() ){
    echo '<div id="pre_sl">';
if( isset($_SESSION['pdxpdx_node_type']) and strlen($_SESSION['pdxpdx_node_type']) and $_SESSION['pdxpdx_node_type']=='item' and isset($_SESSION['pdxpdx_node_uid']) and is_numeric($_SESSION['pdxpdx_node_uid']) and !is_string(arg(2)) ){
    echo '<div id="showmeuser'.$_SESSION['pdxpdx_node_uid'].'" class="showmeuser"></div>';
}else{
    if( file_exists('api/other/slogan') ){
        $outslogan=file('api/other/slogan');
        if( isset($outslogan) and is_array($outslogan) and count($outslogan) ){
        echo '<div class="slogan"><div class="sloganin"><span class="slin"><span class="slinqout">«</span>'. trim(str_replace("\r", '',str_replace("\n", '', filter_xss($outslogan[mt_rand(0, count($outslogan)-1)])))) .'»</span></div></div>';
        }
    }
}
    echo '<div id="pre_statusline"><div id="statusline">';
    
        echo '<div class="footer_name">Needto.me';
        if( defined('PDX_CITY_NAME') ){
            echo '<span>'.PDX_CITY_NAME.'</span>';
        }
        echo '</div>';
        if( defined('PDX_CITY_CREATED') and is_numeric(PDX_CITY_CREATED) and PDX_CITY_CREATED>0 ){
            $citycount=PDX_CITY_CREATED;
            if( $citycount>time() ){
                $citycount=time()-87777;
            }
        }else{
            $citycount=0;
        }
        echo '<div class="status_item status_item_1"><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/btm_ico1.png" /><noindex><a rel="nofollow" target="_blank" href="http://needto.me/i.html">';
        echo '<div class="cnt">';
        echo '<span class="datereg datereg_'.$citycount.'"></span>';
        echo '</div>';
        echo '<div class="title">дней сервису</div>';
        echo '</a></noindex></div>';


        echo '<div class="status_item status_item_2"><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/btm_ico2.png" /><a href="/catalog">';
        echo '<div class="cnt">';
        echo '<span class="status_count_item">???</span>';
        echo '</div>';
        echo '<div class="title">объявлений</div>';
        echo '</a></div>';


        echo '<div class="status_item status_item_3"><img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/btm_ico3.png" /><a href="/users/rents">';
        echo '<div class="cnt">';
        echo '<span class="status_count_users">???</span>';
        echo '</div>';
        echo '<div class="title">участников</div>';
        echo '</a></div>';

    
    echo '<div class="clear">&nbsp;</div></div></div><div>';
//}

?>
<footer id="footer"><div id="footer_post">
<div class="region_item" id="region_utility_bottom"><section id="block-block-56" class="block"><div class="content"><div class="footer_subname">
<?php
switch(PDX_CITY_COUNTRY){
case 62:
    echo '<iframe width="301" height="225" src="https://www.youtube.com/embed/1g-xklk1IX4" frameborder="0" allowfullscreen></iframe>';
    break;
case 61:
    echo '<iframe width="301" height="225" src="https://www.youtube.com/embed/rCys2SyPkTM" frameborder="0" allowfullscreen></iframe>';
    break;
default:
    echo '<img alt="" class="lazy" data-original="'.PDX_IMGPATH.'/img/text224.png" />';
}
?>
</div></div></section></div>
<div id="region_footer"><div class="block"><div class="content">
<p>Данный сервис является площадкой для поиска и общения между людьми, которые хотят взять некий товар в аренду, и теми, кто согласен этот товар предоставить.</p>

<p>Мы не отвечаем за качество товара и добросовестность &laquo;арендаторов&raquo; или &laquo;арендодателей&raquo;. По любым вопросам следует обращаться непосредственно к противоположной стороне договора об аренде. Если Вы &laquo;арендодатель&raquo;, всегда защищайте себя достаточной залоговой суммой, или иным подходящим для Вас способом. &laquo;Арендаторам&raquo; следует внимательно относиться к отзывам тех, кто уже брал нужный Вам товар, либо другие товары этого же &laquo;арендодателя&raquo;. И стараться не иметь дела с людьми, об услугах которых есть отрицательные отзывы.</p>

<p>Желаем Вам удачных сделок!</p>
</div></div></div>
<div class="clear">&nbsp;</div>
<div class="counters">
<?php
if( defined('PDX_METRIKA_YANDEX') and strlen(PDX_METRIKA_YANDEX) ){
    echo '<!-- Yandex.Metrika informer --><a href="https://metrika.yandex.by/stat/?id='.PDX_METRIKA_YANDEX.'&amp;from=informer"target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/'.PDX_METRIKA_YANDEX.'/2_1_FFFFFFFF_EFEFEFFF_0_pageviews"style="width:80px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры)" onclick="try{Ya.Metrika.informer({i:this,id:'.PDX_METRIKA_YANDEX.',lang:\'ru\'});return false}catch(e){}" /></a><!-- /Yandex.Metrika informer -->';
}
?>
</div>
<div class="clear">&nbsp;</div></div></footer>
<div id="footer2"><div class="region_item" id="region_search">
<section id="block-system-main-menu" class="block block-system block-menu"><div class="postblockover"><div class="content"><noindex>
<ul class="megamenu-1"><li class="megamenu-li-first-level primlink1" id="menu-main-title-8163"><a href="http://needto.me/news.html" target="_blank" rel="nofollow">Новости сайта</a></li><li class="megamenu-li-first-level primlink2" id="menu-main-title-8136"><a href="http://needto.me/a.html" target="_blank" rel="nofollow">Реклама на сайте</a></li><li class="megamenu-li-first-level primlink3" id="menu-main-title-8158"><a href="http://needto.me/press.html" target="_blank" rel="nofollow">Для прессы</a></li><li class="megamenu-li-first-level primlink4" id="menu-main-title-6879"><a href="http://help.needto.me/index.html" target="_blank" rel="nofollow">Помощь</a></li><li class="megamenu-li-first-level primlink5" id="menu-main-title-8138"><a href="http://needto.me/rules.html" target="_blank" rel="nofollow">Правила сайта</a></li><li class="megamenu-li-first-level primlink6" id="menu-main-title-8137"><a href="http://needto.me/i.html" target="_blank" rel="nofollow">Инвестору</a></li><li class="megamenu-li-first-level primlink7 last" id="menu-main-title-8159"><a href="http://needto.me/apime.html" target="_blank" rel="nofollow">API</a></li></ul>
<div class="mistakes" onclick="stpr(event || window.event); PressLink();"><strong>Нашли ошибку?</strong> Выделите её, и нажмите Ctrl+Enter, либо кликните по данному сообщению</div></noindex></div></div></section>

<section id="block-block-47" class="block"><div class="postblockover"><div class="content"><?php

$out='<a href="/"><img alt="" class="btmlogo lazy" data-original="'.PDX_IMGPATH.'/img/logo224.png"></a>';

if( defined('PDX_SOC_FACEBOOK') and strlen(PDX_SOC_FACEBOOK) ){
    $out.='<span class="psml"><a href="'.PDX_SOC_FACEBOOK.'" target="_blank" rel="nofollow" class="sml sm_fb">F<span class="smli" style="display: none;">acebook</span></a></span>';
}
if( defined('PDX_SOC_TWITTER') and strlen(PDX_SOC_TWITTER) ){
    $out.='<span class="psml"><a href="'.PDX_SOC_TWITTER.'" target="_blank" rel="nofollow" class="sml sm_tw">T<span class="smli" style="display: none;">witter</span></a></span>';
}
if( defined('PDX_SOC_VKONTAKTE') and strlen(PDX_SOC_VKONTAKTE) ){
    $out.='<span class="psml"><a href="'.PDX_SOC_VKONTAKTE.'" target="_blank" rel="nofollow" class="sml sm_vk">В<span class="smli" style="display: none;">контакте</span></a></span>';
}

$out.='<a href="'.PDX_IMGPATH.'/static/rss/'.PDX_CITY_ID.'.rss" target="_blank" rel="nofollow" class="rss"><img alt="RSS" class="lazy" data-original="'.PDX_IMGPATH.'/img/rss2.png" /></a>';

if( isset($out) and strlen($out) ){
    echo '<noindex>'.$out.'</noindex>';
}

?></div><div class="sm copy"><noindex>© <a title="Разработка и поддержка http://soonmy.com" rel="nofollow" target="_blank" href="http://soonmy.com">ФОП Клименко Р.А.</a> разработка и поддержка. <a target="_blank" rel="nofollow" href="http://needto.me/dev.html">Нужен сайт?</a></noindex></div>
<?php
echo '<div><noindex>';
if( defined('PDX_APP_ANDROID') and strlen(PDX_APP_ANDROID) ){
    echo '<div class="mobileicos"><a target="_blank" rel="nofollow" class="mobilegp mobilea" href="'.PDX_APP_ANDROID.'">'.PDX_CITY_NAME.': Аренда</a></div>';
}
if( defined('PDX_APP_IOS') and strlen(PDX_APP_IOS) ){
    echo '<div class="mobileicos"><a target="_blank" rel="nofollow" class="mobileios mobilea" href="'.PDX_APP_IOS.'">'.PDX_CITY_NAME.': Аренда</a></div>';
}
echo '<div class="clear">&nbsp;</div></noindex></div>';
?>
</div></section>
<div class="clear">&nbsp;</div></div></div>
<?php if($page['overcontent']){ print render($page['overcontent']); } ?>

<?php
if( $user->uid and function_exists('getaddtocat') ){
    echo '<div class="inlinebodydiv" id="add_product_more" style="display: none;"><img class="closeimg" onclick=" jQuery(this).parent().hide(); " alt="x" src="'.PDX_IMGPATH.'/img/ico_close.png" /><div class="inlinetitle"><div class="inlinetitlein">Выберите раздел для подачи объявления</div></div><div class="fieldset-wrapper"><div class="cnt">';
    echo '</div></div></div>';
}
if( arg(0)=='findmy' or ( is_string(arg(1)) and arg(1)=='findmy' ) ){
    echo '<div class="inlinebodydiv" id="addfindmyis" style="display: none;"><img class="closeimg" onclick=" jQuery(this).parent().hide(); " alt="x" src="'.PDX_IMGPATH.'/img/ico_close.png" /><div class="inlinetitle"><div class="inlinetitlein"><a target="blank" class="helpme" href="http://'.PDX_URL_HELP.'/podacha-zayavki-na-arendu-veshchi-kotoroy-net-v-kataloge.html" title="Открыть справку в новом окне">&nbsp;</a>Подать заявку на поиск вещи</div></div><div class="fieldset-wrapper"><div class="cnt"></div></div></div>';
}

if( !$user->uid ){
    $act=db_query('select b.body_value from {field_data_body} as b inner join {node} as n on n.nid=b.entity_id where b.bundle=\'action\' and b.body_value<>\'\' and n.status=1 order by RAND() limit 0,1');
    $act=$act->fetchAssoc();
    if( isset($act['body_value']) and strlen($act['body_value']) ){
        echo '<div style="display: none;" id="actionmsg"';
        if( isset($_COOKIE['actionday']) and $_COOKIE['actionday']==date('j', time()) ){}else{
            echo ' class="regshow"';
        }
        echo '><div id="actionmsgin"><div class="cnt">'.$act['body_value'].'</div><span onclick=" stpr(event || window.event); jQuery(this).parent().parent().removeClass(\'regshow\'); " class="wndclose">&nbsp;</span></div></div>';
    }
}


?>
<div id="ajaxrs"></div><noindex><div id="forreg" onclick=" stpr(event || window.event); " style="display: none;"><div id="forregin"><div>Для доступа к данной возможности <a href="javascript:void(0);" onclick="onbasic(this, 2);"><span class="thr" style="display: none;"></span>зарегистрируйтесь</a> или <a href="javascript:void(0);" onclick="onbasic(this, 1);"><span class="thr" style="display: none;"></span>войдите</a> на сайт.</div><div id="ulogin2" x-ulogin-params="display=small&fields=first_name,last_name,email,photo&optional=&providers=vkontakte,facebook,youtube,mailru,twitter,google,yandex,odnoklassniki,livejournal,flickr,vimeo,webmoney,foursquare,tumblr&redirect_uri=<?php echo urlencode($GLOBALS['base_url'].'/ulogin?destination='.implode('/', arg())); ?>"></div> <span onclick=" stpr(event || window.event); jQuery(this).parent().parent().removeClass('regshow'); " class="wndclose">&nbsp;</span></div></div><div id="othermsg" style="display: none;"><div id="othermsgin"><div class="cnt"></div><span onclick=" stpr(event || window.event); jQuery(this).parent().parent().removeClass('regshow'); " class="wndclose">&nbsp;</span></div></div><div style="display: none;" class="inlinebodydiv" id="showcompanyoncart"><img class="closeimg" onclick=" jQuery(this).parent().hide(); " alt="x" src="<?php echo PDX_IMGPATH; ?>/img/ico_close.png" /><div class="inlinetitle"><div class="inlinetitlein">Компания на карте</div></div><div class="cnt"></div></div></noindex>

<?php /* ?>
<script type="text/javascript">
<?php
if( isset($_SESSION['pdxpdx_node_type']) and $_SESSION['pdxpdx_node_type']=='item' and isset($_SESSION['pdxpdx_node_nid']) and is_numeric($_SESSION['pdxpdx_node_nid']) ){

    echo '
dataLayer.push({
    "ecommerce": {
        "detail": {
            "products": [
                {
                    "id": \'N1'.$_SESSION['pdxpdx_node_nid'].'\',
                    "name" : "';
        if( isset($_SESSION['pdxpdx_node_title']) and strlen($_SESSION['pdxpdx_node_title']) ){
            echo $_SESSION['pdxpdx_node_title'];
        }
        echo '",
                    "price": "0",
                    "brand": "';
        if( isset($_SESSION['pdxpdx_node_brand_name']) and strlen($_SESSION['pdxpdx_node_brand_name']) ){
            echo $_SESSION['pdxpdx_node_brand_name'];
        }
        echo '",
                    "category": "';
        if( isset($_SESSION['pdxpdx_node_tid_name']) and strlen($_SESSION['pdxpdx_node_tid_name']) ){
            echo $_SESSION['pdxpdx_node_tid_name'];
        }
        echo '",
                    "variant" : ""
                }
            ]
        }
    }
});';
    
}
?>
</script>
<?php */ ?>

<?php
if( $isadm ){
    $curpath=implode('/',arg());
    echo '<div style=" position: fixed; background: #eee; right: 0; top: 111px; padding: 7px; z-index: 777; "><div><a style="font-size: 8pt !important; '.(($curpath=='admin/store/orders/create')?(' text-decoration: none;'):('')).'" href="/admin/store/orders/create">добавить заказ</a></div><div><a style="font-size: 8pt !important; '.(($curpath=='admin/store/orders/view')?(' text-decoration: none;'):('')).'" href="/admin/store/orders/view" class="adm_orders">Все заказы</a></div><div><a style="font-size: 8pt !important; '.(($curpath=='admin/premod')?(' text-decoration: none;'):('')).'" href="/admin/premod" class="adm_premod">Премодерация</a></div><div><a style="font-size: 8pt !important; '.(($curpath=='admin/postmod')?(' text-decoration: none;'):('')).'" href="/admin/postmod" class="adm_postmod">Постмодерация</a></div><div><a style="font-size: 8pt !important; '.(($curpath=='admin/users')?(' text-decoration: none;'):('')).'" href="/admin/users" class="adm_users">Участники</a></div><div><a style="font-size: 8pt !important; '.(($curpath=='admin/deals')?(' text-decoration: none;'):('')).'" href="/admin/deals" class="adm_deals">Сделки</a></div><div><a style="font-size: 8pt !important; '.(($curpath=='admin/config/people/userpoints/transaction')?(' text-decoration: none;'):('')).'" href="/admin/config/people/userpoints/transaction" class="adm_balance">Транзакции</a></div><div><a style="font-size: 8pt !important; '.(($curpath=='admin/last100msg')?(' text-decoration: none;'):('')).'" href="/admin/last100msg" class="adm_ls">Сообщения</a></div></div>';
    echo '<script type="text/javascript"> 
    var curpagepath=\''.implode('/',arg()).'\';
     </script>';
    include_once 'tb/include_admin.php';
}else{
/*
drupal_get_path('theme','pdxneedto')
*/

?>
<?php
switch(PDX_CITY_ID){
case 28:
?>
<div style="display: none;">
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=sAJlO8xshfPSyoZbRvwqb7Rrpmxc1aYr2GNfu*xC6*vj9cMiq8DSsOsCtEDj**ppgLKo2SoSdNjrHm6B/ltwfkaLfoEsdPhoGmmlLmCIFrdE0Y6MrmfjsDR/oVM38nVdPgGmovSzgqytR91*la2gIuGf1eeIfq9AX0JwY8eFxa8-';</script>
</div>
<?php
    break;
}

if( !defined('PDX_JIVOSITE') or !strlen(PDX_JIVOSITE) or isset($user->roles[3]) or isset($user->roles[4]) ){  }else{
    echo '<script type=\'text/javascript\'> (function(){ if( window.innerWidth>1111 ){ var widget_id = \''.PDX_JIVOSITE.'\'; var s = document.createElement(\'script\'); s.type = \'text/javascript\'; s.async = true; s.src = \'//code.jivosite.com/script/widget/\'+widget_id; var ss = document.getElementsByTagName(\'script\')[0]; ss.parentNode.insertBefore(s, ss); } })();</script>';
}


?>
<div id="presubscribe"><a target="_blank" href="http://<?php echo PDX_URL_HELP; ?>/rabota-s-rassylkami-sayta.html" class="smhelp">&nbsp;</a><div id="subscribeis">
<div class="block-title" onclick=" switchsubscribe('Подпишись на наши рассылки'); ">Подпишись на наши рассылки</div>
<div class="content subscribecnt" style="display: none;"><div class="contentin"><img alt="" src="<?php echo PDX_IMGPATH; ?>/img/throbber.gif" /></div></div>
</div></div>

<?php

}
?>
