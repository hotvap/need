<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

if( isset( $_GET['id'] ) and is_numeric($_GET['id']) ){
        $node=node_load($_GET['id']);
        webform_node_view($node,'full');
        $out= theme_webform_view($node->content);
        $out= str_replace('name="submitted[s]" value=""', 'name="submitted[s]" value="7778"', $out);
        $out= str_replace('action="/getwebform.php?id='.$_GET['id'].'"', 'action="?adm=1"', $out);
//        $out= str_replace('name="submitted[url]" value=""', 'name="submitted[url]" value="'.str_replace('"', '', $parent).'"', $out);      
        header('Content-Type: text/html; charset=utf-8');
        echo $out;
?>
<script type="text/javascript">
<?php
switch($_GET['id']){
case 1673:
    echo ' if( usN!=\'\' ){ jQuery(\'#webform-client-form-1673 .webform-component--name .form-text\').val(usN); }';    
    break;
case 135:
?>
    jQuery('#webform-client-form-135 .form-actions .form-submit').bind('click',
        function(){
            var stop=0;
            
            if( jQuery('#webform-client-form-135 #edit-submitted-name').val() ){
                jQuery('#webform-client-form-135 #edit-submitted-name').css('border','1px solid #dbdbdb');
            }else{
                jQuery('#webform-client-form-135 #edit-submitted-name').css('border','1px solid #b62d1b');
                stop=1;
            }
            
            if( jQuery('#webform-client-form-135 #edit-submitted-message').val() ){
                jQuery('#webform-client-form-135 #edit-submitted-message').css('border','1px solid #dbdbdb');
            }else{
                jQuery('#webform-client-form-135 #edit-submitted-message').css('border','1px solid #b62d1b');
                stop=1;
            }
            
            if( jQuery('#webform-client-form-135 #edit-submitted-email').val() ){
                jQuery('#webform-client-form-135 #edit-submitted-email').css('border','1px solid #dbdbdb');
            }else{
                jQuery('#webform-client-form-135 #edit-submitted-email').css('border','1px solid #b62d1b');
                stop=1;
            }

            if(stop){
                return false;
            }
        }
    );
<?
    break;
}
?>
 </script>
<?php
//        echo '<script type="text/javascript"> var p1=\'a href="mai\',p2=\'onestyle\',p3=\'">\',p4,p5=\'/a\',p6=\'\',p7=\'\',p8=\'\'; p1+=\'lto:\'; p2+=\'@\'; p2+=\'list.ru\'; p4=p2; jQuery(\'.formail\').html(p6+p7+p8+p7+\' <span><\'+p1+p2+p3+p4+\'<\'+p5+\'></span>\'); jQuery(\'.webform-component--phone input.form-text\').mask(\'+375 (99) 9999999\',{placeholder:"+375 (__) _______"}); </script>';
}

?>