<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */
 
 
?>
<?php
if( isset($view->human_name) ){
    switch($view->human_name){
    case 'slider':
    case 'portfoliofull':
    case 'uc_catalog_rec':
    case 'brand':
        break;
    default:
        if( strpos($output,'/libraries/img/')===false ){
            if( strpos($output,'src=')===false ){}else{
                if( strpos($output,'class="hover-preview-imgpreview')===false ){
                    $output=str_replace('src=','class="lazy" data-original=',$output).'<noscript>'.$output.'</noscript>';
                }else{
                    $output=str_replace('class="hover-preview-imgpreview','class="lazy hover-preview-imgpreview',$output);
                    $output=str_replace('src=','data-original=',$output).'<noscript>'.$output.'</noscript>';
                }
            }
        }
    }    
}
print $output; ?>
