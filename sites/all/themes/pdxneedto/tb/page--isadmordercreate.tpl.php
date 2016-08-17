<div id="admpage" style="padding: 0 47px 41px 47px; border: 1px solid #727271; position: relative; left: +0px;">
<?php
global $user;

/*
echo '<div class="admmenu">';
if(isset($user->roles[6])){}else{
    echo '<a class="active" href="/admin/store/orders/create">Оформление</a>';
}
echo '<a href="/admin/store/orders/view">Заказы</a>';
if(isset($user->roles[6])){}else{
    echo '<a href="/admin/mystock">Склад</a><a href="/admin/reserve">В резерве</a>';
}
echo '</div>';
*/

echo '<div class="admclock" style="float: right; margin: 21px 111px 0 0; text-align: right; text-transform: lowercase; "><p>';

echo date('d.m.Y',time()).'</p><p>'.format_date(time(),'custom','l').'</p><p class="clock">'.date('H:i:j', time()).'</p>';
echo '<script type="text/javascript">
  function digitalWatch() {
    var date = new Date();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();

    if (hours < 10) hours = "0" + hours;
    if (minutes < 10) minutes = "0" + minutes;
    if (seconds < 10) seconds = "0" + seconds;
    jQuery(\'.clock\').html(hours + ":" + minutes + ":" + seconds);
    setTimeout("digitalWatch()", 1000);
  }    

jQuery(document).ready(function($){ 
    digitalWatch();
}); </script>';

echo '</div>';

echo '<div style="margin: 36px 0 0 41px; float: left; width: 263px;">';
echo '<strong style="display: block; padding-bottom: 3px; "><a style=" text-decoration: none; font-size: 12pt; " href="/user/'.$user->uid.'/edit">'.$user->name.'</a></strong>';
echo '<a href="/user/logout">Выйти</a>';
echo '</div>';
?>
<?php
print render($title_prefix);
if ($title){
echo '<div style=" margin: 37px 619px 0 313px; text-transform: uppercase; font-size: 18pt; ">';

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
    
    if(strlen($correct_title)){
        echo $correct_title;
    }

echo '</div>';
}
print render($title_suffix);
?>
<div class="clear">&nbsp;</div>


<?php
if(isset($messages) and strlen($messages)){
    print '<div id="mymessages">'.$messages.'</div><a id="amymessages" class="colorbox-inline" href="?width=553&height=177&inline=true#mymessages">&nbsp;</a>';
}
?>
    <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>

    <div id="content">
    <?php 
    
    print render($page['content']);
    
    ?>
    </div>
<div class="clear">&nbsp;</div></div>
<script type="text/javascript">
var checkproduct;
function checkproducts(){
    if( jQuery('#edit-product-controls-nid').length ){
        jQuery('#edit-product-controls-nid').before('<div style="width: 777px; float: right; " id="moreproductinfo"></div>');
        jQuery('#edit-product-controls-nid').bind('change',
            function(){
                jQuery('#moreproductinfo').html('<img alt="" src="/misc/throbber.gif" />');
                jQuery('#moreproductinfo').load(Drupal.settings.basePath + 'stockbottom2.php?nid='+jQuery(this).val());
            }
        );
        window.clearInterval(checkproduct);
/*
        if( jQuery('#edit-product-controls-product-search').length ){
            jQuery('#edit-product-controls-product-search').bind('blur',
                function(){
                    if( jQuery(this).val() ){
                        var val=jQuery(this).val();
                        if( val.indexOf('*') == -1 ){
                            jQuery(this).val('*'+jQuery(this).val()+'*');
                        }
                    }
                }
            );
        }
*/
    }
}

jQuery(document).ready(function($){
    if( jQuery('#edit-add-product-button').length ){
        checkproduct = window.setInterval("checkproducts()", 1000);
    }
    
<?php
if( arg(0)=='admin' and is_numeric(arg(3)) and is_string(arg(4)) and arg(4)=='edit' ){
?>
    if( jQuery('#edit-field-order-track-und').length ){
        jQuery('#edit-field-order-track-und').bind('change',
            function(){
                if( jQuery('#edit-field-order-track-und').attr('checked')==true ){
                    if( jQuery('#edit-field-order-tracking-und-0-value').val() ){
                        admchange2(<?php echo arg(3); ?>, "order", "track", jQuery('#edit-field-order-tracking-und-0-value').val(), "value");
                    }else{
                        jQuery('#track_info').html('Введите трекинг-номер');
                        jQuery(this).removeAttr('checked');
                    }
                }else{
                }
            }
        );
    }
<?php
}
?>
});


</script>
<?php
/*
drupal_get_path('theme','pdxbagz')
*/
?>