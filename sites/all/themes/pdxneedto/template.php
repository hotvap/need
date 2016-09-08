<?php // $Id$
    global $user;
//$user = user_load(156);

if( arg(0)=='node' and is_string(arg(1)) and arg(1)=='add' and is_string(arg(2)) ){
    if( $user->uid and arg(2)=='item' ){
        $isapply=db_query('select entity_id from {field_data_field_apply1} where entity_type=\'user\' and field_apply1_value=1 and entity_id='.$user->uid);
        $isapply=$isapply->fetchAssoc();
        if( isset($isapply['entity_id']) and is_numeric($isapply['entity_id']) ){}else{
            drupal_set_message(t('Please first agree to the rules of the site.'), 'error');
            drupal_goto('user/'.$user->uid.'/edit');
        }
    }elseif( arg(2)=='findmy' ){
        drupal_goto('findmy');
    }
    
}

//pdx_reapi('parad0x', 1);

    if( !isset($_COOKIE['pdxuseruid']) or !strlen($_COOKIE['pdxuseruid']) or $_COOKIE['pdxuseruid']!=$user->uid ){
        setcookie("pdxuseruid",$user->uid);
        if(isset($user->roles[3])){
            setcookie("pdxuserrid",3);
        }elseif(isset($user->roles[4])){
            setcookie("pdxuserrid",4);
        }elseif(isset($user->roles[8])){
            setcookie("pdxuserrid",8);
        }elseif(isset($user->roles[2])){
            setcookie("pdxuserrid",2);
        }elseif(isset($user->roles[6])){
            setcookie("pdxuserrid",2);
        }else{
            setcookie("pdxuserrid",0);
        }
    }
    if( !isset($_SESSION['pdxuseruid']) or !strlen($_SESSION['pdxuseruid']) or $_SESSION['pdxuseruid']!=$user->uid ){
        $_SESSION['pdxuseruid']=$user->uid;
        if(isset($user->roles[3])){
            $_SESSION['pdxuserrid']=3;
        }elseif(isset($user->roles[4])){
            $_SESSION['pdxuserrid']=4;
        }elseif(isset($user->roles[8])){
            $_SESSION['pdxuserrid']=8;
        }elseif(isset($user->roles[2])){
            $_SESSION['pdxuserrid']=2;
        }elseif(isset($user->roles[6])){
            $_SESSION['pdxuserrid']=2;
        }else{
            $_SESSION['pdxuserrid']=0;
        }
    }else{
        if( !isset($_SESSION['pdxuserrid']) ){
            if(isset($user->roles[3])){
                $_SESSION['pdxuserrid']=3;
            }elseif(isset($user->roles[4])){
                $_SESSION['pdxuserrid']=4;
            }elseif(isset($user->roles[8])){
                $_SESSION['pdxuserrid']=8;
            }elseif(isset($user->roles[2])){
                $_SESSION['pdxuserrid']=2;
            }elseif(isset($user->roles[6])){
                $_SESSION['pdxuserrid']=2;
            }else{
                $_SESSION['pdxuserrid']=0;
            }
        }
    }
        if( !isset($_SESSION['pdxuserrid']) ){
            if(isset($user->roles[3])){
                $_SESSION['pdxuserrid']=3;
            }elseif(isset($user->roles[4])){
                $_SESSION['pdxuserrid']=4;
            }elseif(isset($user->roles[8])){
                $_SESSION['pdxuserrid']=8;
            }elseif(isset($user->roles[2])){
                $_SESSION['pdxuserrid']=2;
            }elseif(isset($user->roles[6])){
                $_SESSION['pdxuserrid']=2;
            }else{
                $_SESSION['pdxuserrid']=0;
            }
        }

    
    if( !$user->uid and arg(0)=='user' and ( !is_string(arg(1)) or arg(1)!='reset' ) ){
        if( is_numeric(arg(1)) ){}else{
            drupal_goto('node');
        }
    }
    if( arg(0)=='user' and is_string(arg(1)) and arg(1)=='reset' and is_numeric(arg(2)) ){
        if( file_exists('pdxcache/user/'.arg(2).'_sm') ){
            unlink('pdxcache/user/'.arg(2).'_sm');
        }
    }
    

if( arg(0)=='node' and is_string(arg(1)) and arg(1)=='add' and is_string(arg(2)) and arg(2)=='faq' ){
    global $user; if(isset($user->roles[3])){  }else{
        drupal_goto('faq', array(), 301);
    }
}

if( isset( $_GET['inline'] ) and strlen( $_GET['inline'] ) and $_GET['inline']==true ){
    drupal_goto(implode('/',arg()), array(), 301);
}

//*

//*/

if( isset($_SERVER['SCRIPT_NAME'])){
    switch($_SERVER['SCRIPT_NAME']){
    case '/index.php':

if( isset($_GET['sort_by']) and strlen($_GET['sort_by']) ){
    $_SESSION['pdxpdx_sort_by']=$_GET['sort_by'];
}else{
    if( isset($_SESSION['pdxpdx_sort_by']) and strlen($_SESSION['pdxpdx_sort_by']) and arg(0)=='catalog' ){
        $_REQUEST['sort_by']=$_SESSION['pdxpdx_sort_by'];
        $_GET['sort_by']=$_SESSION['pdxpdx_sort_by'];
    }
}
if( isset($_GET['sort_order']) and strlen($_GET['sort_order']) ){
    $_SESSION['pdxpdx_sort_order']=$_GET['sort_order'];
}else{
    if( isset($_SESSION['pdxpdx_sort_order']) and strlen($_SESSION['pdxpdx_sort_order']) and arg(0)=='catalog' ){
        $_REQUEST['sort_order']=$_SESSION['pdxpdx_sort_order'];
        $_GET['sort_order']=$_SESSION['pdxpdx_sort_order'];
    }
}


$_SESSION['pdxpdx_getcurtheme']='pdxneedto';

$_SESSION['pdxpdx_node_type']="";
$_SESSION['pdxpdx_node_status']="";
$_SESSION['pdxpdx_node_nid']="";
$_SESSION['pdxpdx_node_uid']="";
$_SESSION['pdxpdx_node_title']="";
$_SESSION['pdxpdx_node_tid']="";
$_SESSION['pdxpdx_node_tid_name']="";
$_SESSION['pdxpdx_par_tid']=0;
$_SESSION['pdxpdx_par_vid']=0;
$_SESSION['pdxpdx_par_part']=0;
$_SESSION['pdxpdx_par_subpart']=0;
$_SESSION['pdxpdx_node_brand']="";
$_SESSION['pdxpdx_node_brand_name']="";
$_SESSION['pdxpdx_user_name']="";
$_SESSION['pdxpdx_user_type']="";
$pdxcatis='pdxcatis';
if( defined('PDXCAT_NAME') ){
    $pdxcatis=PDXCAT_NAME;
}
switch(arg(0)){
case 'user':
    if( is_numeric(arg(1)) ){
            $correct_title=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.arg(1));
            $correct_title=$correct_title->fetchAssoc();
            if( isset($correct_title['field_name_value']) and strlen($correct_title['field_name_value']) ){
                $_SESSION['pdxpdx_user_name']= filter_xss(strip_tags($correct_title['field_name_value']));
            }
        
    }
    break;
case $pdxcatis:
    if( is_numeric(arg(3)) ){
        $_SESSION['pdxpdx_par_vid']= db_query('select d.vid, f.field_part_tid, s.field_subpart_tid from {taxonomy_term_data} as d left join {field_data_field_part} as f on (d.tid=f.entity_id and f.entity_type=\'taxonomy_term\') left join {field_data_field_subpart} as s on (d.tid=s.entity_id and s.entity_type=\'taxonomy_term\') where d.tid='.arg(3));
        $_SESSION['pdxpdx_par_vid']=$_SESSION['pdxpdx_par_vid']->fetchAssoc();
        if( isset($_SESSION['pdxpdx_par_vid']['field_part_tid']) and is_numeric($_SESSION['pdxpdx_par_vid']['field_part_tid']) ){
            $_SESSION['pdxpdx_par_part']=$_SESSION['pdxpdx_par_vid']['field_part_tid'];
        }
        if( isset($_SESSION['pdxpdx_par_vid']['field_subpart_tid']) and is_numeric($_SESSION['pdxpdx_par_vid']['field_subpart_tid']) ){
            $_SESSION['pdxpdx_par_subpart']=$_SESSION['pdxpdx_par_vid']['field_subpart_tid'];
        }
        $_SESSION['pdxpdx_par_vid']=$_SESSION['pdxpdx_par_vid']['vid'];
        $_SESSION['pdxpdx_par_tid']=arg(3);
        $_SESSION['pdxpdx_node_tid']=arg(1);
    }elseif( is_numeric(arg(2)) ){
        $_SESSION['pdxpdx_par_vid']= db_query('select d.vid, f.field_part_tid, s.field_subpart_tid from {taxonomy_term_data} as d left join {field_data_field_part} as f on (d.tid=f.entity_id and f.entity_type=\'taxonomy_term\') left join {field_data_field_subpart} as s on (d.tid=s.entity_id and s.entity_type=\'taxonomy_term\') where d.tid='.arg(2));
        $_SESSION['pdxpdx_par_vid']=$_SESSION['pdxpdx_par_vid']->fetchAssoc();
        if( isset($_SESSION['pdxpdx_par_vid']['field_part_tid']) and is_numeric($_SESSION['pdxpdx_par_vid']['field_part_tid']) ){
            $_SESSION['pdxpdx_par_part']=$_SESSION['pdxpdx_par_vid']['field_part_tid'];
        }
        if( isset($_SESSION['pdxpdx_par_vid']['field_subpart_tid']) and is_numeric($_SESSION['pdxpdx_par_vid']['field_subpart_tid']) ){
            $_SESSION['pdxpdx_par_subpart']=$_SESSION['pdxpdx_par_vid']['field_subpart_tid'];
        }
        $_SESSION['pdxpdx_par_vid']=$_SESSION['pdxpdx_par_vid']['vid'];
        $_SESSION['pdxpdx_par_tid']=arg(2);
        $_SESSION['pdxpdx_node_tid']=arg(1);
    }elseif( is_numeric(arg(1)) ){
        $_SESSION['pdxpdx_par_vid']= db_query('select d.vid from {taxonomy_term_data} as d where d.tid='.arg(1));
        $_SESSION['pdxpdx_par_vid']=$_SESSION['pdxpdx_par_vid']->fetchAssoc();
        $_SESSION['pdxpdx_par_vid']=$_SESSION['pdxpdx_par_vid']['vid'];
        $_SESSION['pdxpdx_par_tid']=arg(1);
    }
    break;
case 'taxonomy':
    if( is_numeric(arg(2)) ){
        $_SESSION['pdxpdx_par_vid']= db_query('select d.vid, f.field_part_tid, s.field_subpart_tid from {taxonomy_term_data} as d left join {field_data_field_part} as f on (d.tid=f.entity_id and f.entity_type=\'taxonomy_term\') left join {field_data_field_subpart} as s on (d.tid=s.entity_id and s.entity_type=\'taxonomy_term\') where d.tid='.arg(2));
        $_SESSION['pdxpdx_par_vid']=$_SESSION['pdxpdx_par_vid']->fetchAssoc();
        if( isset($_SESSION['pdxpdx_par_vid']['field_part_tid']) and is_numeric($_SESSION['pdxpdx_par_vid']['field_part_tid']) ){
            $_SESSION['pdxpdx_par_part']=$_SESSION['pdxpdx_par_vid']['field_part_tid'];
        }
        if( isset($_SESSION['pdxpdx_par_vid']['field_subpart_tid']) and is_numeric($_SESSION['pdxpdx_par_vid']['field_subpart_tid']) ){
            $_SESSION['pdxpdx_par_subpart']=$_SESSION['pdxpdx_par_vid']['field_subpart_tid'];
        }
        $_SESSION['pdxpdx_par_vid']=$_SESSION['pdxpdx_par_vid']['vid'];
        $_SESSION['pdxpdx_par_tid']=arg(2);
    }
    break;
case 'node':
	if(is_numeric(arg(1))){
		$_SESSION['pdxpdx_node_type']=db_query("select * from {node} where nid=".arg(1));
        $_SESSION['pdxpdx_node_type']=$_SESSION['pdxpdx_node_type']->fetchAssoc();
		if(!$_SESSION['pdxpdx_node_type']) {
            $_SESSION['pdxpdx_node_type']="";
            $_SESSION['pdxpdx_node_status']="";
            $_SESSION['pdxpdx_node_nid']="";
            $_SESSION['pdxpdx_node_uid']="";
            $_SESSION['pdxpdx_node_title']="";
            $_SESSION['pdxpdx_node_tid']="";
            $_SESSION['pdxpdx_node_brand']="";
            $_SESSION['pdxpdx_node_brand_name']="";
            $_SESSION['pdxpdx_node_tid_name']="";
        }else{
            if( is_string(arg(2)) and arg(2)=='clone' ){
                unset($_GET['destination']);
                unset($_REQUEST['destination']);
                drupal_goto('node/add/'.$_SESSION['pdxpdx_node_type']['type'], array('query'=>array('clone'=>arg(1))));
            }

  		    $_SESSION['pdxpdx_node_nid']=$_SESSION['pdxpdx_node_type']['nid'];
  		    $_SESSION['pdxpdx_node_uid']=$_SESSION['pdxpdx_node_type']['uid'];
  		    $_SESSION['pdxpdx_node_status']=$_SESSION['pdxpdx_node_type']['status'];
  		    $_SESSION['pdxpdx_node_title']=str_replace('"', '', $_SESSION['pdxpdx_node_type']['title']);
       	    $_SESSION['pdxpdx_node_type']=$_SESSION['pdxpdx_node_type']['type'];

                $term=db_query('select t.taxonomy_catalog_tid, i.name from {field_data_taxonomy_catalog} as t inner join {taxonomy_term_data} as i on t.taxonomy_catalog_tid=i.tid where t.entity_id='.$_SESSION['pdxpdx_node_nid']);
                $term=$term->fetchAssoc();
                if(isset($term['taxonomy_catalog_tid']) and is_numeric($term['taxonomy_catalog_tid'])){
                    $_SESSION['pdxpdx_node_tid_name']=str_replace('"', '', $term['name']);
                    $_SESSION['pdxpdx_node_tid']=$term['taxonomy_catalog_tid'];
                }else{
                    $_SESSION['pdxpdx_node_tid']="";
                    $_SESSION['pdxpdx_node_tid_name']="";
                }
                
                $term=db_query('select t.tid, d.name from {taxonomy_index} as t inner join {taxonomy_term_data} as d on t.tid=d.tid where d.vid=5 and t.nid='.$_SESSION['pdxpdx_node_nid']);
                $term=$term->fetchAssoc();
                if( isset($term['tid']) and is_numeric($term['tid']) ){
                    $_SESSION['pdxpdx_node_brand_name']=str_replace('"', '', $term['name']);
                    $_SESSION['pdxpdx_node_brand']=$term['tid'];
                }else{
                    $_SESSION['pdxpdx_node_brand']="";
                    $_SESSION['pdxpdx_node_brand_name']="";
                }

        }
	}
	break;
/*
case 'comment':
    if( is_string(arg(1)) and arg(1)=='reply' and is_numeric(arg(2)) and is_numeric(arg(3)) ){
        $nt=db_query('select subject from {comment} where cid='.arg(3));
        $nt=$nt->fetchAssoc();
        if( isset($nt['subject']) and strlen($nt['subject']) ){
            $_SESSION['cmtitle']=$nt['subject'];
        }
    }
	break;
*/
}
if(isset($_SESSION['pdxpdx_node_tid']) and is_numeric($_SESSION['pdxpdx_node_tid']) and $_SESSION['pdxpdx_node_tid']>0){
    if(function_exists('taxonomy_get_parents_all')){
        $parents=taxonomy_get_parents_all($_SESSION['pdxpdx_node_tid']);
        if($parents and is_array($parents) and count($parents)){
            foreach($parents as $parent){
                $_SESSION['pdxpdx_par_tid']=$parent->tid;
            }
        }
    }
}

        break;
    default:

}
}


// we define a global tag to use in diferent templates
if (defined('OUTTAG')!=true) define('OUTTAG', ( theme_get_setting('outside_tags') ? 'p' : 'h2' ) );


/**
 * Overrides of theme implementations
 */
function pdxneedto_theme() {
  return array(
    'custom_links' => array( // function that renders classic primary menu with <h2> 
      'variables' => array('links' => NULL, 'attributes' => NULL, 'heading' => NULL),
    ),
    'mega_menu' => array(
      'variables' => array('menu' => NULL),
    ),
  );
}

/**
 * Custom primary menu with <h2> for each item
 */
function pdxneedto_custom_links($variables) {
  global $language_url;
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {
        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = array($key);

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
          && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '><h2>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</h2></li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

/**
 * Mega drop down primary links.
 *
 * param <array> $menu
 *   Full array of main menu
 *
 * return string
 *   Html with mega menu to printo into page
 */
function pdxneedto_mega_menu($variables) {
global $user;

  $menu   = $variables['menu'];
  $alt    = theme_get_setting('menu_alt_class');

  $all_num=0;
  foreach ($menu as $key => $value) {
  	if ($value['link']['hidden'] != 1) { $all_num++; }
  }
//if($all_num>0){
//    $awidth=floor(1035/$all_num);
//}
  $output = '<ul class="megamenu-1">'; // open list

  $count_main_links = 1;
$first_num=0;
  foreach ($menu as $key => $value) {
//    echo '<pre>'.print_r($value['below'], true).'</pre>';
    if ($value['link']['hidden'] != 1) { // check if the link is hidden
//        if( $user->uid && $value['link']['href']=='node/113'){
//            continue;
//        }
	  $first_num++;
      $id = 'menu-main-title-' . $value['link']['mlid']; // give an unique id for better styling
      $options = array();
      if (isset($value['link']['options']['attributes']['title'])) {
        $options = array('attributes' => array('title' => $value['link']['options']['attributes']['title']));
      }

//        $options2 = array();
//        if($awidth>0){
//            $options2 = array('attributes' => array('style' => 'width:'.$awidth.'px'));
//        }
//        $options['html']=true;

        $output .= '<li class="megamenu-li-first-level primlink'.$first_num;
		if($first_num==$all_num){
			$output .= ' last';
		}
        
        if($value['link']['href']=='user'){
            if( $user->uid ){
                $value['link']['link_title']='Личный кабинет';
            }else{
                $options['attributes']['class'][]='colorbox-inline';
                $value['link']['href']='user?inline=true&width=233#user-login-form';
            }
        }
        $options['attributes']['target'][]='_blank';
        $options['attributes']['rel'][]='nofollow';
        if( isset($_SESSION['pdxpdx_node_type']) and strlen($_SESSION['pdxpdx_node_type']) ){
            switch($_SESSION['pdxpdx_node_type']){
            case 'article': if( $value['link']['href']=='node/135' ){ $output .= ' active'; } break;
            case 'news': if($value['link']['href']=='news'){ $output .= ' active'; } break;
            case 'product': if($value['link']['href']=='catalog'){ $output .= ' active'; } break;
            case 'action': if($value['link']['href']=='action'){ $output .= ' active'; } break;
            case 'video_youtube': if($value['link']['href']=='video'){ $output .= ' active'; } break;
            }
        }elseif( is_string(arg(0)) and is_numeric(arg(1)) ){
            switch(arg(0)){
                case 'txt': if($value['link']['href']=='txt'){ $output .= ' active'; } break;
                case 'news': if($value['link']['href']=='news'){ $output .= ' active'; } break;
                case 'catalog': if($value['link']['href']=='catalog'){ $output .= ' active'; } break;
            }
        }
        
        if (count($value['below']) > 0 ) {
            $activein=0;
            foreach ($value['below'] as $key2 => $value2) {
                $test=l($value2['link']['link_title'], $value2['link']['href'], array());
                if(strpos($test,'active')===false){}else{ $activein=1; }
            }
            if($activein){
                $output .= ' activein';
            }
        }

        if (count($value['below']) > 0 ) {
            $output .= ' expand';
        }
		if(strpos(l($value['link']['link_title'], $value['link']['href'], $options),'class="active"')===false){}else{ $output .= ' active'; }
		$output .= '" id="' . $id . '">';
        $options['html']=true;

/*
        if(isset($user->roles[3])){
            $pdxshowhelp=variable_get('pdxshowhelp', 0);
            if(!$pdxshowhelp){
                $output .= '<div class="admin">';
                $output .= '<a title="Редактировать ссылку меню" href="/admin/structure/menu/item/'.$value['link']['mlid'].'/edit"> Р </a>';
                $output .= '<a title="Удалить ссылку меню" href="/admin/structure/menu/item/'.$value['link']['mlid'].'/delete"> У </a>';
                $output .= '</div>';
            }
        }
*/
        if($value['link']['href']=='javascript: void(0);'){
            $output .= '<a href="javascript: void(0);">'.$value['link']['link_title'].'</a>';
        }else{
            if($value['link']['href']=='bookmarks' and module_exists('flag')){
                $value['link']['link_title'].=' (<span class="curmarks">';
                $flagcount = flag_get_user_flags('node',NULL,$user->uid);
                if(isset($flagcount['bookmarks']) and is_array($flagcount['bookmarks'])){
                    $value['link']['link_title'].= count($flagcount['bookmarks']); }else{ $value['link']['link_title'].='0'; }
                $value['link']['link_title'].='</span>)';
            }
            if( strpos($value['link']['href'],'#')===false ){
        	   $output .= l($value['link']['link_title'], $value['link']['href'], $options);
            }else{
        	   $output .= '<a class="colorbox-inline" href="/'.$value['link']['href'].'">'.$value['link']['link_title'].'</a>';
            }
         }

//		$output .= l('<span>'.$value['link']['link_title'].'</span>', $value['link']['href'], $options);
//        $path=path_load($value['link']['href']);
//        if(strlen($path['alias'])) $path=$path['alias']; else $path=$value['link']['href'];
//        $output .= '<a href="/'.$path.'">'.$value['link']['link_title'].'</a>';
        $output .= '';

      $class = "";
      $altclass = "";

      if (in_array($count_main_links, $alt)) { // add the alt class based on theme settings
        $altclass = " alt";
      }

      switch (count($value['below'])) { // choose mega class (div width based on the numbers of columns)
        case 1:
          $class = 'one-col' . $altclass;
          break;
        case 2:
          $class = 'two-col' . $altclass;
          break;
        case 3:
          $class = 'three-col' . $altclass;
          break;
        case 4:
          $class = 'four-col' . $altclass;
          break;
        case 5:
          $class = 'five-col' . $altclass;
          break;
        case 6:
          $class = 'six-col' . $altclass;
          break;
      }
      if (count($value['below']) > 0 ) { // check if we have children
        $output .= '<ul class="mega ' . $class . '" style="display: none;">';
        $all2_num=0;
        foreach ($value['below'] as $key2 => $value2) {
            if ($value2['link']['hidden'] != 1) {
                $all2_num++;
            }
        }

        $first_num2=0;
        foreach ($value['below'] as $key2 => $value2) {
          if ($value2['below']) {
            $output .= '<div class="menu-section">'; // open div menusection
          }

          $id = 'menu-section-title-' . $value2['link']['mlid']; // give an unique id for better styling
          $options = array('class' => array('menu-section-link'));
          if (isset($value2['link']['options']['attributes']['title'])) {
            $options['attributes'] = array('title' => $value2['link']['options']['attributes']['title']);
          }
          
          if ($value2['link']['hidden'] != 1) { // check if the link is hidden
            $first_num2++;
            $output .= '<li class="menu-section-title prim2link'.$first_num2;
    		if($first_num2==$all2_num){
    			$output .= ' last2';
    		}
            $output .= '" id="' . $id . '">' . l($value2['link']['link_title'], $value2['link']['href'], $options).'</li>';
          }

          if ($value2['below']) {
            $output .= '<ul class="megamenu-2">'; // open 2nd level list

            foreach ($value2['below'] as $key3 => $value3) {
              $options = array('class' => array('menu-leaf-link'));
              if (isset($value3['link']['options']['attributes']['title'])) {
                $options['attributes'] = array('title' => $value3['link']['options']['attributes']['title']);
              }

              if ($value3['link']['hidden'] != 1) { // check if the link is hidden
                $output .= '<li class="menu-leaf-list">' . l($value3['link']['link_title'], $value3['link']['href'], $options) . '</li>'; // 2nd level <li>
              }
            } // end third foreach

            $output .= '</ul>'; // close 2nd level <ul>

            if (theme_get_setting('menu_headings') == 1) { // close the list only if we use <li>
              $output .= '</li>'; // close 2ndlevel <li>
              $output .= '</ul>'; // close section <ul>
            }

            $output .= '</div>'; // close <div> menu-section
          }
        } // end second foreach

        // $output .= '<div class="closepanel"><span class="close-panel" title="close this panel">' . t('close this panel') . '</span></div>';
        $output .= '</ul>'; // close <div> mega
      } // end check for children
      $output .= '</li>'; // close first level <li>
      $count_main_links++;
    } // end check if link is hidden
  } //end first foreach
//  $output .= '<li><a href="#" class="menu-info"></a></li>';
  $output .= '</ul>'; // close first level <ul>

  return $output;
}



/**
 * Additional page variables
 */
function pdxneedto_preprocess_page(&$vars) {

global $user; if(isset($user->roles[3]) or isset($user->roles[4])){
//    drupal_add_js(path_to_theme() . '/tb/js/adm.js', array('scope' => 'footer', 'weight' => 9) );
//    drupal_add_css('sites/all/themes/pdxneedto/tb/css/adm.css', array('group' => CSS_THEME, 'every_page' => TRUE));

    if( function_exists('getisadm') ){
        $isadmproduct=getisadm();
        switch($isadmproduct){
//        case 1: $vars['theme_hook_suggestions'][] = 'page__isadmproduct'; break;
        case 2: $vars['theme_hook_suggestions'][] = 'page__isadmstock'; break;
        case 3: $vars['theme_hook_suggestions'][] = 'page__isadmorders'; break;
        case 4: $vars['theme_hook_suggestions'][] = 'page__isadmordercreate'; break;
        case 5: $vars['theme_hook_suggestions'][] = 'page__isadmreserve'; break;
        }
    }
}
//drupal_add_css('sites/all/themes/pdxneedto/tb/css/bootstrap.min.css', array('group' => CSS_THEME, 'every_page' => TRUE));

    
    
//    drupal_add_library('system', 'ui');
    drupal_add_library('system', 'ui.datepicker');
    if( defined('PDXCAT_NAME') and strlen(PDXCAT_NAME) and arg(0)==PDXCAT_NAME ){
        drupal_add_library('system', 'ui.slider');
    }
    if( arg(0)=='user' and is_string(arg(1)) and arg(1)=='item' and is_string(arg(2)) and arg(2)=='export' ){
        drupal_add_library('system', 'ui.sortable');
    }

  // stores single sidebar presence into a variable
  $vars['exception'] = "";
  if($vars['page']['sidebar_second']) {
    $vars['exception'] = 2;
  }
  else if($vars['page']['sidebar_first']){
    $vars['exception'] = 1;
  }
  
  
  // LOGO SECTION  ==============================================================
  // site logo

/*
  $myalt='';
  if( function_exists('page_title_page_get_title') ){
    $myalt=page_title_page_get_title();
  }else{
    $myalt=drupal_get_title();
  }
*/
if( !PDX_ISLOCAL ){
    $vars['logo']='http://d4zbhil5kxgyr.cloudfront.net/static/needtome/logo.png';
}
  $vars['imagelogo'] = theme('image', array(
    'path' => $vars['logo'],
    'alt'  => $vars['site_name'],
    'getsize' => FALSE,
    'attributes' => array('id' => 'logo'),
  ));

if(drupal_is_front_page()){}else{
  $vars['imagelogo'] = l(
    $vars['imagelogo'],
    '<front>',
    array(
      'html' => TRUE,
      'attributes' => array(
        'title' => $vars['site_name'],
      )
    )
  );
}


  // MENU SECTION ==============================================================

  if (isset($vars['main_menu'])) {
    $vars['primary_nav'] = theme('links__system_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('main-menu')
                )
        ));
  }
  else {
    $vars['primary_nav'] = FALSE;
  }
  // primary links markup
  if (theme_get_setting('menu_type') == 2) { // use mega menu
    $menu=menu_tree_all_data(theme_get_setting('menu_element'));
    $vars['mainmenu'] = theme('mega_menu', array('menu' => $menu ));
  }elseif (theme_get_setting('menu_type') == 1) {
    if (theme_get_setting('menu_headings') == 1) { // use classic <li>
      $vars['mainmenu'] = theme('links', array('links' => $vars['main_menu'], 'attributes' => array('id' => 'primary', 'class' => array('links', 'clearfix', 'main-menu'))));
    }
    elseif (theme_get_setting('menu_headings') == 2){ // use <h2> (custom_links in theme/theme.inc)
      $vars['mainmenu'] = theme('custom_links', array('links' => $vars['main_menu'], 'attributes' => array('id' => 'primary', 'class' => array('links', 'clearfix', 'main-menu'))));
    }
  }
  
  if( function_exists('pdx_meta_description_set') ){
    $out=pdx_meta_description_set();
    if( isset($out) and strlen($out) ){
        $data = array(
            '#tag' => 'meta',
            '#attributes' => array(
                'name' => 'description',
                'content' => $out,
            ),
        );
        drupal_add_html_head($data, 'pdxmydesc');
    }
  }
  if( defined('PDXCAT_NAME') and arg(0)==PDXCAT_NAME and is_numeric(arg(1)) ){
    if( is_numeric(arg(2)) ){
        $data = array(
            '#tag' => 'meta',
            '#attributes' => array(
                'property' => 'og:image',
                'content' => 'http://d4zbhil5kxgyr.cloudfront.net/static/part/i'.arg(2).'.png',
            ),
        );
        drupal_add_html_head($data, 'ogimage');
        $data = array(
            '#tag' => 'meta',
            '#attributes' => array(
                'name' => 'twitter:image:src',
                'content' => 'http://d4zbhil5kxgyr.cloudfront.net/static/part/i'.arg(2).'.png',
            ),
        );
        drupal_add_html_head($data, 'twitterimage');
    }else{
        $data = array(
            '#tag' => 'meta',
            '#attributes' => array(
                'property' => 'og:image',
                'content' => 'http://d4zbhil5kxgyr.cloudfront.net/static/part/i'.arg(1).'.png',
            ),
        );
        drupal_add_html_head($data, 'ogimage');
        $data = array(
            '#tag' => 'meta',
            '#attributes' => array(
                'name' => 'twitter:image:src',
                'content' => 'http://d4zbhil5kxgyr.cloudfront.net/static/part/i'.arg(1).'.png',
            ),
        );
        drupal_add_html_head($data, 'twitterimage');
    }
  }
  
  
}


function pdxneedto_preprocess_node(&$vars){
  $type = $vars['type'];
  if ($vars['teaser']) {
    $vars['theme_hook_suggestions'][] = 'node__teaser';
  }
  if ($vars['teaser'] && $vars['type']) {
    $vars['theme_hook_suggestions'][] = 'node__' . $type . '__teaser';
  }
}

function pdxneedto_preprocess_comment(&$vars) {
  $vars['classes_array'][] = $vars['zebra'];
}

/**
 * Breadcrumb.
 */
function pdxneedto_breadcrumb($variables) {

$bpath=implode('_',arg());
//if(file_exists('pdxcache/breadcrumb/'.$bpath) and filectime('pdxcache/breadcrumb/'.$bpath)>(time()-186400) and  filesize('pdxcache/breadcrumb/'.$bpath) ){
//    return @file_get_contents('pdxcache/breadcrumb/'.$bpath);
//}else{
    global $user;

  $breadcrumb = $variables['breadcrumb'];
  
  $isttitle=drupal_get_title();

    if( arg(0)=='user' and is_numeric(arg(1)) ){
        $breadcrumb=array('<a href="/">'.t('Home').'</a>');
        $breadcrumb[]='<a href="/users/rents">'.t('All landlords').'</a>';

        $name=db_query('select d.title as name, d.nid as tid from {field_data_field_rn} as f inner join {node} as d on (f.field_rn_nid=d.nid and f.entity_type=\'user\') where f.entity_id='.arg(1));
        $name=$name->fetchAssoc();
        if( isset($name['name']) and strlen($name['name']) ){
            $url=url('node/'.$name['tid'], array('absolute'=>TRUE));
            $breadcrumb[]='<a href="'.$url.'">'.$name['name'].'</a>';
        }

                    $name=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.arg(1));
                    $name=$name->fetchAssoc();
                    if( isset($name['field_name_value']) and strlen($name['field_name_value']) ){
                        if( is_string(arg(2)) ){
                            $url=url('user/'.arg(1), array('absolute'=>TRUE));
                            $breadcrumb[]='<a href="'.$url.'">'.$name['field_name_value'].'</a>';
                        }
                        $isttitle=$name['field_name_value'];
                    }else{
                        $isttitle='???';
                    }

    }elseif( arg(0)=='messages' ){
        $breadcrumb=array('<a href="/">'.t('Home').'</a>');
        $breadcrumb[]='<a href="/users/rents">'.t('All landlords').'</a>';

        $name=db_query('select d.title as name, d.nid as tid from {field_data_field_rn} as f inner join {node} as d on (f.field_rn_nid=d.nid and f.entity_type=\'user\') where f.entity_id='.$user->uid);
        $name=$name->fetchAssoc();
        if( isset($name['name']) and strlen($name['name']) ){
            $url=url('node/'.$name['tid'], array('absolute'=>TRUE));
            $breadcrumb[]='<a href="'.$url.'">'.$name['name'].'</a>';
        }

        $name=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.$user->uid);
        $name=$name->fetchAssoc();
        if( isset($name['field_name_value']) and strlen($name['field_name_value']) ){
            $url=url('user/'.$user->uid, array('absolute'=>TRUE));
            $breadcrumb[]='<a href="'.$url.'">'.$name['field_name_value'].'</a>';
        }
        
        if( is_string(arg(1)) ){
            $breadcrumb[]='<a href="/messages">'.t('Messages').'</a>';
        }

    }elseif( arg(0)=='user' and is_string(arg(1)) and (arg(1)=='findmy' or arg(1)=='item') ){
        $breadcrumb=array('<a href="/">'.t('Home').'</a>');
        $breadcrumb[]='<a href="/users/rents">'.t('All landlords').'</a>';

        $name=db_query('select d.title as name, d.nid as tid from {field_data_field_rn} as f inner join {node} as d on (f.field_rn_nid=d.nid and f.entity_type=\'user\') where f.entity_id='.$user->uid);
        $name=$name->fetchAssoc();
        if( isset($name['name']) and strlen($name['name']) ){
            $url=url('node/'.$name['tid'], array('absolute'=>TRUE));
            $breadcrumb[]='<a href="'.$url.'">'.$name['name'].'</a>';
        }

        $name=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.$user->uid);
        $name=$name->fetchAssoc();
        if( isset($name['field_name_value']) and strlen($name['field_name_value']) ){
            $url=url('user/'.$user->uid, array('absolute'=>TRUE));
            $breadcrumb[]='<a href="'.$url.'">'.$name['field_name_value'].'</a>';
        }
        
    }elseif( defined('PDXCAT_NAME') and arg(0)==PDXCAT_NAME and is_numeric(arg(1)) ){
//        if( is_numeric(arg(2)) ){
        $breadcrumb=array('<a href="/">'.t('Home').'</a>');
        $breadcrumb[]='<a href="/catalog">'.t('Catalog').'</a>';
//        $breadcrumb[]='<a href="/brands">'.t('All brands').'</a>';

        if( isset($_SESSION['pdxpdx_par_part']) and is_numeric($_SESSION['pdxpdx_par_part']) and $_SESSION['pdxpdx_par_part']>0 ){
            $name=db_query('select name from {taxonomy_term_data} where tid='.$_SESSION['pdxpdx_par_part']);
            $name=$name->fetchAssoc();
            if( isset($name['name']) and strlen($name['name']) ){
                $url=url(PDXCAT_NAME.'/'.$_SESSION['pdxpdx_par_part'], array('absolute'=>TRUE));
                $breadcrumb[]='<a href="'.$url.'" class="partis'.$_SESSION['pdxpdx_par_part'].'">'.$name['name'].'</a>';
            }
        }

        if( isset($_SESSION['pdxpdx_par_subpart']) and is_numeric($_SESSION['pdxpdx_par_subpart']) and $_SESSION['pdxpdx_par_subpart']>0 ){
            $name=db_query('select name from {taxonomy_term_data} where tid='.$_SESSION['pdxpdx_par_subpart']);
            $name=$name->fetchAssoc();
            if( isset($name['name']) and strlen($name['name']) ){
                if( isset($_SESSION['pdxpdx_par_part']) and is_numeric($_SESSION['pdxpdx_par_part']) and $_SESSION['pdxpdx_par_part']>0 ){
                    $url=url(PDXCAT_NAME.'/'.$_SESSION['pdxpdx_par_part'].'/'.$_SESSION['pdxpdx_par_subpart'], array('absolute'=>TRUE));
                }else{
                    $url=url(PDXCAT_NAME.'/'.$_SESSION['pdxpdx_par_subpart'], array('absolute'=>TRUE));
                }
                $breadcrumb[]='<a href="'.$url.'" class="partis'.$_SESSION['pdxpdx_par_subpart'].'">'.$name['name'].'</a>';
            }
        }
        
        if( isset($_SESSION['pdxpdx_par_vid']) and $_SESSION['pdxpdx_par_vid']==6 ){
            $breadcrumb[]='<a href="/users/rents">'.t('All landlords').'</a>';
        }
//        }
    }elseif( arg(0)=='item' and is_string(arg(1)) and arg(1)=='viewed' ){
        $breadcrumb=array('<a href="/">'.t('Home').'</a>');
        $breadcrumb[]='<a href="/catalog">'.t('Catalog').'</a>';
    }elseif( arg(0)=='taxonomy' and is_numeric(arg(2)) ){
        $breadcrumb=array('<a href="/">'.t('Home').'</a>');
        $breadcrumb[]='<a href="/catalog">'.t('Catalog').'</a>';

//        if( isset($_SESSION['pdxpdx_par_vid']) and $_SESSION['pdxpdx_par_vid']==4 ){
//            $breadcrumb[]='<a href="/brands">'.t('All brands').'</a>';
//        }

        if( isset($_SESSION['pdxpdx_par_vid']) and $_SESSION['pdxpdx_par_vid']==6 ){
            $breadcrumb[]='<a href="/users/rents">'.t('All landlords').'</a>';
        }

    }elseif( isset($_SESSION['pdxpdx_node_type']) and is_numeric($_SESSION['pdxpdx_node_nid']) ){
        switch( $_SESSION['pdxpdx_node_type'] ){
        case 'deal':
            $breadcrumb=array('<a href="/">'.t('Home').'</a>');
            $breadcrumb[]='<a href="/users/rents">'.t('All landlords').'</a>';
            if( $user->uid ){
                $name=db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and entity_id='.$user->uid);
                $name=$name->fetchAssoc();
                if( isset($name['field_name_value']) and strlen($name['field_name_value']) ){
                    $url=url('user/'.$user->uid, array('absolute'=>TRUE));
                    $breadcrumb[]='<a href="'.$url.'">'.$name['field_name_value'].'</a>';
                }
            }
            $breadcrumb[]='<a href="/user/deals">'.t('All deals').'</a>';
            break;
        case 'article':
            $name=db_query('select l2.link_path, l2.link_title from {menu_links} as l inner join {menu_links} as l2 on l.plid=l2.mlid where l.depth=2 and l.link_path=\'node/'.$_SESSION['pdxpdx_node_nid'].'\'');
            $name=$name->fetchAssoc();
            if( isset($name['link_path']) and strlen($name['link_path']) ){
                $breadcrumb[]='<a href="'.url($name['link_path'], array('absolute'=>TRUE)).'">'.$name['link_title'].'</a>';
            }
            break;
        case 'raion':
            $breadcrumb[]='<a href="/users/rents">'.t('All landlords').'</a>';
            break;
        case 'item':
            if( isset( $_SESSION['pdxpdx_node_tid'] ) and is_numeric( $_SESSION['pdxpdx_node_tid'] ) and isset( $_SESSION['pdxpdx_node_tid_name'] ) and strlen( $_SESSION['pdxpdx_node_tid_name'] ) ){
                $breadcrumb=array('<a href="/">'.t('Home').'</a>');
                $breadcrumb[]='<a href="/catalog">'.t('Catalog').'</a>';
        
                $url=url(PDXCAT_NAME.'/'.$_SESSION['pdxpdx_node_tid'], array('absolute'=>TRUE));
                $breadcrumb[]='<a href="'.$url.'" class="partis'.$_SESSION['pdxpdx_node_tid'].'">'.$_SESSION['pdxpdx_node_tid_name'].'</a>';
                
                $name=db_query('select d.tid, d.name from {field_data_field_subpart} as f inner join {taxonomy_term_data} as d on f.field_subpart_tid=d.tid where f.entity_type=\'node\' and f.entity_id='.$_SESSION['pdxpdx_node_nid']);
                $name=$name->fetchAssoc();
                if( isset($name['name']) and strlen($name['name']) ){
                    $url=url(PDXCAT_NAME.'/'.$_SESSION['pdxpdx_node_tid'].'/'.$name['tid'], array('absolute'=>TRUE));
                    $breadcrumb[]='<a href="'.$url.'" class="partis'.$name['tid'].'">'.$name['name'].'</a>';
        
                    $name2=db_query('select d.tid, d.name from {field_data_field_tags} as f inner join {taxonomy_term_data} as d on f.field_tags_tid=d.tid where f.entity_type=\'node\' and f.entity_id='.$_SESSION['pdxpdx_node_nid']);
                    $name2=$name2->fetchAssoc();
                    if( isset($name2['name']) and strlen($name2['name']) ){
                        $url=url(PDXCAT_NAME.'/'.$_SESSION['pdxpdx_node_tid'].'/'.$name['tid'].'/'.$name2['tid'], array('absolute'=>TRUE));
                        $breadcrumb[]='<a href="'.$url.'" class="partis'.$name2['tid'].'">'.$name2['name'].'</a>';
                    }
        
                }
                
                
            }
            break;
        }
    }

  if (!empty($breadcrumb)) {
    $output = '';
//    $output = '<'.OUTTAG.' class="element-invisible">' . t('You are here') . '</'.OUTTAG.'>';

    unset($tmpbreadcrumb);
    
    for($i=0;$i<count($breadcrumb);$i++){
        if(strpos($breadcrumb[$i], '/all')===false){
            $tmpbreadcrumb[]='<span typeof="v:Breadcrumb">'.str_replace('<a ','<a rel="v:url" property="v:title" ',strip_tags( htmlspecialchars_decode( $breadcrumb[$i] ),'<a>')).'</span>';
            if($i==0 ){
                if( arg(0)=='cart' and is_string(arg(1)) ){
                    switch(arg(1)){
                    case 'checkout':
                        $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="/user/'.$user->uid.'">'.t('My profile').'</a></span>';
                        $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="/user/'.$user->uid.'/points">'.t('My balance').'</a></span>';
                        break;
                    }
                }elseif( arg(0)=='printmail' ){
                    if( is_string(arg(1)) and arg(1)=='item' and is_numeric(arg(2)) ){
                        $path=path_load('node/'.arg(2)); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.arg(2);
                        $name=db_query('select title from {node} where nid='.arg(2));
                        $name=$name->fetchAssoc();
                        if( isset($name['title']) and strlen($name['title']) ){
                            $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="/'.$path.'">'.$name['title'].'</a></span>';
                        }
                    }elseif( is_string(arg(1)) and arg(1)=='users' and is_numeric(arg(3)) ){
                        $tmpbreadcrumb[]='<a href="/users/rents">'.t('All landlords').'</a>';
                        $path=path_load('node/'.arg(3)); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.arg(3);
                        $name=db_query('select title from {node} where nid='.arg(3));
                        $name=$name->fetchAssoc();
                        if( isset($name['title']) and strlen($name['title']) ){
                            $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="/'.$path.'">'.$name['title'].'</a></span>';
                        }
                    }
                }
            }
        }
    }

    if( isset($_SESSION['pdxpdx_node_type']) and strlen($_SESSION['pdxpdx_node_type']) and isset($_SESSION['pdxpdx_node_title']) and strlen($_SESSION['pdxpdx_node_title']) and isset($_SESSION['pdxpdx_node_nid']) and is_numeric($_SESSION['pdxpdx_node_nid']) ){
        if( is_string(arg(2)) and arg(2)=='edit' ){
            $path=path_load('node/'.$_SESSION['pdxpdx_node_nid']); if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$_SESSION['pdxpdx_node_nid'];
            $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="/'.$path.'">'.$_SESSION['pdxpdx_node_title'].'</a></span>';
        }
        
        if( strpos($_SESSION['pdxpdx_node_type'],'product')===false ){
            
        }else{
            $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="/catalog">'.t('Catalog').'</a></span>';
            if( isset($_SESSION['pdxpdx_node_tid']) and is_numeric($_SESSION['pdxpdx_node_tid']) and isset($_SESSION['pdxpdx_node_tid_name']) and strlen($_SESSION['pdxpdx_node_tid_name']) ){
                if(function_exists('taxonomy_get_parents_all')){
                    $parents=taxonomy_get_parents_all($_SESSION['pdxpdx_node_tid']);
                    if($parents and is_array($parents) and count($parents)){
                        $parents=array_reverse($parents);
                        foreach($parents as $parent){
                            $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.url('catalog/'.$parent->tid, array('absolute'=>TRUE)).'">'.$parent->name.'</a></span>';
                        }
                    }
                }
            }
//            $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.url('catalog/'.$_SESSION['pdxpdx_node_tid'], array('absolute'=>TRUE)).'">'.$_SESSION['pdxpdx_node_tid_name'].'</a></span>';

        }
    }elseif( arg(0)=='catalog' and is_numeric(arg(1)) ){
        if(function_exists('taxonomy_get_parents_all')){
            $parents=taxonomy_get_parents_all(arg(1));
            if($parents and is_array($parents) and count($parents)){
                $parents=array_reverse($parents);
                foreach($parents as $parent){
                    if( $parent->tid==arg(1) ){ continue; }
                    $tmpbreadcrumb[]='<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="'.url('catalog/'.$parent->tid, array('absolute'=>TRUE)).'">'.$parent->name.'</a></span>';
                }
            }
        }
    }

    $tmpbreadcrumb=array_unique($tmpbreadcrumb);

    if (theme_get_setting('breadcrumb_title') == 1) { // show the title setting
      $tmpbreadcrumb[] = truncate_utf8(strip_tags(htmlspecialchars_decode($isttitle)), theme_get_setting('breadcrumb_title_length'), $wordsafe = TRUE, $dots = TRUE);
    }
        
    if(isset($tmpbreadcrumb)){
        $output .= '<span typeof="v:Breadcrumb"><noindex><a rel="v:url" property="v:title" rel="nofollow" href="http://needto.me/index.html">'.t('All cities').'</a></noindex></span><span class="brline">&gt;</span>'. implode(' <span class="brline">&gt;</span> ', $tmpbreadcrumb);
    }
    if( isset($output) and strlen($output) ){
        return $output;    
    }

  }
//}

}

function pdxneedto_privatemsg_username($variables) {
  $recipient = $variables['recipient'];
  $options = $variables['options'];
  if (!isset($recipient->uid)) {
    $recipient->uid = $recipient->recipient;
  }
  if (!empty($options['plain'])) {
    $name = strip_tags(format_username($recipient));
    if (!empty($options['unique'])) {
      $name .= ' [user]';
    }
    return $name;
  }
  else {
    if( isset($recipient->field_name['und'][0]['value']) and strlen($recipient->field_name['und'][0]['value']) ){
        $retis='<a class="username" target="_blank" href="/user/'.$recipient->uid.'">'.$recipient->field_name['und'][0]['value'].'</a>';
        if(isset($recipient->roles[4])){
            $retis.=' ('.t('content manager').')';
        }elseif(isset($recipient->roles[3])){
            $retis.=' ('.t('administrator').')';
        }
        return $retis.'<span title="offline" class="ustat ustat_'.$recipient->uid.'"></span>';
    }else{
        return theme('username', array('account' => $recipient));
    }
  }
}
function pdxneedto_privatemsg_list_field__participants($variables) {
  $thread = $variables['thread'];
  $participants = _privatemsg_generate_user_array($thread['participants'], -4);
  $field = array();
  
  $auids=array();
  if( isset($participants) and is_array($participants) and count($participants) ){
    foreach( $participants as $uid ){
        if( isset($uid->field_name['und'][0]['value']) and strlen($uid->field_name['und'][0]['value']) ){
            $auids[]='<a class="username" target="_blank" href="/user/'.$uid->uid.'">'.$uid->field_name['und'][0]['value'].'</a>';
        }
    }
  }
  if( isset($auids) and is_array($auids) and count($auids) ){ 
    $field['data'] = implode(', ', $auids);    
  }else{
    $field['data'] = _privatemsg_format_participants($participants, 3, TRUE);    
  }
  $field['class'][] = 'privatemsg-list-participants';
  return $field;
}

function pdxneedto_print_mail_form($variables) {
  $form = $variables['form'];
  
  global $user; if($user->uid){ 
    $out = db_query('select field_name_value from {field_data_field_name} where entity_type=\'user\' and delta=0 and entity_id='.$user->uid);
    $out = $out->fetchAssoc();
    if( isset($out['field_name_value']) and strlen($out['field_name_value']) ){
        $form['fld_from_name']['#default_value']=$out['field_name_value'];
        $form['fld_from_name']['#value']=$out['field_name_value'];
        if( function_exists('pdxgetemailtitle') ){
            $form['fld_subject']['#value']=pdxgetemailtitle($out['field_name_value']);
            $form['fld_subject']['#default_value']=pdxgetemailtitle($out['field_name_value']);
        }
    }
  }
  
  

  drupal_add_css(drupal_get_path('module', 'print_mail') . '/css/print_mail.theme.css');
  $content = '';
  foreach (element_children($form) as $key) {
    $tmp = drupal_render($form[$key]);
    switch ($key) {
      case 'fld_from_addr':
      case 'fld_from_name':
      case 'txt_to':
      case 'fld_subject':
      case 'fld_title':
        $tmp = str_replace('<label', '<label class ="printmail-label"', $tmp);
        break;
    }
    $content .= $tmp;
  }
  return $content;
}
function pdxneedto_form_element($variables) {

  $element = &$variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';
  $prefix.='<span class="inputleft inputleft-';
  if (!empty($element['#type'])) {
      $prefix.=strtr($element['#type'], '_', '-');
  }elseif (!empty($element['#name'])) {
      $prefix.=strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  $prefix.='">';
  $suffix.='</span>';
  if( isset($element['#id']) and strlen($element['#id']) ){
      if( strpos($element['#id'], '-price')===false ){}else{
        if( strpos($element['#id'], '-price-min')===false and strpos($element['#id'], '-price-about')===false ){
            $suffix.=' <span class="cursign">'.PDX_CITY_CUR.'</span>';
        }
      }
  }
   
  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}

function pdxneedto_form_faq_node_form_alter(&$form, &$form_state, $form_id) {
    global $user;
    if( !isset($user->roles[3]) and !isset($user->roles[4]) ){
        unset($form['field_answer']['und'][0]);
    }
//    $form['field_music']['und'][0]['#description']='';
//    $form['field_price']['und'][0]['value']['#field_suffix']='';
}

function pdxneedto_admin_menu_links($variables) {
  $destination = &drupal_static('admin_menu_destination');
  $elements = $variables['elements'];

  if (!isset($destination)) {
    $destination = drupal_get_destination();
    $destination = $destination['destination'];
  }

  // The majority of items in the menu are sorted already, but since modules
  // may add or change arbitrary items anywhere, there is no way around sorting
  // everything again. element_sort() is not sufficient here, as it
  // intentionally retains the order of elements having the same #weight,
  // whereas menu links are supposed to be ordered by #weight and #title.
  uasort($elements, 'admin_menu_element_sort');
  $elements['#sorted'] = TRUE;

  $output = '';
  foreach (element_children($elements) as $path) {
    // Early-return nothing if user does not have access.
    if (isset($elements[$path]['#access']) && !$elements[$path]['#access']) {
      continue;
    }
    $elements[$path] += array(
      '#attributes' => array(),
      '#options' => array(),
    );
    // Render children to determine whether this link is expandable.
    if (isset($elements[$path]['#type']) || isset($elements[$path]['#theme']) || isset($elements[$path]['#pre_render'])) {
      $elements[$path]['#children'] = drupal_render($elements[$path]);
    }
    else {
      $elements[$path]['#children'] = theme('admin_menu_links', array('elements' => $elements[$path]));
      if (!empty($elements[$path]['#children'])) {
        $elements[$path]['#attributes']['class'][] = 'expandable';
      }
      if (isset($elements[$path]['#attributes']['class'])) {
        $elements[$path]['#attributes']['class'] = $elements[$path]['#attributes']['class'];
      }
    }

    $link = '';
    // Handle menu links.
    if (isset($elements[$path]['#href'])) {

  if( strpos($elements[$path]['#href'],'http://parad0x')===false ){}else{
    $elements[$path]['#href']=str_replace('http://parad0x',$GLOBALS['base_url'],$elements[$path]['#href']);
    $elements[$path]['#options']['attributes']['target'] = '_blank';
  }

      // Strip destination query string from href attribute and apply a CSS class
      // for our JavaScript behavior instead.
      if (isset($elements[$path]['#options']['query']['destination']) && $elements[$path]['#options']['query']['destination'] == $destination) {
        unset($elements[$path]['#options']['query']['destination']);
        $elements[$path]['#options']['attributes']['class'][] = 'admin-menu-destination';
      }

      $link = l($elements[$path]['#title'], $elements[$path]['#href'], $elements[$path]['#options']);
    }
    // Handle plain text items, but do not interfere with menu additions.
    elseif (!isset($elements[$path]['#type']) && isset($elements[$path]['#title'])) {
      if (!empty($elements[$path]['#options']['html'])) {
        $title = $elements[$path]['#title'];
      }
      else {
        $title = check_plain($elements[$path]['#title']);
      }
      $attributes = '';
      if (isset($elements[$path]['#options']['attributes'])) {
        $attributes = drupal_attributes($elements[$path]['#options']['attributes']);
      }
      $link = '<span' . $attributes . '>' . $title . '</span>';
    }

    $output .= '<li' . drupal_attributes($elements[$path]['#attributes']) . '>';
    $output .= $link . $elements[$path]['#children'];
    $output .= '</li>';
  }
  // @todo #attributes probably required for UL, but already used for LI.
  // @todo Use $element['#children'] here instead.
  if ($output) {
    $elements['#wrapper_attributes']['class'][] = 'dropdown';
    $attributes = drupal_attributes($elements['#wrapper_attributes']);
    $output = "\n" . '<ul' . $attributes . '>' . $output . '</ul>';
  }
  return $output;
}
function pdxneedto_menu_link__user_menu(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  if( $element['#href']=='user/login' ) {$element['#href']='user'; }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function pdxneedto_menu_link__menu_foradmin(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  if( module_exists('uc_product') ){
      switch($element['#href']){
      case 'admin/product/noimage':
        $res=db_query("SELECT count(node.nid) FROM {node} node LEFT JOIN {field_data_uc_product_image} field_data_uc_product_image ON node.nid = field_data_uc_product_image.entity_id AND (field_data_uc_product_image.entity_type = 'node' AND field_data_uc_product_image.deleted = '0') WHERE (( (node.type IN  ('product')) AND (node.status = '1') AND (field_data_uc_product_image.uc_product_image_fid IS NULL ) ))");
        $res=$res->fetchAssoc();
        if(isset($res['count(node.nid)']) and is_numeric($res['count(node.nid)'])){
            $element['#title'].=' (';
            $element['#title'].=$res['count(node.nid)'];
            $element['#title'].=')';
        }
        break;
      case 'admin/product/nocatalog':
        $res=db_query("SELECT count(node.nid) FROM {node} node LEFT JOIN {field_data_taxonomy_catalog} field_data_taxonomy_catalog ON node.nid = field_data_taxonomy_catalog.entity_id AND (field_data_taxonomy_catalog.entity_type = 'node' AND field_data_taxonomy_catalog.deleted = '0') WHERE (( (node.type IN  ('product')) AND (node.status = '1') AND (field_data_taxonomy_catalog.taxonomy_catalog_tid IS NULL ) ))");
        $res=$res->fetchAssoc();
        if(isset($res['count(node.nid)']) and is_numeric($res['count(node.nid)'])){
            $element['#title'].=' (';
            $element['#title'].=$res['count(node.nid)'];
            $element['#title'].=')';
        }
        break;
      case 'admin/product/noprice':
        $res=db_query("SELECT count(node.nid) FROM {node} node LEFT JOIN {uc_products} uc_products ON node.vid = uc_products.vid WHERE (( (node.type IN  ('product')) AND (node.status = '1') AND (uc_products.sell_price = '0') ))");
        $res=$res->fetchAssoc();
        if(isset($res['count(node.nid)']) and is_numeric($res['count(node.nid)'])){
            $element['#title'].=' (';
            $element['#title'].=$res['count(node.nid)'];
            $element['#title'].=')';
        }
        break;
      }
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function pdxneedto_uc_object_attributes_form($variables) {
  $form = $variables['form'];

  $output = '';

  if ($form['view']['#value'] == 'overview') {
    $header = array(t('Remove'), t('Name'), t('Label'), t('Default'), t('Required'), t('List position'), t('Display'));

    $rows = array();
    foreach (element_children($form['attributes']) as $aid) {
      $rows[] = array(
        'data' => array(
          drupal_render($form['attributes'][$aid]['remove']),
          drupal_render($form['attributes'][$aid]['name']),
          drupal_render($form['attributes'][$aid]['label']),
          drupal_render($form['attributes'][$aid]['option']),
          drupal_render($form['attributes'][$aid]['required']),
          drupal_render($form['attributes'][$aid]['ordering']),
          drupal_render($form['attributes'][$aid]['display']),
        ),
        'class' => array('draggable'),
      );
    }
    if( !isset($rows) or !is_array($rows) or !count($rows) ){
    drupal_goto(url('node/' . $form['id']['#value'] . '/edit/attributes/add', array('absolute'=>TRUE)));
    }

    drupal_add_tabledrag('uc-attribute-table', 'order', 'sibling', 'uc-attribute-table-ordering');

    if ($form['type']['#value'] == 'class') {
      $path = url('admin/store/products/classes/' . $form['id']['#value'] . '/attributes/add');
    }
    elseif ($form['type']['#value'] == 'product') {
      $path = url('node/' . $form['id']['#value'] . '/edit/attributes/add');
    }

    $output = theme('table', array(
      'header' => $header,
      'rows' => $rows,
      'attributes' => array('id' => 'uc-attribute-table'),
      'empty' => t('You must first <a href="!url">add attributes to this !type</a>.', array('!url' => $path, '!type' => $form['type']['#value'])),
    ));
  }
  else {
    $output = '<div class="uc-attributes-add-link">';
    $output .= t('You may add more attributes <a href="!url">here</a>.', array('!url' => url('admin/store/products/attributes/add')));
    $output .= '</div>';
  }

  $output .= drupal_render_children($form);

  return $output;
}
function pdxneedto_uc_object_options_form($variables) {
  $form = $variables['form'];
  $output = '';

  drupal_add_js('misc/tableselect.js');
  $header = array(array('data' => '&nbsp;&nbsp;' . t('Options'), 'class' => array('select-all')), t('Default'), t('Cost'), t('Price'), t('Weight'), t('List position'));
  $tables = 0;

  if (isset($form['attributes'])) {
    foreach (element_children($form['attributes']) as $key) {
      $rows = array();
      foreach (element_children($form['attributes'][$key]['options']) as $oid) {
        $rows[] = array(
          'data' => array(
            drupal_render($form['attributes'][$key]['options'][$oid]['select']),
            drupal_render($form['attributes'][$key]['default'][$oid]),
            drupal_render($form['attributes'][$key]['options'][$oid]['cost']),
            drupal_render($form['attributes'][$key]['options'][$oid]['price']),
            drupal_render($form['attributes'][$key]['options'][$oid]['weight']),
            drupal_render($form['attributes'][$key]['options'][$oid]['ordering']),
          ),
          'class' => array('draggable', 'orow'.$oid),
        );
      }

      $table_id = 'uc-attribute-option-table-' . $tables++;
      drupal_add_tabledrag($table_id, 'order', 'sibling', 'uc-attribute-option-table-ordering');

      $filter='<div id="allfilter"><input type="text" value="" class="pfilter" style="border: 1px solid #000;" /><a href="javascript:void(0);" onclick=" jQuery(\'#oldfilter\').append(jQuery(\'#allfilter input.pfilter\').val()); var findf=0; jQuery(\'.product_attributes\').find(\'.form-type-checkbox label\').each( function(){ if( jQuery(this).html().toLowerCase().search(jQuery(\'#allfilter input.pfilter\').val().toLowerCase())==-1 ){ jQuery(this).parent().parent().parent().hide();  }else{ jQuery(this).parent().parent().parent().show(); findf=1;  } } ); if(findf==0) jQuery(\'#oldfilter\').append(\' - no\'); jQuery(\'#oldfilter\').append(\' | \'); "> Filter</a><div id="oldfilter"></div></div>';
      $output .= $filter.theme('table', array(
        'header' => $header,
        'rows' => $rows,
        'attributes' => array(
          'class' => array('product_attributes'),
          'id' => $table_id,
        ),
        'caption' => '<h2>' . drupal_render($form['attributes'][$key]['name']) . '</h2>',
        'empty' => t('This attribute does not have any options.'),
      ));
    }
  }

  if (!$tables) {
    if ($form['type']['#value'] == 'product') {
      drupal_set_message(t('This product does not have any attributes.'), 'warning');
    }
    else {
      drupal_set_message(t('This product class does not have any attributes.'), 'warning');
    }
  }

  $output .= drupal_render_children($form);

  return $output;
}
function pdxneedto_uc_cart_complete_sale($variables) {
   if( isset($variables['order']) and isset($variables['message']) ){
        //echo '<pre>'.print_r($variables['order']->billing_phone, true). '</pre>';
    
if($pdxsmspassword=variable_get('sms_password')){}else{
    $pdxsmspassword='';
}
if($pdxsmslogin=variable_get('sms_login')){}else{
    $pdxsmslogin='';
}
if($smsopcheckout=variable_get('sms_op_checkout')){}else{
    $smsopcheckout='';
}
if($pdxsmstel=variable_get('sms_tel')){}else{
    $pdxsmstel='';
}

if( isset($pdxsmspassword) and strlen($pdxsmspassword) and isset($pdxsmslogin) and strlen($pdxsmslogin) and isset($smsopcheckout) and $smsopcheckout==1 and isset($pdxsmstel) and strlen($pdxsmstel) ){
        if( isset($variables['order']->billing_phone) and strlen($variables['order']->billing_phone) ){
            $code=t('Your order is received');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://gate.smsaero.ru/send/?user=".$pdxsmslogin."&password=".md5($pdxsmspassword)."&to=".urlencode($variables['order']->billing_phone)."&text=".urlencode($code)."&from=REKLAMA");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_POST, 0);
            $ch_result = curl_exec($ch);
            curl_close($ch);
        }
            

            $code=t('New order on the site ').$GLOBALS['base_url'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://gate.smsaero.ru/send/?user=".$pdxsmslogin."&password=".md5($pdxsmspassword)."&to=".urlencode($pdxsmstel)."&text=".urlencode($code)."&from=REKLAMA");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_POST, 0);
            $ch_result = curl_exec($ch);
            curl_close($ch);


/*    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://smsc.ru/sys/send.php?login=".$pdxsmslogin."&psw=".$pdxsmspassword."&phones=".urlencode($pdxsmstel)."&mes=".urlencode($code)."&charset=utf-8&cost=1");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_POST, 0);
            $ch_result = curl_exec($ch);
            curl_close($ch);
            if(strlen($ch_result) and strpos($ch_result,'ERROR')===false){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://smsc.ru/sys/send.php?login=".$pdxsmslogin."&psw=".$pdxsmspassword."&phones=".urlencode($pdxsmstel)."&charset=utf-8&mes=".$code);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_POST, 0);
                $ch_result = curl_exec($ch);
                curl_close($ch);
    
            }else{
                if( strpos($ch_result,'ERROR = 6')===false ){
                }else{
                }
            }    
*/


}


        $text='';
        if( isset($variables['order']->payment_method) and strlen($variables['order']->payment_method) and function_exists( 'pdxgetdescforpayment' ) ){
            $text.=pdxgetdescforpayment($variables['order']->payment_method);
        }
        
        if( isset($text) and strlen($text) ){
            $variables['message'].='<div class="complete_more">'.$text.'</div>';
        }
        
        if( function_exists('pdxcreate_dirty') ){
            pdxcreate_dirty('admin');
        }
        

    }
    return $variables['message'];
    
}

function pdxneedto_horizontal_tabs($variables) {
  $element = $variables['element'];
  // Add required JavaScript and Stylesheet.
  drupal_add_library('field_group', 'horizontal-tabs');

  $output = '<div class="element-invisible">' . (!empty($element['#title']) ? $element['#title'] : t('Horizontal Tabs')) . '</div>';
  $output .= '<div class="horizontal-tabs-panes">' . $element['#children'] . '</div>';

  return $output;
}
function pdxneedto_multipage($variables) {
  $element = $variables['element'];
  // Add required JavaScript and Stylesheet.
  drupal_add_library('field_group', 'multipage');

  $output = '<div class="element-invisible">' . (!empty($element['#title']) ? $element['#title'] : t('Multipage')) . '</div>';

  $output .= '<div class="multipage-panes">';
  $output .= $element['#children'];
  $output .= '</div>';

  return $output;
}

function pdxneedto_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total, $theme;
  
  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('В« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => '<', 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => '>', 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last В»')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {

/*
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
*/
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {

/*
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => 'вЂ¦',
        );
      }
*/

      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
/*
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => 'вЂ¦',
        );
      }
*/

    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
/*
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }
*/

$ret='<div class="pager_item">';
$ret.=theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager')),
    )).'</div>';
    
    return $ret;
  }
}
function pdxneedto_uc_cart_checkout_review($variables) {
  $panes = $variables['panes'];
  $form = $variables['form'];

  drupal_add_css(drupal_get_path('module', 'uc_cart') . '/uc_cart.css');

  $output = '<div id="review-instructions">' . filter_xss_admin(variable_get('uc_checkout_review_instructions', uc_get_message('review_instructions'))) . '</div>';

  $output .= '<table class="order-review-table">';

    if( function_exists('pdxreplacereviewtd') ){
        $output .= pdxreplacereviewtd();
    }
    
  $output .= '</table>';
    
    $output .= '<div class="review_action">'.drupal_render($form).'</div>';

  return $output;
}
function pdxneedto_html_head_alter(&$head_elements) {
  foreach ($head_elements as $key => $element) {
    if (isset($element['#name']) && ($element['#name'] == 'shortlink' or $element['#name'] == 'canonical') ) {
      unset($head_elements[$key]);
    }elseif (isset($element['#attributes']['rel']) && ($element['#attributes']['rel'] == 'shortlink' or $element['#attributes']['rel'] == 'canonical') ) {
      unset($head_elements[$key]);
    }
  }
  if( isset( $head_elements['system_meta_generator']['#attributes']['content'] ) ){
    $head_elements['system_meta_generator']['#attributes']['content']='Drupal';
  }
  if( isset( $head_elements['fix_blocking_css_ie'] ) ){
    unset($head_elements['fix_blocking_css_ie']);
  }
}
//echo pdxneedto_month_declination_ru(format_date($eventdate,'custom','F'),date('n',$eventdate));
function pdxneedto_month_declination_ru ( $long_month_name, $month  ) {
   $long_month_name = ( $month == 3 || $month == 8 ) ? 
          $long_month_name . t('pdx_month_1') : drupal_substr($long_month_name, 0, drupal_strlen($long_month_name)-1) . t('pdx_month_2');  
   return $long_month_name;
}

/*

function pdxneedto_webform_element($variables) {

  // Ensure defaults.
  $variables['element'] += array(
    '#title_display' => 'before',
  );

  $element = $variables['element'];

  // All elements using this for display only are given the "display" type.
  if (isset($element['#format']) && $element['#format'] == 'html') {
    $type = 'display';
  }
  else {
    $type = (isset($element['#type']) && !in_array($element['#type'], array('markup', 'textfield', 'webform_email', 'webform_number'))) ? $element['#type'] : $element['#webform_component']['type'];
  }

  // Convert the parents array into a string, excluding the "submitted" wrapper.
  $nested_level = $element['#parents'][0] == 'submitted' ? 1 : 0;
  $parents = str_replace('_', '-', implode('--', array_slice($element['#parents'], $nested_level)));

  $wrapper_classes = array(
   'form-item',
   'webform-component',
   'webform-component-' . $type,
  );
  if (isset($element['#title_display']) && strcmp($element['#title_display'], 'inline') === 0) {
    $wrapper_classes[] = 'webform-container-inline';
  }
  $output = '<div class="' . implode(' ', $wrapper_classes) . '" id="webform-component-' . $parents . '">' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . _webform_filter_xss($element['#field_prefix']) . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . _webform_filter_xss($element['#field_suffix']) . '</span>' : '';
  $prefix.='<span class="inputleft">';
  $suffix.='</span>';
  if(isset($variables['element']['#default_value']) and is_string($variables['element']['#default_value']) and strlen($variables['element']['#default_value']) and isset($variables['element']['#webform_component']['form_key']) and strlen($variables['element']['#webform_component']['form_key'])){
    $suffix.='
<script type="text/javascript">
jQuery(document).ready(function($){
    if(jQuery("#edit-submitted-'.$variables['element']['#webform_component']['form_key'].'").length){
        jQuery("#edit-submitted-'.$variables['element']['#webform_component']['form_key'].'").bind("click",
        function(){
            if(jQuery("#edit-submitted-'.$variables['element']['#webform_component']['form_key'].'").val()=="'.$variables['element']['#default_value'].'"){
                jQuery("#edit-submitted-'.$variables['element']['#webform_component']['form_key'].'").val("");
            }
        }
        );
    }
});
</script>
';
  }

  switch ($element['#title_display']) {
    case 'inline':
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= ' <div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}
function pdxneedto_captcha($element) {
  static $js_added = FALSE;
  if (!$js_added) {
      $js_added = TRUE;
        drupal_add_js('jQuery(document).ready(function($){ jQuery(".captcha img").each(function(){ jQuery(this).click(function(){var src = jQuery(this).attr("src").split("/");src[src.length-1]++;jQuery(this).attr({"src":src.join("/")});}).css({"cursor":"pointer"});}); });','inline','footer');
  }
    return theme_captcha($element);
}
function pdxneedto_form_faq_node_form_alter(&$form, &$form_state, $form_id) {
    global $user;
    if($user->uid!=1){
        unset($form['field_answer']['und'][0]);
    }
//    $form['field_music']['und'][0]['#description']='';
//    $form['field_price']['und'][0]['value']['#field_suffix']='';
}
function pdxneedto_menu_link__user_menu(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  if($element['#href']=='bookmarks'){
    global $user;
    $element['#title'].=' (<span class="curmarks">';
    $flagcount = flag_get_user_flags('node',NULL,$user->uid);
    if(isset($flagcount['bookmarks']) and is_array($flagcount['bookmarks'])){ $element['#title'].= count($flagcount['bookmarks']); }else{ $element['#title'].='0'; }
    $element['#title'].='</span>)';
    $element['#localized_options']['html']=true;
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}
function pdxneedto_month_declination_ua ( $long_month_name, $month  ) {  
   if ( $month == 2 ) return  drupal_substr($long_month_name, 0, drupal_strlen($long_month_name)-2) . 'РѕРіРѕ';
   if ( $month == 11 ) return  $long_month_name . 'Р°';
   if ( drupal_substr($long_month_name, -3) == 'РµРЅСЊ' ) 
         return drupal_substr($long_month_name, 0, drupal_strlen($long_month_name)-3) . 'РЅСЏ';
 
   return $long_month_name;
}
function pdxneedto_form_alter(&$form, &$form_state, $form_id) {
    if( strpos($form_id,'webform_client_form_')===false ){
        switch($form_id){
        case 'user_login_block':
    //            if( isset($form['field_catalog']['und']) and is_array($form['field_catalog']['und']) ){
    //                $form['field_catalog']['#suffix']='<a href="/admin/structure/taxonomy/catalog/add">'.t('Add new termin').'</a>';
    //            }
    
    //        if( isset($form['field_votecount']['und']) and is_array($form['field_votecount']['und']) ){
    //            unset($form['field_votecount']['und']);
    //        }
    
    //        $form['actions']['#prefix']='<div class="preactions">';
    //        $form['actions']['#suffix']='<div class="clear">&nbsp;</div></div>';
            break;
        }
    }else{
        if( isset($form['submitted']['name']['#title']) ){
            $form['submitted']['name']['#attributes'] = array('placeholder' => $form['submitted']['name']['#title']);
            unset($form['submitted']['name']['#title']);
        }
        if( isset($form['submitted']['email']['#title']) ){
            $form['submitted']['email']['#attributes'] = array('placeholder' => $form['submitted']['email']['#title']);
            unset($form['submitted']['email']['#title']);
        }
        if( isset($form['submitted']['message']['#title']) ){
            $form['submitted']['message']['#attributes'] = array('placeholder' => $form['submitted']['message']['#title']);
            unset($form['submitted']['message']['#title']);
        }
        if( isset($form['submitted']['phone']['#title']) ){
            $form['submitted']['phone']['#attributes'] = array('placeholder' => $form['submitted']['phone']['#title']);
            unset($form['submitted']['phone']['#title']);
        }
        $form['actions']['#prefix']='<div class="preactions">';
        $form['actions']['#suffix']='<div class="clear">&nbsp;</div></div>';
    }

	if(isset($form['#submit']['0'])){
		switch($form['#submit']['0']){
		case 'uc_product_add_to_cart_form_submit':
		case 'search_box_form_submit':
			break;
		default:
//			echo '<pre>'.print_r($form, true). '</pre>';
			if(isset($form['actions']) and is_array($form['actions']) and count($form['actions'])){
				foreach($form['actions'] as $formid => $forma){
					if(isset($forma['#type']) and $forma['#type']=='submit'){
						$form['actions'][$formid]['#prefix']='<span class="inputleft-submit">';
						$form['actions'][$formid]['#suffix']='</span>';
					}
				}
			}
		}
	}
}
function pdxneedto_form_user_login_block_alter(&$form) {
  //print_r ($form);
  $form['name']['#attributes']['placeholder'] = $form['name']['#title'];
  unset($form['name']['#title']);
  $form['pass']['#attributes']['placeholder'] = $form['pass']['#title'];
  unset($form['pass']['#title']);
}

function pdxneedto_preprocess_search_result(&$variables) {
  global $language;

  $result = $variables['result'];

  $variables['url'] = check_url($result['link']);
  $variables['title'] = check_plain($result['title']);
  if (isset($result['language']) && $result['language'] != $language->language && $result['language'] != LANGUAGE_NONE) {
    $variables['title_attributes_array']['xml:lang'] = $result['language'];
    $variables['content_attributes_array']['xml:lang'] = $result['language'];
  }

  $info = array();
  if (!empty($result['module'])) {
    $info['module'] = check_plain($result['module']);
  }
  if (!empty($result['user'])) {
    $info['user'] = $result['user'];
  }
  
  if( isset($result['node']->created) and is_numeric($result['node']->created) ){
    $info['created'] = $result['node']->created;
  }
  if( isset($result['node']->nid) and is_numeric($result['node']->nid) ){
    $info['nid'] = $result['node']->nid;
  }
  if( isset($result['node']->sell_price) and is_numeric($result['node']->sell_price) ){
    $info['sell_price'] = $result['node']->sell_price;
  }
  if( isset($result['node']->field_part['und'][0]['tid']) and is_numeric($result['node']->field_part['und'][0]['tid']) ){
    $name=db_query('select name from {taxonomy_term_data} where tid='.$result['node']->field_part['und'][0]['tid']);
    $name=$name->fetchAssoc();
    if( isset($name['name']) and strlen($name['name']) ){
        $path=path_load('taxonomy/term/'.$result['node']->field_part['und'][0]['tid']);
        if(strlen($path['alias'])) $path=$path['alias']; else $path='taxonomy/term/'.$result['node']->field_part['und'][0]['tid'];
        $info['tid'] = '<a href="/'.$path.'">'.$name['name'].'</a>';
    }
  }
  if( isset($result['node']->uc_product_image['und'][0]['uri']) and strlen($result['node']->uc_product_image['und'][0]['uri']) ){
    $uri = image_style_url('news_preview', $result['node']->uc_product_image['und'][0]['uri']);
    if( isset($uri) and strlen($uri) ){
        $info['image'] = '<img alt="" src="'.$uri.'" />';
    }
  }
  if( isset($result['node']->body['und'][0]['value']) and strlen($result['node']->body['und'][0]['value']) ){
    $body=strip_tags($result['node']->body['und'][0]['value']);
    if( mb_strlen($body)>133 ){
        $body=mb_substr($body,0,133).'...';
    }
    $info['body'] = $body;
  }
  if( isset($result['node']->nid) and is_numeric($result['node']->nid) ){
    $path=path_load('node/'.$result['node']->nid);
    if(strlen($path['alias'])) $path=$path['alias']; else $path='node/'.$result['node']->nid;
    $info['path'] = '<a href="/'.$path.'">'.t('Read More').'</a>';
  }

  if (!empty($result['date'])) {
    $info['date'] = format_date($result['date'], 'short');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    $info = array_merge($info, $result['extra']);
  }
  // Check for existence. User search does not include snippets.
  $variables['snippet'] = isset($result['snippet']) ? $result['snippet'] : '';
  // Provide separated and grouped meta information..
  $variables['info_split'] = $info;
  $variables['info'] = implode(' - ', $info);
  $variables['theme_hook_suggestions'][] = 'search_result__' . $variables['module'];
}


function pdxneedto_site_map_box($variables) {
  $title = $variables['title'];
  $content = $variables['content'];
  $attributes = $variables['attributes'];

  $output = '';
  if (!empty($title) || !empty($content)) {
    $output .= '<div' . drupal_attributes($attributes) . '>';
    if (!empty($title)) {
        
        $notitle=0;
        if( isset($attributes['class']) and is_array($attributes['class']) and count($attributes['class']) ){
            if( array_search('site-map-box-menu',$attributes['class'])===false ){}else{
                $notitle=1;
            }
        }
        if($notitle){
        }else{
            $output .= '<h2 class="title">' . $title . '</h2>';
        }
        
    }
    if (!empty($content)) {
      $output .= '<div class="content">' . $content . '</div>';
    }
    $output .= '</div>';
  }

  return $output;
}

function pdxneedto_uc_payment_totals($variables) {
  $order = $variables['order'];
                    uc_discounts_apply($order);
                    uc_discounts_uc_checkout_pane_discounts('process', $order, array());
                    uc_discounts_uc_order('save', $order, '');
  $line_items = uc_order_load_line_items_display($order);
  $output = '<table id="uc-order-total-preview">';

  foreach ($line_items as $line) {
    if (!empty($line['title'])) {
      $attributes = drupal_attributes(array('class' => array('line-item-' . $line['type'])));
      $output .= '<tr' . $attributes . '><td class="title">' . filter_xss($line['title']) . ':</td>'
        . '<td class="price">' . theme('uc_price', array('price' => $line['amount'])) . '</td></tr>';
    }
  }

  $output .= '</table>';

  return $output;
}

*/

