<?php

  session_start();
  $error = '';

  // unsets session and clears cookie
  if (array_key_exists('logout', $_GET)) {
      unset($_SESSION['id']);
      setcookie('id', '', time() - 60 * 60);
      $_COOKIE['id'] = '';
  } elseif ((array_key_exists('id', $_SESSION) and $_SESSION['id']) or (array_key_exists('id', $_COOKIE) and $_COOKIE['id'])) {
      header('Location: loggedinpage.php');
  }

  if (array_key_exists('submit', $_POST)) {
      include 'dbconnection.php';
      if (!$_POST['email']) {
          $error .= 'Please enter a valid email';
      }
      if (!$_POST['password']) {
          $error .= 'A Password Is Required';
      }
      if ($error != '') {
          $error;
      } else {
          //if submit is clicked
          if ($_POST['signUp'] == '1') {
              //Finds if email is already taken
              $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
              $result = mysqli_query($link, $query);
              if (mysqli_num_rows($result) > 0) {
                  //if query retuns a number of rows the email is already taken
                  $error .= 'That email address is already registered';
              } else {
                  //inserts given email and password into database
                  $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."','".mysqli_real_escape_string($link, $_POST['password'])."')";

                  if (!mysqli_query($link, $query)) {
                      $error .= 'Error! please try again later';
                  } else {
                      //password encryption using bcrypt
                      $hashedpass = password_hash($_POST['password'], PASSWORD_BCRYPT);
                      $query = "UPDATE `users` SET password = '".$hashedpass."' WHERE id = ".mysqli_insert_id($link).' LIMIT 1';
                      // established session id
                      $_SESSION['id'] = mysqli_insert_id($link);
                      mysqli_query($link, $query);

                      // creates login  cookie
                      if ($_POST['stayLoggedIn'] == 1) {
                          setcookie('id', mysqli_insert_id($link), time() + 60 * 60 * 24 * 365);
                      }
                      header('Location:loggedinpage.php');
                  }
              }
          } else {
              //log in
              $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
              $id = mysqli_query($link, $query);
              if (mysqli_num_rows($id) == 0) {
                  echo 'Email or Password is Incorrect';
              } else {
                  //gets password
                $row = mysqli_fetch_array($id);
                //verifies password
                if (password_verify($_POST['password'], $row['password'])) {
                    $_SESSION['id'] = $row['id'];
                  // creates login cookie
                  if ($_POST['stayLoggedIn'] == 1) {
                      setcookie('id', $row['id'], time() + 60 * 60 * 24 * 365);
                  }
                    header('Location:loggedinpage.php');
                } else {
                    echo 'Email or Password is Incorrect';
                }
              }
          }
      }
  }
?>
  <?php include 'bootstrap1.php'; ?>
  <body>
  <div class='container' id="indexContainer">
    <div id="title">
      <h1>secretPage</h1>
      <p><strong>A Secret and Secure Page for Your Thoughts and Ideas</strong></p>
    </div>

    <form id="signUpbox" method="post">
      <p>Want to get started? Sign up now.</p>
      <div id="error">
      <?php
      if ($error != '') {
          echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
      }
      ?>
      </div>

      <fieldset class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Your Email" />
      </fieldset>
      <fieldset class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Password"/>
      </fieldset>
      <div class="checkbox">
          <label class="checkbox-inline">
            <input type="checkbox" name="stayLoggedIn" value="1" />
            Stay Logged In?
          </label>
      </div>
      <fieldset class="form-group">
        <input type="hidden" name="signUp" value="1" />
      </fieldset>
      <fieldset class="form-group">
        <input type="submit" class="btn btn-primary" name="submit" value="Sign Up!">
      </fieldset>
      <p><a class="toggleBox"> Log In </a></p>
    </form>

    <form id="logInbox" method="post">
      <p><stong>Already a member? Log in using your email and password.</stong></p>
      <div id="error">
      <?php
      if ($error != '') {
          echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
      }
      ?>
      </div>
      <fieldset class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Your Email" />
      </fieldset>
      <fieldset class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Password"/>
      </fieldset>
      <div class="checkbox">
          <label class="checkbox-inline">
            <input type="checkbox" name="stayLoggedIn" value="1" />
            Stay Logged In?
          </label>
      </div>
      <fieldset class="form-group">
        <input type="hidden" name="signUp" value="0" />
      </fieldset>
      <fieldset class="form-group">
        <input type="submit" class="btn btn-primary" name="submit" value="Log In!">
      </fieldset>
      <p><a class="toggleBox"> Sign Up </a></p>
    </form>

  </div>
  <?php include 'bootstrap2.php'; ?>
