<?php
  //MYSQL DATABASE INFO
  $link = mysqli_connect('localhost', "my_user", "my_password", "my_db");

  if (mysqli_connect_error()) {
      die('Connection Error');
  }

?>
