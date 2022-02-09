<?php
get_header();
?>


<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

<!-- Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">

<div class="aw-main-wrap bg-yellow">

  <div class="aw-title-heading">
    <h1>Week's Menu</h1>
    <h3>Choose Your Plan</h3>
  </div><!---- ======== aw-title-heading ======---->

  <div class="aw-product-wrap">

    <?php

    while (have_rows('weeks_planner')) {
      the_row();
      $planner = get_sub_field('planner_product');


      ?>


      <div class="product-wrapper">
        <a href="">
          <div class="img-wrap" style="background: url('<?= get_the_post_thumbnail_url($planner)?>');background-size: cover;">
          </div><!-- ===== img-wrap ====-->

          <h3 class="product-title"><?= get_the_title($planner);?></h3>
          <p class="product-desc">
            <?= get_sub_field('description')?></p>
          </a>
        </div><!--- ========== product-wrapper ====== --->


      <?php } ?>


    </div><!--- ========== aw-product-wrap ====== --->

  </div><!------========= aw-main-wrap ======------>


  <?php

  get_footer();
  ?>
