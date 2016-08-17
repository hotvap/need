<?php
/**
 * @file
 * Service functions
 */

/**
 * Admin page with templates list
 */
function ocupload_config_page() {
  $table_data = array();
  foreach (ocupload_templates() as $template) {
    $actions = array(
      l(t('edit'), 'admin/config/content/ocupload/edit/' . $template->tid),
      l(t('delete'), 'admin/config/content/ocupload/delete/' . $template->tid),
    );
    $table_data[] = array($template->mask, implode(' | ', $actions));
  }

  $build = array();
  $build['templates'] = array(
    '#theme' => 'table',
    '#header' => array(t('Mask'), t('Operations')),
    '#rows' => $table_data,
    '#prefix' => '<h3>' . t('Templates') . '</h3>',
    '#suffix' => '<br />',
  );
  $build['form'] = drupal_get_form('ocupload_form_settings');
  $build['form']['#prefix'] = '<h3>' . t('Options') . '</h3>';

  return $build;
}

/**
 * Form module settings
 */
function ocupload_form_settings($form, &$form_state) {
  $form['ocupload_delete_unused_files'] = array(
    '#type' => 'checkbox',
    '#title' => t('Delete unused files'),
    '#description' => t('To save space uploaded files that are not used in text will be deleted'),
    '#default_value' => variable_get('ocupload_delete_unused_files', 1),
  );
  return system_settings_form($form);
}

/**
 * Form add/edit template
 */
function ocupload_form_template($form, &$form_state, $template = NULL) {
  if (!$template) {
    $template = (object)array(
      'tid' => 0,
      'mask' => '',
      'path' => '',
      'max_filesize' => 0,
      'template' => '<a href="!filepath">!filename</a>',
      'template_select' => '<a href="!filepath">!text</a>',
      'image_style' => '',
      'image_style_original' => '',
      'link_to_original' => 0,
      'link_template' => '<a href="!filepath" target="_blank">!image</a>',
      'link_only_big' => 0,
      'max_dimensions' => '',
      'rename_file' => 0,
    );
    drupal_set_title(t('Add template'));
  }

  $form['tid'] = array(
    '#type' => 'value',
    '#value' => $template->tid,
  );

  $form['mask'] = array(
    '#type' => 'textfield',
    '#title' => t('File extensions'),
    '#description' => t('Comma separated list of file extensions which should be handled within this template. Example: <code>jpg,gif,png</code>'),
    '#default_value' => $template->mask,
    '#size' => 40,
    '#required' => TRUE,
  );

  $public_path = variable_get('file_public_path', conf_path() . '/files');
  $form['path'] = array(
    '#type' => 'textfield',
    '#title' => t('Upload path'),
    '#description' => t('Dir name, relative <code>!path</code>, with will upload files. Without the initial and trailing slashes.', array('!path' => $public_path)),
    '#default_value' => $template->path,
    '#field_prefix' => $public_path . '/',
    '#size' => 22,
    '#required' => TRUE,
  );

  if (module_exists('token')) {
    $form['token_help'] = array(
      '#title' => t('Replacement patterns'),
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );

    $form['token_help']['help'] = array(
      '#theme' => 'token_tree',
      '#token_types' => array('user'),
    );
  }

  $form['max_filesize'] = array(
    '#type' => 'textfield',
    '#title' => t('Maximum upload size'),
    '#default_value' => $template->max_filesize ? format_size($template->max_filesize, 'en') : 0,
    '#description' => t('Enter a value like "512" (bytes), "80 KB" (kilobytes) or "50 MB" (megabytes) in order to restrict the allowed file size. If left empty the file sizes will be limited only by PHP\'s maximum post and file upload sizes (current limit <strong>%limit</strong>).', array('%limit' => format_size(file_upload_max_size()))) . ' ' . t('Limit except for the user #1') . '.',
    '#size' => 10,
    '#element_validate' => array('_ocupload_check_max_filesize'),
  );

  $form['template'] = array(
    '#type' => 'textfield',
    '#title' => t('Template to insert into editor'),
    '#default_value' => $template->template,
    '#maxlength' => NULL,
    '#size' => 100,
    '#required' => TRUE,
  );

  $form['template_select'] = array(
    '#type' => 'textfield',
    '#title' => t('Template to replace selected text'),
    '#default_value' => $template->template_select,
    '#maxlength' => NULL,
    '#size' => 100,
    '#required' => TRUE,
  );

  $form['help'] = array(
    '#type' => 'fieldset',
    '#title' => t('Replacement patterns'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['help']['text'] = array(
    '#markup' => '
      !filepath     — ' . t('full path to file') . '<br />
      !filename     — ' . t('file name after upload') . '<br />
      !originalname — ' . t('original file name') . '<br />
      !fileext      — ' . t('file extension') . '<br />
      !filesize     — ' . t('file size') . '<br />
      !text         — ' . t('selected text') . '<br />
    ',
  );

  if (module_exists('image')) {
    $form['image_settings'] = array(
      '#type' => 'fieldset',
      '#title' => t('Options for images'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );

    $form['image_settings']['image_style'] = array(
      '#type' => 'select',
      '#title' => t('Use image style'),
      '#options' => image_style_options(),
      '#default_value' => $template->image_style,
    );

    $form['image_settings']['link_to_original'] = array(
      '#type' => 'checkbox',
      '#title' => t('Add link to original image'),
      '#default_value' => $template->link_to_original,
      '#states' => array(
        'invisible' => array(
          'select[name="image_style"]' => array('value' => ''),
        ),
      ),
    );

    $form['image_settings']['link_template'] = array(
      '#type' => 'textfield',
      '#title' => t('Link template'),
      '#default_value' => $template->link_template,
      '#states' => array(
        'visible' => array(
          'input[name="link_to_original"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['image_settings']['link_only_big'] = array(
      '#type' => 'checkbox',
      '#title' => t('Add link only for those images which size exceeds the size, specified in the style'),
      '#default_value' => $template->link_only_big,
      '#states' => array(
        'visible' => array(
          'input[name="link_to_original"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['image_settings']['use_image_style_original'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use image style for original image'),
      '#default_value' => (bool)$template->image_style_original,
      '#states' => array(
        'visible' => array(
          'input[name="link_to_original"]' => array('checked' => TRUE),
        )
      ),
    );

    $form['image_settings']['image_style_original'] = array(
      '#type' => 'select',
      '#title' => t('Image style for original image'),
      '#options' => image_style_options(FALSE),
      '#default_value' => $template->image_style_original,
      '#states' => array(
        'visible' => array(
          'input[name="use_image_style_original"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['image_settings']['max_dimensions'] = array(
      '#type' => 'textfield',
      '#title' => 'Maximum image resolution',
      '#description' => t('The maximum allowed image size expressed as WIDTHxHEIGHT (e.g. 640x480). Leave blank for no restriction. If a larger image is uploaded, it will be resized to reflect the given width and height. Resizing images on upload will cause the loss of <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format">EXIF data</a> in the image.'),
      '#default_value' => $template->max_dimensions,
      '#size' => 10,
    );
  }

  $form['rename_file'] = array(
    '#type' => 'checkbox',
    '#title' => t('Rename uploaded file'),
    '#description' => t('Checked, if you want to rename uploded files according to current date/time.'),
    '#default_value' => $template->rename_file,
  );

  $form['roles'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Roles'),
    '#description' => t('Checked roles which can upload files of this type.'),
    '#options' => array_map('check_plain', user_roles()),
    '#default_value' => array_keys(user_roles(FALSE, 'upload files use template ' . $template->tid)),
  );

  $form['actions'] = array(
    '#type' => 'actions',
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => $template->tid ? t('Save template') : t('Add template'),
  );

  return $form;
}

/**
 * Form submit
 */
function ocupload_form_template_submit($form, &$form_state) {
  $template = array(
    'tid'             => $form_state['values']['tid'],
    'mask'            => str_replace(' ', '', $form_state['values']['mask']),
    'path'            => $form_state['values']['path'],
    'max_filesize'    => parse_size($form_state['values']['max_filesize']),
    'template'        => $form_state['values']['template'],
    'template_select' => $form_state['values']['template_select'],
    'rename_file'     => $form_state['values']['rename_file'],
  );

  if (module_exists('image')) {
    $template += array(
      'image_style'          => $form_state['values']['image_style'],
      'link_to_original'     => $form_state['values']['link_to_original'],
      'link_template'        => $form_state['values']['link_template'],
      'link_only_big'        => $form_state['values']['link_only_big'],
      'image_style_original' => $form_state['values']['use_image_style_original'] ? $form_state['values']['image_style_original'] : '',
      'max_dimensions'       => $form_state['values']['max_dimensions'],
    );
  }

  ocupload_template_save($template);

  foreach ($form_state['values']['roles'] as $rid => $enabled) {
    user_role_change_permissions($rid, array('upload files use template ' . $template['tid'] => $enabled));
  }

  drupal_set_message($form_state['values']['tid'] ? t('Template saved') : t('Template added'));
  $form_state['redirect'] = 'admin/config/content/ocupload';
}

/**
 * Confirm form delete teamplate
 */
function ocupload_delete_confirm($form, &$form_state, $template) {
  $form['tid'] = array(
    '#type' => 'value',
    '#value' => $template->tid,
  );

  return confirm_form(
    $form,
    t('Do you really want to delete template') . ' ' . $template->tid . '?',
    'admin/config/content/ocupload'
  );
}

/**
 * Confirm form submit
 */
function ocupload_delete_confirm_submit($form, &$form_state) {
  ocupload_template_delete($form_state['values']['tid']);
  drupal_set_message(t('Deleted'));
  $form_state['redirect'] = 'admin/config/content/ocupload';
}

/**
 * Upload file
 */
function ocupload_upload() {
  global $user, $base_url;

  _ocupload_auth();

  $template = NULL;
  $extension_uploaded_file = _ocupload_get_file_extension($_FILES['files']['name']['file']);
  $selected_text = !empty($_POST['selectedText']) ? $_POST['selectedText'] : '';

  foreach (ocupload_templates() as $value) {
    if (in_array($extension_uploaded_file, explode(',', $value->mask)) && user_access('upload files use template ' . $value->tid)) {
      $template = $value;
      break;
    }
  }

  if (!$template) {
    return _ocupload_answer(FALSE, t('This file type can not be upload'));
  }

  $validators = array(
    'file_validate_extensions' => array(str_replace(',', ' ', $template->mask)),
    'file_validate_size' => array(file_upload_max_size()),
  );
  if ($template->max_filesize) {
    $validators['file_validate_size'][0] = $template->max_filesize;
  }
  if (_ocupload_is_image($_FILES['files']['name']['file'])) {
    $validators['file_validate_is_image'] = array();
    if ($template->max_dimensions) {
      $validators['file_validate_image_resolution'] = array($template->max_dimensions);
    }
  }

  $destination = 'public://' . token_replace($template->path, array('user' => $user));
  file_prepare_directory($destination, FILE_CREATE_DIRECTORY);
  $file = file_save_upload('file', $validators, $destination);

  if ($file) {
    if (!variable_get('ocupload_delete_unused_files', 1)) {
      $file->status = FILE_STATUS_PERMANENT;
      $file = file_save($file);
    }

    if ($template->rename_file) {
      $file = file_move($file, $destination . '/' . format_date(REQUEST_TIME, 'custom', 'YdmHis') . '.' . $extension_uploaded_file);
    }

    $cur_template = $selected_text ? $template->template_select : $template->template;
    $filepath = $file->uri;

    // If upload image and template image style not empty
    if ($selected_text == '' && _ocupload_is_image($file->filename) && $template->image_style && module_exists('image')) {
      $filepath = image_style_url($template->image_style, $file->uri);

      if ($template->link_to_original) {
        $wrap_template = TRUE;
        if ($template->link_only_big) {
          $image_style = image_style_load($template->image_style);
          $image_info = image_get_info($file->uri);
          $styled_image_dimensions = array(
            'width' => $image_info['width'],
            'height' => $image_info['height'],
          );

          image_style_transform_dimensions($template->image_style, $styled_image_dimensions);

          if (
            $image_info['width'] <= $styled_image_dimensions['width'] &&
            $image_info['height'] <= $styled_image_dimensions['height']
          ) {
            $wrap_template = FALSE;
          }
        }
        if ($wrap_template) {
          $original_image_uri = $file->uri;
          if ($template->image_style_original) {
            $original_image_uri = image_style_url($template->image_style_original, $original_image_uri);
          }
          $cur_template = strtr($template->link_template, array(
            '!filepath' => _ocupload_get_local_url($original_image_uri),
            '!image' => $cur_template,
          ));
        }
      }
    }

    $data = strtr($cur_template, array(
      '!filepath'     => _ocupload_get_local_url($filepath),
      '!filename'     => drupal_basename($file->uri),
      '!originalname' => $file->filename,
      '!fileext'      => $extension_uploaded_file,
      '!filesize'     => format_size($file->destination),
      '!text'         => $selected_text,
    ));

    if (variable_get('ocupload_delete_unused_files', 1)) {
      $cid = 'ocupload:' . check_plain($_POST['formId']) . ':' . $user->uid;
      if ($cache = cache_get($cid)) {
        $files = $cache->data;
      }
      $files[check_plain($_POST['fieldName'])][$file->fid] = drupal_basename($file->uri);
      cache_set($cid, $files, 'cache', REQUEST_TIME + DRUPAL_MAXIMUM_TEMP_FILE_AGE);
    }

    return _ocupload_answer(TRUE, $data);
  }
  // If upload error
  else {
    $errors = drupal_get_messages(NULL, TRUE);
    $data = array();
    foreach ($errors as $error_per_type) {
      $data[] = strip_tags(implode("\n\n", $error_per_type));
    }
    return _ocupload_answer(FALSE, implode("\n\n", $data));
  }
}

/**
 * Add/save template
 */
function ocupload_template_save(&$template, $multi = FALSE) {
  if ($multi) {
    return array_map('ocupload_template_save', $template);
  }

  $template_array = (array)$template;
  return drupal_write_record('ocupload_templates', $template, !empty($template_array['tid']) ? 'tid' : array());
}

/**
 * Delete template
 */
function ocupload_template_delete($tid) {
  db_delete('ocupload_templates')->condition('tid', $tid)->execute();
}

/**
 * Authorize by $_POST data
 */
function _ocupload_auth() {
  $phpsessid = isset($_POST['phpsessid']) ? $_POST['phpsessid'] : FALSE;
  if ($phpsessid && session_id() != $phpsessid) {
    @session_destroy();
    session_id($phpsessid);
    $_COOKIE[session_name()] = $phpsessid;
    drupal_session_started(FALSE);
    drupal_session_initialize();
  }
}

/**
 * Return file extension
 */
function _ocupload_get_file_extension($filename) {
  return drupal_strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Return local url by uri. Example:
 * "_ocupload_get_local_url('public://images/logo.jpg')" return
 * "/sites/default/files/images/logo.jpg"
 */
function _ocupload_get_local_url($uri) {
  return drupal_substr(file_create_url($uri), drupal_strlen($GLOBALS['base_root']));
}

/**
 * Return TRUE if file extension equal jpg,gif,png,bmp
 */
function _ocupload_is_image($filename) {
  $extension = _ocupload_get_file_extension($filename);
  return in_array($extension, array('jpg', 'jpeg', 'png', 'gif', 'bmp'));
}

/**
 * Return answer in json format
 */
function _ocupload_answer($status, $data) {
  drupal_json_output(array('status' => $status, 'data' => $data));
}

/**
 * Element validate callback for the maximum upload size field.
 * Ensure a size that can be parsed by parse_size() has been entered.
 */
function _ocupload_check_max_filesize($element, &$form_state) {
  if (!empty($element['#value']) && !is_numeric(parse_size($element['#value']))) {
    form_error($element, t('The "!name" option must contain a valid value. You may either leave the text field empty or enter a string like "512" (bytes), "80 KB" (kilobytes) or "50 MB" (megabytes).', array('!name' => t($element['title']))));
  }
}
