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
$unitid = 0;
$unitname = "";
$aliasid = 0;
$sql = "";

if(Input::get("unitid")){
    $unitid = Input::get("unitid");
    $sql = "SELECT * FROM units WHERE UnitID=?"; //get the unit from the DB
    $DB->query($sql, array(escape($unitid)));

    if($DB->error()){
        print_r($DB->errorinfo());
        die;
    } else{
        if ($DB->count() > 0){
            $unitname = $DB->results();
            $aliasid = $unitname[0]->AliasID;
            $unitname = $unitname[0]->UnitName;
            
        }
    }
}

if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $v1 = Input::get('unitname');
        $v2 = $user->data()->id;
        $v3 = Input::get('aliasfor');

        if($v3 == 'NONE'){
            $v3 = 0;
        }

        if($unitid == ""){
            $DB->insert('units',array(
                    'UserID' => $v2,
                    'UnitName' => $v1,
                    'AliasID' => $v3
            ));

        }else{
            $DB->update('units', $unitid ,array(
                'UserID' => $v2,
                'UnitName' => $v1,
                'AliasID' => $v3
            ));

        }

        if($DB->error()){
            print_r($DB->errorinfo());
            die;
        } else{
            Redirect::to('listunits.php');
        }
    }
}


$sql = "SELECT * FROM units WHERE userid=? AND AliasID IS NULL"; //get all units that aren't an alias
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
    <form autocomplete="off" action="" method="post">
        <label for="unitname" class="form-label">Unit</label>
        <input type="text" class="form-control" name="unitname" id="unitname" value="<?php echo $unitname; ?>" placeholder="Type Unit Name Here" aria-label="Unit Name">
        <label for="aliasfor">Alias for</label>
        <select id="aliasfor" class="form-control" name="aliasfor">
            
        <?php 
            $hasAlias = 0;
            foreach($unit_list as $unit){
                //echo("<li><a href='editunit.php?unitid={$unit->UnitID}'>{$unit->UnitName}<a/></li>");
                if ($aliasid == $unit->UnitID){
                    echo("<option selected value={$unit->UnitID}>{$unit->UnitName}</option>");
                    $hasAlias = 1;
                } else{
                    echo("<option value={$unit->UnitID}>{$unit->UnitName}</option>");
                }
            }

            if ($hasAlias >0){
                echo('<option value="NONE">none</option>');
            }else{
                echo('<option value="NONE" selected>none</option>');
            }
        ?>
        </select>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
<script src="core/bootstrap/bootstrap.bundle.min.js"></script>
