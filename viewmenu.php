<?php
require_once 'core/init.php';


$user = new User(); //Current

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
                    
                        <div class="row">
                            <div class="col-10 gy-4">
                            <h5>Recipe Title</h5>
                            </div>
                            <div class="col gy-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                    
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-10 gy-4">
                            <h5>Long example recipe title to check wrapping</h5>
                            </div>
                            <div class="col gy-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                   
                                </div>
                            </div>
                        </div>                    
                        <div class="row">
                            <div class="col-10 gy-4">
                            <h5>Recipe Title</h5>
                            </div>
                            <div class="col gy-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                    
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-10 gy-4">
                            <h5>Recipe Title</h5>
                            </div>
                            <div class="col gy-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                    
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-10 gy-4">
                            <h5>Recipe Title</h5>
                            </div>
                            <div class="col gy-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                    
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-10 gy-4">
                            <h5>Recipe Title</h5>
                            </div>
                            <div class="col gy-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                    
                                </div>
                            </div>

                        </div>

                    </ul>
                </div>
                <div class="col-md-5 colstuff p-4 border text-light bg-dark overflow-auto">
                    <ul>









                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="core/bootstrap/bootstrap.bundle.min.js"></script>
  </body>
</html>