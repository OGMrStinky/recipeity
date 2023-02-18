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
$sql = "SELECT * FROM usersrecipes LEFT JOIN recipes ON usersrecipes.recipeid = recipes.recipeid WHERE userid=?";
$recipe_list = array();
if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
        if(Input::get("RecipeName")){
            $sql .= " AND recipename LIKE ?";
            $DB->query($sql, array($user->data()->id, "%" . Input::get('RecipeName') . "%"));
        } elseif (Input::get("Ingred")){
            $sql = "SELECT recipes.RecipeID, IngredName, RecipeName FROM recipepartsingreds LEFT JOIN ingredients ON recipepartsingreds.IngredID = ingredients.IngredID LEFT JOIN recipes ON recipepartsingreds.RecipeID = recipes.RecipeID WHERE UserID=? AND IngredName LIKE ?";
            $DB->query($sql, array($user->data()->id, "%" . Input::get('Ingred') . "%"));
        } else {
            $DB->query($sql, array(escape($user->data()->id)));
        }
    }
} else {
    $DB->query($sql, array(escape($user->data()->id)));
}
if($DB->error()){
    print_r($DB->errorinfo());
    die;
} else{
    $recipe_list = $DB->results();
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
                            <li><a class="dropdown-item" href="listunits.php">Manage Units</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<form autocomplete="off" action="" method="post" class="row row-cols-lg-auto g-3 align-items-center p-4 justify-content-md-center">
    <div>
        <label class="visually-hidden" for="RecipeName">Recipe Name</label>
        <div class="input-group">
        <input type="text" class="form-control" id="RecipeName" name="RecipeName" placeholder="Recipe Name">
        </div>
    </div>
    <div class="text-center">or</div>
    <div>
        <label class="visually-hidden" for="Ingred">Ingredient</label>
        <div class="input-group">
        <input type="text" class="form-control" id="Ingred" name="Ingred" placeholder="Ingredient">
        </div>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <div>
        <button type="submit" class="btn btn-primary full">Filter</button>
    </div>
</form>

<div class="container">
    <div class="row">

        <?php 
            
            foreach($recipe_list as $recipe){
                echo('<div class="col-md-3 col-sm-6 col-xs-12 mb">');
                echo('  <div class="card h-100">');
                echo('      <div class="card-body">');
                echo("          <h5 class='card-title'><a href='viewrecipe.php?recipeid={$recipe->RecipeID}'>{$recipe->RecipeName}<a/></h5>");
                echo("      </div>");
                echo("  </div>");
                echo("</div>");
            }
        ?>

<?php

    if($user->hasPermission('admin')) {
        echo '<p>You are a Administrator!</p>';
    }


?>
    </div>
</div>
<style>
.mb{
	margin-bottom: 19px;
}
.full{
	width:100% !important;
}
body{
	overflow-x: hidden;	
}
</style>
<script src="core/bootstrap/bootstrap.bundle.min.js"></script>
