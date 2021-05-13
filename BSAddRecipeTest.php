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
    <div class="container">
        <div class="navbar" role="navigation">
            <a class="navbar-brand" href="#">Recipeity</a>

        </div>

        <div class="container">
            <div class="row justify-content-around">
                <div class="col-md-8 p-4 border text-light bg-dark">
                    <h2 class="text-light">Add Recipe</h2>
                    <form autocomplete="off" action="" method="post">
                      <div >
                        <input type="text" class="form-control" name="name" placeholder="Recipe Name" aria-label="Recipe Name">
                      </div>
                      <label for="ingredscontainer" class="form-label">Ingredients</label>
                      <div id="ingredscontainer">
                      <div id="duplicater" class="row p-1">
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
                      </div>
                      </div>
                      <button id="moreIngred" class="btn btn-primary" type="button">More</button>
                      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                      <div >
                          <label for="exampleFormControlTextarea1" class="form-label">Instructions</label>
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="15" name="instruct" ></textarea>
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

</html>