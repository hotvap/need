var enterhide=0;
var findstart=0;
var timerepeat=0;
var findsrc='';
var buyInterval;
var curs=0;
var curs2=0;
var nnid=0;
var mis;
var yacontact='clickcontact';
nN = navigator.appName; 

function createMessage() {
  var misphploc = '/mistakes.php'
  var container = document.createElement('div')
  var scroll = dde.scrollTop || db.scrollTop;
  var mtop = scroll + 100 + 'px';
  var mleft = Math.floor(dde.clientWidth/2) - 175 + 'px';
  container.innerHTML = '<div id="mistake">\
  <div id="m_window" style="top:' + mtop + '; left:' + mleft + '";>\
        <iframe frameborder="0" name="mis" id="m_frame" src="' + misphploc + '"></iframe></div> \
  </div></div>'

  return container.firstChild
}

function positionMessage(elem) {
  elem.style.position = 'absolute';
  var pageheight = Math.max(dde.scrollHeight, db.scrollHeight, dde.clientHeight);
  var pagewidth = Math.max(dde.scrollWidth, db.scrollWidth, dde.clientWidth);
  elem.style.height = pageheight + 'px';
  elem.style.width = pagewidth + 'px';
}

function winop() {
  dde=document.documentElement;
  db=document.body;
  var messageElem = createMessage()
  positionMessage(messageElem)
  db.appendChild(messageElem)
}

function getText(e) 
{
        if (!e) e= window.event; 
        if((e.ctrlKey) && ((e.keyCode==10)||(e.keyCode==13))) 
        {CtrlEnter();} 
    return true;}
    
function mis_get_sel_text(){
   if (window.getSelection) {
    txt = window.getSelection();
    selected_text = txt.toString();
    full_text = txt.anchorNode.textContent;
    selection_start = txt.anchorOffset;
    selection_end = txt.focusOffset;
  }
  else if (document.getSelection) {
    txt = document.getSelection();
    selected_text = txt.toString();
    full_text = txt.anchorNode.textContent;
    selection_start = txt.anchorOffset;
    selection_end = txt.focusOffset;
  }
  else if (document.selection) {
    txt = document.selection.createRange();
    selected_text = txt.text;
    full_text = txt.parentElement().innerText;

    var stored_range = txt.duplicate();
    stored_range.moveToElementText(txt.parentElement());
    stored_range.setEndPoint('EndToEnd', txt);
    selection_start = stored_range.text.length - txt.text.length;
    selection_end = selection_start + selected_text.length;
  }
  else {
    return;
  }
  var txt = {
    selected_text: selected_text,
    full_text: full_text,
    selection_start: selection_start,
    selection_end: selection_end
  };
  return txt;
}

function mis_get_sel_context(sel) {
  selection_start = sel.selection_start;
  selection_end = sel.selection_end;
  if (selection_start > selection_end) {
    tmp = selection_start;
    selection_start = selection_end;
    selection_end = tmp;
  }
  
  context = sel.full_text;

  context_first = context.substring(0, selection_start);
  context_second = context.substring(selection_start, selection_end);
  context_third = context.substring(selection_end, context.length);
  context = context_first + '<strong>' + context_second + '</strong>' + context_third;
  
  context_start = selection_start - 60;
  if (context_start < 0) {
    context_start = 0;
  }

  context_end = selection_end + 60;
  if (context_end > context.length) {
    context_end = context.length;
  }

  context = context.substring(context_start, context_end);

  context_start = context.indexOf(' ') + 1;

  if (selection_start + 60 < context.length) {
    context_end = context.lastIndexOf(' ', selection_start + 60);
  }
  else {
    context_end = context.length;
  }

  selection_start = context.indexOf('<strong>');
  if (context_start > selection_start) {
    context_start = 0;
  }

  if (context_start) {
    context = context.substring(context_start, context_end);
  }

  return context;
}

function CtrlEnter(){
    var sel = mis_get_sel_text();
    if (sel.selected_text.length > 300) {
		alert('Можно выделить не более 300 символов!');
    }
    else if (sel.selected_text.length == 0) {
		alert('Выделите ошибку!');
    }
    else {
      mis = mis_get_sel_context(sel);
      winop();

    }
  };
  
 function PressLink(){
		mis = 'Пожалуйста, опишите ошибку в комментарии.';
		winop();
  };

document.onkeypress = getText;


jQuery(document).ready(function($){
	jQuery("*").removeClass("pdxneedto-hide-no-js"); // remove the hide class (see common.css)

    var myhash=window.location.hash.toString();
    if( myhash!='' ){
        if( myhash=='#showdlg' ){
            jQuery('.addfindmy').eq(0).find('span.is_a').click();
        }else if( jQuery('body.page-findmy').length ){
            myhash=myhash.replace('#findmy_nid_','');
            if( myhash>0 ){
                showfindmy(myhash);
            }
        }
    }
    
    if( jQuery('body.page-cart-checkout').length ){
        jQuery('#edit-panes-billing-billing-phone').mask( jQuery('.maskisme').html(), { placeholder: jQuery('.maskisme').html().replaceAll('9', '_') } );
    }

    jQuery("ul.mega, div.catsubmenu").addClass("open").css('display','none');
    jQuery('#catmenu li a.level1').bind('click',
        function(){
            if( jQuery(this).hasClass('active') ){
                jQuery(this).removeClass('active');
                jQuery('.catsubmenu').hide();
                return false;
            }
            var istid=jQuery(this).attr('class');
            istid=istid.replace('item ','');
            istid=istid.replace('level1 ','');
            istid=istid.replace('item ','');
            istid=istid.replace('mtid','');
    
            if( istid>=0 ){
                jQuery.post(Drupal.settings.basePath + 'html/amenu/'+istid+'.htm#start?r='+(new Date).getTime(), { }, function( data ) {  jQuery('.catsubmenu').html(data); if(istid>0){ shmyc(); } } );
                jQuery('.catsubmenu').show();
                jQuery('#catmenu li a.level1').removeClass('active');
                jQuery(this).addClass('active');
                return false;
            }
        }
    );
    
    jQuery("img.lazy").lazyload({
        threshold : 200,
        effect : "fadeIn"
    });
    jQuery("img.lazy2").lazyload({
        threshold : 200,
        effect : "fadeIn",
    });

    if( jQuery('.swiper-container-front').length ){
        var swiper = new Swiper('.swiper-container-front', {
            pagination: '.swiper-pagination-front',
            paginationClickable: true,
            spaceBetween: 30,
            initialSlide: getRandomInt(0, jQuery('.swiper-container-front .swiper-slide').length),
            onSlideChangeEnd: function(){ jQuery(".swiper-container-front .swiper-slide img.lazy").each( function(){ if( !jQuery(this).attr('src') || jQuery(this).attr('src').search('http')==-1 ){  jQuery(this).lazyload({ threshold : 200, effect : "fadeIn" }); } } ); },
        });
    }
    if( jQuery('.swiper-container-node').length ){
        var swiper = new Swiper('.swiper-container-node', {
            pagination: '.swiper-pagination-node',
            paginationClickable: true,
            spaceBetween: 30,
            onSlideChangeEnd: function(){ jQuery(".swiper-container-node .swiper-slide img.lazy").each( function(){ if( !jQuery(this).attr('src') || jQuery(this).attr('src').search('http')==-1 ){  jQuery(this).lazyload({ threshold : 200, effect : "fadeIn" }); } } ); },
        });
        jQuery(".swiper-container-node .swiper-slide span").each( function(){ 
            jQuery(this).zoom({on:'click', url: jQuery(this).attr('data-zoom-image') });
        } );
    }else if( jQuery('.nodeimage_one .swiper-slide img').length ){
        jQuery('.nodeimage_one .swiper-slide span').zoom({on:'click', url: jQuery('.nodeimage_one .swiper-slide span').attr('data-zoom-image') });
    }
    updateswiperitem();

    if( jQuery('.field-name-field-price-hour .form-text').length ){
        jQuery('.field-name-field-price-hour .form-text').click( function (){jQuery(this).select();} );
        jQuery('.field-name-field-price-hour .form-text').keypress(
            function(){
                window.setTimeout('updPrice(1)', 233);
            }
        );
        jQuery('.field-name-field-price-hour .form-text').change(
            function(){
                updPrice(1);
            }
        );
    }
    if( jQuery('.field-name-field-price-day .form-text').length ){
        jQuery('.field-name-field-price-day .form-text').click( function (){jQuery(this).select();} );
        jQuery('.field-name-field-price-day .form-text').keypress(
            function(){
                window.setTimeout('updPrice(2)', 233);
            }
        );
        jQuery('.field-name-field-price-day .form-text').change(
            function(){
                updPrice(2);
            }
        );
        jQuery('.field-name-field-price-day .form-text').blur( function (){jQuery(this).addClass('manual');} );
    }
    if( jQuery('.field-name-field-price-week .form-text').length ){
        jQuery('.field-name-field-price-week .form-text').click( function (){jQuery(this).select();} );
        jQuery('.field-name-field-price-week .form-text').keypress(
            function(){
                window.setTimeout('updPrice(3)', 233);
            }
        );
        jQuery('.field-name-field-price-week .form-text').change(
            function(){
                updPrice(3);
            }
        );
        jQuery('.field-name-field-price-week .form-text').blur( function (){jQuery(this).addClass('manual');} );
    }
    if( jQuery('.field-name-field-price-month .form-text').length ){
        jQuery('.field-name-field-price-month .form-text').click( function (){jQuery(this).select();} );
        jQuery('.field-name-field-price-month .form-text').blur( function (){jQuery(this).addClass('manual');} );
    }


    if( jQuery('.filter_item_select_brand select').length ){
        if( jQuery('#edit-brand option').length ){
            jQuery('#edit-brand option').each(
                function(){
                    if(jQuery(this).attr('value')>0){
                        jQuery('.filter_item_select_brand select').append('<option value="'+jQuery(this).attr('value')+'">'+jQuery(this).html()+'</option>');
                    }
                }
            );
            if( jQuery('#edit-brand').val()>0 ){
                jQuery('.filter_item_select_brand select').val(jQuery('#edit-brand').val());
            }
        }else{
            jQuery('.filter_item_select_brand').hide();
        }
        
    }
    
    if( jQuery('.view-display-id-findmy ol').length && !jQuery('.view-display-id-findmy .views-row-3').length ){
        jQuery('.view-display-id-findmy div.view-header').hide();
    }

    
    if( jQuery('#block_rec_items').length ){
        jQuery.post(Drupal.settings.basePath + 'html/rec.htm#start?r='+(new Date).getTime(), function( data ) { jQuery('#block_rec_items').html(data); } );
    }
    if( jQuery('#block_new_items').length ){
        jQuery.post(Drupal.settings.basePath + 'html/new.htm#start?r='+(new Date).getTime(), { }, function( data ) {  jQuery('#block_new_items').html(data); } );
    }
    if( jQuery('#block_viewed_items').length ){
        jQuery('#block_viewed_items').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
        jQuery.post(Drupal.settings.basePath + 'addinline.php', { id: 5, city: curCity }, function( data ) { jQuery('#block_viewed_items').html(data); } );
    }
    if( jQuery('#block_recall_items').length ){
        jQuery('#block_recall_items').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
        jQuery.post(Drupal.settings.basePath + 'addinline.php', { id: 6, city: curCity }, function( data ) { jQuery('#block_recall_items').html(data); } );
    }
    if( jQuery('#block_favall_items').length ){
        jQuery('#block_favall_items').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
        jQuery.post(Drupal.settings.basePath + 'addinline.php', { id: 7, city: curCity }, function( data ) { jQuery('#block_favall_items').html(data); } );
    }
    if( jQuery('#block_favuserall_items').length ){
        jQuery('#block_favuserall_items').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
        jQuery.post(Drupal.settings.basePath + 'addinline.php', { id: 8, city: curCity }, function( data ) { jQuery('#block_favuserall_items').html(data); } );
    }
    
    if( isPTid>0 && bi[isPTid].length && ball.length && jQuery('#bist1').length ){
        jQuery('#bist1').append( ball[bi[isPTid][getRandomInt(0, bi[isPTid].length-1)]] );
    }
    
    if( jQuery('#map_canvas_profile').length && window.ymaps ){
        ymaps.ready(init);
    }
    
    if( jQuery('body.logged-in').length && curUid>0 ){
        jQuery('.dealcontacts_pm_'+curUid).hide();
        addadmuser();
        if( !jQuery('body.page-user-'+curUid).length ){
            jQuery('.myuserlink').hide();
        }
        if( jQuery('.text_user_'+curUid).length ){
            jQuery('.text_user_'+curUid).append('<span class="isyou2">'+isYou2+'</span>');
        }
        if( jQuery('body.node-type-item #block-system-main .node_uid_'+curUid+' .rentis').length ){
            jQuery('body.node-type-item #block-system-main .node_uid_'+curUid+' .rentis').hide();
        }
    }
    
    if( jQuery('.form-item-field-part-und .form-radio').length && jQuery('.form-item-field-subpart-und .form-radio').length ){
        jQuery('.form-item-field-part-und .form-radio').bind('click',
            function(){
                jQuery('.form-item-field-subpart-und .form-radio').removeAttr('checked');
                jQuery.post(Drupal.settings.basePath + 'getsubparts.php', { part: jQuery(this).val(), type: 1 }, function( data ) { jQuery('#ajaxrs').html(data); } );
            }
        );
        if( jQuery('.form-item-field-part-und .form-radio[checked=checked]').length ){
            jQuery('.form-item-field-part-und .form-radio[checked=checked]').click();
        }
    }
    if( jQuery('.form-item-taxonomy-catalog-und .form-radio').length && jQuery('.form-item-field-subpart-und .form-radio').length ){
        jQuery('.form-item-taxonomy-catalog-und .form-radio').bind('click',
            function(){
                jQuery.post(Drupal.settings.basePath + 'getsubparts.php', { part: jQuery(this).val(), type: 1 }, function( data ) { jQuery('#ajaxrs').html(data); } );
            }
        );
        if( jQuery('.form-item-taxonomy-catalog-und .form-radio[checked=checked]').length ){
            jQuery('.form-item-taxonomy-catalog-und .form-radio[checked=checked]').click();
        }
    }
    if( jQuery('.form-item-taxonomy-catalog-und .form-radio').length && jQuery('.form-item-field-tags-und .form-radio').length ){
        jQuery('.form-item-taxonomy-catalog-und .form-radio').bind('click',
            function(){
                jQuery.post(Drupal.settings.basePath + 'getsubparts.php', { part: jQuery(this).val(), type: 2 }, function( data ) { jQuery('#ajaxrs').html(data); } );
            }
        );
    }
    if( jQuery('.form-item-field-tags-und .form-radio').length && jQuery('.form-item-field-subpart-und .form-radio').length ){
        jQuery('.form-item-field-subpart-und .form-radio').bind('click',
            function(){
                var curtag=0;
                if( !jQuery('body.page-node-edit').length ){
                    if( jQuery('.form-item-field-tags-und .form-radio[checked=checked]').length ){
                        curtag=jQuery('.form-item-field-tags-und .form-radio[checked=checked]').val();
                    }
                    jQuery('.form-item-field-tags-und .form-radio').removeAttr('checked');
                }
                jQuery.post(Drupal.settings.basePath + 'getsubparts.php', { part: jQuery(this).val(), type: 3 }, function( data ) { jQuery('#ajaxrs').html(data); if( curtag>0 && jQuery('#edit-field-tags-und-'+curtag).parent().parent().css('display')!='none' ){ jQuery('#edit-field-tags-und-'+curtag).attr('checked', 'checked'); } } );
            }
        );
        if( jQuery('.form-item-field-subpart-und .form-radio[checked=checked]').length ){
            jQuery('.form-item-field-subpart-und .form-radio[checked=checked]').click();
        }else if( jQuery('.form-item-taxonomy-catalog-und .form-radio[checked=checked]').length ){
            jQuery('.form-item-taxonomy-catalog-und .form-radio[checked=checked]').click();
        }
    }else{
        if( jQuery('.form-item-taxonomy-catalog-und .form-radio[checked=checked]').length ){
            jQuery('.form-item-taxonomy-catalog-und .form-radio[checked=checked]').click();
        }
    }
    
    checkTimeProkat();

    jQuery('.form-item-field-cur-und .form-radio').bind('click',
        function(){
            switch(jQuery(this).val()){
            case 2:
            case '2':
                jQuery('.cursign').html('$');
                break;
            case 3:
            case '3':
                jQuery('.cursign').html('euro');
                break;
            default:
                jQuery('.cursign').html(siteCur);
            }
        }
    );
    if( jQuery('.form-item-field-cur-und .form-radio[checked=checked]').length ){
        jQuery('.form-item-field-cur-und .form-radio[checked=checked]').click();
    }
 
    
    jQuery('#user-profile-form fieldset, #item-node-form fieldset').each(
        function(){
            if( jQuery(this).find('#edit-field-delivery1-und').length || jQuery(this).find('#edit-field-price-hour').length || jQuery(this).find('#edit-field-rent-apply4').length ){
                jQuery(this).addClass('userset_isglobal');
            }
        }
    );
    if( jQuery('#item-node-form .field-name-field-image').length ){
        window.setInterval('checkImage()', 1111);
    }
    
    
    jQuery('body').bind('click',
        function(){
            jQuery('#forreg, #othermsg').removeClass('regshow');
            jQuery('.showbrand, div.catsubmenu, #city_current_list, #inline_ajax_search_results_pre, #mistake').hide(111);
            jQuery('#catmenu li a.level1').removeClass('active');
            jQuery('.myfilter_item').removeClass('myfilter_active');
        }
    );

    if( jQuery('.maps_raion').length ){
        jQuery.post(Drupal.settings.basePath + 'showmapall.php', { tid: jQuery('.maps_raion').html(), cat: 0, city: curCity }, function( data ) { jQuery('#map_canvas_profiles_cnt').html(data); } );
    }
    if( jQuery('.maps_cat').length ){
        jQuery.post(Drupal.settings.basePath + 'showmapall.php', { tid: 0, cat: jQuery('.maps_cat').html(), city: curCity }, function( data ) { jQuery('#map_canvas_profiles_cnt').html(data); } );
    }

    
    if( jQuery('#getaboutuser').length ){
        if( isPUid>0 ){
            jQuery('#getaboutuser').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
            jQuery.post(Drupal.settings.basePath + 'html/user/'+isPUid+'.htm#start?r='+(new Date).getTime(), { }, function( data ) {  jQuery('#getaboutuser').html(data); } );
        }
    }
    if( jQuery('#linkprofile').length && curUid>0 ){
        jQuery.post(Drupal.settings.basePath + 'linkprofile.php', { uid: curUid }, function( data ) { jQuery('#linkprofile').html(data); } );
        jQuery.post(Drupal.settings.basePath + 'linkprofile_needto.php', { uid: curUid }, function( data ) { jQuery('#ajaxrs').before(data); } );
    }
    jQuery('.li_ispremium').each(
        function(){
            var prem = jQuery(this).attr('class').replace('li_ispremium li_ispremium_','');
            if( prem>0 && curd<prem ){
                prem=parseInt((prem-curd)/86400);
                prem++;
                jQuery(this).find('a.bonus_premium').html('Premium '+dtPre+' '+pdxnumfoot(prem, dtPost1, dtPost, dtPost3)+'. '+SoonMore+'?');
            }
        }
    );

	jQuery(".catsubmenu").hover(

	  function () {

        jQuery(this).parent().addClass('activejs');

	  }, 

	  function () {

	  	jQuery(this).parent().removeClass('activejs');
        jQuery(this).parent().find('a span.innera').addClass('activenojs');


	  }

	);
    
    jQuery('#inline_ajax_search').keyup(function(event) {
        if( jQuery(document).width()>585 ){
            findstart=new Date().getTime();
            window.setTimeout('findsubmit("'+jQuery(this).val()+'")', 777);
        }
        return false;
    });
    
    jQuery('form.node-form .field-type-number-integer .form-text, form#user-profile-form .field-type-number-integer .form-text, .filter_item_input_num .filter-text, .view-user-nomod td .form-text').autoNumeric('init', {aSep: ' ', aDec: ',', aPad: false, aSign: ''});
    
    if( jQuery('.body_restrict').length ){
        jQuery('.body_restrict').each(
            function(){
                if( jQuery(this).height()>233 ){
                    hidedescfull(this);
                }
            }
        );
    }

    if( jQuery('.form-dt').length ){
        jQuery.datepicker.setDefaults({
            showOn: "both",
            buttonImageOnly: true,
            buttonImage: "/sites/all/themes/pdxneedto/img/ico_cal.png",
            minDate: 1,
            maxDate: 60,
            dateFormat: 'yy-mm-dd',
        });
        jQuery('.form-dt').datepicker();
    }
        
    var url=window.location.href;
    if(url.charAt(url.length - 1)=='/') url=url+'node';


    if( jQuery('#export_cols').length ){
        jQuery('#export_cols #sortable, #export_cols2 #sortable2').sortable({ connectWith: ".connectedSortable", stop: function( event, ui ) {
            var cols= new Array();
            jQuery('#export_cols #sortable li').each(
                function(){
                    cols.push(jQuery(this).attr('id'));
                }
            );
            if( cols.length ){
                jQuery('#icols').val( cols.join('|') );
                jQuery('.savemyorder .th').html('');
                jQuery('.savemyorder').show(111);
            }
        } });
        jQuery('#export_cols #sortable, #export_cols2 #sortable2').disableSelection();
    }
    
    jQuery('.showmeuser').each(
        function(){
            var uid=jQuery(this).attr('id').replace('showmeuser', '');
            if( uid>0 ){
                jQuery.post(Drupal.settings.basePath + 'html/map/'+uid+'.htm#start?r='+(new Date).getTime(), { }, function( data ) { jQuery('#showmeuser'+uid).html(data); } );
            }
        }
    );

    setCookie("actionday", (new Date()).getDate() , "Mon, 01-Jan-2017 00:00:00 GMT", "/");
    
    var needRelated = getCookie('needRelated');
    if( !needRelated ){ needRelated=''; }
    needRelated=needRelated.replaceAll('null', '');
    if( jQuery('#content div.node-item').length ){
        nnid=jQuery('#content div.node-item').attr('id').replace('node-', '');
        if( nnid>0 ){
            
            recalc(nnid);
            
            needRelated='pdxmy_'+needRelated;
            needRelated=needRelated.replaceAll('_'+nnid, '');
            needRelated=needRelated.replaceAll('pdxmy_', '');
            needRelated=nnid+'_'+needRelated;
        }
    }
    if( needRelated && needRelated.length ){
        var arrNeed=needRelated.split('_', 50);
        jQuery('#related a.lnk').append(' ('+ (arrNeed.length-1)+')' );
        jQuery('#related').show();
        needRelated=arrNeed.join('_');
    }
    if( nnid>0 ){
        setCookie("needRelated", needRelated , "Mon, 01-Jan-2017 00:00:00 GMT", "/");
    }

        var r = (new Date).getTime();

        scrt(s3b+"a_"+curCity+".js?r="+(new Date).getDay()+'_'+(new Date).getHours());
        scrt(s3b+"b_"+curCity+".js?r="+(new Date).getDay()+'_'+(new Date).getHours());
        scrt("http://needtominsk.s3.amazonaws.com/curs/"+siteCountry+".js?r="+(new Date).getDay()+'_'+(new Date).getHours());
//        scrt("http://needtominsk.s3.amazonaws.com/curs/"+curCity+"_w.js?r="+(new Date).getDay()+'_'+(new Date).getHours());

    checkonline();

});

function testest(nid){
    jQuery('#getcmtform_'+nid).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery('#getcmtform_'+nid).load(Drupal.settings.basePath + 'getcmtform.php?nid='+nid);
}

function onbasic(obj, id){
    jQuery(obj).find('.thr').css('display', 'inline-block');
    if( id==1 ){
        jQuery('#ajaxrs').load(Drupal.settings.basePath + 'html/login.htm#start' );
    }else{
        jQuery('#ajaxrs').load(Drupal.settings.basePath + 'addblock.php?id='+id );
    }
}
function showbrand(){
    if( jQuery('.showbrand').css('display')=='block' ){
        jQuery('.showbrand').hide();
    }else{
        jQuery('.showbrand').load(Drupal.settings.basePath + 'showfindbrand.php');
    }
}
function findsubmit(val){
    if( (new Date().getTime())-findstart >= 666 ){
        if( findsrc!='' ){
            jQuery('#inline_ajax_search_container .custom-search-button').attr('src', findsrc);
            findsrc='';
        }
        if (val.length >= 3) {
            if( findsrc!='' ){}else{
                findsrc=jQuery('#inline_ajax_search_container .custom-search-button').attr('src');
                jQuery('#inline_ajax_search_container .custom-search-button').attr('src', '/sites/all/libraries/img/throbber.gif');
            }
            jQuery.post(Drupal.settings.basePath + 'autocomplete.php', { req: val }, function( data ) { jQuery('#inline_ajax_search_results_pre #inline_ajax_search_results').html(data); } );
        }else if (val.length > 0) {
            jQuery('#inline_ajax_search_results_pre #inline_ajax_search_results').html('<div class="searchresult lowcharcount">' + searchLess + '</div>');
            jQuery('#inline_ajax_search_results_pre').show(277);
        }else{
            jQuery('#inline_ajax_search_results_pre #inline_ajax_search_results').html('');
            jQuery('#inline_ajax_search_results_pre').hide(277);
        }
    }
}
function showmap(uid){
    jQuery.post(Drupal.settings.basePath + 'html/map/'+uid+'.htm#start?r='+(new Date).getTime(), { }, function( data ) {  jQuery('#showcompanyoncart .cnt').html(data); jQuery('#showcompanyoncart').show(); } );

}
function switchsubscribe(label){
    if( jQuery('#presubscribe').hasClass('presubscribeyes') ){
        jQuery('#presubscribe').removeClass('presubscribeyes');
        jQuery('#presubscribe .block-title').html(label);
    }else{
        jQuery.post(Drupal.settings.basePath + 'html/subscribe_'+curCity+'.htm#start?r='+(new Date).getTime(), { }, function( data ) {  jQuery('#presubscribe .subscribecnt').html(data); } );
        jQuery('#presubscribe').addClass('presubscribeyes');
        jQuery('#presubscribe .block-title').html('<img class="subclose" alt="X" src="/sites/all/themes/pdxneedto/img/no.png" />');
    }
}
function addadmuser(){
    if( jQuery('.user_item_'+curUid).length ){
        jQuery('.user_item_'+curUid+' div.image').append('<div class="isyou">'+isYou+'</div>');
    }
    jQuery('.item_uid_'+curUid).each(
        function(){
            var nid = jQuery(this).attr('id').replace('item_nid_','');
            if( nid>0 ){
                if( !jQuery('.myadmplace_for'+nid).length ){
                    jQuery(this).prepend('<div class="myadmplace1 myadmplace_for'+nid+'"><div class="myadmplacein"><a title="Редактировать" href="/node/'+nid+'/edit"><img src="/sites/all/libraries/img/adm_edit.png" /></a></div></div>');
                }
            }
        }
    );
    jQuery('.findmy_uid_'+curUid).each(
        function(){
            var nid = jQuery(this).attr('id').replace('findmy_nid_','');
            if( nid>0 ){
                if( !jQuery('.myadmplace_for'+nid).length ){
                    jQuery(this).prepend('<div class="myadmplace1 myadmplace_for'+nid+'"><div class="myadmplacein"><a title="Удалить" href="/node/'+nid+'/delete"><img src="/sites/all/libraries/img/adm_del.png" /></a></div></div>');
                }
            }
        }
    );
}
function buyCheck(){
    if( jQuery('#nid_price').val() && jQuery('#countbalance').val() && jQuery('#nid_price').val()>0 && jQuery('#countbalance').val()>0 ){
        if( jQuery('#countbalance').val()<30 || jQuery('#countbalance').val()>10000 ){
            jQuery('#countbalance').val(30);
        }
        jQuery('.balanceresult').html(accounting.formatNumber(jQuery('#nid_price').val()*jQuery('#countbalance').val(), 0, " ", "."));
    }
}
function balancego(nid){
    if( jQuery('#countbalance').val() && jQuery('#countbalance').val()>0 ){
        if( jQuery('#countbalance').val()<30 || jQuery('#countbalance').val()>10000 ){
            jQuery('#countbalance').val(30);
        }
        var count=jQuery('#countbalance').val();
        jQuery('#addbalancein').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
        jQuery('#addbalancein').load(Drupal.settings.basePath + 'balancego.php?nid='+nid+'&qty='+count);
    }
}
function showvideo(nid, delta){
    jQuery('.nid_video_'+nid+'_'+delta).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery.post(Drupal.settings.basePath + 'html/video/'+nid+'.htm#start?r='+(new Date).getTime(), { }, function( data ) {  jQuery('#videoblock'+nid+' .cnt').html(data); } );
}
function gotoshop(nid, page, brand){
    jQuery('.itemofuser_pager .throbber').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery('#itemofuser_'+nid).load(Drupal.settings.basePath + 'gotoshop.php?nid='+nid+'&p='+page+'&b='+brand);
}
function checkTimeProkat(){
        if( jQuery('.item_offline_text_dt').length ){
            var dt = new Date();
            dt=parseInt(dt.getTime()/1000);
            if( dt>0 ){
                jQuery('.item_offline_text_dt').each(
                    function(){
                        var isdt=jQuery(this).attr('class').replace('item_offline_text_dt ', '').replace('item_offline_text_dt_', '');
                        if( isdt>0 ){
                            if( isdt > dt ){
                                isdt=parseInt((isdt-dt)/86400);
                                isdt++;
                                var isday=dtPost;
                                switch(isdt){
                                case 1:
                                case 21:
                                    isday=dtPost1;
                                    break;
                                case 2:
                                case 3:
                                case 4:
                                case 22:
                                case 23:
                                case 24:
                                    isday=dtPost3;
                                }
                                jQuery(this).html(' '+dtPre+' '+isdt+' '+isday);
                                jQuery(this).removeClass('item_offline_text_dt');
                            }else{
                                jQuery(this).hide();
                            }
                        }
                    }
                );
            }
        }
    if( jQuery('body.front').length && timerepeat<7 ){
        window.setTimeout('checkTimeProkat();', 999);
        timerepeat++;
    }

}
function dealarch(nid){
    jQuery('#dealarch_'+nid).addClass('ajaxproc');
    jQuery.post(Drupal.settings.basePath + 'dealarch.php', { nid: nid }, function( data ) { jQuery('#dealarch_'+nid).html(data); jQuery('#dealarch_'+nid).removeClass('ajaxproc'); } );
}
function rentsmb(nid){
    var rCount=-1;
    var rEd=-1;
    var rDate=-1;
    var rD=0;
    var rZalog='';
    
    if( jQuery('#rent'+nid+'count').val() && jQuery('#rent'+nid+'count').val()>0 ){
        rCount=jQuery('#rent'+nid+'count').val();
    }
    if( jQuery('#rent'+nid+'ed').val() && jQuery('#rent'+nid+'ed').val()>0 ){
        rEd=jQuery('#rent'+nid+'ed').val();
    }
    if( jQuery('#rent'+nid+'d').val() && jQuery('#rent'+nid+'d').val()>0 ){
        rD=jQuery('#rent'+nid+'d').val();
    }
    if( jQuery('#rent'+nid+'date').val() ){
        rDate=jQuery('#rent'+nid+'date').val();
    }
    if( jQuery('#rent'+nid+'zalog').val() ){
        rZalog=jQuery('#rent'+nid+'zalog').val();
    }
    
    jQuery('#showrent'+nid+' .rentsmb').addClass('ajaxproc');
    jQuery.post(Drupal.settings.basePath + 'rentsmb.php', { nid: nid, count: rCount, ed: rEd, date: rDate, zalog: rZalog, deliv: rD }, function( data ) { jQuery('#showrent'+nid+' .rentsmb').html(data); jQuery('#showrent'+nid+' .rentsmb').removeClass('ajaxproc'); } );
}
function deal_step1_yes(nid){
    jQuery('.skipgoto_about').addClass('ajaxproc');
    jQuery.post(Drupal.settings.basePath + 'deal_step1_yes.php', { nid: nid }, function( data ) { jQuery('.skipgoto_about').html(data); jQuery('.skipgoto_about').removeClass('ajaxproc'); } );
}
function deal_step1_no(nid){
    if( jQuery('#skipgoto_no1').val() ){
        jQuery('#skipgoto_no1').removeClass('warn');
        jQuery('#skipgoto_no1').parent().addClass('ajaxproc');
        jQuery.post(Drupal.settings.basePath + 'deal_step1_no.php', { nid: nid, dt: encodeURIComponent(jQuery('#skipgoto_no1').val()) }, function( data ) { jQuery('#skipgoto_no1_txt').html(data); jQuery('#skipgoto_no1').parent().removeClass('ajaxproc'); } );
        return;
    }
    jQuery('#skipgoto_no1').addClass('warn');
    jQuery('#othermsgin .cnt').html(reqField);
    jQuery('#othermsg').addClass('regshow');
    return false;
}
function deal_step1_end(nid){
    if( jQuery('#skipgoto_end1').val() ){
        jQuery('#skipgoto_end1').removeClass('warn');
        jQuery('#skipgoto_end1').parent().addClass('ajaxproc');
        
        var rat=4;
        if( jQuery('#myrating1').attr('checked')=='checked' ){
            rat=1;
        }else if( jQuery('#myrating2').attr('checked')=='checked' ){
            rat=2;
        }else if( jQuery('#myrating3').attr('checked')=='checked' ){
            rat=3;
        }else if( jQuery('#myrating5').attr('checked')=='checked' ){
            rat=5;
        }
        
        jQuery.post(Drupal.settings.basePath + 'deal_step1_end.php', { nid: nid, rat: rat, dt: encodeURIComponent(jQuery('#skipgoto_end1').val()) }, function( data ) { jQuery('#skipgoto_end1_txt').html(data); jQuery('#skipgoto_end1').parent().removeClass('ajaxproc'); } );
        return;
    }
    jQuery('#skipgoto_end1').addClass('warn');
    jQuery('#othermsgin .cnt').html(reqField);
    jQuery('#othermsg').addClass('regshow');
    return false;
}
function deal_step2_end(nid){
    if( jQuery('#skipgoto_end2').val() ){
        jQuery('#skipgoto_end2').removeClass('warn');
        jQuery('#skipgoto_end2').parent().addClass('ajaxproc');
        
        var rat=4;
        if( jQuery('#myrating1').attr('checked')=='checked' ){
            rat=1;
        }else if( jQuery('#myrating2').attr('checked')=='checked' ){
            rat=2;
        }else if( jQuery('#myrating3').attr('checked')=='checked' ){
            rat=3;
        }else if( jQuery('#myrating5').attr('checked')=='checked' ){
            rat=5;
        }
        
        jQuery.post(Drupal.settings.basePath + 'deal_step2_end.php', { nid: nid, rat: rat, dt: encodeURIComponent(jQuery('#skipgoto_end2').val()) }, function( data ) { jQuery('#skipgoto_end2_txt').html(data); jQuery('#skipgoto_end2').parent().removeClass('ajaxproc'); } );
        return;
    }
    jQuery('#skipgoto_end2').addClass('warn');
    jQuery('#othermsgin .cnt').html(reqField);
    jQuery('#othermsg').addClass('regshow');
    return false;
}
function checkImage(){
    jQuery('#item-node-form .field-name-field-image .form-file').each(
        function(){
            if( jQuery(this).val() ){
                jQuery(this).parent().find('.form-submit').click(function() {
                  jQuery(this).trigger('mousedown');
                });
                jQuery(this).parent().find('.form-submit').trigger('click');
            }
        }
    );
}
function shmycin(tid, count){
    jQuery('.partis'+tid+'').each(
        function(){
            if( jQuery(this).hasClass('already') ){}else{
                jQuery(this).addClass('already');
                jQuery(this).append(' <span class="partcount"> '+count+'</span>');
            }
        }
    );
}
function preparecurrency(){
    jQuery('.nodeinval .isprice, div.item .isprice').not('.isnal').each(
        function(){
                var oldprice=jQuery(this).html().replaceAll(' ', '').replaceAll('&nbsp;', '');
                var price=oldprice;
                if( price>0 && curs>0 ){
                    price*=curs;
                    if(price>100 && siteCountry==62){
                        price=(Math.ceil(price/100))*100;
                    }
                    jQuery(this).html(accounting.formatNumber(price, 0, " ", "."));
                    var text=curInNal.replace("%cursign", '$').replace("%curprice", accounting.formatNumber(oldprice, 0, " ", ".")).replace("%curs", accounting.formatNumber(curs, 2, " ", ".").replace('.00', ''));
                    jQuery(this).attr('title', text );
                    jQuery(this).addClass('isnal');
                    jQuery(this).bind('click',
                        function(event){
                            stpr(event || window.event); 
                            jQuery('#othermsg .cnt').html( text.replaceAll('. ','.<br />') );
                            jQuery('#othermsg').addClass('regshow');
                        }
                    );
                }
        }
    );
    jQuery('.nodeinval2 .isprice, div.item .isprice2').not('.isnal').each(
        function(){
                var oldprice=jQuery(this).html().replaceAll(' ', '').replaceAll('&nbsp;', '');
                var price=oldprice;
                if( price>0 && curs2>0 ){
                    price*=curs2;
                    if(price>100 && siteCountry==62){
                        price=(Math.ceil(price/100))*100;
                    }
                    jQuery(this).html(accounting.formatNumber(price, 0, " ", "."));
                    var text=curInNal.replace("%cursign", 'euro').replace("%curprice", accounting.formatNumber(oldprice, 0, " ", ".")).replace("%curs", accounting.formatNumber(curs2, 2, " ", ".").replace('.00', ''));
                    jQuery(this).attr('title', text );
                    jQuery(this).addClass('isnal');
                    jQuery(this).bind('click',
                        function(event){
                            stpr(event || window.event); 
                            jQuery('#othermsg .cnt').html( text.replaceAll('. ','.<br />') );
                            jQuery('#othermsg').addClass('regshow');
                        }
                    );
                }
        }
    );
    jQuery('.nodeinval, .nodeinval2, div.item').find('.signcur').html(siteCur);
    if( curs ){
        jQuery('.showcurs').html(accounting.formatNumber(curs, 2, " ", ".").replace('.00', ''));
    }
    if( curs2 ){
        jQuery('.showcurs2').html(accounting.formatNumber(curs2, 2, " ", ".").replace('.00', ''));
    }
}
function addsel(){
    jQuery('.add_product').addClass('ajaxproc2');
    
    jQuery.post(Drupal.settings.basePath + 'html/addsel.htm#start?r='+(new Date).getTime(), { }, function( data ) { jQuery('#add_product_more .cnt').html(data); jQuery('#add_product_more').show(); jQuery('.add_product').removeClass('ajaxproc2'); } ).fail(function(e, e2) { window.alert(e+'_'+e2); });
}
function addfindmy(tid){
    jQuery('.addfindmy span.is_a').addClass('ajaxproc2');
    jQuery('#addfindmyis .cnt').load(Drupal.settings.basePath + 'addfindmy.php?tid='+tid);
}
function showfindmy(nid){
    jQuery('#findmy_nid_'+nid+' div.all').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery('#findmy_nid_'+nid).addClass('findmyopen');
    jQuery('#findmy_nid_'+nid+' span.is_a').hide();
    jQuery('#findmy_nid_'+nid+' div.all').load(Drupal.settings.basePath + 'showfindmy.php?nid='+nid);
}
function showdescfull(obj){
    jQuery(obj).parent().parent().find('.body_restrict').eq(0).removeClass('body_restrict_hide');
    jQuery(obj).html('');
}
function hidedescfull(obj){
    jQuery(obj).addClass('body_restrict_hide');
    jQuery(obj).parent().find('.body_restrict_more').eq(0).html('<a href="javascript: void(0);" onclick=" showdescfull(this); ">'+txtExpand+'</a>');
}
function admchangedelta(nid, type, field, val, typeval, delta){
    jQuery.post(Drupal.settings.basePath + 'change.php', { nid: nid, val: val, type: type, field: field, typeval: typeval, delta: delta }, function( data ) { jQuery('#ajaxrs').html(data); } );    
}
function admdeldelta(nid, type, field, typeval, delta){
    jQuery.post(Drupal.settings.basePath + 'admdel.php', { nid: nid, type: type, field: field, typeval: typeval, delta: delta }, function( data ) { } );    
}
function updateswiperitem(){
    jQuery('div.image img.lazy').each(
        function(){
            if( !jQuery(this).attr('src') || jQuery(this).attr('src').search('http')==-1 ){
                jQuery(this).lazyload({ threshold : 200, effect : "fadeIn" });
            }
        }
    );
}
function catanimate(){
    jQuery('html, body').animate({ scrollTop: jQuery('.mycatalogmore').offset().top-77 }, 500);
}
function gotocatuser(page, replace){
    var ret='.mycatalogmore';
    if( replace==1 ){
        ret='.view-content_mycat';
    }
    var sort='created';
    if( jQuery('#my_sort_by').val() ){
        sort=jQuery('#my_sort_by').val();
    }
    var fstring='';
    
    jQuery('.myf_hidden_select').each(
        function(){
            var sid=jQuery(this).attr('id').replace('my_','');
            jQuery(this).find('option').each(
                function(){
                    if( jQuery(this).attr('selected')=='selected' ){
                        if( fstring!='' ){
                            fstring+='&';
                        }
                        fstring+=sid+'[]='+jQuery(this).val();
                    }
                }
            );
        }
    );
    jQuery('.myf_hidden_num_from').each(
        function(){
            if( jQuery(this).val() ){
                var sid=jQuery(this).attr('id').replace('my_from_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'[from]='+jQuery(this).val().replaceAll(' ','');
            }
        }
    );
    jQuery('.myf_hidden_num_to').each(
        function(){
            if( jQuery(this).val() ){
                var sid=jQuery(this).attr('id').replace('my_to_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'[to]='+jQuery(this).val().replaceAll(' ','');
            }
        }
    );
    jQuery('.myf_hidden_list').each(
        function(){
            if( jQuery(this).val()==1 ){
                var sid=jQuery(this).attr('id').replace('my_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'=1';
            }
        }
    );
    
    jQuery(ret).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery(ret).load(Drupal.settings.basePath + 'gotocatuser.php?p='+page+'&sort='+encodeURIComponent(sort)+'&replace='+replace+'&str='+encodeURIComponent(fstring));
}
function gotocat(path, cat, brand, tags, page, replace, celebration){
    var ret='.mycatalogmore';
    if( replace==1 ){
        ret='.view-content_mycat';
    }
    var sort='created';
    if( jQuery('#my_sort_by').val() ){
        sort=jQuery('#my_sort_by').val();
    }
    var fstring='';
    
    jQuery('.myf_hidden_select').each(
        function(){
            var sid=jQuery(this).attr('id').replace('my_','');
            jQuery(this).find('option').each(
                function(){
                    if( jQuery(this).attr('selected')=='selected' ){
                        if( fstring!='' ){
                            fstring+='&';
                        }
                        fstring+=sid+'[]='+jQuery(this).val();
                    }
                }
            );
        }
    );
    jQuery('.myf_hidden_num_from').each(
        function(){
            if( jQuery(this).val() ){
                var sid=jQuery(this).attr('id').replace('my_from_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'[from]='+jQuery(this).val().replaceAll(' ','');
            }
        }
    );
    jQuery('.myf_hidden_num_to').each(
        function(){
            if( jQuery(this).val() ){
                var sid=jQuery(this).attr('id').replace('my_to_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'[to]='+jQuery(this).val().replaceAll(' ','');
            }
        }
    );
    jQuery('.myf_hidden_list').each(
        function(){
            if( jQuery(this).val()==1 ){
                var sid=jQuery(this).attr('id').replace('my_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'=1';
            }
        }
    );
    
    jQuery(ret).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery(ret).load(Drupal.settings.basePath + 'gotocat.php?cat='+cat+'&p='+page+'&b='+brand+'&t='+tags+'&sort='+encodeURIComponent(sort)+'&replace='+replace+'&path='+encodeURIComponent(path)+'&str='+encodeURIComponent(fstring)+'&celebration='+celebration);
}
function myfiltclick(obj){
    var myfilt_show=0;
    if( jQuery(obj).parent().hasClass('myfilter_active') ){
        myfilt_show=1;
    }
    jQuery('.myfilter_item').removeClass('myfilter_active');

    if( myfilt_show ){
    }else{
        jQuery(obj).parent().addClass('myfilter_active');
        
        if( jQuery(obj).parent().find('.myfilter_content').hasClass('myfilter_number') ){

            if( jQuery(obj).parent().find('.ffilter').length && !jQuery(obj).parent().find('.ffilter .ui-slider-handle').length ){
                        var id=jQuery(obj).parent().find('.ffilter').attr('class').replace('ffilter ', '').replace('ffilter_', '');
                        if( id!='' ){
                            var slstep=1;
                            if( siteCountry==62 && id.search('price')!=-1 ){
                                slstep=100;
                            }
                            jQuery('#filter_'+id).slider({
                       	        min: parseInt(jQuery('.minval_'+id).val()),
                                max: parseInt(jQuery('.maxval_'+id).val()),
                                values: [jQuery('#fedit_'+id+'_min').val(),jQuery('#fedit_'+id+'_max').val()],
                                range: true,
                                step: slstep,
                                stop: function(event, ui) {
                                    jQuery("#fedit_"+id+"_min").val( accounting.formatNumber( parseInt(jQuery("#filter_"+id).slider("values",0)) , 0, " ", ".") );
                                    jQuery("#fedit_"+id+"_max").val( accounting.formatNumber( parseInt(jQuery("#filter_"+id).slider("values",1)) , 0, " ", ".") );
                                },
                                slide: function(event, ui){
                                    jQuery("#fedit_"+id+"_min").val( accounting.formatNumber( parseInt(jQuery("#filter_"+id).slider("values",0)) , 0, " ", ".") );
                                    jQuery("#fedit_"+id+"_max").val( accounting.formatNumber( parseInt(jQuery("#filter_"+id).slider("values",1)) , 0, " ", ".") );
                                }
                            });
                            jQuery("input#fedit_"+id+"_min").change(function(){
                       	        var value1=parseInt( jQuery("#fedit_"+id+"_min").val().replaceAll(' ', '') );
                                var value2=parseInt( jQuery("#fedit_"+id+"_max").val().replaceAll(' ', '') );
                           	    
                                if(parseInt(value1) > parseInt(value2)){
                                    value1 = value2;
                                    jQuery("#fedit_"+id+"_min").val( accounting.formatNumber( parseInt(value1) , 0, " ", ".") );
                           	    }
                           	    jQuery("#filter_"+id).slider("values",0,value1);
                            });
                           	jQuery("#fedit_"+id+"_max").change(function(){
                       	        var value1=parseInt(jQuery("#fedit_"+id+"_min").val().replaceAll(' ', ''));
                                var value2=parseInt(jQuery("#fedit_"+id+"_max").val().replaceAll(' ', ''));
        
                           	    if(parseInt(value1) > parseInt(value2)){
                       	            value2 = value1;
                                    jQuery("#fedit_"+id+"_max").val(accounting.formatNumber( parseInt(value2) , 0, " ", "."));
                                }
                           	    jQuery("#filter_"+id).slider("values",1,value2);
                            }); 
                        }
                jQuery(obj).parent().find('.slider_from, .slider_to').autoNumeric('init', {aSep: ' ', aDec: ',', aPad: false, aSign: ''});
            }
        }
    }
    
    if( jQuery(obj).parent().position().left>597 ){
        jQuery(obj).parent().addClass('myfilter_right');
    }else{
        jQuery(obj).parent().removeClass('myfilter_right');
    }
}
function myfilt_apply_term(id, ispath, cat, brand, tags, type, celebration){
    if( jQuery('#my_'+id).length ){
        jQuery('#my_'+id+' option').removeAttr('selected');
        jQuery('.myfilter_fields_'+id+' input.form-checkbox').each(
            function(){
                if( jQuery(this).attr('checked')=='checked' ){
                    jQuery('#my_'+id+' option[value='+jQuery(this).val()+']').attr('selected', 'selected');
                }
            }
        );
        switch(type){
        case 2:
            gotocatuser(1, 1);
            break;
        default:
            gotocat(ispath, cat, brand, tags, 1, 1, celebration);
        }
    }
}
function myfilt_apply_num(id, ispath, cat, brand, tags, type, celebration){
    if( jQuery('#my_from_'+id).length && jQuery('#my_to_'+id).length ){
        if( jQuery('#fedit_'+id+'_min').val() ){
            jQuery('#my_from_'+id).val(jQuery('#fedit_'+id+'_min').val().replaceAll(' ','') );
        }else{
            jQuery('#my_from_'+id).val('');
        }
        if( jQuery('#fedit_'+id+'_max').val() ){
            jQuery('#my_to_'+id).val(jQuery('#fedit_'+id+'_max').val().replaceAll(' ','') );
        }else{
            jQuery('#my_to_'+id).val('');
        }
        switch(type){
        case 2:
            gotocatuser(1, 1);
            break;
        default:
            gotocat(ispath, cat, brand, tags, 1, 1, celebration);
        }
    }
}
function myfilt_apply_feat(ispath, cat, brand, tags, type, celebration){
    var id='';
    jQuery('.myfilt_feat').each(
        function(){
            id=jQuery(this).attr('class').replace('form-checkbox myfilt_val_term myfilt_feat myfilt_feat_', '');
            if( jQuery('#my_'+id).length ){
                jQuery('#my_'+id+' option').removeAttr('selected');
            }
        }
    );
    jQuery('.myfilt_feat').each(
        function(){
            id=jQuery(this).attr('class').replace('form-checkbox myfilt_val_term myfilt_feat myfilt_feat_', '');
            if( jQuery('#my_'+id).length ){
                if( jQuery(this).attr('checked')=='checked' ){
                    jQuery('#my_'+id+' option[value='+jQuery(this).val()+']').attr('selected', 'selected');
                }
            }
        }
    );
    
    switch(type){
    case 2:
        gotocatuser(1, 1);
        break;
    default:
        gotocat(ispath, cat, brand, tags, 1, 1, celebration);
    }
}
function myfilt_filter_reset(id, ispath, cat, brand, tags, type, celebration){
    jQuery('#my_'+id+' option').removeAttr('selected');
    jQuery('#my_'+id).val('');
    jQuery('#my_from_'+id).val('');
    jQuery('#my_to_'+id).val('');
    switch(type){
    case 2:
        gotocatuser(1, 1);
        break;
    default:
        gotocat(ispath, cat, brand, tags, 1, 1, celebration);
    }
}
function myfilt_filter_feat_reset(ispath, cat, brand, tags, type, celebration){
    jQuery('.myfilt_feat').each(
        function(){
            var id=jQuery(this).attr('class').replace('form-checkbox myfilt_val_term myfilt_feat myfilt_feat_', '');
            if( jQuery('#my_'+id).length ){
                jQuery('#my_'+id+' option').removeAttr('selected');
            }
        }
    );
    switch(type){
    case 2:
        gotocatuser(1, 1);
        break;
    default:
        gotocat(ispath, cat, brand, tags, 1, 1, celebration);
    }
}
function clearrelated(obj, op){
    switch(op){
        case 1:
            setCookie("needRelated", "" , "Mon, 01-Jan-2017 00:00:00 GMT", "/");
            break;
        case 2:
            jQuery.post(Drupal.settings.basePath + 'needto_op.php', { op: 4, nid: 1 }, function( data ) { jQuery('#ajaxrs').html(data); } );
            break;
        case 3:
            jQuery.post(Drupal.settings.basePath + 'needto_op.php', { op: 5, nid: 1 }, function( data ) { jQuery('#ajaxrs').html(data); } );
            break;
    }
    jQuery(obj).parent().parent().html('');
}    
function thismymail(){
    var p1='a href="mai',p2='needtome',p3='">',p4,p5='/a',p6='',p7='',p8=''; p1+='lto:'; p2+='@'; p2+='yandex.ru'; p4=p2; jQuery('.thismymail').html(p6+p7+p8+p7+' <span><'+p1+p2+p3+p4+'<'+p5+'></span>');
}
function athismymail(){
    var p1='mai',p2='needtome',p3='',p4,p5='',p6='',p7='',p8=''; p1+='lto:'; p2+='@'; p2+='yandex.ru'; p4=p2;
    jQuery('a.athismail').each(
        function(){
            var sbj='';
            if( jQuery(this).attr('title') ){
                sbj=jQuery(this).attr('title');
            }
            if( sbj!='' ){
                p4='?subject='+encodeURIComponent(sbj);
            }
            jQuery(this).attr('href', p1+p2+p3+p4);
            
        }
    );
}
function recalc(nid){
    if( !jQuery('.calc').length ) return;
    if( nid==0 ){
        if( nnid>0 ){
            nid=nnid;
        }
    }
    if( nid==0 ) return;
    
    var addon=0;
    var cur=1;
    if( jQuery('.nodeinval').length && curs>0 ){
        cur=curs;
    }else if( jQuery('.nodeinval2').length && curs2>0 ){
        cur=curs2;
    }
    
    if( jQuery('#calc'+nid+'count').val() && jQuery('#calc'+nid+'count').val()>0 ){
        var cnt=jQuery('#calc'+nid+'count').val();
        jQuery('#rent'+nid+'count').val(cnt);
        if( jQuery('#calc'+nid+'ed').val() && jQuery('#calc'+nid+'ed').val()>0 ){
            jQuery('#rent'+nid+'ed').val(jQuery('#calc'+nid+'ed').val());
            
            if( jQuery('#calc'+nid+'bx').length && jQuery('#calc'+nid+'bx').attr('checked')=='checked' && jQuery('#calc'+nid+'bx').val()>0 ){
                addon=jQuery('#calc'+nid+'bx').val();
                jQuery('#rent'+nid+'d').val(2);
            }
            
            var hours=0;
            var priceind=0;
            switch(jQuery('#calc'+nid+'ed').val()){
            case '1': case 1:
                hours=cnt;
                if( calcp[0]==0 ){
                    calcp[0]=calcp[1];
                }
                break;
            case '2': case 2:
                hours=cnt*24;
                break;
            case '3': case 3:
                hours=cnt*168;
                break;
            case '4': case 4:
                hours=cnt*720;
                break;
            }
            if( hours>719 ){
                priceind=3;
            }else if( hours>167 ){
                priceind=2;
            }else if( hours>23 ){
                priceind=1;
            }
            if( hours>0 ){
                var price = (parseFloat(hours*calcp[priceind])+parseInt(addon))*cur;
                if(price>100 && siteCountry==62){
                    price-=7;
                    price=(Math.ceil(price/100))*100;
                }
                
                jQuery('#calc'+nid+'res').html( accounting.formatNumber(price, 0, " ", ".")+' '+siteCur );
                return;
            }
            
        }
    }
    
    jQuery('#calc'+nid+'res').html('');
}
function checkbrand(tid, obj, id){
    if( tid==0 ){ 
        jQuery('.'+id+'_line .brands_line_item').removeClass('brands_line_active');
        jQuery('.'+id+'_line .brands_line_item_0').addClass('brands_line_active');
        jQuery('.views-exposed-form-rest .form-item-'+id+' select').val(tid);
    }else{
        jQuery('.'+id+'_line .brands_line_item_0').removeClass('brands_line_active');
        jQuery('.views-exposed-form-rest .form-item-'+id+' select option[value=0]').removeAttr('selected');
        if( jQuery(obj).hasClass('brands_line_active') ){
            jQuery(obj).removeClass('brands_line_active');
            jQuery('.views-exposed-form-rest .form-item-'+id+' select option[value='+tid+']').removeAttr('selected');
        }else{
            jQuery(obj).addClass('brands_line_active');
            jQuery('.views-exposed-form-rest .form-item-'+id+' select option[value='+tid+']').attr('selected', 'selected');
        }
    }
}
function savemyorder(type){
    if( jQuery('#icols').val() ){
        jQuery('.savemyorder .th').html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
        jQuery('.savemyorder .th').load(Drupal.settings.basePath + 'savemyorder.php?type='+type+'&cols='+encodeURIComponent(jQuery('#icols').val()));
    }
}
function smmi(uid){
    jQuery('.slinkta').addClass('thr');
    jQuery.post(Drupal.settings.basePath + 'html/userimgs/'+uid+'.htm#start?r='+(new Date).getTime(), { }, function( data ) {  jQuery('#ajaxrs').html(data); } );
}
function abuse(type, nid, obj){
    jQuery(obj).find('span').addClass('thr');
    jQuery.post(Drupal.settings.basePath + 'html/abuse'+parseInt(type)+'.htm#start?id='+parseInt(nid)+'&city='+parseInt(curCity), { }, function( data ) {  jQuery('#ajaxrs').html(data); } );
}
function getGet(name) {
    var s = window.location.search;
    s = s.match(new RegExp(name + '=([^&=]+)'));
    return s ? s[1] : false;
}
function updPrice(type){
    if( !jQuery('body.page-node-add').length ){
        return;
    }
    var id='';
    var id2='';
    var prec=1;
    switch(type){
    case 1: id='day'; id2='hour'; prec=24; break;
    case 2: id='week'; id2='day'; prec=7; break;
    case 3: id='month'; id2='day'; prec=30; break;
    }
    if( id!='' ){
        var num=jQuery('.field-name-field-price-'+id2+' .form-text').val().replaceAll(' ', '');
        if(num>0){
            if( !jQuery('.field-name-field-price-'+id+' .form-text').hasClass('manual') ){
                jQuery('.field-name-field-price-'+id+' .form-text').val(accounting.formatNumber( (num*prec) , 0, " ", "."));
            }
            jQuery('.field-name-field-price-'+id+' .form-text').change();
        }
    }
}
function catanimate(){
    jQuery('html, body').animate({ scrollTop: jQuery('.mycatalogmore').offset().top-77 }, 500);
}
function gotocatuser(page, replace){
    var ret='.mycatalogmore';
    if( replace==1 ){
        ret='.view-content_mycat';
    }
    var sort='created';
    if( jQuery('#my_sort_by').val() ){
        sort=jQuery('#my_sort_by').val();
    }
    var fstring='';
    
    jQuery('.myf_hidden_select').each(
        function(){
            var sid=jQuery(this).attr('id').replace('my_','');
            jQuery(this).find('option').each(
                function(){
                    if( jQuery(this).attr('selected')=='selected' ){
                        if( fstring!='' ){
                            fstring+='&';
                        }
                        fstring+=sid+'[]='+jQuery(this).val();
                    }
                }
            );
        }
    );
    jQuery('.myf_hidden_num_from').each(
        function(){
            if( jQuery(this).val() ){
                var sid=jQuery(this).attr('id').replace('my_from_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'[from]='+jQuery(this).val().replaceAll(' ','');
            }
        }
    );
    jQuery('.myf_hidden_num_to').each(
        function(){
            if( jQuery(this).val() ){
                var sid=jQuery(this).attr('id').replace('my_to_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'[to]='+jQuery(this).val().replaceAll(' ','');
            }
        }
    );
    jQuery('.myf_hidden_list').each(
        function(){
            if( jQuery(this).val()==1 ){
                var sid=jQuery(this).attr('id').replace('my_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'=1';
            }
        }
    );
    
    jQuery(ret).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery(ret).load(Drupal.settings.basePath + 'gotocatuser.php?p='+page+'&sort='+encodeURIComponent(sort)+'&replace='+replace+'&str='+encodeURIComponent(fstring));
}
function gotocat(path, cat, brand, tags, page, replace, celebration){
    var ret='.mycatalogmore';
    if( replace==1 ){
        ret='.view-content_mycat';
    }
    var sort='created';
    if( jQuery('#my_sort_by').val() ){
        sort=jQuery('#my_sort_by').val();
    }
    var fstring='';
    
    jQuery('.myf_hidden_select').each(
        function(){
            var sid=jQuery(this).attr('id').replace('my_','');
            jQuery(this).find('option').each(
                function(){
                    if( jQuery(this).attr('selected')=='selected' ){
                        if( fstring!='' ){
                            fstring+='&';
                        }
                        fstring+=sid+'[]='+jQuery(this).val();
                    }
                }
            );
        }
    );
    jQuery('.myf_hidden_num_from').each(
        function(){
            if( jQuery(this).val() ){
                var sid=jQuery(this).attr('id').replace('my_from_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'[from]='+jQuery(this).val().replaceAll(' ','');
            }
        }
    );
    jQuery('.myf_hidden_num_to').each(
        function(){
            if( jQuery(this).val() ){
                var sid=jQuery(this).attr('id').replace('my_to_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'[to]='+jQuery(this).val().replaceAll(' ','');
            }
        }
    );
    jQuery('.myf_hidden_list').each(
        function(){
            if( jQuery(this).val()==1 ){
                var sid=jQuery(this).attr('id').replace('my_','');
                if( fstring!='' ){
                    fstring+='&';
                }
                fstring+=sid+'=1';
            }
        }
    );
    
    jQuery(ret).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery(ret).load(Drupal.settings.basePath + 'gotocat.php?cat='+cat+'&p='+page+'&b='+brand+'&t='+tags+'&sort='+encodeURIComponent(sort)+'&replace='+replace+'&path='+encodeURIComponent(path)+'&str='+encodeURIComponent(fstring)+'&celebration='+celebration);
}
function myfiltclick(obj){
    var myfilt_show=0;
    if( jQuery(obj).parent().hasClass('myfilter_active') ){
        myfilt_show=1;
    }
    jQuery('.myfilter_item').removeClass('myfilter_active');

    if( myfilt_show ){
    }else{
        jQuery(obj).parent().addClass('myfilter_active');
        
        if( jQuery(obj).parent().find('.myfilter_content').hasClass('myfilter_number') ){

            if( jQuery(obj).parent().find('.ffilter').length && !jQuery(obj).parent().find('.ffilter .ui-slider-handle').length ){
                        var id=jQuery(obj).parent().find('.ffilter').attr('class').replace('ffilter ', '').replace('ffilter_', '');
                        if( id!='' ){
                            var slstep=1;
                            if( siteCountry==62 && id.search('price')!=-1 ){
                                slstep=100;
                            }
                            jQuery('#filter_'+id).slider({
                       	        min: parseInt(jQuery('.minval_'+id).val()),
                                max: parseInt(jQuery('.maxval_'+id).val()),
                                values: [jQuery('#fedit_'+id+'_min').val(),jQuery('#fedit_'+id+'_max').val()],
                                range: true,
                                step: slstep,
                                stop: function(event, ui) {
                                    jQuery("#fedit_"+id+"_min").val( accounting.formatNumber( parseInt(jQuery("#filter_"+id).slider("values",0)) , 0, " ", ".") );
                                    jQuery("#fedit_"+id+"_max").val( accounting.formatNumber( parseInt(jQuery("#filter_"+id).slider("values",1)) , 0, " ", ".") );
                                },
                                slide: function(event, ui){
                                    jQuery("#fedit_"+id+"_min").val( accounting.formatNumber( parseInt(jQuery("#filter_"+id).slider("values",0)) , 0, " ", ".") );
                                    jQuery("#fedit_"+id+"_max").val( accounting.formatNumber( parseInt(jQuery("#filter_"+id).slider("values",1)) , 0, " ", ".") );
                                }
                            });
                            jQuery("input#fedit_"+id+"_min").change(function(){
                       	        var value1=parseInt( jQuery("#fedit_"+id+"_min").val().replaceAll(' ', '') );
                                var value2=parseInt( jQuery("#fedit_"+id+"_max").val().replaceAll(' ', '') );
                           	    
                                if(parseInt(value1) > parseInt(value2)){
                                    value1 = value2;
                                    jQuery("#fedit_"+id+"_min").val( accounting.formatNumber( parseInt(value1) , 0, " ", ".") );
                           	    }
                           	    jQuery("#filter_"+id).slider("values",0,value1);
                            });
                           	jQuery("#fedit_"+id+"_max").change(function(){
                       	        var value1=parseInt(jQuery("#fedit_"+id+"_min").val().replaceAll(' ', ''));
                                var value2=parseInt(jQuery("#fedit_"+id+"_max").val().replaceAll(' ', ''));
        
                           	    if(parseInt(value1) > parseInt(value2)){
                       	            value2 = value1;
                                    jQuery("#fedit_"+id+"_max").val(accounting.formatNumber( parseInt(value2) , 0, " ", "."));
                                }
                           	    jQuery("#filter_"+id).slider("values",1,value2);
                            }); 
                        }
                jQuery(obj).parent().find('.slider_from, .slider_to').autoNumeric('init', {aSep: ' ', aDec: ',', aPad: false, aSign: ''});
            }
        }
    }
    
    if( jQuery(obj).parent().position().left>597 ){
        jQuery(obj).parent().addClass('myfilter_right');
    }else{
        jQuery(obj).parent().removeClass('myfilter_right');
    }
}
function myfilt_apply_term(id, ispath, cat, brand, tags, type, celebration){
    if( jQuery('#my_'+id).length ){
        jQuery('#my_'+id+' option').removeAttr('selected');
        jQuery('.myfilter_fields_'+id+' input.form-checkbox').each(
            function(){
                if( jQuery(this).attr('checked')=='checked' ){
                    jQuery('#my_'+id+' option[value='+jQuery(this).val()+']').attr('selected', 'selected');
                }
            }
        );
        switch(type){
        case 2:
            gotocatuser(1, 1);
            break;
        default:
            gotocat(ispath, cat, brand, tags, 1, 1, celebration);
        }
    }
}
function myfilt_apply_num(id, ispath, cat, brand, tags, type, celebration){
    if( jQuery('#my_from_'+id).length && jQuery('#my_to_'+id).length ){
        if( jQuery('#fedit_'+id+'_min').val() ){
            jQuery('#my_from_'+id).val(jQuery('#fedit_'+id+'_min').val().replaceAll(' ','') );
        }else{
            jQuery('#my_from_'+id).val('');
        }
        if( jQuery('#fedit_'+id+'_max').val() ){
            jQuery('#my_to_'+id).val(jQuery('#fedit_'+id+'_max').val().replaceAll(' ','') );
        }else{
            jQuery('#my_to_'+id).val('');
        }
        switch(type){
        case 2:
            gotocatuser(1, 1);
            break;
        default:
            gotocat(ispath, cat, brand, tags, 1, 1, celebration);
        }
    }
}
function myfilt_apply_feat(ispath, cat, brand, tags, type, celebration){
    var id='';
    jQuery('.myfilt_feat').each(
        function(){
            id=jQuery(this).attr('class').replace('form-checkbox myfilt_val_term myfilt_feat myfilt_feat_', '');
            if( jQuery('#my_'+id).length ){
                jQuery('#my_'+id+' option').removeAttr('selected');
            }
        }
    );
    jQuery('.myfilt_feat').each(
        function(){
            id=jQuery(this).attr('class').replace('form-checkbox myfilt_val_term myfilt_feat myfilt_feat_', '');
            if( jQuery('#my_'+id).length ){
                if( jQuery(this).attr('checked')=='checked' ){
                    jQuery('#my_'+id+' option[value='+jQuery(this).val()+']').attr('selected', 'selected');
                }
            }
        }
    );
    
    switch(type){
    case 2:
        gotocatuser(1, 1);
        break;
    default:
        gotocat(ispath, cat, brand, tags, 1, 1, celebration);
    }
}
function myfilt_filter_reset(id, ispath, cat, brand, tags, type, celebration){
    jQuery('#my_'+id+' option').removeAttr('selected');
    jQuery('#my_'+id).val('');
    jQuery('#my_from_'+id).val('');
    jQuery('#my_to_'+id).val('');
    switch(type){
    case 2:
        gotocatuser(1, 1);
        break;
    default:
        gotocat(ispath, cat, brand, tags, 1, 1, celebration);
    }
}
function myfilt_filter_feat_reset(ispath, cat, brand, tags, type, celebration){
    jQuery('.myfilt_feat').each(
        function(){
            var id=jQuery(this).attr('class').replace('form-checkbox myfilt_val_term myfilt_feat myfilt_feat_', '');
            if( jQuery('#my_'+id).length ){
                jQuery('#my_'+id+' option').removeAttr('selected');
            }
        }
    );
    switch(type){
    case 2:
        gotocatuser(1, 1);
        break;
    default:
        gotocat(ispath, cat, brand, tags, 1, 1, celebration);
    }
}
function favbuild(type, id){
    if( type=='node' && jQuery('.fav_'+type+'_'+id+' .fv').length ){
        jQuery('.fav_'+type+'_'+id+' .fv').attr('title', inFav);
    }
    if( type=='user' && jQuery('.fav_'+type+'_'+id+' .fv').length ){
        jQuery('.fav_'+type+'_'+id+' .fv').attr('title', inFav2);
    }
    jQuery('.fav_'+type+'_'+id+' .fv').addClass('favout');

    if( !jQuery('.fav_'+type+'_'+id+' .fv').length && favrepeat<7 ){
        window.setTimeout('favbuild("'+type+'", "'+id+'");', 999);
        favrepeat++;
    }
}
function favswitch(type, id){
    jQuery('.fav_'+type+'_'+id+' .fv').addClass('favproc');
    jQuery('.fav_'+type+'_'+id+' .fv').load(Drupal.settings.basePath + 'favswitch.php?id='+id+'&type='+type);
}
function showplaceonmap(nids, long, lat){
    jQuery.post(Drupal.settings.basePath + 'needto_op.php', { op: 10, nid: 1, data2: nids, long: long, lat: lat }, function( data ) { jQuery('#showcompanyoncart .cnt').html(data); jQuery('#showcompanyoncart').show(); } );
}
function setFavico(id){
    if( id<1 ){ id='.ico'; }else{ id=id+'.gif'; }
    
    var link = document.createElement("LINK");
    var head = document.getElementsByTagName("HEAD")[0];
 
    link.setAttribute("rel", "shortcut icon");
    link.setAttribute("href", '/sites/all/themes/pdxneedto/needtofavicon'+id);
    if(head.childNodes.length){
        head.insertBefore(link, head.childNodes[0]);
    }else{
        head.appendChild(link);
    }    
}
