<?php
require_once 'core/init.php';


$user = new User(); //Current

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (!Input::exists('get')) {
    Redirect::to('index.php');
}

$recipeID = Input::get('recipeid');
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
                        <a class="nav-link" href="addrecipe.php?recipeid=<?php echo $recipeID ?>">Edit Recipe</a>
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
            <div class="row justify-content-around p-4">
                <div class="col-md-4 colstuff p-4 border text-light bg-dark overflow-auto">
                    <ul>
<?php




if ($recipeID) {
    $DB = DB::getInstance();
    $sql = "SELECT RecipeID, IngredName, UnitName, AmountVal, isDivided FROM recipepartsingreds LEFT JOIN ingredients ON recipepartsingreds.IngredID = ingredients.IngredID LEFT JOIN units ON recipepartsingreds.UnitsID = units.UnitID WHERE RecipeID=?";
    $ingred_list = $DB->query($sql, array(escape($recipeID)))->results();
    foreach($ingred_list as $ingred){
        $eamnt = float2rat(escape($ingred->AmountVal));
        //$eamnt = escape($ingred->AmountVal);
        $eunit = escape($ingred->UnitName);
        $enam = escape($ingred->IngredName);
        echo("<li>{$eamnt} {$eunit} {$enam}</li>");
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
        $estep = escape($recipetext->StepText);
        echo("<li>{$estep}</li>");
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