<?php
//https://onetsp.com/account/signin
//https://github.com/onetsp/RecipeParser
//https://developers.google.com/search/docs/data-types/recipe
//https://codelabs.developers.google.com/codelabs/structured-data/index.html#0
//cat /var/log/apache2/error.log
require_once 'core/init.php';

if(Session::exists('home')) {
    echo '<p>' . Session::flash('home'). '</p>';
}

$user = new User(); //Current

if(!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

$DB = DB::getInstance();
$sql = "SELECT * FROM units WHERE userid=?";
$unit_list = array();

$DB->query($sql, array(escape($user->data()->id)));

if($DB->error()){
    print_r($DB->errorinfo());
    die;
} else{
    $unit_list = $DB->results();
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Recipeity</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="addrecipe.php">Add Recipe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="viewmenu.php">View Menu</a>
                    </li>
                    
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Hello, <?php echo escape($user->data()->name); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="update.php">Update</a></li>
                            <li><a class="dropdown-item" href="changepassword.php">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <ul>
            <?php 
                
                foreach($unit_list as $unit){
                    echo("<li><a href='editunit.php?unitid={$unit->UnitID}'>{$unit->UnitName}<a/></li>");
                }
            ?>
        </ul>
        <div  class="row p-2">
            <a href="editunit.php" id="addunit" name="AddUnit" class="btn btn-primary">Add Unit</a>
        </div>
    </div>

</body>
<script src="core/bootstrap/bootstrap.bundle.min.js"></script>
