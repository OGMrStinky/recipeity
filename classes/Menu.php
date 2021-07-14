<?php

class Menu {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_userid,
            $_lastitemid;

    public function __construct($userid) {
        $this->_userid = $userid;
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('sessions/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

    }

    public function addtomenu($recipeid){
        if($this->_db->insert("Menu", array("UserID"=>$this->_userid, "RecipeID"=>$recipeid))){
            return true;
        } else{
            echo $this->_db->errorinfo(); die;
        }
        return false;
    }

    public function removefrommenu($recipeid){
        if($this->isonmenu($recipeid)){

            if(!$this->_db->delete("Menu", array("MenuID", "=", $this->_lastitemid))->error()){
                return true;
            } else{
                echo $this->_db->errorinfo(); die;
            }
        }
        return false;
    }

    public function markascooked($recipeid){
        if($this->isonmenu($recipeid)){
            $sql = "UPDATE Menu SET CookedOnDate = CURRENT_TIMESTAMP WHERE MenuID=?";
            if(!$this->_db->query($sql, array($this->_lastitemid))->error()){
                return true;
            } else{
                echo $this->_db->errorinfo(); die;
            }
        }        
        return false;
    }

    public function isonmenu($recipeid){
        $sql = "SELECT MenuID FROM Menu WHERE UserID=? AND RecipeID=? AND CookedOnDate IS NULL";
        if(!$this->_db->query($sql, array($this->_userid, $recipeid))->error()){
            if($this->_db->count() > 0){
                $this->_lastitemid = $this->_db->first()->MenuID;
                return true;
            }
        } else{
            echo $this->_db->errorinfo(); die;
        }
        return false;
    }

    public function getmenu(){
        $sql = "SELECT Menu.RecipeID, RecipeName FROM Menu LEFT JOIN Recipes ON Menu.RecipeID = Recipes.RecipeID WHERE UserID=? AND CookedOnDate IS NULL";
        if(!$this->_db->query($sql, array($this->_userid))->error()){
            return $this->_db->results();
        } else{
            echo $this->_db->errorinfo(); die;
        }
        return array();
    }
}