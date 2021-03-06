<?php

require_once 'core/init.php';

$user = new User();
$recipe = new Recipe();

if(!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
        if(Input::get("recipeid")){
            try {
                if($recipe->checkismine($user->data()->id, Input::get("recipeid") )){
                    $recipe->updatename(array('RecipeName' => Input::get('name')));                   
                    //print_r(Input::get('ingAmt')); die;
                    $recipe->updateingreds(
                        Input::get('ingIng'),
                        Input::get('ingUnit'),
                        Input::get('ingAmt'),
                        Input::get('ingIsDivided'),
                        $user->data()->id
                    );
                    $recipe->updatesteps(Input::get('instruct'));
                    $toview = "viewrecipe.php?recipeid=" . Input::get("recipeid");
                    Redirect::to($toview);
                }
            } catch (Exception $e) {
                echo $e->getTraceAsString(), '<br>';
            }
        }else{
            try {
                $recipe->create(array(
                    'RecipeName' => Input::get('name'),
                    'RecipePartsCnt' => 1
                    ),$user->data()->id);
                $recipe->addingreds(
                    Input::get('ingIng'),
                    Input::get('ingUnit'),
                    Input::get('ingAmt'),
                    Input::get('ingIsDivided'),
                    $user->data()->id
                );

                $recipe->addsteps(Input::get('instruct'));
                Redirect::to('index.php');
                

            } catch(Exception $e) {
                echo $e->getTraceAsString(), '<br>';
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

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Recipeity</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

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
                <div class="col-md-8 p-4 border text-light bg-dark">
<?php
    $DB = DB::getInstance();
    $recipeID = Input::get('recipeid');
    if($recipeID){print("<h2 class='text-light'>Edit Recipe</h2>");}else{print("<h2 class='text-light'>Add Recipe</h2>");}
?>
                    
                    <form autocomplete="off" action="" method="post">
                      <div >
<?php
    if($recipeID){
        if($DB->action("SELECT RecipeName", "recipes", array("RecipeID", "=", $recipeID))){
            $enam = escape($DB->first()->RecipeName);
            print("<input type='text' value='{$enam}' class='form-control' name='name' placeholder='Recipe Name' aria-label='Recipe Name'>");
        }else{
            print('<input type="text" class="form-control" name="name" placeholder="Recipe Name" aria-label="Recipe Name">');
        }
    }else{
        print('<input type="text" class="form-control" name="name" placeholder="Recipe Name" aria-label="Recipe Name">');
    }
?>
                        </div>
                      <label for="ingredscontainer" class="form-label">Ingredients</label>
                      <div id="ingredscontainer">
<?php


if ($recipeID) {
    
    $sql = "SELECT RecipeID, IngredName, UnitName, AmountVal, isDivided FROM recipepartsingreds LEFT JOIN ingredients ON recipepartsingreds.IngredID = ingredients.IngredID LEFT JOIN units ON recipepartsingreds.UnitsID = units.UnitID WHERE RecipeID=?";
    $ingred_list = $DB->query($sql, array(escape($recipeID)))->results();
    $ingredcnt = 0;
    foreach($ingred_list as $ingred){
        //echo("<li>{$ingred->AmountVal} {$ingred->UnitName} {$ingred->IngredName}</li>");
        $ingid = "duplicater";
        if($ingredcnt>0){
            $ingid .= $ingredcnt;
        }
        $eamnt = escape($ingred->AmountVal);
        $eunit = escape($ingred->UnitName);
        $eingred = escape($ingred->IngredName);
        $eamnt = float2rat(escape($ingred->AmountVal));
        print("<div id='{$ingid}' class='row p-1'>
            <div class='col-sm-2'>
            <input type='text' value='{$eamnt}' class='form-control AmntInput' name='ingAmt[]' placeholder='Amount' aria-label='Amount'>
            </div>
            <div class='col-sm-2'>
            <input type='text' value='{$eunit}'class='form-control UnitsInput' name='ingUnit[]' placeholder='Units' aria-label='Units'>
            </div>
            <div class='col-sm'>
            <input type='text' value='{$eingred}' class='form-control IngredInput' name='ingIng[]' placeholder='Ingredient' aria-label='Ingredient'>
            </div>
            <div class='col-sm-2 form-check'>
                <input type='checkbox' class='form-check-input IsDivided' name='ingIsDivided[]' id='exampleCheck1'>
                <label class='form-check-label' for='exampleCheck1'>Divided</label>
            </div>
            </div>");
        $ingredcnt += 1;
    }
}else{
    print('<div id="duplicater" class="row p-1">
        <div class="col-sm-2">
        <input type="text" class="form-control AmntInput" name="ingAmt[]" placeholder="Amount" aria-label="Amount">
        </div>
        <div class="col-sm-2">
        <input type="text" class="form-control UnitsInput" name="ingUnit[]" placeholder="Units" aria-label="Units">
        </div>
        <div class="col-sm">
        <input type="text" class="form-control IngredInput" name="ingIng[]" placeholder="Ingredient" aria-label="Ingredient">
        </div>
        <div class="col-sm-2 form-check">
            <input type="checkbox" class="form-check-input IsDivided" name="ingIsDivided[]" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Divided</label>
        </div>
        </div>');
}
?>
                      
                      </div>
                      <button id="moreIngred" class="btn btn-primary" type="button">More</button>
                      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                      <div >
                          <label for="exampleFormControlTextarea1" class="form-label">Instructions</label>
<?php
/*$array = array('lastname', 'email', 'phone');
$comma_separated = implode(",", $array);

PHP_EOL
*/
if ($recipeID) {
    $recsteps = "";
    $sql = "SELECT StepText FROM recipesteps WHERE RecipeID=?";
    $recipetexts = $DB->query($sql, array(escape($recipeID)))->results();
    $x = 1;
    foreach($recipetexts as $recipetext){
        $recsteps .= escape($recipetext->StepText);
        if($x < count ($recipetexts)) {
            $recsteps .= PHP_EOL;
        }
        $x++;
    }
    //print_r($DB->action("SELECT StepText", "recipesteps", array("RecipeID", "=", $recipeID))->results());
    //$recsteps = implode(PHP_EOL, $DB->action("SELECT StepText", "recipesteps", array("RecipeID", "=", $recipeID))->results());
    print("<textarea class='form-control' id='exampleFormControlTextarea1' rows=15 name='instruct' placeholder='Paste instructions here. Each step on its own line.' >{$recsteps}</textarea>");
}else{
    print("<textarea class='form-control' id='exampleFormControlTextarea1' value='' rows=15 name='instruct' placeholder='Paste instructions here. Each step on its own line.' ></textarea>");
}
?>
                        </div>
                      <div  class="row p-2">
                          <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                      <div  class="row p-2">
                          <a href="index.php" id="cancel" name="cancel" class="btn btn-danger">Cancel</a>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
<?php
    print($recipe->getUnits($user->data()->id));
    print($recipe->getIngreds($user->data()->id));


?>
</script>
<script src="core/scripts.js"></script>
<script src="core/bootstrap/bootstrap.bundle.min.js"></script>
</html>