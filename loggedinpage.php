<?php
  session_start();
  $error = '';
  $pagecontent = '';

  if (array_key_exists('id', $_COOKIE)) {
      //if cookie exists set session id to cookie id
    $_SESSION['id'] = $_COOKIE['id'];
  }

  if (array_key_exists('id', $_SESSION)) {
      //outputs page content to textarea
    include 'dbconnection.php';
      include 'cryptcontent.php';

      $query = "SELECT `page` FROM `users` WHERE id='".mysqli_real_escape_string($link, $_SESSION['id'])."' LIMIT 1 ";

      $row = mysqli_fetch_array(mysqli_query($link, $query));

      $crypt = new Crypt();

      if ($row['page'] != '') {
          $decoded = $crypt->decrypt($row['page']);

      //replaces every '/n' with a new line after decoding
      $pagecontent = str_replace('\n', PHP_EOL, $decoded);
      }
  } else {
      header('Location: index.php');
  }

?>

<?php include 'bootstrap1.php'; ?>
  <body>
      <nav class="navbar navbar-light bg-faded">
      <a class="navbar-brand" href="#">secretPage</a>
      <form class="form-inline pull-xs-right">
        <a href ='index.php?logout=1'><button type="button" class="btn btn-primary">Log Out</button>
</a>
      </form>
      </nav>
    <div class="container-fluid">
      <!--page content-->
      <textarea id="texta" class="form-control"><?php echo $pagecontent; ?></textarea>
    </div>

<?php include 'bootstrap2.php'; ?>
