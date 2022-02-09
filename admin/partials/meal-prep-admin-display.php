<?php

/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       https://abdulwahab.live/
* @since      1.0.0
*
* @package    Meal_Prep
* @subpackage Meal_Prep/admin/partials
*/
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>




<h1>Dislike Meals</h1>

<table id="customers">
  <tr>
    <th>#ID</th>
    <th>User</th>
    <th>Name</th>
    <th>Primary</th>
    <th>Sms</th>
  </tr>
  <?php

  $query = new WP_Query(['post_type' => 'user_favourite','posts_per_page' => -1]);

  while($query->have_posts()){
    $query->the_post();

    global $post;
    $favourites = get_post_meta(get_the_ID(),'aw_dislike_meal');
    foreach ($favourites as $key => $value) {

      ?>
      <tr>
        <td><?= $value['meal'];?></td>
        <td>

          <?php

          $user = get_userdata($post->post_author);
          echo "#$post->post_author - $user->display_name";

          ?>

        </td>
        <td><?= get_the_title($value['meal']);?></td>
        <td>

          <?php

          if(isset($value['primary'])){

            foreach ($value['primary'] as $value1) {
              $term = get_term($value1);
              echo $term->name ."<br>";
            }
          }

          ?>


        </td>
        <td><?= $value['sms'];?></td>
      </tr>
    <?php } }?>
  </table>
