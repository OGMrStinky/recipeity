<?php
require_once 'core/init.php';


$user = new User(); //Current

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title>Recipeity</title>
  </head>

  <style>
      .colstuff{
          max-height: 500px;
      }
  </style>
  <body>
    <div class="container">
        <div class="navbar" role="navigation">
            <a class="navbar-brand" href="index.php">Recipeity</a>

        </div>

        <div class="container">
            <div class="row justify-content-around";">
                <div class="col-md-4 colstuff p-4 border text-light bg-dark overflow-auto">
                    <ul>
<?php



if (!Input::exists('get')) {
    Redirect::to('index.php');
}

$recipeID = Input::get('recipeid');
if ($recipeID) {
    $DB = DB::getInstance();
    $sql = "SELECT RecipeID, IngredName, UnitName, AmountVal, isDivided FROM recipepartsingreds LEFT JOIN ingredients ON recipepartsingreds.IngredID = ingredients.IngredID LEFT JOIN units ON recipepartsingreds.UnitsID = units.UnitID WHERE RecipeID=?";
    $ingred_list = $DB->query($sql, array(escape($recipeID)))->results();
    foreach($ingred_list as $ingred){
        echo("<li>{$ingred->AmountVal} {$ingred->UnitName} {$ingred->IngredName}</li>");
    }
            //button to add notes that saves timestamp.  displayed at top of recipe steps            
}
?>
                    </ul>
                </div>
                <div class="col-md-7 colstuff p-4 border text-light bg-dark overflow-auto">
                    <ul>
<?php
if ($recipeID) {
    $sql = "SELECT StepText FROM recipesteps WHERE RecipeID=?";
    $recipetexts = $DB->query($sql, array(escape($recipeID)))->results();
    foreach($recipetexts as $recipetext){
        echo("<li>{$recipetext->StepText}</li>");
    }
}
?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  </body>
</html>