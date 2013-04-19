<?php

// Plugin definition
$plugin = array(
  'title' => t('Col1x2x3x1'),
  'category' => t('Custom'),
  'icon' => 'hks_1231.png',
  'theme' => 'panels_hks_1231',
  //'css' => 'threecol_33_34_33_stacked.css',
  'regions' => array(
    'r1top' => t('Row 1 - Top'),
    'r2left' => t('Row 2 - Left side'),
    'r2right' => t('Row 2 - Right side'),
    'r3left' => t('Row 3 - Left side'),
    'r3middle' => t('Row 3 - Middle column'),
    'r3right' => t('Row 3 - Right side'),
    'r4bottom' => t('Row 4 - Bottom')
  ),
);
