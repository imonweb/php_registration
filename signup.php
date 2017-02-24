<?php
session_start();
require_once 'class.user.php';

$reg_user = new USER();

if($reg_user->is_logged_in()!="")
{
	$reg_user->redirect('home.php');
} // is_logged_in

if(isset($_POST['btn-signup']))
{
	$uname = trim($_POST['txtuname']);
	$email = trim($_POST['txtemail']);
	$upass = trim($_POST['txtpass']);
	$code  = md5(uniqid(rand()));

	$stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
	$stmt->execute(array(":email_id"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($stmt->rowCount() > 0)
	{
		$msg = "
				<div class='alert alert-error'>
				<button class='close' data-dismiss='alert'>&times;</button>
				<strong>Sorry!</strong> email already exists, Please try another one</div>
			";
	} else 
	{
		if($reg_user->register($uname,$email,$upass,$code))
		{
			$id = $reg_user->lastID();
			$key = base64_encode($id);
			$id = $key;

			$message = "
				Hello $uname,
				<br><br />
				Welcome to iMON Web!<br />
				To complete your registration please, just click following link<br /> <br /> <br />
				<a href='http://http://localhost/php/PDO/codingcage.com/login-registration-email-verification-forgot-password/verify.php?id=$id&code=$code'>Click here to Activate :)</a>
				<br /> <br />
				Thanks,";
			 $subject = "Confirm registration";

			 $reg_user->send_mail($email,$message,$subject);
			 $msg = "
				<div class='alert alert-success'>
				<button class='close' data-dismiss='alert'>&times;</button>
				<strong>Success!</strong> We've sent an email to $email.
				Please click on the confirmation link in the email to create your account.
				</div>
			 ";
		} else 
		{
			echo "Sorry, Query could not execute...";
		}

	 
	} // rowCount
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Signup | iMONWeb</title>
    <!-- Bootstrap -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
    <link href="assets/styles.css" rel="stylesheet" media="screen">
     <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script> -->
  </head>
  <body id="login">
    <div class="container">
    <?php if(isset($msg)) echo $msg;  ?>
      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Sign Up</h2><hr />
        <input type="text" class="input-block-level" placeholder="Username" name="txtuname" required />
        <input type="email" class="input-block-level" placeholder="Email address" name="txtemail" required />
        <input type="password" class="input-block-level" placeholder="Password" name="txtpass" required />
      <hr />
        <button class="btn btn-large btn-primary" type="submit" name="btn-signup">Sign Up</button>
        <a href="index.php" style="float:right;" class="btn btn-large">Sign In</a>
      </form>

    </div> <!-- /container -->
   <!-- Latest compiled and minified JavaScript -->
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>