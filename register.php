<?php
/**
 * Created by Chris on 9/29/2014 3:53 PM.
 */

require_once 'core/init.php';

if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validate->check($_POST, array(
            'name' => array(
                'name' => 'Name',
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'username' => array(
                'name' => 'Username',
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
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

        if ($validate->passed()) {
            $user = new User();
            //$salt = Hash::salt(32);
            $salt = "";

            try {
                $user->create(array(
                    'name' => Input::get('name'),
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'joined' => date('Y-m-d H:i:s'),
                    'group' => 1
                ));

                Session::flash('home', 'Welcome ' . Input::get('name') . '! Your account has been registered. You may now log in.');
                Redirect::to('index.php');
            } catch(Exception $e) {
                echo $e->getTraceAsString(), '<br>';
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo $error . "<br>";
            }
        }
    }
}
?>



<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="core/bootstrap/bootstrap.min.css" rel="stylesheet">
    <title>Recipeity</title>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-around p-4">
          <div class="col-md-4 p-4 border text-light bg-dark">
            <h1 class="text-center p-4">Recipeity</h1>
      <form action="" method="post">
        <div class="form-outline mb-4">
            <input type="text" name="name" class="form-control" value="<?php echo escape(Input::get('name')); ?>" id="name">
            <label class="form-label" for="name">Name</label>
        </div>
        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" name="username" id="username" class="form-control" value="<?php echo escape(Input::get('username')); ?>"/>
          <label class="form-label" for="username">Email address</label>
        </div>
      
        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" name="password" id="password" class="form-control" />
          <label class="form-label" for="password">Password</label>
        </div>

        <div class="form-outline mb-4">
          <input type="password" name="password_again" id="password_again" class="form-control" />
          <label class="form-label" for="password_again">Password Again</label>
        </div>
      
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Register</button>
        <a href="login.php" id="cancel" name="cancel" class="btn btn-danger btn-block mb-4">Cancel</a>
      

      </form>
        </div>
      </div>
    </div>
    <script src="core/bootstrap/bootstrap.bundle.min.js"></script>
  </body>
</html>