<div id="admpage" style="padding: 0 0px 41px 47px; border: 1px solid #727271; position: relative; left: +0px;">
<?php
global $user;

/*
echo '<div class="admmenu">';
if(isset($user->roles[6])){}else{
    echo '<a href="/admin/store/orders/create">Оформление</a>';
}
echo '<a href="/admin/store/orders/view">Заказы</a>';
if(isset($user->roles[6])){}else{
    echo '<a class="active" href="/admin/mystock">Склад</a><a href="/admin/reserve">В резерве</a>';
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
    <div id="stockright"><div id="stockrightis">Выберите товар...</div></div>
    <div id="stockleft"><div id="content" class="contentstock">

    <?php 
    
    print render($page['content']);
    
    ?>
    <div id="stockbottom"></div>
    </div></div>
<div class="clear">&nbsp;</div></div>
<script type="text/javascript"> jQuery(document).ready(function($){

});

function stockadd(nid){
    if( jQuery('#stockadd_'+nid).val()>0 ){
        jQuery.post(Drupal.settings.basePath + 'stockadd.php', { nid: nid, val: jQuery('#stockadd_'+nid).val() }, function( data ) { jQuery('#stockis_'+nid).html(data); } );    
    }
}
function stockclick(nid){
    jQuery('.stocktitle').removeClass('stockactive');
    jQuery('#stocktitle_'+nid).addClass('stockactive');

    jQuery('#stockbottom').html('<img alt="" src="/misc/throbber.gif" />');
    jQuery('#stockbottom').load(Drupal.settings.basePath + 'stockbottom.php?nid='+nid);

    jQuery('#stockrightis').html('<img alt="" src="/misc/throbber.gif" />');
    jQuery('#stockrightis').load(Drupal.settings.basePath + 'stockright.php?nid='+nid);
    
}

</script>
<?php
/*
drupal_get_path('theme','pdxbagz')
*/
?>