<?php

session_start();
include 'cryptcontent.php';
if (array_key_exists('content', $_POST)) {
    include 'dbconnection.php';

    $crypt = new Crypt();

    $encoded = $crypt->encrypt(mysqli_real_escape_string($link, $_POST['content']));

    echo $encoded;

    $query = "UPDATE `users` SET `page` = '".$encoded."' WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id']).' LIMIT 1';

    mysqli_query($link, $query);
}
