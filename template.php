<?php

/**
 * Implements hook_css_alter().
 * Clearer than omitting in .info file.
 * Omitted:
 * - color.css
 * - contextual.css
 * - dashboard.css
 * - field_ui.css
 * - image.css
 * - locale.css
 * - shortcut.css
 * - simpletest.css
 * - toolbar.css
 */
function hks_css_alter(&$css) {
  $exclude = array(
    'misc/vertical-tabs.css' => FALSE,
    'modules/aggregator/aggregator.css' => FALSE,
    'modules/block/block.css' => FALSE,
    'modules/book/book.css' => FALSE,
    'modules/comment/comment.css' => FALSE,
    'modules/dblog/dblog.css' => FALSE,
    'modules/file/file.css' => FALSE,
    'modules/filter/filter.css' => FALSE,
    'modules/forum/forum.css' => FALSE,
    'modules/help/help.css' => FALSE,
    'modules/menu/menu.css' => FALSE,
    'modules/node/node.css' => FALSE,
    'modules/openid/openid.css' => FALSE,
    'modules/poll/poll.css' => FALSE,
    'modules/profile/profile.css' => FALSE,
    'modules/search/search.css' => FALSE,
    'modules/statistics/statistics.css' => FALSE,
    'modules/syslog/syslog.css' => FALSE,
    'modules/system/admin.css' => FALSE,
    'modules/system/maintenance.css' => FALSE,
    'modules/system/system.css' => FALSE,
    'modules/system/system.admin.css' => FALSE,
    'modules/system/system.base.css' => FALSE,
    'modules/system/system.maintenance.css' => FALSE,
    'modules/system/system.menus.css' => FALSE,
    //'modules/system/system.messages.css' => FALSE,
    'modules/system/system.theme.css' => FALSE,
    'modules/taxonomy/taxonomy.css' => FALSE,
    'modules/tracker/tracker.css' => FALSE,
    'modules/update/update.css' => FALSE,
    'modules/user/user.css' => FALSE,
  );
  $css = array_diff_key($css, $exclude);
}


/**
 * Preprocess functions ===============================================
 */
function hks_preprocess_html(&$vars) {
  if(!module_exists('jquery_update')){
    drupal_set_message('This theme requires the jQuery Update module.','notice');
  }
  
  $vars['classes_array'][] = 'hks';
  //drupal_flush_all_caches()
  //cache_clear_all(); //TODO
}

/**
 * Implementation of preprocess_page().
 */
function hks_preprocess_page(&$vars) {
  // Split primary and secondary local tasks
  $vars['primary_local_tasks'] = menu_primary_local_tasks();
  $vars['secondary_local_tasks'] = menu_secondary_local_tasks();

  /*
    USER ACCOUNT
    Removes the tabs from user  login, register & password
    fixes the titles to so no more "user account" all over
  */
  switch (current_path()) {
    case 'user':
      $vars['title'] = t('Login');
      unset( $vars['primary_local_tasks'] );
      break;
    case 'user/register':
      $vars['title'] = t('New account');
      unset( $vars['primary_local_tasks'] );
      break;
    case 'user/password':
      $vars['title'] = t('I forgot my password');
      unset( $vars['primary_local_tasks'] );
      break;

    default:
      # code...
      break;
  }

  //$vars['main_menu'] = theme('links', array('links' => $vars['main_menu'], 'attributes' => array('class' => 'links main-menu main-nav fix')));
  //$vars['secondary_menu'] = theme('links', array('links' => $vars['secondary_menu'], 'attributes' => array('class' => 'links secondary-menu fix')));

// calculate main width based on sidebar visibility
  $grid = 12;
  $side1 = 2;
  $side2 = 2;

  if(!$vars['page']['sidebar_second']){
    $side2 = 0;
  }
  if(!$vars['page']['sidebar_first']){
    $side1 = 0;
  }

  $main = $grid - $side1 - $side2;
  $vars['main_content_class'] = 'col_' . $main;
  $vars['sidebar_first_class'] = 'col_' . $side1;
  $vars['sidebar_second_class'] = 'col_' . $side2;

}

/**
 * Implementation of preprocess_block().
 */
function hks_preprocess_block(&$vars) {
  $vars['hook'] = 'block';

  $vars['attributes_array']['class'][] = $vars['block_html_id'];

  $vars['title_attributes_array']['class'][] = 'block-title';
  $vars['title_attributes_array']['class'][] = 'clearfix';

  $vars['content_attributes_array']['class'][] = 'block-content';
  $vars['content_attributes_array']['class'][] = 'clearfix';

  if ($vars['block']->module == 'block') {
    $vars['content_attributes_array']['class'][] = 'prose';
  }

  $vars['title'] = !empty($vars['block']->subject) ? $vars['block']->subject : '';

  // In D7 the page content may be served as a block. Replace the generic
  // 'block' class from the page content with a more specific class that can
  // be used to distinguish this block from others.
  // Subthemes can easily override this behavior in an implementation of
  // preprocess_block().
  if ($vars['block']->module === 'system' && $vars['block']->delta === 'main') {
    $vars['classes_array'] = array_diff($vars['classes_array'], array('block'));
    $vars['classes_array'][] = 'block-page-content';
  }

/* col_9 column  
if ($vars['block']->delta === 'main-menu') {
    $vars['classes_array'][] = 'column';
    $vars['classes_array'][] = 'col_9';
}

if ($vars['block']->delta === 'user-menu') {
    $vars['classes_array'][] = 'column';
    $vars['classes_array'][] = 'col_3';
}
*/

}

/**
 * Implementation of preprocess_node().
 */
function hks_preprocess_node(&$vars) {
  //global $node;
  $vars['hook'] = 'node';

  $vars['attributes_array']['id'] = "node-{$vars['node']->nid}";

  $vars['title_attributes_array']['class'][] = 'node-title';
  $vars['title_attributes_array']['class'][] = 'clearfix';

  $vars['content_attributes_array']['class'][] = 'node-content';
  $vars['content_attributes_array']['class'][] = 'clearfix';

  if (isset($vars['content']['links'])) {
    $vars['links'] = $vars['content']['links'];
    unset($vars['content']['links']);
  }

  if (isset($vars['content']['comments'])) {
    $vars['post_object']['comments'] = $vars['content']['comments'];
    unset($vars['content']['comments']);
  }
  
// Submitted area done Wordpress style
  if ($vars['display_submitted']) {
   $submitted = '<span class="sword">' . t('by') . ' </span><span class="author vcard fn">' . $vars['name'] . '</span>';
   $submitted .= '<span class="sword"> ' . t('on') . '</span> ' . format_date($vars['changed'], $type = 'custom', $format = 'l\, F j\, Y');
   $submitted .= ' · ' . '<a href="'.$vars['node_url'].'#comments">' . format_plural($vars['comment_count'], '1 Comment', '@count Comments') .'</a>';
   $vars['submitted'] = $submitted;
  }

}

/**
 * Implementation of preprocess_comment().
 */
function hks_preprocess_comment(&$vars) {
  $vars['hook'] = 'comment';

  $vars['title_attributes_array']['class'][] = 'comment-title';
  $vars['title_attributes_array']['class'][] = 'clearfix';

  $vars['content_attributes_array']['class'][] = 'comment-content';
  $vars['content_attributes_array']['class'][] = 'clearfix';

  $vars['submitted'] = t('Submitted by !username on !datetime', array(
    '!username' => $vars['author'],
    '!datetime' => $vars['created'],
  ));

  if (isset($vars['content']['links'])) {
    $vars['links'] = $vars['content']['links'];
    unset($vars['content']['links']);
  }
}

/**
 * Implementation of preprocess_fieldset().
 */
function hks_preprocess_fieldset(&$vars) {
  $element = $vars['element'];
  _form_set_class($element, array('form-wrapper'));
  $vars['attributes'] = isset($element['#attributes']) ? $element['#attributes'] : array();
  $vars['attributes']['class'][] = 'fieldset';
  if (!empty($element['#title'])) {
    $vars['attributes']['class'][] = 'titled';
  }
  if (!empty($element['#id'])) {
    $vars['attributes']['id'] = $element['#id'];
    $vars['attributes']['class'][] = $element['#id'];
  }

  $description = !empty($element['#description']) ? "<div class='description'>{$element['#description']}</div>" : '';
  $children = !empty($element['#children']) ? $element['#children'] : '';
  $value = !empty($element['#value']) ? $element['#value'] : '';
  $vars['content'] = $description . $children . $value;
  $vars['title'] = !empty($element['#title']) ? $element['#title'] : '';
  $vars['hook'] = 'fieldset';
}

/**
 * Implementation of preprocess_field().
 */
function hks_preprocess_field(&$vars) {
  // Add prose class to long text fields.
  if ($vars['element']['#field_type'] === 'text_with_summary') {
    $vars['classes_array'][] = 'prose';
  }
}

function hks_preprocess_table(&$vars){
  //table styles: striped, tight, sortable (can mix/match them)
  $vars['attributes']['class'] = array('striped','tight', 'sortable');
}
/**
 * Function overrides =================================================
 */

/**
 * Override of theme('textarea').
 * Deprecate misc/textarea.js in favor of using the 'resize' CSS3 property.
 */
function hks_textarea($vars) {
  $element = $vars['element'];
  $element['#attributes']['name'] = $element['#name'];
  $element['#attributes']['id'] = $element['#id'];
  $element['#attributes']['cols'] = $element['#cols'];
  $element['#attributes']['rows'] = $element['#rows'];
  _form_set_class($element, array('form-textarea'));

  $wrapper_attributes = array(
    'class' => array('form-textarea-wrapper'),
  );

  // Add resizable behavior.
  if (!empty($element['#resizable'])) {
    $wrapper_attributes['class'][] = 'resizable';
  }

  $output = '<div' . drupal_attributes($wrapper_attributes) . '>';
  $output .= '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';
  $output .= '</div>';
  return $output;
}

// GROUP OF BUTTONS
function hks_container($vars) {
  $element = $vars['element'];

  // Special handling for form elements.
  if (isset($element['#array_parents'])) {
    // Assign an html ID.
    if (!isset($element['#attributes']['id'])) {
      $element['#attributes']['class'][] = $element['#id'];
    }
    // Add the 'form-wrapper' class.
    $element['#attributes']['class'][] = 'form-wrapper';
    $element['#attributes']['class'][] = 'edit-actions';
    //TODO translations ok?
    if(isset($element['#value']) && ($element['#value'] == 'Save' || $element['#value'] == 'Preview' )){
      $element['#attributes']['class'] .= ' save-preview-wrapper';
    }
  }

  return '<div' . drupal_attributes($element['#attributes']) . '>' . $element['#children'] . '</div>';
}

// BUTTONS
function hks_button($vars) {
  $element = $vars['element'];
  $element['#attributes']['type'] = 'submit';
  element_set_attributes($element, array('id', 'name', 'value'));

  $element['#attributes']['class'][] = 'form-' . $element['#button_type'];
  if ($element['#value'] == 'Save'){
    $element['#attributes']['class'][] = 'button blue';
  }
  
  if (!empty($element['#attributes']['disabled'])) {
    $element['#attributes']['class'][] = 'form-button-disabled';
  }

  return '<input' . drupal_attributes($element['#attributes']) . ' />';
}

function hks_menu_breadcrumb_alter(&$active_trail, $item) {
  // Always display a link to the current page by duplicating the last link in
  // the active trail. This means that menu_get_active_breadcrumb() will remove
  // the last link (for the current page), but since it is added once more here,
  // it will appear.
  /*if (!drupal_is_front_page()) {
    $end = end($active_trail);
    if ($item['href'] == $end['href']) {
      $active_trail[] = $end;
    }
  }*/
}

/**
  * === BREADCRUMB ===
  * Override of theme_breadcrumb().
  */
 function hks_breadcrumb($vars) {
  $breadcrumbs = $vars['breadcrumb'];
  //remove last breadcrumb as it contains the current page
  array_pop($breadcrumbs);
  // single breadcrumb makes no sense
  $num = count($breadcrumbs);
  if(empty($breadcrumbs) || $num == 1){
    return '';
  }
 foreach($breadcrumbs as $key => $crumb) {
  if($key == 0){
    $breadcrumb = '<li class="first">' . $crumb . '</li>';
   }
   else if($key+1 != $num ) {
    $breadcrumb .= '<li>' . $crumb . '</li>';
   }
   else if($key+1 == $num ) {
    $breadcrumb .= '<li class="last" >' . $crumb . '</li>';
   }
 }
  return '<ul class="breadcrumbs alt1">' . $breadcrumb . '</ul>';

}

// LOCAL LINKS
function hks_menu_local_task($vars) {
  $link = $vars['element']['#link'];
  $link_text = $link['title'];
  $link['localized_options']['html'] = TRUE;

if(strpos($link['path'], 'devel')){
    $icon = 'icon-lightbulb';
  }
  else {
  switch ($link['path']) {
    case 'node/%/view':
      $icon = 'icon-eye-open';
      break;
    case 'node/%/edit':
      $icon = 'icon-edit';
      break;
    case 'user/%/view':
      $icon = 'icon-user';
      break;
    case 'user/%/edit':
      $icon = 'icon-edit';
      break;
    case 'user/%/shortcuts':
      $icon = 'icon-bookmark';
      break;
    default:
      $icon ='icon-cog';
      break;
  }
}
  $link_icon = '<i class="'. $icon .'"></i>';
  
  if (!empty($vars['element']['#active'])) {
    // Add text to indicate active tab for non-visual users.
    $active = '<span class="element-invisible">' . t('(active tab)') . '</span>';

    $link_text = t('!local-task-title!active', array('!local-task-title' => $link['title'], '!active' => $active));
  }

  if (!empty($vars['element']['#active'])) {
    $classes_ar[] = 'active';
    }

  $classes = (isset($classes_ar) && is_array($classes_ar))? implode(' ', $classes_ar): '';
  return '<li ' . 'class="' . $classes . '">' . l($link_icon . $link_text, $link['href'], $link['localized_options']) . " </li>\n";
}

function hks_menu_link($vars) {
  //clean up the classes

    $remove = array('first','last','leaf','collapsed','expanded','expandable');
    $vars['element']['#attributes']['class'] = array_diff($vars['element']['#attributes']['class'],$remove);

  //Remove thee menu-mlid-[NUMBER]
    $vars['element']['#attributes']['class'] = preg_grep('/^menu-mlid-/', $vars['element']['#attributes']['class'], PREG_GREP_INVERT);


  $element = $vars['element'];

  $sub_menu = '';
  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }

  //replace active-trail with current
  if($key = array_search('active-trail', $element['#attributes']['class'])){
    $element['#attributes']['class'][$key] = 'current';
  }

   //dpr($vars['element']['#attributes']);
   //dpr($element['#localized_options']);

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Returns HTML for a marker for new or updated content.
 *
 * @param $variables
 *   An associative array containing:
 *   - type: Number representing the marker type to display. See MARK_NEW,
 *     MARK_UPDATED, MARK_READ.
 */
function hks_mark($variables) {
  $type = $variables['type'];
  global $user;
  if ($user->uid) {
    if ($type == MARK_NEW) {
      return ' <span class="marker marker-new icon-star" title="' . t('new') . '"></span>';
    }
    elseif ($type == MARK_UPDATED) {
      return ' <span class="marker marker-updated  icon-star-empty" title="' . t('updated') . '"></span>';
    }
  }
}

function hks_username($variables) {
  if($variables['extra']){
    $variables['attributes_array']['title'] = $variables['extra'];
  }

  if (isset($variables['link_path'])) {
    // We have a link path, so we should generate a link using l().
    // Additional classes may be added as array elements like
    // $variables['link_options']['attributes']['class'][] = 'myclass';
    $output = l($variables['name'] . $variables['extra'], $variables['link_path'], $variables['link_options']);
  }
  else {
    // Modules may have added important attributes so they must be included
    // in the output. Additional classes may be added as array elements like
    // $variables['attributes_array']['class'][] = 'myclass';
    $output = '<a href="" ><span' . drupal_attributes($variables['attributes_array']) . '>' . $variables['name'] . '</span><a>';
  }
  return $output;
}

// PAGER
// (customized from Mothership menu.php)

/*
  @hook_pager
we rewrites this so we can get shorter class names
Remove all the pager- prefixes classes we dont need this the pager have the pager class on the ul
pager-first & pager-last removed we use the css :first-child instead

we add a daddy item (whos your daddy) so the wrapper item_list gets an idea who called it
*/

function hks_pager($variables) {

  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

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

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
//            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('current'),
            'data' => '<a>' . $i .'</a>', // CHANGED for HKS pager menu display
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
          //  'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('last'),
        'data' => $li_last,
      );
    }
  //we wrap this in *gasp* so
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager ', 'button-bar') ),
      'daddy' => 'pager'
    ));
  }
}

/*
views pagers
  theme_views_mini_pager
  original: /views/theme/theme.inc
*/

function hks_views_mini_pager($vars) {
  global $pager_page_array, $pager_total;

  $tags = $vars['tags'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];
  $quantity = $vars['quantity'];

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.


  $li_previous = theme('pager_previous',
    array(
      'text' => (isset($tags[1]) ? $tags[1] : t('‹‹')),
      'element' => $element,
      'interval' => 1,
      'parameters' => $parameters,
    )
  );
  if (empty($li_previous)) {
    $li_previous = "&nbsp;";
  }

  $li_next = theme('pager_next',
    array(
      'text' => (isset($tags[3]) ? $tags[3] : t('››')),
      'element' => $element,
      'interval' => 1,
      'parameters' => $parameters,
    )
  );
  if (empty($li_next)) {
    $li_next = "&nbsp;";
  }

  if ($pager_total[$element] > 1) {
    $items[] = array(
      'class' => array('previous'),
      'data' => $li_previous,
    );

    $items[] = array(
      'class' => array('current'),
      'data' => t('@current of @max', array('@current' => $pager_current, '@max' => $pager_max)),
    );

    $items[] = array(
      'class' => array('next'),
      'data' => $li_next,
    );
    return theme('item_list',
      array(
        'items' => $items,
        'title' => NULL,
        'type' => 'ul',
        'attributes' => array('class' => array('pager')),
        'daddy' => 'pager'
      )
    );
  }
}


/*
the non saying item-list class haw now added an -daddy element
so if the theme that calls the itemlist adds an 'daddy' => '-pager' to the theme call
the item list haves an idea of what it is
*/

function hks_item_list($variables) {
  $items = $variables['items'];
  $title = $variables['title'];
  $type  = $variables['type'];
  $attributes = $variables['attributes'];

  //get the daddy if its set and add it is item-list-$daddy
  if(isset($variables['daddy'])){
    $wrapperclass = "item-list-" . $variables['daddy'];
  }else{
    $wrapperclass = "item-list";
  }

  $output = '<div class="'. $wrapperclass .'">';
  if (isset($title)) {
    $output .= '<h3>' . $title . '</h3>';
  }

  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    foreach ($items as $i => $item) {
      $attributes = array();
      $children = array();
      $data = '';
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        // Render nested list.
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      if ($i == 0) {
        //TODO remove first
        $attributes['class'][] = 'first';
      }
      if ($i == $num_items - 1) {
        //TODO remove last
        $attributes['class'][] = 'last';
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}
/*
 * ==== ADMIN THEMING ====
 *
 */

/**
 * Returns HTML for the output of the dashboard page.
 */
function hks_system_admin_index($vars) {
  $menu_items = $vars['menu_items'];

  $stripe = 0;
  $container = array('leftcol column col_6' => '', 'rightcol column col_6' => '');
  $flip = array('leftcol column col_6' => 'rightcol column col_6', 'rightcol column col_6' => 'leftcol column col_6');
  $position = 'leftcol column col_6';

  // Iterate over all modules.
  foreach ($menu_items as $module => $block) {
    list($description, $items) = $block;

    // Output links.
    if (count($items)) {
      $block = array();
      $block['title'] = $module;
      $block['content'] = theme('admin_block_content', array('content' => $items));
      $block['description'] = t($description);
      $block['show'] = TRUE;

      if ($block_output = theme('admin_block', array('block' => $block))) {
        if (!isset($block['position'])) {
          // Perform automatic striping.
          $block['position'] = $position;
          $position = $flip[$position];
        }
        $container[$block['position']] .= $block_output;
      }
    }
  }

  $output = '<div class="admin clearfix">';
  $output .= theme('system_compact_link');
  foreach ($container as $id => $data) {
    $output .= '<div class="' . $id . ' clearfix">';
    $output .= $data;
    $output .= '</div>';
  }
  $output .= '</div>';

  return $output;
}
