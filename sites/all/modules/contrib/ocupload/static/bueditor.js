BUE.postprocess.ocupload = function(E, $) {
  E.showFileSelectionDialog = function () {
    alert(Drupal.t('Module initialization error.'));
  }

  for (var i = 0; i < E.tpl.buttons.length; i++) {
    if (E.tpl.buttons[i][1] == 'js: E.showFileSelectionDialog();') {
      var $button = $('#bue-' + E.index + '-button-' + i);

      $button.load(function() {
        if (Drupal.settings.ocupload.allowedExt == '*.;') {
          $button.remove();
          return;
        }

        // Get real button width and height
        var $buttonClone = $button.clone();
        $buttonClone.css('visibility', 'hidden').insertAfter($button);
        var buttonWidth = $buttonClone.outerWidth();
        var buttonHeight = $buttonClone.outerHeight();
        $buttonClone.remove();

        var $wrapper = $('<span class="ocupload-button-wrapper"></span>');
        $button.wrap($wrapper);
        $button.closest('.bue-ui').append('<span id="ocupload-progress-' + E.index + '" class="ocupload-progress"></span>');

        var $placeholder = $('<input type="button" />');
        $placeholder.insertBefore($button);

        var swfu = new SWFUpload({
          flash_url              : Drupal.settings.basePath + 'sites/all/libraries/swfupload/Flash/swfupload.swf',
          upload_url             : Drupal.settings.ocupload.uploadPath,
          button_placeholder     : $placeholder.get(0),
          file_size_limit        : Drupal.settings.ocupload.sizeLimit + ' B',
          file_types             : Drupal.settings.ocupload.allowedExt,
          file_types_description : Drupal.t('Files'),
          file_upload_limit      : 0,
          prevent_swf_caching    : false,
          button_image_url       : Drupal.settings.ocupload.modulePath + '/static/clear.gif',
          button_window_mode     : SWFUpload.WINDOW_MODE.TRANSPARENT,
          button_width           : buttonWidth,
          button_height          : buttonHeight,
          button_cursor          : SWFUpload.CURSOR.HAND,
          file_post_name         : 'files[file]',
          post_params            : {'phpsessid':Drupal.settings.ocupload.phpsessid},
          // After files select
          file_dialog_complete_handler: function() {
            swfu.addPostParam('selectedText', BUE.active.getSelection());
            swfu.addPostParam('formId', $('textarea[name="' + E.textArea.name + '"]').closest('form').find('input[name="form_id"]').val());
            swfu.addPostParam('fieldName', E.textArea.name);
            this.startUpload();
          },
          // Start upload one file in queue
          upload_start_handler: function(file) {
            $('#ocupload-progress-' + E.index).html(Drupal.t('Uploading @filename', {'@filename':file.name}));
          },
          // After upload one file
          upload_success_handler: function(file, serverData) {
            serverData = jQuery.trim(serverData);
            if (serverData.substring(0, 1) != '{') {
              return alert(Drupal.t('Server response came not in JSON format') + ': "' + serverData + '"');
            }
            response = $.parseJSON(serverData);
            if (response.status) {
              BUE.active.replaceSelection(response.data + "\n", 'end');
            }
            else {
              alert(response.data);
            }
            BUE.active.focus();
          },
          // After upload all files
          queue_complete_handler: function() {
            $('#ocupload-progress-' + E.index).html('');
          },
          // Error during upload
          upload_error_handler: function(file, errorCode, message) {
            alert('Error ' + errorCode + ': ' + message);
            $('#ocupload-progress-' + E.index).html('');
          }
        });

        $button.parent().mousedown(function(event){
          event.stopPropagation();
        });
      });

      if ($button.attr('type') != 'image') {
        $button.load();
      }

      break;
    }
  }
};
