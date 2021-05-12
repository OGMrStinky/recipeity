<?php

require_once 'core/init.php';

$user = new User();
$recipe = new Recipe();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
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


?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="core/styles.css">
</head>     
<body>

<h2>Add Recipe</h2>



<!--Make sure the form has the autocomplete function switched off:-->
<form autocomplete="off" action="" method="post">
    <div>
        <input class="RecName" type="text" name="name" placeholder="Name">
    </div>
    <p>Ingredients</p>
    <div>
    <div id="duplicater" class="autocomplete" style="width:700px;">
        <input class="AmntInput" type="text" name="ingAmt[]" placeholder="Amount">
        <input class="UnitsInput" type="text" name="ingUnit[]" placeholder="Units">
        <input class="IngredInput" type="text" name="ingIng[]" placeholder="Ingredient">
        <input class="IsDivided" type ="checkbox" name="ingIsDivided[]" value=0>Divided
    </div>
    </div>
    <button id="moreIngred" type="button">More</button>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <p>Instructions</p>
    <div>
        <textarea name="instruct" rows=24 cols=58></textarea>
    </div>
    <input type="submit" value="Save">
</form>

</body>


<script>
<?php
    print($recipe->getUnits($user->data()->id));
    print($recipe->getIngreds($user->data()->id));


?>
</script>
<script src="core/scripts.js"></script>
</html>