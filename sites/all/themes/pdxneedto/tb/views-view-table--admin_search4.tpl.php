<script type="text/javascript" >
var allselect=0;
</script>
<?php

$type='product';
if( isset($_GET['type']) and strlen($_GET['type']) ){
    $type=$_GET['type'];
}
if( isset($type) and strlen($type) ){
    echo '<span id="preaddonfieldlist"></span><select id="addonfieldlist" onchange=" if( jQuery(this).val()!=\'\' ){ addonfieldlistsel(jQuery(this).val()); } "><option value="">Добавить доп. поле</option>';
    $lists=db_query('select i.field_name, i.data from {field_config_instance} as i inner join {field_config} as f on i.field_id=f.id where i.field_name not in(\'body\', \'taxonomy_catalog\', \'field_sort_price_min\', \'field_sort_price_max\', \'field_selcur\', \'field_isnone\', \'field_israt\', \'field_nb\', \'field_option\', \'field_innal\') and f.module IN (\'taxonomy\', \'number\', \'text\', \'list\', \'node_reference\') and f.active=1 and i.deleted=0 and i.bundle=:combo', array(':combo'=>$type));
    while( $list = $lists->fetchAssoc() ){
        if( strpos($list['field_name'],'field_name_')===false ){}else{
            continue;
        }
        $list['data']=unserialize($list['data']);
        echo '<option value="'.$list['field_name'].'">'.$list['data']['label'].'</option>';
    }
    echo '</select>';
}

if (isset($_FILES['fexcel']['name']) and !(strpos($_FILES['fexcel']['name'],'.')===false) and isset($_FILES['fexcel']['size']) and $_FILES['fexcel']['size']<7777777 and isset($_FILES['fexcel']['tmp_name']) and file_exists($_FILES['fexcel']['tmp_name'])){
    $ext="";
    $ext=substr($_FILES['fexcel']['name'],1+strrpos($_FILES['fexcel']['name'],"."));
    if ($ext=='jpg' || $ext=='jpeg' || $ext=='png' || $ext=='gif' ){
        
    }else{
        echo '<div><strong>Разрешены только изображения в форматах JPG, JPEG, GIF или PNG!!!</strong></div>';
    }
}


$innal_vars=array();
$tmp=db_query('select data from {field_config} where field_name=\'field_innal\'');
$tmp=$tmp->fetchAssoc();
if( isset($tmp['data']) ){
    $tmp=unserialize($tmp['data']);
    if( isset($tmp['settings']['allowed_values']) and is_array($tmp['settings']['allowed_values']) and count($tmp['settings']['allowed_values']) ){
        $innal_vars=$tmp['settings']['allowed_values'];
    }
}

?>
<table <?php if ($classes) { print 'class="'. $classes . '" '; } ?><?php print $attributes; ?>>
   <?php if (!empty($title) || !empty($caption)) : ?>
     <caption><?php print $caption . $title; ?></caption>
  <?php endif; ?>
  <?php if (!empty($header)) : ?>
    <thead>
      <tr>
        <?php foreach ($header as $field => $label):
        if( $field=='nid' ){ continue; }
        echo '<th class="'.$field.' ';
        if ($header_classes[$field]) { echo $header_classes[$field]; }
        echo '">';
        switch($field){
        case 'nothing_1':
            echo '<span id="isaddonfieldlabel">Доп. поле</span>';
            break;
        case 'nothing':
            echo '<div><label><input type="radio" value="0" id="massedit_all_0" name="massedit_all" onchange=" massedit_all_sel(this); " />-</label></div>';
            echo '<div><label><input type="radio" value="1" id="massedit_all_1" name="massedit_all" onchange=" massedit_all_sel(this); " />+</label></div>';
//            echo '<div><label><input type="radio" value="2" id="massedit_all_2" name="massedit_all" onchange=" massedit_all_sel(this); " />++</label></div>';
            break;
        case 'field_noindex_desc':
            echo '<span title="Скрыть описание в NOINDEX">';
            echo $label;
            echo '</span>';
            break;
        case 'field_innal':
            if( isset($innal_vars) and is_array($innal_vars) and count($innal_vars) ){
                echo '<select onchange=" if( jQuery(this).val()>0 ){ sendinnalto( this ); } ">';
                echo '<option value="-1">Выбрать наличие</option>';
                foreach( $innal_vars as $id=>$val ){
                    echo '<option value="'.$id.'">'.$val.'</option>';
                }
                echo '</select>';
            }
            break;
        case 'uc_product_image':
        case 'field_image2':
        case 'field_image':
/*
            echo '<form enctype="multipart/form-data" method="post" action="/admin/pub/massedit/">
        <input name="MAX_FILE_SIZE" type="hidden" value="7777777" />
        <input id="sendfiletoall" name="sendfiletoall" type="hidden" value="0" />
        <input id="sendfiletonid" name="sendfiletonid" type="hidden" value="" />
        <input name="fexcel" type="file" /><br />
        <input type="submit" onclick=" var res= sendfileto(this);  if( !res ){ return false; } " value="Добавить" />
    </form>';
*/
            echo 'Изображение';

            break;
        default:
            echo $label;
        }
        echo '</th>';
        ?>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $row_count => $row):
    ?>
      <tr <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
        <?php foreach ($row as $field => $content):
            if( $field=='nid' ){ continue; }
        ?>
          <td class="<?php echo $field.' '; if ($field_classes[$field][$row_count]) { print $field_classes[$field][$row_count]; } ?>" <?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php
            
            switch($field){
            case 'nothing_1':
                echo '<div class="addonfields" id="addonfield_'.$row['nid'].'"></div>';
                break;
            case 'nothing':
                echo '<input type="checkbox" value="'.$row['nid'].'" class="massedit_select" id="massedit_one_'.$row['nid'].'" name="massedit_one_'.$row['nid'].'" />';
                break;
            case 'title':
                if(isset($content) and strlen($content)){
                    $content=str_replace("'",'"',$content);

                echo '<textarea style="border: 1px solid #000;" cols="21" rows="9" onchange=" if( jQuery(this).val() ){ admchange('.$row['nid'].', \'node\', \'title\', jQuery(this).val(), \'\'); } ">';
                echo $content;
                echo '</textarea>';


//                    echo '<input type="text" style="border: 1px solid #000;" size="31" value="';
//                    echo $content;
//                    echo '" onchange="admchange('.$row['nid'].', \'node\', \'title\', jQuery(this).val(), \'\');" />';
                }
                break;
            case 'field_nb':
//                echo '<input type="text" style="border: 1px solid #000;" size="31" value="';
                echo '<textarea style="border: 1px solid #000;" cols="21" rows="9" onchange=" if( jQuery(this).val() ){ admchange('.$row['nid'].', \'field\', \''.$field.'\', jQuery(this).val(), \'value\'); }else{ admdel('.$row['nid'].', \'field\', \''.$field.'\', \'value\'); } ">';
                if(isset($content) and strlen($content)){
                    $content=str_replace("'",'"',$content);
                    echo $content;
                }
//                echo '" onchange=" if( jQuery(this).val() ){ admchange('.$row['nid'].', \'field\', \''.$field.'\', jQuery(this).val(), \'value\'); }else{ admdel('.$row['nid'].', \'field\', \''.$field.'\', \'value\'); } "  />';
                echo '</textarea>';
                break;
            case 'field_innal':
                if(isset($content) and is_numeric($content)){
                    if( isset($innal_vars) and is_array($innal_vars) and count($innal_vars) ){
                        echo '<select onchange=" admchange('.$row['nid'].', \'field\', \'field_innal\', jQuery(this).val(), \'value\' ); ">';
                        foreach( $innal_vars as $id=>$val ){
                            echo '<option value="'.$id.'"';
                            if( $id==$content ){
                                echo ' selected="selected"';
                            }
                            echo '>'.$val.'</option>';
                        }
                        echo '</select>';
                    }
                    
                }
                break;
            case 'field_noindex_desc':
                echo '<input type="checkbox" onchange=" var status=0; if( jQuery(this).attr(\'checked\')==true ){ status=1; } admchange('.$row['nid'].', \'field\', \'field_noindex_desc\', status, \'value\');"';
                if( $content==1 ){
                    echo ' checked="checked"';
                }
                echo ' /> ';
                break;
//            case 'uc_product_image':
//            case 'field_image2':
//            case 'field_image':
//                print str_replace('class="field-items"','class="field-items" style=" max-height:233px; overflow: hidden; "',$content);
//                break;
            default:
                print $content;
            }
            
            ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<script type="text/javascript">
function massedit_all_sel(obj){
    allselect=0;
    jQuery('.massedit_select').attr('disabled', false);
    if( jQuery(obj).val() ){
        switch(jQuery(obj).val()){
        case 0: //-
        case '0':
            jQuery('.massedit_select').attr('checked', false);
            break;
        case 1: //+
        case '1':
            jQuery('.massedit_select').attr('checked', true);
            break;
        case 2: //++
        case '2':
//            allselect=1;
            jQuery('.massedit_select').attr('checked', false);
            jQuery('.massedit_select').attr('disabled', true);
            break;
        }
    }
}
function sendfileto(){
    if( allselect==1 ){
        jQuery('#sendfiletoall').val(1);
        return 1;
    }
    if( jQuery('.massedit_select').length ){
        var tonid='';
        jQuery('.massedit_select').each(
            function(){
                if( jQuery(this).attr('checked')==true && jQuery(this).val() ){
                    tonid+=jQuery(this).val()+'|';
                }
            }
        );
        if( tonid=='' ){
            window.alert('Сначала выберите несколько товаров при помощи флажков, расположенных слева.');
            return 0;
        }
        jQuery('#sendfiletonid').val(tonid);
        return 1;
    }
    return 0;
}
function sendinnalto(obj){
    var tonid='';
    if( allselect==0 ){
        if( jQuery('.massedit_select').length ){
            jQuery('.massedit_select').each(
                function(){
                    if( jQuery(this).attr('checked')==true && jQuery(this).val() ){
                        if(tonid!=''){
                            tonid+='|px|';
                        }
                        tonid+=jQuery(this).val();
                    }
                }
            );
            if( tonid=='' ){
                jQuery(obj).val('-1');
                window.alert('Сначала выберите несколько товаров при помощи флажков, расположенных слева.');
                return;
            }            
            admchange(tonid, 'field', 'field_innal', jQuery(obj).val(), 'value');
            jQuery(obj).val('-1');
        }
    }else{
        return;
    }
}
function addonfieldlistsel(field){
    jQuery('#preaddonfieldlist').html('<img alt="" src="/misc/throbber.gif" />');

    var nids = '';
    jQuery('.massedit_select').each(
        function(){
            var tmp = jQuery(this).attr('id');
            tmp = tmp.replace('massedit_one_', '');
            if(tmp>0){
                if( nids != '' ){
                    nids +='_';
                }
                nids +=tmp;
            }
        }
    );
    
    jQuery.post(Drupal.settings.basePath + 'addonfieldlist.php', { field: field, nids: encodeURIComponent(nids), type: encodeURIComponent("<?php echo $type; ?>") }, function( data ) { jQuery('#preaddonfieldlist').html(data); } );

}

</script>
