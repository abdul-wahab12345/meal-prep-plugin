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

  global $post;
  $favourites = get_post_meta($post->ID,'aw_fav_meal');
  foreach ($favourites as $key => $value) {

    ?>
    <tr>
      <td><?= $value;?></td>
      <td><?= get_the_title($value);?></td>
    </tr>
  <?php } ?>
</table>



<h1>Dislike Meals</h1>

<table id="customers">
  <tr>
    <th>#ID</th>
    <th>Name</th>
    <th>Sms</th>
  </tr>
  <?php

  global $post;
  $favourites = get_post_meta($post->ID,'aw_dislike_meal');
  foreach ($favourites as $key => $value) {

    ?>
    <tr>
      <td><?= $value['meal'];?></td>
      <td><?= get_the_title($value['meal']);?></td>
      <td><?= $value['sms'];?></td>
    </tr>
  <?php } ?>
</table>
