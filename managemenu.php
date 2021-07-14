<?php
require_once 'core/init.php';


$user = new User(); //Current
$recipe = new Recipe();
$menu = new Menu($user->data()->id);

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $recipeID = Input::get('recipeid');
        $todo = Input::get('todo');

        if(is_numeric($recipeID)){
            if($recipe->checkismine($user->data()->id, $recipeID )){
                if($todo == 'AddToMenu'){
                    if($menu->addtomenu($recipeID)){
                        //echo "added to menu"; die;
                        Redirect::to("viewrecipe.php?recipeid={$recipeID}");
                    }
                } elseif($todo == 'MarkAsCooked'){
                    if($menu->markascooked($recipeID)){
                        //echo "marked as cooked"; die;
                        Redirect::to("viewrecipe.php?recipeid={$recipeID}");
                    }
                } elseif($todo == 'RemoveFromMenu'){
                    if($menu->removefrommenu($recipeID)){
                        //echo "removed from menu"; die;
                        Redirect::to("viewrecipe.php?recipeid={$recipeID}");
                    }
                }
            }

        } else{
            Redirect::to('logout.php');
        }
        echo "something went wrong"; die;
    }
}