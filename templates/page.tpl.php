<div class='header-region clearfix'>
<div id='branding'>
  <h1 class='site-name'>
    <a class="logo" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" >
      <?php if ($logo): ?>
        <img src="<?php print $logo ?>" alt="<?php print $site_name ?>" />
      <?php else: ?>
        <?php print $site_name ?>
      <?php endif; ?>
    </a>
  </h1>
  <?php if ($site_slogan): ?>
    <i class="slogan">
      <?php print $site_slogan ?>
    </i>
  <?php endif; ?>
</div>
    <?php print render($page['header']) ?>
</div>

<div class="grid flex">

<?php if ($page['help'] || ($show_messages && $messages)): ?>
  <div class='help-region clearfix'>
    <?php print render($page['help']); ?>
    <?php if ($show_messages && $messages): print $messages; endif; ?>
  </div>
<?php endif; ?>

<div class='navigation clearfix'>
  <?php if (isset($main_menu)) : ?>
    <?php print theme('links', array('links' => $main_menu, 'attributes' => array('class' => 'menu main-menu'))) ?>
  <?php endif; ?>
  <?php if (isset($secondary_menu)) : ?>
    <?php print theme('links', array('links' => $secondary_menu, 'attributes' => array('class' => 'button-bar col_12 column right secondary-menu'))) ?>
  <?php endif; ?>
</div>

<?php if ($page['highlighted']): ?>
  <div class='highlighted-region limiter clearfix'>
    <?php print render($page['highlighted']); ?>
  </div>
<?php endif; ?>

<div id='page' class='page clearfix'>

  <?php if ($page['sidebar_first']): ?>
    <div id="sidebar_first" class="column clearfix sidebar-first-region <?php print $sidebar_first_class ?>">
      <?php print render($page['sidebar_first']) ?>
    </div>
  <?php endif; ?>

  <div id='main-content' class='column clearfix main-content-region <?php print $main_content_class ?>'>
    <?php if ($breadcrumb) print $breadcrumb; ?>
    <?php print render($title_prefix); ?>
    <?php if ($title): ?><h1 class='page-title'><?php print $title ?></h1><?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php if ($primary_local_tasks): ?><ul class='button-bar clearfix'><?php print render($primary_local_tasks) ?></ul><?php endif; ?>
    <?php if ($secondary_local_tasks): ?><ul class='button-bar clearfix'><?php print render($secondary_local_tasks) ?></ul><?php endif; ?>
    <?php if ($action_links): ?><ul class='links clearfix'><?php print render($action_links); ?></ul><?php endif; ?>
    <div id='content' class='content clearfix'><?php print render($page['content']) ?></div>
  </div>

  <?php if ($page['sidebar_second']): ?>
    <div id="sidebar_second" class='column clearfix sidebar-second-region <?php print $sidebar_second_class ?>'>
      <?php print render($page['sidebar_second']) ?></div>
  <?php endif; ?>

</div><!-- /.page -->

</div><!-- /.grid -->

<div class="footer clearfix region-footer">
  <?php print $feed_icons ?>
  <?php print render($page['footer']) ?>
</div>
