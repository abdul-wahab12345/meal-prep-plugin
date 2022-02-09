<?php
get_header();

if(isset($_GET['aw-all-details'])){

  include AW_MEAL_PATH . "public/partials/weeks-meals.php";

  get_footer();
  return;
}

$current_time = current_time('timestamp');


$current_week = get_field("aw_current_week","option");


$aw_current_week_date = get_option("aw_current_week_date");
$week_index = get_option("aw_week_index");
if(!$aw_current_week_date){
  $counter = 1;

  while (have_rows("aw_weeks","option")) {
    the_row();
    $week = get_sub_field("week");
    if($current_week == $week){
      $week_index = $counter;
    }
    $counter++;

  }
  update_option('aw_week_index',$week_index);

  $sunday = new DateTime(date("Y-m-d",$current_time));
  $sunday->modify("Next Sunday");
  $sunday->setTime(23,59,59);

  update_option("aw_current_week_date",$sunday->format("Y-m-d"));
}else{
  $aw_current_week_number = get_option("aw_current_week_date");

  if(date("Y-m-d",$current_time) > $aw_current_week_number){

    $sunday = new DateTime(date("Y-m-d",$current_time));
    $sunday->modify("Next Sunday");
    $sunday->setTime(23,59,59);

    update_option("aw_current_week_date",$sunday->format("Y-m-d"));

    $counter = 1;
    $week_index = $week_index + 1;

    while (have_rows("aw_weeks","option")) {
      the_row();
      $week = get_sub_field("week");
      if($counter == ($week_index)){
        update_field("aw_current_week",$week,"option");
      }
      $counter++;

    }

    update_option('aw_week_index',$week_index);

  }

}

$all_weeks = get_field("aw_weeks","option");

if(count($all_weeks) > 0 && count($all_weeks) < $week_index){
  $week_index = 1;
  update_option('aw_week_index',$week_index);

  $counter = 1;

  while (have_rows("aw_weeks","option")) {
    the_row();
    $week = get_sub_field("week");
    if($counter == 1){
      update_field("aw_current_week",$week,"option");
    }
    $counter++;

  }

}

$current_week = get_field("aw_current_week","option");

if($current_week):


  ?>


  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">

  <div class="aw-main-wrap bg-yellow">

    <div class="aw-title-heading">
      <h1>Week's Menu</h1>
      <h3>Choose Your Plan <?=  get_the_title($current_week);?></h3>
    </div><!---- ======== aw-title-heading ======---->

    <div class="aw-product-wrap">

      <?php


      while (have_rows('weeks_planner',$current_week)) {
        the_row();
        $planner = get_sub_field('planner_product');


        ?>


        <div class="product-wrapper">
          <a href="<?= get_the_permalink();?>?aw-all-details=true&aw_product=<?= $planner;?>&aw_week=<?= $current_week;?>">
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

  endif;

  get_footer();
  ?>
