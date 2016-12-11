<?php include("includes/header.php") ?>

<?php 
$user = new User();

if(isset($_POST['submit'])) {


if($user) {
$user->username = $_POST['username'];
$user->first_name =$_POST['first_name'];
$user->last_name =$_POST['last_name'];
$user->password =$_POST['password'];

$user_create = User::create_user($first_name, $last_name, $username, $password);
$user->save();
$user_create = User::verify_user($username, $password);

if ( $user_create ) {
		$session->login($user_create);
		redirect("index.php");
	} else {
		$message = "Your username or Password is incorrect.";
	}
} 
}else {
	$message  = null;
	$username = null;
	$password = null;
}


?>

	<div class="row">
	<div class="col-md-6 col-md-offset-2">
		<div class="panel panel-login">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-6">
						<a href="login.php">Login</a>
					</div>
					<div class="col-xs-6">
						<a href="register.php" class="active" id="">Register</a>
					</div>
				</div>
				<hr>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form id="register-form" action="" method="post">
							<div class="form-group">
								<input type="text" name="first_name" id="first_name" tabindex="1" class="form-control" placeholder="First Name" <?php echo isset($first_name) ? $first_name : NULL; ?> required>
							</div>
							
							<div class="form-group">
								<input type="text" name="last_name" id="last_name" tabindex="1" class="form-control" placeholder="Last Name" <?php echo isset($last_name) ? $last_name : NULL; ?> required>
							</div>
							
							<div class="form-group">
								<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" <?php echo isset($username) ? $username : NULL; ?> required>
							</div>
							<div class="form-group">
								<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" <?php echo isset($password) ? $password : NULL; ?> required>
							</div>
							<div class="form-group">
								<input type="password" name="confirm_password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" <?php echo isset($password) ? $password : NULL; ?> required>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6 col-sm-offset-3">
										<input type="submit" name="submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include("includes/footer.php") ?>