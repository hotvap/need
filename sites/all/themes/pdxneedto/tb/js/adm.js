jQuery(document).ready(function($){

    jQuery('a.adm').hover(
	  function () {
	  	jQuery(this).parent().addClass('markadm');
	  }, 
	  function () {
	  	jQuery(this).parent().removeClass('markadm');
	  }
	);
    
    if( jQuery('body.page-admin-modules').length ){
        jQuery('#system-modules .description').hide();
        jQuery('#system-modules').before('<a href=" javascript:void(0); " onclick="modshowdesc();">Show/Hide module desc</a>');
    }
    if( jQuery('body.page-node-add-product').length ){
        jQuery('#edit-model').bind('focus',
            function(){
                if(jQuery(this).val()=='dev.biz.ua new articul'){
                    jQuery(this).val('');
                }
            }
        );
        jQuery('#edit-model').bind('blur',
            function(){
                if(jQuery(this).val()==''){
                    jQuery(this).val('dev.biz.ua new articul');
                }
            }
        );
    }
    
    addadm();
    window.setTimeout('preparemenu();', 777);

    if( jQuery('.form-item-fields--add-existing-field-field-name').length ){
        jQuery('.form-item-fields--add-existing-field-field-name').prepend('<input class="form-text" type="text" value="" placeholder="Filter..." onchange=" filteraddfield( jQuery(this).val() ); " />');
    }
});
jQuery(document).ajaxComplete(function(e, xhr, settings) {
  if (settings.url.search("/views/ajax") != -1){
    addadm();
  }
});

function modshowdesc(){
    jQuery('#system-modules .description').toggle();
}

function filteraddfield(num){
    if( num!='' ){
        num=num.toLowerCase();
        jQuery('#edit-fields-add-existing-field-field-name option').each(
            function(){
                if( jQuery(this).html().toLowerCase().search(num)== -1 ){
                    jQuery(this).hide();
                }else{
                    jQuery(this).show();
                }
            }
        );
    }else{
        jQuery('#edit-fields-add-existing-field-field-name option').each(
            function(){
                jQuery(this).show();
            }
        );
    }
}
function filterbrandfield(num, field){
    if( num!='' ){
        num=num.toLowerCase();
        jQuery('#'+field+' option').each(
            function(){
                if( jQuery(this).html().toLowerCase().search(num)== -1 ){
                    jQuery(this).hide();
                }else{
                    jQuery(this).show();
                }
            }
        );
    }else{
        jQuery('#'+field+' option').each(
            function(){
                jQuery(this).show();
            }
        );
    }    
}
function preparemenu(){
    jQuery('#admin-menu #admincontent').parent().find('li ul').addClass('isadmincontent');
}
function addadm(){
    jQuery('body.user-is-admin .admlnk').each(
        function(){
            var cl=jQuery(this).attr('class').split(' ');
            var path='';
            var obj='';
            var objs='';
            var ico='edit';
            var pos='left';
            var title='';
            var blank='';
            for( var i=0; i<cl.length; i++ ){
                if( cl[i]=='pdxe' ){ ico='edit'; continue; }
                if( cl[i]=='pdxc' ){ ico='clone'; continue; }
                if( cl[i]=='pdxd' ){ ico='del'; continue; }
                if( cl[i]=='pdxl' ){ ico='clear'; continue; }
                if( cl[i]=='pdxa' ){ ico='add'; continue; }

                if( cl[i]=='pdxib' ){ blank=' target="_blank"'; continue; }

                if( cl[i]=='pdxpr' ){ pos='right'; continue; }
                if( cl[i]=='pdxpt' ){ pos='top'; continue; }
                if( cl[i]=='pdxptl' ){ pos='topleft'; continue; }
                if( cl[i]=='pdxptr' ){ pos='topright'; continue; }
                if( cl[i]=='pdxpb' ){ pos='bottom'; continue; }
                if( cl[i]=='pdxpbl' ){ pos='bottomleft'; continue; }
                if( cl[i]=='pdxpbr' ){ pos='bottomright'; continue; }
                if( cl[i]=='pdxpl' ){ pos='left'; continue; }
                if( cl[i]=='pdxpl2' ){ pos='left2'; continue; }
                if( cl[i]=='pdxplr' ){ pos='leftright'; continue; }
                if( cl[i]=='pdxpi' ){ pos='in'; continue; }
                if( cl[i]=='pdxpp' ){ pos='place'; continue; }

                if( cl[i]=='pdxob' ){ path='/admin/structure/block/manage/block/'+obj+'/configure'; objs='b'; title=''; continue; }
                if( cl[i]=='pdxot' ){ path='/taxonomy/term/'+obj+'/edit'; objs='t'; title=''; continue; }
                if( cl[i]=='pdxotl' ){ path='/taxonomy/term/'+obj+'/edit'; objs='tl'; title=''; continue; }
                if( cl[i]=='pdxop' ){ path='/taxonomy/term/'+obj+'/edit'; objs='t'; title=''; continue; }
                if( cl[i]=='pdxon' ){ path='/node/'+obj+'/edit'; objs='n'; title=admEditNode; continue; }
                if( cl[i]=='pdxou' ){ path='/user/'+obj+'/edit'; objs='n'; title=admEditNode; continue; }
                if( cl[i]=='pdxona' ){ path='/node/'+obj+'/edit'; objs='na'; title=admEditNode; continue; }
                if( cl[i]=='pdxof' ){ path='/clearfilters.php?tid='+obj; objs='f'; title=''; continue; }
                if( cl[i]=='pdxoa' ){ path='/node/add/'+obj; objs='a'; title=''; continue; }
                if( cl[i]=='pdxoat' ){ path='/admin/structure/taxonomy/'+obj+'/add'; objs='at'; title=''; continue; }
                if( cl[i]=='pdxoc' ){ path='/comment/'+obj+'/edit'; objs='c'; title=''; continue; }

                if (cl[i].search('pdxobj') != -1){
                    obj=cl[i].replace('pdxobj','');
                    obj=obj.replaceAll('"', '');
                    obj=obj.replaceAll("'", '');
                    obj=obj.replaceAll('.', '');
                    obj=obj.replaceAll('<', '');
                    obj=obj.replaceAll('>', '');
                }

            }
            if( ico!='' && path!='' ){
                switch(objs){
                case 'a':
                    jQuery(this).prepend('<div class="admplace admplace_'+pos+'"><div class="admplacein"><a'+blank+' title="'+title+'" href="'+path+'"><img src="/sites/all/libraries/img/adm_'+ico+'.png" /></a><a'+blank+' href="/admin/store/search?type='+obj+'"><img src="/sites/all/libraries/img/adm_all.png" /></a></div></div>');
                    break;
                case 'at':
                    jQuery(this).prepend('<div class="admplace admplace_'+pos+'"><div class="admplacein"><a'+blank+' title="'+title+'" href="'+path+'"><img src="/sites/all/libraries/img/adm_'+ico+'.png" /></a><a'+blank+' href="/admin/structure/taxonomy/'+obj+'"><img src="/sites/all/libraries/img/adm_all.png" /></a></div></div>');
                    break;
                case 'na':
                    jQuery(this).prepend('<div class="admplace admplace_'+pos+'"><div class="admplacein"><a'+blank+' title="'+title+'" href="'+path+'"><img src="/sites/all/libraries/img/adm_'+ico+'.png" /></a><a'+blank+' href="/node/'+obj+'/clone"><img src="/sites/all/libraries/img/adm_clone.png" /></a> <a'+blank+' title="'+admDelMat+'" href="/node/'+obj+'/delete"><img src="/sites/all/libraries/img/adm_del.png" /></a></div></div>');
                    break;
                case 'tl':
                    jQuery(this).prepend('<div class="admplace admplace_'+pos+'"><div class="admplacein"><a'+blank+' title="'+title+'" href="'+path+'"><img src="/sites/all/libraries/img/adm_'+ico+'.png" /></a><a'+blank+' href="?labeldel='+obj+'"><img src="/sites/all/libraries/img/adm_del.png" /></a></div></div>');
                    break;
                case 'c':
                    jQuery(this).prepend('<div class="admplace admplace_'+pos+'"><div class="admplacein"><a'+blank+' title="'+title+'" href="'+path+'"><img src="/sites/all/libraries/img/adm_'+ico+'.png" /></a><a'+blank+' href="/comment/'+obj+'/delete"><img src="/sites/all/libraries/img/adm_del.png" /></a></div></div>');
                    break;
                default:
                    jQuery(this).prepend('<div class="admplace admplace_'+pos+'"><div class="admplacein"><a'+blank+' title="'+title+'" href="'+path+'"><img src="/sites/all/libraries/img/adm_'+ico+'.png" /></a></div></div>');
                }
            }
            jQuery(this).removeClass('admlnk');

        }
    );
}

function foradmin_status(nid){
    jQuery('.foradmin_status'+nid).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery('.foradmin_status'+nid).load(Drupal.settings.basePath + 'foradmin.php?nid='+nid+'&a=3');
}
function foradmin_publ(nid){
    jQuery('.foradmin_publ'+nid).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery('.foradmin_publ'+nid).load(Drupal.settings.basePath + 'foradmin.php?nid='+nid+'&a=0');
}
function foradmin_hit(nid){
    jQuery('.foradmin_hit'+nid).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery('.foradmin_hit'+nid).load(Drupal.settings.basePath + 'foradmin.php?nid='+nid+'&a=1');
}
function foradmin_sticky(nid){
    jQuery('.foradmin_sticky'+nid).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery('.foradmin_sticky'+nid).load(Drupal.settings.basePath + 'foradmin.php?nid='+nid+'&a=2');
}
function edittitle(nid,a){
    jQuery('#pe_'+nid+'_'+a).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery.post(Drupal.settings.basePath + 'edittitle.php', { nid: nid, a: a, title: jQuery('#e_'+nid+'_'+a).val() }, function( data ) { jQuery('#pe_'+nid+'_'+a).html(data); } );
}
function editmodel(nid,aid,oid,a){
    jQuery('#modpe_'+nid+'_'+oid+'_'+a).html('<img alt="" src="/sites/all/libraries/img/throbber.gif" />');
    jQuery('#modpe_'+nid+'_'+oid+'_'+a).load(Drupal.settings.basePath + 'edittitle.php?nid='+nid+'&aid='+aid+'&oid='+oid+'&a='+a+'&title='+encodeURIComponent( jQuery('#mode_'+nid+'_'+oid+'_'+a).val() ) );
}

function admchange(nid, type, field, val, typeval){
    jQuery.post(Drupal.settings.basePath + 'change.php', { nid: nid, val: val, type: type, field: field, typeval: typeval }, function( data ) { } );    
}
function admchange2(nid, type, field, val, typeval){
    jQuery.post(Drupal.settings.basePath + 'change.php', { nid: nid, val: val, type: type, field: field, typeval: typeval }, function( data ) {
        if(field=='track'){
            jQuery('#track_info').html(data);    
        }else{
            jQuery('.order_status_'+nid).html(data);    
        }
     } );
}
function admchangemulti(nid, type, field, obj, typeval){
    var vals='';
    jQuery(obj).find('option').each(
        function(){
            if( jQuery(this).attr('selected')==true ){
                if(vals!=''){
                    vals+='|px|';
                }
                vals+=jQuery(this).val();
            }
        }
    );
    
    jQuery.post(Drupal.settings.basePath + 'change.php', { nid: nid, val: vals, type: type, field: field, typeval: typeval }, function( data ) { } );    
}
function admdel(nid, type, field, typeval){
    jQuery.post(Drupal.settings.basePath + 'admdel.php', { nid: nid, type: type, field: field, typeval: typeval }, function( data ) { } );    
}

