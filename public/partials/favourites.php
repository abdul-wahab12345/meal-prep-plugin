<?php

$user_id = get_current_user_id();
$user = get_userdata($user_id);

$user_fav_post = get_user_meta($user_id,"aw_fav_post",true);

if($user_fav_post){

  ?>

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

  <h1>Favourites Meals</h1>

  <table id="customers">
    <tr>
      <th>#ID</th>
      <th>Name</th>
    </tr>
    <?php


    $favourites = get_post_meta($user_fav_post,'aw_fav_meal');
    foreach ($favourites as $key => $value) {

      ?>
      <tr>
        <td><?= $value;?></td>
        <td><?= get_the_title($value);?></td>
      </tr>
    <?php } ?>
  </table>

  <?php

}

?>
