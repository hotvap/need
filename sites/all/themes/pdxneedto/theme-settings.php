<?php // $Id$
include_once 'template.php';

/**
 * Advanced theme settings.
 */
function pdxneedto_form_system_theme_settings_alter(&$form, $form_state) {
  // fieldgroups
  $form['breadcrumb'] = array(
    '#type' => 'fieldset', 
    '#title' => t('Breadcrumb Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['menu'] = array(
    '#type' => 'fieldset', 
    '#title' => t('Primary menu settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  
  $form['accessibility'] = array(
    '#type' => 'fieldset', 
    '#title' => t('Accessibility and code semantics settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  
  // Breadcrumb elements
  $form['breadcrumb']['breadcrumb_title'] = array(
    '#type' => 'select',
    '#title' => t('Breadcrumb title'),
    '#description' => t('Do you want to show the title of the page in the breadcrumb?'),
    '#options' => array(
      0 => t('No'),
      1 => t('Yes'),
    ),
    '#default_value' => theme_get_setting('breadcrumb_title'),
  );

  $form['breadcrumb']['breadcrumb_title_length'] = array(
    '#type' => 'select',
    '#title' => t('Title length'),
    '#description' => t('The title in the breadcrumb will be truncated after this number of character'),
    '#options' => array(
      10 => 10,
      20 => 20,
      30 => 30,
      40 => 40,
      50 => 50,
      60 => 60,
      70 => 70,
      80 => 90,
      100 => 100,
    ),
    '#default_value' => theme_get_setting('breadcrumb_title_length'),
  );

  // Menu elements
  $form['menu']['menu_type'] = array(
    '#type' => 'select',
    '#title' => t('Which kind of primary links do you want to use?'),
    '#description' => t('Classic one-level primary links, or mega drop-down menu'),
    '#options' => array(
      1 => t('Classic Primary Links'),
      2 => t('Mega Drop Down'),
    ),
    '#default_value' => theme_get_setting('menu_type'),
  );
  
  $form['menu']['menu_element'] = array(
    '#type' => 'select',
    '#title' => t('Megamenu Source'),
    '#description' => t('Choose a menu to render as megamenu'),
    '#options' => menu_get_menus(),
    '#default_value' => theme_get_setting('menu_element'),
  );

  $form['menu']['menu_alt_class'] = array(
    '#type' => 'select',
    '#multiple' => TRUE,
    '#title' => t('Alt class (mega menu only)'),
    '#description' => t('On which occurency of primary links would you like to put the alt class?'),
    '#options' => array(
      0 => t('none'),
      1 => 1,
      2 => 2,
      3 => 3,
      4 => 4,
      5 => 5,
      6 => 6,
      7 => 7,
      8 => 8,
      9 => 9,
      10 => 10,
      11 => 11,
      12 => 12,
    ),
    '#suffix' => '<strong>' . t('The alt class will open the mega menu on the left') . '</strong>',
    '#default_value' => theme_get_setting('menu_alt_class'),
  );
  
  // accessibility and code semantics settings
  
  $form['accessibility']['outside_tags'] = array(
    '#type' => 'select',
    '#title' => t('Do you want to use p tag for all the section titles that come before main site content?'),
    '#description' => t('If you choose yes, the theme will try to comply WCAG2 headings specifications by using the p tag instead of h2 for all the section title tags that come before the main content (for example banner title and top region block titles) '),
    '#options' => array(
	    0 => t('NO'),
	    1 => t('YES'),
    ),
    
    '#default_value' => theme_get_setting('outside_tags'),
  );
  
  $form['accessibility']['title_tags'] = array(
    '#type' => 'select',
    '#title' => t('Do you want to use h1 and h2 tags for site title and site slogan on the front page?'),
    '#description' => t('If you choose NO, the theme will force the p tag for title and slogan in every page. This might help with WCAG2 headings specification if you front page has the page title'),
    '#options' => array(
	    0 => t('YES'),
	    1 => t('NO'),
    ),
    
    '#default_value' => theme_get_setting('title_tags'),
  );
  
  $form['accessibility']['menu_headings'] = array(
    '#type' => 'select',
    '#title' => t('Which tag do you want to use for menu section titles?'),
    '#description' => t('Using headings will improve screen-reader based navigation, however you will fail Wcag2 headings order raccomandations'),
    '#options' => array(
      1 => t('only <li>'),
      2 => t('<h2> for first level and <h3> for megamenu sections'),
    ),
    '#default_value' => theme_get_setting('menu_headings'),
  );
  
  // text vars

  return $form;
}

/**
 * Check if folder is available or create it.
 *
 * @param <string> $dir
 *    Folder to check
 */
function _pdxneedto_check_dir($dir) {
  // Normalize directory name
  $dir = file_stream_wrapper_uri_normalize($dir);

  // Create directory (if not exist)
  file_prepare_directory($dir,  FILE_CREATE_DIRECTORY);
}

