<?php

/**
 * @file
 * Default theme implementation for displaying search results.
 *
 * This template collects each invocation of theme_search_result(). This and
 * the child template are dependent to one another sharing the markup for
 * definition lists.
 *
 * Note that modules may implement their own search type and theme function
 * completely bypassing this template.
 *
 * Available variables:
 * - $search_results: All results as it is rendered through
 *   search-result.tpl.php
 * - $module: The machine-readable name of the module (tab) being searched, such
 *   as "node" or "user".
 *
 *
 * @see template_preprocess_search_results()
 */
?>
<div class="search_content">
<?php if ($search_results): ?>
  <h2 class="search_title"><?php
print t('Search results');
if( arg(0)=='search' and is_string(arg(1)) and arg(1)=='node' and is_string(arg(2)) ){
    echo ': «'.arg(2).'»';
}
  
?></h2>
    <?php print $search_results; ?>
  <?php print $pager; ?>
  <div class="clear">&nbsp;</div>
<?php else : ?>
  <h2><?php print t('Your search yielded no results');?></h2>
  <?php print search_help('search#noresults', drupal_help_arg()); ?>
<?php endif; ?>
</div>