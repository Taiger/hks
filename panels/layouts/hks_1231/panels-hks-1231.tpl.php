<?php
/**
 * @file
 * Template for a 3 column panel layout.
 *
 * This template provides a three column 25%-50%-25% panel display layout, with
 * additional areas for the top and the bottom.
 *
 * Variables:
 * - $id: An optional CSS id to use for the layout.
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   'regions' => array(
 *  r1top        Row 1 - Top
 *  r2left        Row 2 - Left side
 *  r2right        Row 2 - Right side
 *  r3left        Row 3 - Left side
 *  r3middle        Row 3 - Middle column
 *  r3right        Row 3 - Right side
 *  r4bottom        Row 4 - Bottom
 *   
 */
?>

<?php if($content['r1top']): ?>
  <div class="r1top">
    <?php print $content['r1top']; ?>
  </div>
<?php endif; ?>

  <div class="column col_6 r2left">
    <?php print $content['r2left']; ?>
  </div>
  <div class="column col_6 r2right">
    <?php print $content['r2right']; ?>
  </div>
  
  <div class="column col_4 r3left">
    <?php print $content['r3left']; ?>
  </div>
  <div class="column col_4 r3middle">
    <?php print $content['r3middle']; ?>
  </div>
  <div class="column col_4 r3right">
    <?php print $content['r3right']; ?>
  </div>
  
  <?php if($content['r4bottom']): ?>
    <div class="r1top">
      <?php print $content['r4bottom']; ?>
    </div>
  <?php endif; ?>

