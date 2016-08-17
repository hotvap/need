<?php

if( isset($fields['nid']->content) and is_numeric($fields['nid']->content) and function_exists('pdxgetmyitem') ){
    echo pdxgetmyitem($fields['nid']->content);
}



//if( isset($fields['ops']->content) and strlen($fields['ops']->content) ){
//    echo '<div class="ops">'.$fields['ops']->content.'</div>';
//}
?>
