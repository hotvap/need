
var favrepeat=0;
var curd=new Date().getTime();
var curd2=new Date( new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 0, 0, 0, 0 ).getTime();
curd=parseInt(curd/1000);
curd2=parseInt(curd2/1000);
var mailcor = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
var loc = window.location;
var chatneed=0;
var chatprocess=0;
var activeWindow=1;
var lastchatconn=0;
var favmenode = new Array();
var favmeuser = new Array();

var txtExpand="Подробнее"; 
var reqField="Вы не заполнили обязательные поля";
var inFav="Из Избранного";
var inFav2="Отписаться";
var isYou="Это <br />Вы =)";
var isYou2=" (это Вы =))";
var dtPre="ещё ≈";
var dtPost="дней";
var dtPost1="день";
var dtPost3="дня";
var dtPost0="сегодня";
var confirmtext3='Вы действительно хотите купить GOLD-аккаунт? <span class="confirmyes" onclick=" bonus_gold(); jQuery(\'+"\'.closeconfirm\'"+\').click(); ">&nbsp;</span>';
var SoonMore='Продлить';

document.createElement("header");
document.createElement("nav");
document.createElement("article");
document.createElement("footer");
document.createElement("section");
document.createElement("aside");

String.prototype.replaceAll = function(search, replace){
  return this.split(search).join(replace);
}


jQuery(document).ready(function($){
    if(jQuery('#mymessages').length){
        jQuery('#mymessages').addClass('mymessagesshow');
    }

    if( jQuery('.form-item-field-phones-und-0-value .form-text').length && jQuery('.maskisme').length ){
        jQuery('.form-item-field-phones-und-0-value .form-text, .form-item-field-phones-und-1-value .form-text, .form-item-field-phones-und-2-value .form-text').mask( jQuery('.maskisme').html(), { placeholder: jQuery('.maskisme').html().replaceAll('9', '_') } );
        
    }

	var exist= jQuery('#back-top').length;
	if(exist == 0){
		jQuery("body").append("<p id='back-top'><a href='#top'><span id='button'></span></a></p>");
	}
	jQuery("input").change(function () {
		var style="<style>#scroll-to-top-prev-container #back-top-prev span#button-prev{ background-color:"+jQuery("#edit-scroll-to-top-bg-color-out").val()+";} #scroll-to-top-prev-container #back-top-prev span#button-prev:hover{ background-color:"+jQuery("#edit-scroll-to-top-bg-color-hover").val()+" }</style>"
		var html="<p id='back-top-prev' style='position:relative;'><a href='#top'><span id='button-prev'></span><span id='link'>";
		if(jQuery("#edit-scroll-to-top-display-text").attr('checked')){
		html+=jQuery("#edit-scroll-to-top-label").val();
		}
		html+="</span></a></p>";
		jQuery("#scroll-to-top-prev-container").html(style+html);
	});
	jQuery("#back-top").hide();
	jQuery(function () {
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 100) {
				jQuery('#back-top').fadeIn();
			} else {
				jQuery('#back-top').fadeOut();
			}
		});

		jQuery('#back-top a').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
    
    jQuery("input[name='submitted[s]']").val(7778);

    jQuery('form.node-form #edit-submit, form#user-profile-form #edit-submit').bind('click',
        function(){
            var isreq=0;
            jQuery('form.node-form .form-type-textfield, form#user-profile-form .form-type-textfield').each(
                function(){
                    if( jQuery(this).find('.form-required').length && !jQuery(this).find('.form-text').val() ){
                        jQuery(this).find('.form-text').addClass('warn');
                        isreq=1;
                    }else{
                        jQuery(this).find('.form-text').removeClass('warn');
                    }
                }
            );
            jQuery('form.node-form .form-type-checkbox, form#user-profile-form .form-type-checkbox').each(
                function(){
                    if( jQuery(this).find('.form-required').length && jQuery(this).find('.form-checkbox').attr('checked')!='checked' ){
                        jQuery(this).find('.form-checkbox').parent().parent().addClass('warn');
                        isreq=1;
                    }else{
                        jQuery(this).find('.form-checkbox').parent().parent().removeClass('warn');
                    }
                }
            );
/*
            if( jQuery('#item-node-form #edit-field-image').length ){
                if( !jQuery('#item-node-form #edit-field-image .form-item-field-image-und-0--weight').length ){
                    jQuery('#edit-field-image').addClass('warn');
                    isreq=1;
                }else{
                    jQuery('#edit-field-image').removeClass('warn');
                }
            }
*/            
            jQuery('form.node-form .form-type-textarea, form#user-profile-form .form-type-textarea').each(
                function(){
                    if( jQuery(this).find('.form-required').length && !jQuery(this).hasClass('form-item-body-und-0-value') && !jQuery(this).find('textarea').val() ){
                        jQuery(this).find('textarea').addClass('warn');
                        isreq=1;
                    }else{
                        jQuery(this).find('textarea').removeClass('warn');
                    }
                }
            );
            jQuery('form.node-form .form-type-select, form#user-profile-form .form-type-select').each(
                function(){
                    if( jQuery(this).find('.form-required').length && (!jQuery(this).find('select').val() || jQuery(this).find('select').val()=='_none' ) ){
                        jQuery(this).find('select').addClass('warn');
                        isreq=1;
                    }else{
                        jQuery(this).find('select').removeClass('warn');
                    }
                }
            );

            if( isreq ){
                jQuery('#othermsgin .cnt').html(reqField);
                jQuery('#othermsg').addClass('regshow');
                jQuery('html, body').animate({ scrollTop: jQuery(".warn").parent().parent().offset().top }, 333);
                return false;
            }
            
            jQuery('form.node-form .field-type-number-integer .form-text, form#user-profile-form .field-type-number-integer .form-text').each(
            function(){
                if( jQuery(this).val() ){
                    jQuery(this).val( jQuery(this).val().replaceAll(' ', '') );
                }
            }
            );
        }
    );
    switchTime();
    thismymail();
    athismymail();

    window.onblur = function() {
        activeWindow=0;
    };
    window.onfocus = function() {
        activeWindow=1;
        setFavico(0);
    };
    
    jQuery('#user-profile-form .field-name-field-company-logo, #user-profile-form .field-name-field-ava').each(
        function(){
            if( jQuery(this).find('.image-preview').length ){
                jQuery(this).find('.form-submit').css('display', 'inline-block');
            }
        }
    );

});
function scrt(url){
    var s=document.createElement("script"); s.async = true; s.src = url;
    var a = document.getElementsByTagName("script")[0]; a.parentNode.insertBefore(s, a);
}

function stpr(evt) {
    if (typeof evt.stopPropagation != "undefined") {
        evt.stopPropagation();
    } else {
        evt.cancelBubble = true;
    }
}

function showcountrys(){
    if( jQuery('#city_current_list').css('display')=='block' ){
        jQuery('#city_current_list').hide();
    }else{
        jQuery('#city_current_list').show();
    }
}
function switchTime(){
        jQuery('.datereg, .dateac').each(
            function(){
                var tm=jQuery(this).attr('class').replace('datereg ', '').replace('datereg_', '').replace('dateac ', '').replace('dateac_', '');
                if( tm>0 && curd>tm ){
                    if( new Date(tm*1000).toDateString()==new Date(curd*1000).toDateString() ){
                        jQuery(this).parent().find('.datereg_ago_is').html(dtPost0);
                    }else{
                        tm=parseInt((curd-tm)/86400)+1;
                        if( jQuery(this).parent().find('.datereg_ago_is').length ){
                            jQuery(this).parent().find('.datereg_ago').html( pdxnumfoot(tm, dtPost1, dtPost, dtPost3));
                        }else{
                            jQuery(this).html(tm);
                        }
                    }
                }
            }
        );
}
function pdxnumfoot(count, str1, str2, str3){
    var tmpcount = parseInt(count);
    var tmpiscount = tmpcount;
    if(tmpcount<1){
        return count+' '+str1;
    }
    tmpcount2=-1;
    if(tmpcount>9){
        tmpcount2=tmpcount%100;
    }
    if( tmpcount2>10 && tmpcount2<20 ){
    }else{
        tmpcount2=tmpcount%10;
        switch(tmpcount2){
        case 0: case 5: case 6: case 7: case 8: case 9:
            break;
        case 1:
            return count+' '+str1;
            break;
        case 2: case 3: case 4:
            return count+' '+str3;
            break;
        }
    }
    return accounting.formatNumber(count, 0, " ", ".")+' '+str2;
}
function getCookie(name) {
  var cookie = " " + document.cookie;
  var search = " " + name + "=";
  var setStr = null;
  var offset = 0;
  var end = 0;
  if (cookie.length > 0) {
    offset = cookie.indexOf(search);
    if (offset != -1) {
      offset += search.length;
      end = cookie.indexOf(";", offset)
      if (end == -1) {
        end = cookie.length;
      }
      setStr = unescape(cookie.substring(offset, end));
    }
  }
  return(setStr);
}
function setCookie (name, value, expires, path, domain, secure) {
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}
function getRandomInt(min, max){
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
function bonus_ontop(nid, obj){
    jQuery('.bonus_ontop_'+nid).addClass('ajaxproc');
    jQuery('html, body').animate({ scrollTop: jQuery('.bonus_ontop_'+nid).eq(0).offset().top }, 333);
    jQuery.post(Drupal.settings.basePath + 'bonus_ontop.php', { nid: nid }, function( data ) { jQuery('.bonus_ontop_'+nid).html(data); jQuery('.bonus_ontop_'+nid).removeClass('ajaxproc'); } );
}
function confirmbonus(txt){
    jQuery('#confirmactioncnt').html(txt); jQuery('#confirmaction').show();
}
function bonus_premium(nid, obj){
    jQuery('.bonus_premium_'+nid).addClass('ajaxproc');
    jQuery('html, body').animate({ scrollTop: jQuery('.bonus_premium_'+nid).eq(0).offset().top }, 333);
    jQuery.post(Drupal.settings.basePath + 'bonus_premium.php', { nid: nid }, function( data ) { jQuery('.bonus_premium_'+nid).html(data); jQuery('.bonus_premium_'+nid).removeClass('ajaxproc'); } );
}
function bonus_gold(){
    jQuery('.bonus_gold').addClass('ajaxproc');
    jQuery.post(Drupal.settings.basePath + 'bonus_gold.php', { }, function( data ) { jQuery('.bonus_gold').html(data); jQuery('.bonus_gold').removeClass('ajaxproc'); } );
}
function checkonline(){
    jQuery('.ustat').not('.ustati').each(
        function(){
            var uid=jQuery(this).attr('class').replace('ustat ustat_', '').replace('ustata', '').trim();
            if( uid>0 ){
                jQuery.ajax({
                  url: Drupal.settings.basePath+'curs/online/'+uid+'.txt?r='+(new Date).getTime(),
                  success: function(){
                    jQuery('.ustat_'+uid).addClass('ustata');
                    jQuery('.ustat_'+uid).attr('title', 'online');
                  }
                });
            }
            jQuery(this).addClass('ustati');
        }
    );
}
function sendpmgen(obj){
    if( jQuery('#content div.node-item').length ){
        if( nnid>0 ){
            jQuery(obj).attr('href', jQuery(obj).attr('href')+'&nid='+nnid);
        }
    }
}
function chatop(){
    if( jQuery('.chatop').hasClass('chatop_ishide') ){
        jQuery('#mychat_body').show();
        jQuery('.chatop').removeClass('chatop_ishide');
        setCookie("hidechat", 0 , "Mon, 01-Jan-2017 00:00:00 GMT", "/");
    }else{
        jQuery('#mychat_body').hide();
        jQuery('.chatop').addClass('chatop_ishide');
        setCookie("hidechat", 1 , "Mon, 01-Jan-2017 00:00:00 GMT", "/");
    }
}
function chatstatus(){
    var hidechat = getCookie('hidechat');
    if( !hidechat ){ hidechat=0; }
    if( hidechat==1 ){
        jQuery('#mychat_body').hide();
        jQuery('.chatop').addClass('chatop_ishide');
    }
}
function chatex(){
    if( jQuery('#chatcur').length && jQuery('#chatcur').html() ){
        var tmpchat=jQuery('#chatcur').html();
        if( tmpchat>0 ){
            unchatme();
            location.replace("/pm#pmid"+tmpchat);
        }
    }
}
function chatme(nid){
    jQuery('.mychat_item').removeClass('chatactive');
    jQuery('.mychat_item_'+nid).addClass('chatactive');
    jQuery('#chatpanel').show();
    jQuery('#chatpanel_ttl').html(jQuery('.mychat_item_'+nid+' .chat_fortitle').html());
    jQuery('#chatcur').html(nid);
    jQuery('#chatpanel_input_text').attr('contenteditable', true);
    jQuery('.chat_noti_item_'+nid).remove();
    jQuery('#chatpanel_input_text').focus();
    chatload(nid);
    var chatmedat = new Date();
    chatmedat.setTime(chatmedat.getTime()+(3*60*60000));
    setCookie("chatme", nid , chatmedat.toGMTString(), "/");
}
function pmme(nid){
    jQuery('.pm_left_item').removeClass('pm_left_item_active');
    jQuery('.pm_left_item_'+nid).addClass('pm_left_item_active');
    var myhash=window.location.hash.toString();
    if( myhash!='#pmid'+nid ){
        history.pushState( null, null, "/pm#pmid"+nid );
    }
    jQuery('#pmcur').html(nid);
    
    if( jQuery('.pm_left_item_'+nid).hasClass('pm_left_item_wait') ){
        jQuery('#pmpanel_input').hide();
    }else{
        jQuery('#pmpanel_input').show();
        jQuery('#pmpanel_input_text').attr('contenteditable', true);
        jQuery('#pmpanel_input_text').focus();
    }
    jQuery('.chat_noti_item_'+nid).remove();
    jQuery('.pm_right_ttl').html('<div class="ttlin">'+jQuery('.pm_left_item_'+nid+' .ttl').html()+'</div>');
    jQuery('.closepm').show();
    pmload(nid);
}
function closepm(){
    jQuery('.pm_left_item').removeClass('pm_left_item_active');
    history.pushState( null, null, "/pm" );
    jQuery('.pm_right_ttl').html('');
    jQuery('#pm_right_body').html('<div class="pm_select">Выберите участника</div>');
    jQuery('.closepm').hide();
    jQuery('#pmcur').html('');
}
function unchatme(){
    jQuery('.mychat_item').removeClass('chatactive');
    jQuery('#chatpanel').hide(111);
    jQuery('#chatcur').html('');
    setCookie("chatme", '' , "Thu, 01 Jan 1970 00:00:01 GMT", "/");
}
function chatload(nid){
    jQuery.post(Drupal.settings.basePath + 'chat_op.php', { op: 1, nid: nid }, function( data ) { jQuery('#chatpanel_body').html(data); } );
}
function pmload(nid){
    jQuery.post(Drupal.settings.basePath + 'chat_op.php', { op: 4, nid: nid }, function( data ) { jQuery('#pm_right_body').html(data); } );
}
function chatkey(e, type){
    if(!e) var event = window.event;
    var keycode='';
    if (e.keyCode) keycode = e.keyCode;
    else if(e.which) keycode = e.which;
    
    if(keycode==13){
	    keyShift = e.shiftKey;
        if( !keyShift ){
            var text=jQuery('#'+type+'panel_input_text').html();
            if( text!='' && text!='<br>' ){
                var chatid=jQuery('#'+type+'cur').html();
                if( chatid>0 ){
                    jQuery.post(Drupal.settings.basePath + 'chat_op.php', { op: 2, nid: chatid, body: text }, function( data ) { var res=data; if(res==1){ chatupdate(); } } );
                }
            }
            jQuery('#'+type+'panel_input_text').html('');
        }
    }
}
function chatstat(){
    if( !curUid ){ return; }
    jQuery.ajax({
        url: Drupal.settings.basePath + 'curs/chat/new/'+curUid+'.txt?r='+(new Date).getTime(),
        success: function(){
            chatupdate();
        }
    });
}
function chatupdate(){
    if( chatprocess ){
        return;
    }
    if( !curUid ){ return; }
    chatprocess=1;
    jQuery.post(Drupal.settings.basePath + 'chat_op.php', { op: 3, nid: 1 }, function( data ) {
        var res = new Array();
        res=data.split('|*pdxmy2*|');
        for( var i=0; i<res.length; i++ ){
            var tmp = new Array();
            var from=0;
            var iscurnoti=0;
            
            tmp=res[i].split('|*pdxmy*|');
            if( tmp.length ){
                if( tmp[0]!=curUid ){
                    from=tmp[0];
                }else{
                    from=tmp[1];
                }
                if( from>0 ){
                    if( tmp[3].search('chatimg chatimg_'+curUid+' chatimgis')!=-1 ){
                        iscurnoti=curUid;
                    }else{
                        iscurnoti=from;
                    }
                    if( jQuery('#ichatimg_'+curUid).length ){
                        tmp[3]=tmp[3].replaceAll('<div class="chatimg chatimg_'+curUid+' chatimgis"></div>', '<div class="chatimg chatimg_'+curUid+' chatimgis"><img alt="" src="'+jQuery('#ichatimg_'+curUid).html()+'" /></div>');
                    }
                    if( jQuery('#ichatimg_'+from).length ){
                        tmp[3]=tmp[3].replaceAll('<div class="chatimg chatimg_'+from+' chatimgis"></div>', '<div class="chatimg chatimg_'+from+' chatimgis"><img alt="" src="'+jQuery('#ichatimg_'+from).html()+'" /></div>');
                    }
                    if( jQuery('#chatcur').length && jQuery('#chatcur').html()==from ){
                        if( jQuery('#chatpanel .chatid_'+tmp[2]).length ){}else{
                            if( jQuery('#chatpanel .chatnodata').length ){
                                jQuery('#chatpanel #chatpanel_body' ).html(tmp[3]);
                            }else{
                                jQuery('#chatpanel #chatpanel_body' ).append(tmp[3]);
                                chatbodytop();
                            }
                        }
                        if( !activeWindow ){
                            notiClick();
                            setFavico(1);
                        }
                    }else if( jQuery('#pmcur').length && jQuery('#pmcur').html()==from ){
                        if( jQuery('#pm_right_body .chatid_'+tmp[2]).length ){}else{
                            if( jQuery('#pm_right_body .chatnodata').length ){
                                jQuery('#pm_right_body' ).html(tmp[3]);
                            }else{
                                jQuery('#pm_right_body' ).append(tmp[3]);
                                pmbodytop();
                            }
                            pmtimeprep(tmp[2]);
                        }
                        if( !activeWindow ){
                            notiClick();
                            setFavico(1);
                        }
                    }else{
                        var ismyava='';
                        if( jQuery('#ichatimg_'+iscurnoti).length ){
                            ismyava='<div class="notiimg"><img alt="" src="'+jQuery('#ichatimg_'+iscurnoti).html()+'" /></div>';
                        }
                        
                        var editline='';
                        if( jQuery('#ichatimg_'+tmp[0]).length ){
                            editline='<img class="notireply" onclick=" chatme('+from+'); " alt="e" src="/sites/all/libraries/img/adm_edit.png" />';
                        }else{
                            editline='<a href="/pm#pmid'+from+'" class="anotireply"><img alt="e" src="/sites/all/libraries/img/adm_edit.png" /></a>';
                        }
                        jQuery('#chat_noti').append('<div class="chat_noti_item chat_noti_item_'+iscurnoti+' chat_noti_item_'+tmp[2]+'">'+ismyava+tmp[3].replaceAll('<div class="clear">&nbsp;</div>', '')+'<img class="closenoti" onclick=" jQuery(this).parent().remove(); " alt="x" src="/sites/all/libraries/img/msg_close.png" />'+editline+'</div>');
                        notiClick();
                        if( activeWindow ){
                            window.setTimeout('removeNoti('+tmp[2]+');', 3333);
                        }else{
                            setFavico(1);
                        }
                    }
                }
            }
        }
        
        chatprocess=0;
    } );
    
}
function removeNoti(id){
    jQuery('.chat_noti_item_'+id).remove();
}
function notiClick() {
  var audio = new Audio();
  audio.src = '/sites/all/libraries/snd/noti.mp3';
  audio.autoplay = true;
    delete audio;
}
function pmtimeprep(id){
    var showtime=0;
    var searchstring='';
    if( id!='' && id>0 ){
        searchstring=' .chatid_'+id;
        var tmptime=jQuery('#pm_right_body .time_vis').filter(':last').attr('class').replace('chat_time ', '').replace('time_vis ', '').replace('time_is_', '');
        if( tmptime>0 ){
            showtime=tmptime;
        }
    }
    jQuery('#pm_right_body'+searchstring+' .chat_time').each(
        function(){
            if( showtime<1 ){
                showtime=jQuery(this).html();
                jQuery(this).html( pmrandtime(jQuery(this).html()) );
                jQuery(this).addClass('time_vis');
                jQuery(this).addClass('time_is_'+showtime);
            }else{
                if( jQuery(this).html()-showtime < 333 ){
                    if( jQuery(this).parent().hasClass('chat_old_thread') ){
                        jQuery(this).hide();
                        jQuery(this).addClass('time_hide');
                    }else{
                        jQuery(this).html( pmrandtime(jQuery(this).html()) );
                        jQuery(this).addClass('time_vis');
                        jQuery(this).addClass('time_is_'+showtime);
                    }
                }else{
                    showtime=jQuery(this).html();
                    jQuery(this).html( pmrandtime(jQuery(this).html()) );
                    jQuery(this).addClass('time_vis');
                    jQuery(this).addClass('time_is_'+showtime);
                }
            }
        }
    );
}
function pmrandtime(tm){
    var out='';
    var tmpout='';
    var skipday=0;
    var mlabel='';
    tm=parseInt(tm)*1000;
    if( new Date(tm).getFullYear()==new Date(parseInt(curd)*1000).getFullYear() ){
        if( new Date(tm).getMonth()==new Date(parseInt(curd)*1000).getMonth() && new Date(tm).getDate()==new Date(parseInt(curd)*1000).getDate() ){
            skipday=1;
        }else if( new Date(tm).getMonth()==new Date(parseInt(curd-86400)*1000).getMonth() && new Date(tm).getDate()==new Date(parseInt(curd-86400)*1000).getDate() ){
            mlabel='вчера';
        }
    }else{
        out+=new Date(tm).getFullYear()+', ';
    }
    if( !skipday ){
        if( mlabel!='' ){
            out+=mlabel;
        }else{
            out+=new Date(tm).getDate();
            tmpout=new Date(tm).getMonth();
            switch(tmpout){
                case 0:
                    out+=' января';
                    break;
                case 1:
                    out+=' февраля';
                    break;
                case 2:
                    out+=' марта';
                    break;
                case 3:
                    out+=' апреля';
                    break;
                case 4:
                    out+=' мая';
                    break;
                case 5:
                    out+=' июня';
                    break;
                case 6:
                    out+=' июля';
                    break;
                case 7:
                    out+=' августа';
                    break;
                case 8:
                    out+=' сентября';
                    break;
                case 9:
                    out+=' октября';
                    break;
                case 10:
                    out+=' ноября';
                    break;
                case 11:
                    out+=' декабря';
                    break;
            }
        }
        out+=', ';
    }
    
    tmpout=new Date(tm).getHours();
    if( tmpout<10 ){
        out+='0';
    }
    out+=tmpout+':';
    tmpout=new Date(tm).getMinutes();
    if( tmpout<10 ){
        out+='0';
    }
    out+=tmpout;
    
            tmpout=new Date(tm).getDay();
            switch(tmpout){
                case 0:
                    out+=' (вс)';
                    break;
                case 1:
                    out+=' (пн)';
                    break;
                case 2:
                    out+=' (вт)';
                    break;
                case 3:
                    out+=' (ср)';
                    break;
                case 4:
                    out+=' (чт)';
                    break;
                case 5:
                    out+=' (пт)';
                    break;
                case 6:
                    out+=' (сб)';
                    break;
            }    
    
    return out;
}
function pmbodytop(){
    var pmbl = document.getElementById('pm_right_body'); pmbl.scrollTop = pmbl.scrollHeight+199;
}
function chatbodytop(){
    var chatbl = document.getElementById('chatpanel_body'); chatbl.scrollTop = chatbl.scrollHeight+199;
}
function getXmlHttp(){
  var xmlhttp;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}
function notiWait(){
    var xhr = getXmlHttp();
    xhr.open('GET', 'http://ec2-52-201-227-109.compute-1.amazonaws.com:8000/sub?m='+curUid+'&s='+curCity, true);
    xhr.onreadystatechange = function(){
        if( xhr.readyState==4 ){
            if( lastchatconn==0 || (new Date().getTime() - lastchatconn)>60 ){
                chatupdate();
                notiWait();
                lastchatconn=new Date().getTime();
            }else{
                window.setTimeout('notiWait();', 1111);
            }
            xhr.abort();
        }
    }
	xhr.send(null);
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
function mycatsort(obj){
    jQuery('.sortgroup').removeClass('mactive');
    jQuery('.sortgroup span').removeClass('active');
    jQuery(obj).parent().addClass('mactive');
    jQuery(obj).addClass('active');
}
function pdxchangesettings(setting, obj, folder){
    if( jQuery(obj).attr('checked')=='checked' ){
         jQuery.post(Drupal.settings.basePath + 'pickto_op.php', { op: 16, nid: 1, data2: setting, data3: folder }, function( data ) {  } );
    }else{
         jQuery.post(Drupal.settings.basePath + 'pickto_op.php', { op: 17, nid: 1, data2: setting, data3: folder }, function( data ) {  } );
    }
}
function favmecheck(tp){
    switch(tp){
        case 'node':
            for(i=0; i<favmenode.length; i++){
                favbuild(tp, favmenode[i]);
            }
            break;
        default:
            for(i=0; i<favmeuser.length; i++){
                favbuild(tp, favmeuser[i]);
            }
    }
}