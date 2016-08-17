<?php

/**
 * @file field.tpl.php
 * Default template implementation to display the value of a field.
 *
 * This file is not used and is here as a starting point for customization only.
 * @see theme_field()
 *
 * Available variables:
 * - $items: An array of field values. Use render() to output them.
 * - $label: The item label.
 * - $label_hidden: Whether the label display is set to 'hidden'.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - field: The current template type, i.e., "theming hook".
 *   - field-name-[field_name]: The current field name. For example, if the
 *     field name is "field_description" it would result in
 *     "field-name-field-description".
 *   - field-type-[field_type]: The current field type. For example, if the
 *     field type is "text" it would result in "field-type-text".
 *   - field-label-[label_display]: The current label position. For example, if
 *     the label position is "above" it would result in "field-label-above".
 *
 * Other variables:
 * - $element['#object']: The entity to which the field is attached.
 * - $element['#view_mode']: View mode, e.g. 'full', 'teaser'...
 * - $element['#field_name']: The field name.
 * - $element['#field_type']: The field type.
 * - $element['#field_language']: The field language.
 * - $element['#field_translatable']: Whether the field is translatable or not.
 * - $element['#label_display']: Position of label display, inline, above, or
 *   hidden.
 * - $field_name_css: The css-compatible field name.
 * - $field_type_css: The css-compatible field type.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 *
 * @see template_preprocess_field()
 * @see theme_field()
 *
 * @ingroup themeable
 */
 global $user;

?>
<div class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if (!$label_hidden): ?>
    <div class="field-label"<?php print $title_attributes; ?>><?php print $label ?>:&nbsp;</div>
  <?php endif; ?>
  <div class="field-items"<?php print $content_attributes; ?>>
    <?php
    $isimg=0;
    $fielditems=array();
    foreach ($items as $delta => $item):
    ?>
    <?php
        $myadm='';
        if( isset($item['#options']['entity']->nid) and is_numeric($item['#options']['entity']->nid) ){
            $myadm=' admlnk pdxobj'.$item['#options']['entity']->nid.' pdxona pdxpp';
        }elseif( isset($element['#items'][0]['tid']) and is_numeric($element['#items'][0]['tid']) ){
            $myadm=' admlnk pdxobj'.$element['#items'][0]['tid'].' pdxot';
        }
        $cont=render($item);
        if( strpos($cont,'<img ')===false ){}else{
            $isimg=1;
        }
        $fielditems[]='<div class="field-item '.($delta % 2 ? 'odd' : 'even').$myadm.'"'.$item_attributes[$delta].'>'.$cont.'</div>';
    
    endforeach;
    
    if( isset($fielditems) and is_array($fielditems) and count($fielditems) ){
        if( strpos($classes, ' field-name-body ')===false ){}else{
            $isimg=1;
        }
        if($isimg){
            echo implode('',$fielditems);
        }else{
            echo implode(', ',$fielditems);
        }
    }
    ?>
  </div>
</div>
<?php

    if( isset($user->roles[3]) and  ( isset($items[0]['map_container']) or (isset($items[0]['#attributes']['id']) and strlen($items[0]['#attributes']['id']))) and isset($_SESSION['pdxpdx_node_nid']) and is_numeric($_SESSION['pdxpdx_node_nid']) ){
        if( isset( $_POST['noymap'] ) ){

            $hit=db_query('select field_yandex_coords from {field_data_field_yandex} where entity_id='.$_SESSION['pdxpdx_node_nid']);
            $hit=$hit->fetchAssoc();
            if(!isset($hit['field_yandex_coords'])){
            }else{
                db_query('delete from {field_data_field_yandex} where entity_id='.$_SESSION['pdxpdx_node_nid']);
                db_query('delete from {field_revision_field_yandex} where entity_id='.$_SESSION['pdxpdx_node_nid']);
                echo '<div>Карта удалена. Вы видите ее в последний раз =)</div>';
                cache_clear_all('field:node:' . $_SESSION['pdxpdx_node_nid'], 'cache_field', true);
            }
            
        }else{
            if( strpos($items[0]['#attributes']['id'],'ymap-')===false and !isset($items[0]['map_container']) ){}else{
                echo '<form action="" method="post"><input type="submit" value="Удалить карту у данной публикации" name="noymap" /></form>';
            }
        }
    }
?>