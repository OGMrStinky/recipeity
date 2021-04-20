<?php

class Recipe {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    public function __construct() {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('sessions/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

    }

    public function create($fields = array(), $user) {
        /*if(!$this->_db->insert('users', $fields)) {
            throw new Exception('Sorry, there was a problem creating your account;');
        }*/

        if($this->_db->insert('recipes', $fields)){
            $RecipeID = $this->_db->lastId();
            if($this->_db->insert('usersrecipes',array(
                'UserID' => $user,
                'RecipeID' => $RecipeID
            )));
        }
        
        /*
        print $RecipeID;
        print "<pre>";
        print_r($fields);
        print "</pre>";
        
        check add units
        check add ingredients
        default recipe part to 1
        add recipe
        add recipe part
        add recipe parts ingredients
        

        */
    }

}