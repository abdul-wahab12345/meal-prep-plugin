<?php


$id = $_GET['id'];
$user_id = get_current_user_id();

if(isset($_POST['aw_feedback'])){
  $sms = $_POST['aw_sms'];

  $user = get_userdata($user_id);

  $user_fav_post = get_user_meta($user_id,"aw_fav_post",true);

  if($user_fav_post){
    add_post_meta($user_fav_post,'aw_dislike_meal',['meal' => $id,'sms' => $sms,'primary' => $_POST['primary']]);
  }else{

    $post = wp_insert_post(['post_type' => "user_favourite",'post_title' => "#$user_id - $user->display_name",'post_status' => "publish"]);
    update_user_meta($user_id,"aw_fav_post",$post);
    add_post_meta($post,'aw_dislike_meal',['meal' => $id,'sms' => $sms,'primary' => $_POST['primary']]);

  }
}

$user_fav_post = get_user_meta($user_id,"aw_fav_post",true);
$user_fav = [];
if($user_fav_post){
  $user_fav = get_post_meta($user_fav_post,'aw_fav_meal');
  $temp_fav = [];
  if($user_fav){
    foreach ($user_fav as $key => $value) {
      $temp_fav[] = $value;
    }

    $user_fav = $temp_fav;
  }
}



$ingredients = [];
$carbs = [];
while (have_rows('ingredients_admin', $id)) {
  the_row();
  $ing = explode(":",get_sub_field('name'));
  $ingredients[] = $ing[0];

  $unit_weights = get_sub_field("unit_weights");
  if(!empty($unit_weights)){
    $unit_weights = json_decode($unit_weights,true);
    foreach ($unit_weights['nutrition']['nutrients'] as $key => $value) {
      $carbs[$value['name']][] = $value;
    }
  }

}

$calories = ['weight' => 0,'unit' => ''];
$Carbohydrates = ['weight' => 0,'unit' => ''];
$Fat = ['weight' => 0,'unit' => ''];
$Protein = ['weight' => 0,'unit' => ''];

foreach ($carbs as $key => $value1) {
  foreach ($value1 as $key1 => $value) {

    switch($key){
      case 'Calories':
      $calories['weight'] = $calories['weight'] + $value['amount'];
      $calories['unit'] = $value['unit'];
      break;
      case 'Carbohydrates':
      $Carbohydrates['weight'] = $Carbohydrates['weight'] + $value['amount'];
      $Carbohydrates['unit'] = $value['unit'];

      break;
      case 'Fat':
      $Fat['weight'] = $Fat['weight'] + $value['amount'];
      $Fat['unit'] = $value['unit'];

      break;
      case 'Protein':
      $Protein['weight'] = $Protein['weight'] + $value['amount'];
      $Protein['unit'] = $value['unit'];

      break;
    }
  }

}


?>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

<!-- Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">


<section id="mobile-meals">

  <div class="mb-head">

    <div class="head-titles">
      <h2 class="mb-meal-title"><?= get_the_title($id);?></h2>
      <p class="mb-sub-title"><?= get_field("sub_heading",$id);?></p>
    </div><!--- =============  head-titles =========== -->

    <div class="head-icons">
      <img src="<?= plugin_dir_url(__FILE__)?>images/vector.svg"  class="dislike" alt="">

      <!-- <img src="<?= plugin_dir_url(__FILE__)?>images/heart.svg" alt=""> -->


      <?php

      if(in_array($id,$user_fav)){

        ?>
        <img style="height:20px;display:none;" class="aw-add-favourite"  data-id="<?= $id?>" src="<?= plugin_dir_url(__FILE__)?>images/heart.svg" alt="">

        <img style="height:20px;" class="aw-remove-favourite" data-id="<?= $id?>" src="<?= plugin_dir_url(__FILE__)?>images/heart-fill.svg" alt="">


        <?php

      }else{

        ?>
        <img style="height:20px;" class="aw-add-favourite" data-id="<?= $id?>" src="<?= plugin_dir_url(__FILE__)?>images/heart.svg" alt="">

        <img style="height:20px;display:none;" class="aw-remove-favourite"  data-id="<?= $id?>" src="<?= plugin_dir_url(__FILE__)?>images/heart-fill.svg" alt="">
      <?php } ?>


    </div><!--- ============= head-icons =========== -->

  </div><!--- =============  mb-meal-image =========== -->

  <div class="mb-menu-wrap bg-dark-gray">

    <div class="mb-single-image">
      <img src="<?= get_the_post_thumbnail_url($id)?>" alt="">
    </div><!--- =============  mb-meal-image =========== -->

    <div class="mb-mineral-wrap mb-single-mineral">

      <?php if($calories['weight'] > 0){	?>
        <div class="mb-m-wrap">
          <p class="m-quantity clr-green"><?= $calories['weight']?></p>
          <p class="m-name clr-green">Kcal</p>
        </div><!--- =============  mb-m-wrap =========== -->
      <?php } ?>
      <?php if($Carbohydrates['weight'] > 0){	?>
        <div class="mb-m-wrap">
          <p class="m-quantity"><?= $Carbohydrates['weight']?> <?= $Carbohydrates['unit']?></p>
          <p class="m-name">Carb</p>
        </div><!--- =============  mb-m-wrap =========== -->
      <?php } ?>
      <?php if($Protein['weight'] > 0){	?>
        <div class="mb-m-wrap">
          <p class="m-quantity"><?= $Protein['weight']?> <?= $Protein['unit']?></p>
          <p class="m-name">Protien</p>
        </div><!--- =============  mb-m-wrap =========== -->
      <?php } ?>
      <?php if($Fat['weight'] > 0){	?>
        <div class="mb-m-wrap">
          <p class="m-quantity"><?= $Fat['weight']?> <?= $Fat['unit']?></p>
          <p class="m-name">Fat</p>
        </div><!--- =============  mb-m-wrap =========== -->
      <?php } ?>

    </div><!--- =============  mb-mineral-wrap =========== -->




  </div><!--- =============  mb-meal-wrap =========== -->

  <?php


  $terms = get_the_terms( $id, 'badges' );

  if(count($terms)){

    ?>

    <div class="mb-benifits-wrap">

      <?php foreach ($terms as $key => $value) {
        ?>
        <p class="mb-b-wrap"><?= $value->name;?></p>

        <?php
      }
      ?>

    </div><!--- =============  mobile-meal =========== -->

  <?php } ?>

  <div class="mb-ingredients">
    <h2 class="mb-meal-title">Ingredients</h2>
    <p class="ingred-desc">
      <?= get_field("ingredients",$id);?>	</p>
    </div><!--- =============  mb-ingredients =========== -->

    <?php
    while(have_rows("expert_advice",$id)){
      the_row();

      ?>

      <div class="user-feedback">
        <div class="user-profile">
          <img src="<?= get_sub_field("expert_image")?>">
          <div class="user-bio-mb">
            <p class="user-name"><?= get_sub_field("expert_name");?></p>
          </div>
        </div><!--- =============  user-profile =========== -->

        <p class="ingred-desc">
          <?= get_sub_field("expert_comment");?>		</p>

        </div><!--- =============  user-feedback =========== -->

        <?php
      }
      ?>


      <div class="ingredient-img">
        <img src="<?= get_field("nutrition_image",$id);?>" style="width: 100%;">
      </div>



    </section><!--- =============  mobile-meal =========== -->




    <div class="aw-feedback-popup" style="z-index: 9999;    overflow: scroll;">
      <i class="fa fa-times remove-pop"></i>
      <form action="" method="post" class="aw-form">
        <div class="pop-wrap bg-dark-gray">
          <div class="aw-pop-img align-center">
            <img src="<?= get_the_post_thumbnail_url($id)?>" alt="">
          </div>
          <label for="">Select primary you don't like</label>
          <div>


            <?php
            while (have_rows('ingredients_admin', $id)) {
              the_row();
              $primary = get_sub_field('primary');
              $term = get_term($primary);
              ?>

              <label class="aw-label"><input type="checkbox" name="primary[]" value="<?=$primary?>" > <?=$term->name?></label><br>

              <?php
            }

            ?>
          </div>

          <label for="">What don’t you like about this meal?</label>
          <textarea name="aw_sms" id="" cols="30" rows="10"></textarea>

        </div><!-- ============  pop-wrap ========= ----->

        <input type="Submit" value="Submit Feedback" class="btn-feedback" name="aw_feedback">

      </form>
    </div><!-- ============  aw-feedback-popup ========= ----->


    <style>

    .aw-label{    color: #e0e0e0;
      font-size: 14px;
      line-height: 30px;}

      </style>

      <script>
      var $ = jQuery;
      $(document).ready(function(){


        $(".dislike").click(function(){

          $(".aw-feedback-popup").show();


          setTimeout(function(){  $(".aw-form").css({"transform":"translateY(0)"}); }, 300);

        });


        $(".remove-pop").click(function(){

          $(".aw-form").css({"transform":"translateY(200%)"});

          setTimeout(function(){  $(".aw-feedback-popup").hide(); }, 300);

        });



        $(".aw-add-favourite").click(function(){
          var button = $(this);

          var data = {
            action : "add_to_favourites",
            id : $(this).data('id')
          }

          $.post("<?= admin_url('admin-ajax.php')?>",data,function(){
            button.hide();
            button.parent().find('.aw-remove-favourite').show();
          });
        });

        $(".aw-remove-favourite").click(function(){
          var button = $(this);

          var data = {
            action : "remove_from_favourites",
            id : $(this).data('id')
          }

          $.post("<?= admin_url('admin-ajax.php')?>",data,function(){
            button.hide();
            button.parent().find('.aw-add-favourite').show();
          });
        });

      });

      </script>
