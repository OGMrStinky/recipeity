<?php
require_once 'core/init.php';


$user = new User(); //Current
$menu = new Menu($user->data()->id);

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
/*
if (!Input::exists('get')) {
    Redirect::to('index.php');
}

$recipeID = Input::get('recipeid');
*/
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
                        <a class="nav-link" href="index.php">Search Recipes</a>
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
                <div class="col-md-6 colstuff p-4 border text-light bg-dark overflow-auto">
                    <ul>

                        <div class="row">
                            <div class="col-9 gy-4">
                            
                            </div>
                            <div class="col gy-4">
                                Groceries?
                            </div>

                        </div>
                        <?php
                            $menuitems = $menu->getmenu();
                            if(count($menuitems) > 0){
                                foreach($menuitems as $menuitem){
                                    echo '<div class="row">';
                                    echo '  <div class="col-10 gy-4">';
                                    echo "      <h5><a href='viewrecipe.php?recipeid={$menuitem->RecipeID}'>{$menuitem->RecipeName}</a></h5>";
                                    echo '  </div>';
                                    echo '  <div class="col gy-4">';
                                    echo '      <div class="form-check form-switch">';
                                    echo '          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>';
                                    echo '      </div>';
                                    echo '  </div>';
                                    echo '</div>';
                                }
                            } else{
                                echo '<h5>Nothing on the menu</h5>';
                            }
                        ?>
                        

                    </ul>
                </div>
                <div class="col-md-5 colstuff p-4 border text-light bg-dark overflow-auto">
                    <ul>
                        <?php
                            $groceries = $menu->getgroceries();
                            if(count($groceries) > 0){
                                foreach($groceries as $grocery){
                                    $totamtfrac = float2rat($grocery->total_amount);
                                    echo "<li>{$totamtfrac} {$grocery->UnitName} {$grocery->IngredName}</li>";
                                }
                            }
                        ?>







                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="core/bootstrap/bootstrap.bundle.min.js"></script>
  </body>
</html>