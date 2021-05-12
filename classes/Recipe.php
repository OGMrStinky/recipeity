<?php

class Recipe {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn,
            $_recipeid;

    public function __construct() {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('sessions/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

    }

    public function recipeid(){
        return $this->recipeid;
    }

    public function addingreds($ingreds, $units, $amnt, $divides, $userID){
        $i = 0;
        $unitID = 0;
        $ingredID = 0;
        $isdivided = 0;
        foreach ($ingreds as $ingred) {
            if($this->_db->insertIngredOrUnit('ingredients', $ingred, $userID)){
                $ingredID = $this->_db->lastId();   
            }
            if($this->_db->insertIngredOrUnit('units', $units[$i], $userID)){
                $unitID = $this->_db->lastId();
            }
            if(is_array($divides)){
                if(in_array($i, $divides)){$isdivided = 1;}
            }
            echo("ingredID={$ingredID} and unitID={$unitID}");
            if($ingredID <> 0 && $unitID <> 0){
                if($this->_db->insert('recipepartsingreds', array(
                    "RecipeID" => $this->_recipeid,
                    "IngredID" => $ingredID,
                    "UnitsID" => $unitID,
                    "AmountVal" => $amnt[$i],
                    "isDivided" => $isdivided
                )));
            }
            $i+=1;
        }
    }

    public function addsteps($steps){
        //RecipeID, StepOrder, StepText
        $success = true;
        $steparray = explode(PHP_EOL, $steps);
        foreach ($steparray as $step) {
                
            if(!$this->_db->insert('recipesteps', array(
                "RecipeID" => $this->_recipeid,
                "StepOrder" => 1,
                "StepText" => $step
            ))){
                $success = false;
            }
        }
        return $success;
    }

    public function create($fields = array(), $user) {
        /*if(!$this->_db->insert('users', $fields)) {
            throw new Exception('Sorry, there was a problem creating your account;');
        }*/

        if($this->_db->insert('recipes', $fields)){
            $this->_recipeid = $this->_db->lastId();
            if($this->_db->insert('usersrecipes',array(
                'UserID' => $user,
                'RecipeID' => $this->_recipeid
            ))){
                return true;
            };
        }
        return false;
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

    public function getUnits($userID){
        //var aUnits = ["oz", "tbsp", "lb", "cup"];
        $units = $this->_db->get("units", array("UserID", "=", $userID))->results();
        $sunits = 'var aUnits = ["';
        $x = 1;
        foreach($units as $unit) {
            $sunits .= $unit->UnitName;
            if ($x < count($units)) {
                $sunits .= '", "';
            }
            $x++;
        }
        $sunits .= '"];';
        return $sunits;
    }

    public function getIngreds($userID){
        //var aIngreds = ["beef", "paprika", "celery"];
        $ingreds = $this->_db->get("ingredients", array("UserID", "=", $userID))->results();
        $singreds = 'var aIngreds = ["';
        $x = 1;
        foreach($ingreds as $ingred) {
            $singreds .= $ingred->IngredName;
            if ($x < count($ingreds)) {
                $singreds .= '", "';
            }
            $x++;
        }
        $singreds .= '"];';
        return $singreds;
    }

}