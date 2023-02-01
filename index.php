<?php
    include_once 'header.php';

    // If user is logged in, redirects to the user's homepage
    if (isset($_SESSION["user_id"])) {
      header("location: user-homepage.php");
      exit();
    }
?>

<div class="homepage-title">
  <h1 class="my-3 display-1">Bus Timetabling</h1>
</div>
<!-- Error handling dialog -->
<?php
if (isset($_GET["error"])) {
  if ($_GET["error"] == "emptyinput") {
    echo '<div class="alert alert-danger" role="alert">One or more of the input fields is empty.</div>';
  }
  else if ($_GET["error"] == "invalidemail") {
    echo '<div class="alert alert-danger" role="alert">Please enter a correct email.</div>';
  }
  else if ($_GET["error"] == "pwdmatch") {
    echo '<div class="alert alert-danger" role="alert">Make sure both passwords match.</div>';
  }
  else if ($_GET["error"] == "emailexists") {
    echo '<div class="alert alert-danger" role="alert">This email already exists - please choose a different one.</div>';
  }
  else if ($_GET["error"] == "stmtfailed") {
    echo '<div class="alert alert-danger" role="alert">Please try again in a few minutes.</div>';
  }
  else if ($_GET["error"] == "wronglogin") {
    echo '<div class="alert alert-danger" role="alert">Your email and password are incorrect.</div>';
  }
  else if ($_GET["error"] == "success") {
    echo '<div class="alert alert-success" role="alert">You are logged in!</div>';
  }
}
?>
<section id="login-section" style="display:block">
  <div id="login-box">
    <h2>Login</h2>
    <form action="includes/login.inc.php" method="post">
      <!-- Email for login -->
      <div class="mb-3">
        <label for="loginInputEmail" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control" id="loginInputEmail" aria-describedby="emailHelp">
      </div>
      <!-- Password for login -->
      <div class="mb-3">
        <label for="loginInputPassword" class="form-label">Password</label>
        <input type="password" name="pwd" class="form-control" id="loginInputPassword">
      </div>
      <!-- Login button -->
      <button type="submit" name="submit" class="btn btn-primary btn-lft">Login</button>
    </form>
  </div>

  <button class="btn btn-secondary" onclick="showDiv('register-section');hideDiv('login-section')">Not got an account?</button>

</section>

<section id="register-section" style="display:none">
  <div id="register-box">
    <h2>Register</h2>
    <form action="includes/register.inc.php" method="post">
      <!-- First name for register -->
      <div class="mb-3">
        <label for="registerInputName" class="form-label">Name</label>
        <input type="name" name="name" class="form-control" id="registerInputName">
      </div>
      <!-- Email for register -->
      <div class="mb-3">
        <label for="registerInputEmail" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control" id="registerInputEmail" aria-describedby="emailHelp">
      </div>
      <!-- Password for register -->
      <div class="mb-3">
        <label for="registerInputPassword" class="form-label">Password</label>
        <input type="password" name="pwd" class="form-control" id="registerInputPassword">
      </div>
      <!-- Password repeat for register -->
      <div class="mb-3">
        <label for="registerInputPasswordRepeat" class="form-label">Repeat your password</label>
        <input type="password" name="pwdRepeat" class="form-control" id="registerInputPasswordRepeat">
      </div>
      <!-- Register button -->
      <button type="submit" name="submit" class="btn btn-primary btn-lft">Register</button>
    </form>
  </div>
  
  <button class="btn btn-secondary" onclick="showDiv('login-section');hideDiv('register-section')">Already got an account?</button>

</section>
<?php
    include_once 'footer.php'
?>