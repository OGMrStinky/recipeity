<?php

class Recipe {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn,
            $_recipeid,
            $_ismine;

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
                    "AmountVal" => $amnt,
                    "isDivided" => $isdivided
                )));
            }
            $i+=1;
        }
    }

    public function updateingreds($ingreds, $units, $amnt, $divides, $userID){
        $this->_db->delete("recipepartsingreds", array("RecipeID", "=", $this->_recipeid));
        $this->addingreds($ingreds, $units, $amnt, $divides, $userID);
    }

    public function addsteps($steps){
        //RecipeID, StepOrder, StepText
        $success = true;
        $steparray = explode(PHP_EOL, $steps);
        foreach ($steparray as $step) {
                
            if(!$this->_db->insert('recipesteps', array(
                "RecipeID" => $this->_recipeid,
                "StepOrder" => 1,
                "StepText" => escape($step)
            ))){
                $success = false;
            }
        }
        return $success;
    }

    public function updatesteps($steps){
        $this->_db->delete("recipesteps", array("RecipeID", "=", $this->_recipeid));
        $this->addsteps($steps);
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

    }

    public function updatename($fields){
        if($this->_db->update('recipes', $this->_recipeid, $fields)){
            return true;
        }
        return false;
    }

    public function getUnits($userID){
        //var aUnits = ["oz", "tbsp", "lb", "cup"];
        $units = $this->_db->get("units", array("UserID", "=", $userID))->results();
        $sunits = 'var aUnits = ["';
        $x = 1;
        foreach($units as $unit) {
            $sunits .= escape($unit->UnitName);
            if ($x < count($units)) {
                $sunits .= '", "';
            }
            $x++;
        }
        $sunits .= '"];';
        return $sunits;
    }

    public function checkismine($userid, $recipeid){
        if(is_null($this->_ismine)){
            $recipeuser = $this->_db->get("usersrecipes", array("RecipeID", "=", $recipeid))->first();
            if($userid == $recipeuser->UserID){
                $this->_recipeid = $recipeid;
                $this->_ismine = true;
            }else{
                $this->_ismine = false;
            }
        }
        return $this->_ismine;
    }

    public function getIngreds($userID){
        //var aIngreds = ["beef", "paprika", "celery"];
        $ingreds = $this->_db->get("ingredients", array("UserID", "=", $userID))->results();
        $singreds = 'var aIngreds = ["';
        $x = 1;
        foreach($ingreds as $ingred) {
            $singreds .= escape($ingred->IngredName);
            if ($x < count($ingreds)) {
                $singreds .= '", "';
            }
            $x++;
        }
        $singreds .= '"];';
        return $singreds;
    }

}