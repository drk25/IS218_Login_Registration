<?php require_once("includes/header.php"); ?>


<?php

if ( $session->is_signed_in() ) {
    redirect("index.php");
    
}

if ( isset($_POST['submit']) ) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    //method to check database user
    $user_found = User::verify_user($username, $password);
    
    if ( $user_found ) {
        $session->login($user_found);
        redirect("index.php");
    } else {
        $message = "Your username or Password is incorrect.";
    }
} else {
    $message  = null;
    $username = null;
    $password = null;
}

?>



<div class="row">
        <div class="col-md-6 col-md-offset-2">
        
            <?php if ( isset($message) )echo $message; ?></h4>

            <div class="panel panel-login">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="login.php" class="active" id="login-form-link">Login</a>
                        </div>
                        <div class="col-xs-6">
                            <a href="register.php" id="">Register</a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="login-form" action="" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo isset($username) ? $username : NULL; ?>"required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo isset($password) ? $password : NULL; ?>"required>
                                </div>
                                <div class="form-group text-center">
                                    <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                                    <label> Remember Me</label>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="submit"  class="form-control btn btn-login" value="Log In">
                                        </div>
                                    </div>
                                </div>
                               
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
 <?php include("includes/footer.php") ?>