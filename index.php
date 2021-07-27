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

$menu = new Menu($user->data()->id);
$menuitems = $menu->getmenu();
if(count($menuitems) > 0){
    Redirect::to('viewmenu.php');
} else{
    Redirect::to('searchrecipes.php');
}

