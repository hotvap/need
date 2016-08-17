<?php
/**
 * 
 * @file inline_ajax_search-theme-form.tpl.php
 * @see template_preprocess_inline_ajax_search_theme_form()
 */
?>
<div id="inline_ajax_search_container">
	<form id="inline-ajax-search-form" method="post" accept-charset="UTF-8" action="<?php print $inline_ajax_search->formurl; ?>"><div>
    <input type="text" name="inline_ajax_search" id="inline_ajax_search" title="<?php print t('type a keyword'); ?>" />
    <div style="display:none" class="searchthrobber IAS_block_throbber"></div>
    <div id="inline_ajax_search_results_pre"><div id="inline_ajax_search_results"></div></div>
  </div></form>
</div>
