<?php

if(isset($_GET['aw_week'])){
  $current_week = $_GET['aw_week'];
}else{
  return;
}

$product = $_GET['aw_product'];

?>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

<!-- Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">

<section class="aw-products">
  <div class="aw-main-wrap">

    <div class="aw-title-heading">
      <h1><?= get_the_title($product);?></h1>
      <h4>Click on the meal you would like to see the details such as ingredients, macros, allergens, etc.</h4>
      <br>
      <a href="<?= get_permalink($product);?>" class="aw-btn-order">Order <?= get_the_title($product);?></a>
    </div><!---- ======== aw-title-heading ======---->

    <div class="aw-product-wrap">


      <?php

      while (have_rows('weeks_planner',$current_week)) {
        the_row();
        $planner = get_sub_field('planner_product');
        if ($planner != $product) {
          continue;
        }

        $meals = get_sub_field("meals");
        foreach ($meals as $key => $value) {
          // code...


          ?>


          <div class="product-wrapper">
            <a href="<?= get_permalink($value);?>">
              <div class="img-wrap" style="box-shadow: none">
                <img src="<?= get_the_post_thumbnail_url($value)?>" alt="">
              </div><!-- ===== img-wrap ====-->

              <h4 class="product-title" style="font-size: 18px;"><?= get_the_title($value)?></h4>
            </a>
          </div><!--- ========== product-wrapper ====== --->

        <?php } } ?>

      </div><!--- ========== aw-product-wrap ====== --->





      <center><a href="<?= get_permalink($product);?>" class="aw-btn-order">Order <?= get_the_title($product);?></a></center>

    </div><!------========= aw-main-wrap ======------>
  </section>
