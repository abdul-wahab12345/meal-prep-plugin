<?php
get_header();

$ingredients = [];
$carbs = [];
while (have_rows('ingredients_admin')) {
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


<div class="single-product-wrap">

	<div class="aw-content-wrap">
		<div class="aw-title-heading" style="text-align: left;padding: 0;">
			<h1><?= get_the_title()?></h1>
			<h4><?= get_field("sub_heading")?></h4>
		</div><!---- ======== aw-title-heading ======---->

		<div class="single-img-wrap">
			<img src="<?= get_the_post_thumbnail_url()?>">
		</div>


		<div class="aw-benifits">

			<?php if($calories['weight'] > 0){	?>
				<div class="b-wrap">
					<p class="aw-sign">CALORIES</p>
					<p class="aw-quantity"><?= $calories['weight']?></p>
					<p class="aw-unit"><?= $calories['unit']?></p>
				</div><!---- ======== b-wrap ======= --->
			<?php } ?>

			<?php if($Carbohydrates['weight'] > 0){	?>
				<div class="b-wrap">
					<p class="aw-sign">Carbohydrates</p>
					<p class="aw-quantity"><?= $Carbohydrates['weight']?></p>
					<p class="aw-unit"><?= $Carbohydrates['unit']?></p>
				</div><!---- ======== b-wrap ======= --->
			<?php } ?>

			<?php if($Fat['weight'] > 0){	?>
				<div class="b-wrap">
					<p class="aw-sign">Fat</p>
					<p class="aw-quantity"><?= $Fat['weight']?></p>
					<p class="aw-unit"><?= $Fat['unit']?></p>
				</div><!---- ======== b-wrap ======= --->
			<?php } ?>

			<?php if($Protein['weight'] > 0){	?>
				<div class="b-wrap">
					<p class="aw-sign">Protein</p>
					<p class="aw-quantity"><?= $Protein['weight']?></p>
					<p class="aw-unit"><?= $Protein['unit']?></p>
				</div><!---- ======== b-wrap ======= --->
			<?php } ?>



		</div><!---- ======== aw-benifit ======= -->


		<div class="aw-ingredient">
			<h2>Ingredients</h2>
			<h3 style="text-transform: capitalize;"><?= implode(", ",$ingredients);?></h3>


		</div>



	</div> <!---- ======== aw-product-wrap ======---->

</div> <!---- ======== single-product-wrap ======---->

<?php

get_footer();

?>
