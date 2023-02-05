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
$menu = new Menu($user->data()->id);
$token = Token::generate();
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
            <div class="crow">
                <div class="cbtnone">
                    <form action="managemenu.php" method="post">
                        <?php if($menu->isonmenu($recipeID)){
                            echo '<button type="submit" name="RemoveFromMenu" class="btn btn-danger" type="button">Remove From Menu</button>
                            <input type="hidden" name="todo" value="RemoveFromMenu">';
                        } else{
                            echo '<button type="submit" name="AddToMenu" class="btn btn-success" type="button">Add To Menu</button>
                            <input type="hidden" name="todo" value="AddToMenu">';
                        }?>
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <input type="hidden" name="recipeid" value="<?php echo $recipeID; ?>">
                        
                    </form>
                </div>
                <div class="cbtntwo">
                    <form action="managemenu.php" method="post">
                        <?php if($menu->isonmenu($recipeID)){
                            echo '<button type="submit" name="MarkAsCooked" class="btn btn-primary" type="button">Mark as Cooked</button>';
                        }else{
                            echo '<button type="submit" name="MarkAsCooked" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="Add to menu to mark cooked" type="button" disabled>Mark as Cooked</button>';
                        }
                        ?>
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <input type="hidden" name="recipeid" value="<?php echo $recipeID; ?>">
                        <input type="hidden" name="todo" value="MarkAsCooked">
                    </form>
                    <?php if(!$menu->isonmenu($recipeID)){
                        echo 'Add to menu to mark as cooked';
                    }
                    ?>
                </div>
            </div>
            <div class="row justify-content-around p-4">
                <div class="col-md-4 colstuff p-4 border text-light bg-dark overflow-auto fheight">
                    <ul>
<?php




if ($recipeID) {
    $DB = DB::getInstance();
    $sql = "SELECT RecipeID, IngredName, UnitName, AmountVal, isDivided FROM recipepartsingreds LEFT JOIN ingredients ON recipepartsingreds.IngredID = ingredients.IngredID LEFT JOIN units ON recipepartsingreds.UnitsID = units.UnitID WHERE RecipeID=?";
    $ingred_list = $DB->query($sql, array(escape($recipeID)))->results();
    foreach($ingred_list as $ingred){
        $eamnt = float2rat($ingred->AmountVal);
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
                <div class="col-md-7 colstuff p-4 border text-light bg-dark overflow-auto fheight">
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
    <script src="core/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
    </script>
	<style>
	
		.btn{
				padding: 11px 16px !important;
		}
		.crow{
			    flex-wrap: nowrap;
			display: flex;
			margin-top: 16px;
			    justify-content: center;
				    gap: 25px;
		}
		.cbtnone, .cbtntwo{
			    width: auto;
		}
		.fheight{
				    height: 66vh;
			}
	
		@media screen and (min-width: 768px) {
			body{
				max-height: 100vh;
				overflow: hidden;
			}
		}
		@media screen and (max-width: 768px) {
			.fheight{
				    height: 260px;
			}
			.fheight ul{
				padding: 0;
			}
			.crow{
				display:block
			}
			.cbtnone{
				    margin-bottom: 12px;
			}
			.crow .btn{
				width:100%
			}
			.cbtnone, .cbtntwo {
				text-align: center;
			}
		}
	</style>
  </body>
</html>