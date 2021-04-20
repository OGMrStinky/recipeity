<?php

require_once 'core/init.php';

/*if(Session::exists('home')) {
    echo '<p>' . Session::flash('home'). '</p>';
}*/

$user = new User(); //Current

if($user->isLoggedIn()) {
    if (Input::exists('get')) {
        $recipeID = Input::get('recipeid');
        if ($recipeID) {
?>

    <p><a href="index.php">"Back to list"</a></p>
    <p>recipe for <?php echo($recipeID) ?> goes here</p>
    
        
<?php
            //button to add notes that saves timestamp.  displayed at top of recipe steps            
        }
    }

} else {
    echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register.</a></p>';
}