<?php

require_once 'core/init.php';

if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
		$formtype = Input::get("formtype");
		if($formtype == "register"){
			$validate->check($_POST, array(
				'username' => array(
					'name' => 'Username',
					'required' => true,
					'min' => 2,
					'max' => 20,
					'unique' => 'users',
					'email' => true
				),
				'password' => array(
					'name' => 'Password',
					'required' => true,
					'min' => 6
				),
				'password_again' => array(
					'required' => true,
					'matches' => 'password'
				),
			));
		} else{
			$validate->check($_POST, array(
				'username' => array('required' => true,'email' => true),
				'password' => array('required' => true)
			));
		}
        if ($validate->passed()) {
            $user = new User();
            //$salt = Hash::salt(32);
            $salt = "";
			if($formtype == "register"){
				try {
/*$user->create(array(
						'name' => Input::get('name'),
						'username' => Input::get('username'),
						'password' => Hash::make(Input::get('password'), $salt),
						'salt' => $salt,
						'joined' => date('Y-m-d H:i:s'),
						'group' => 1
					));

					Session::flash('home', 'Registration Successful! Your account has been registered. You may now log in.');
					Redirect::to('index.php');*/
					Session::flash('home', 'Registration is currently suspended');
					Redirect::to('index.php');

				} catch(Exception $e) {
					echo $e->getTraceAsString(), '<br>';
				}
			} else{
				$user = new User();
				$remember = (Input::get('remember') === 'on') ? true : false;
				$login = $user->login(Input::get('username'), Input::get('password'), $remember);
	
				if($login) {
					Redirect::to('index.php');
				} else {
					echo '<p>Incorrect username or password</p>';
				}
			}
        } else {
            foreach ($validate->errors() as $error) {
                echo $error . "<br>";
            }
        }
    }
}
$token = Token::generate();
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Recipeity</title>
		<link rel="stylesheet" href="core/style.css">
	</head>
	<body>
		<div id="bg-overlay"></div>
		<div id="overlay"></div>
		<div class="login-wrap">
	<div class="login-html">
		<div class="login-header">
				<a href="#"><img src="core/images/Recipeity-03.png"></a>
			</div>
			<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab" style="margin-left:26%;">Sign In</label>
			<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Register</label>
		<div class="login-form">
			<form action="" method="post">
				<div class="sign-in-htm">
					<div class="group">
						<label for="user" class="label">Email Address</label>
						<input id="user" name="username" type="text" class="input">
					</div>
					<div class="group">
						<label for="pass" class="label">Password</label>
						<input id="pass" name="password" type="password" class="input" data-type="password">
					</div>
					<div class="group">
						<input id="remember" name="remember" type="checkbox" class="check" checked>
						<label for="remember"><span class="icon"></span> Keep me Signed in</label>
					</div>
					<input type="hidden" name="token" value="<?php echo  $token; ?>">
					<input type="hidden" name="formtype" value="signin">
					<div class="group">
						<input type="submit" class="button" value="Sign In">
					</div>
				</div>
			</form>
			<form action="" method="post">
				<div class="sign-up-htm">
					<div class="group">
						<label for="user" class="label">Email Address</label>
						<input id="user" name="username" type="text" class="input">
					</div>
					<div class="group">
						<label for="" class="label">Password</label>
						<input id="pass" name="password" type="password" class="input" data-type="password">
					</div>
					<div class="group">
						<label for="pass" class="label">Repeat Password</label>
						<input id="pass" name="password_again" type="password" class="input" data-type="password">
					</div>
					<input type="hidden" name="token" value="<?php echo $token; ?>">
					<input type="hidden" name="formtype" value="register">
					<div class="group">
						<input type="submit" class="button" value="Sign Up">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
	</body>
</html>