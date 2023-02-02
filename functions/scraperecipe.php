<?php

require_once 'core/init.php';

function scrape($page_url){

    $recipe_parts=array("title"=>"","ingreds"=>"","instructs"=>"");
    //echo("setup array");
//https://www.php.net/manual/en/curl.examples.php
        // create curl resource
        $ch = curl_init();

        // set url
        //$url = Input::get('RecipeURL');
        //echo ($url);
        curl_setopt($ch, CURLOPT_URL, $page_url);
        //curl_setopt($ch, CURLOPT_URL, "https://www.ambitiouskitchen.com/cheddar-broccoli-chicken-pot-pie/");
        //curl_setopt($ch, CURLOPT_URL, "https://www.chilipeppermadness.com/recipes/couvillion/");
        //curl_setopt($ch, CURLOPT_URL, "https://www.chilipeppermadness.com/recipes/birria/");
        
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);
//https://software.hixie.ch/utilities/js/live-dom-viewer/
        // close curl resource to free up system resources
        curl_close($ch);     
        //printf("curlclose");
        $dom = new DomDocument();
        $dom->loadHTML($output);
        //printf("loadhtml");
        $xpath = new DOMXpath($dom);
        //printf("domxpath");
        
        $try_parts = wprm($xpath);
        //print_r($try_parts);
        if($try_parts){
            $recipe_parts = $try_parts;
        }

        return $recipe_parts;
    }
    
    function wprm($xpath){
        $recipe_parts = false;
         //$xpathquery="//div[contains(concat(' ', normalize-space(@class), ' '), ' wprm-recipe-ingredients-container ')]";
         $xpathquery="//li[@class='wprm-recipe-ingredient']";
         //https://stackoverflow.com/questions/1604471/how-can-i-find-an-element-by-css-class-with-xpath
        //var_dump($xpathquery);
        $elements = $xpath->query($xpathquery);
        //print_r($elements);

//https://www.php.net/manual/en/class.domnodelist.php
        if (!is_null($elements)) {  
            $recipe_parts=array("title"=>"","ingreds"=>"","instructs"=>"");
            $resultarray=array();
            foreach ($elements as $element) {
            //    $xpathquery="//span[@class='wprm-recipe-ingredient-amount']";
            //    $wprmAmount = $xpath->query($xpathquery, $element);
            //    print_r($wprmAmount);
            /*    
                $xpathquery="//span[contains(concat(' ', normalize-space(@class), ' '), ' wprm-recipe-ingredient-unit ')]";
                $wprmUnit = $xpath->query($xpathquery, $elements->item(0));
                //print_r($wprmUnit);
                $xpathquery="//span[contains(concat(' ', normalize-space(@class), ' '), ' wprm-recipe-ingredient-name ')]";
                $wprmName = $xpath->query($xpathquery, $elements->item(0));
                //print_r($wprmName);*/
                $partsarray=array("amount"=>"","unit"=>"ea","name"=>"","notes"=>"","full"=>"");

                foreach($element->childNodes as $cnode){
                    //print_r($cnode->attributes[0]);
                    switch(true){
                        case stristr($cnode->attributes[0]->nodeValue, "wprm-recipe-ingredient-amount"):
                            $partsarray["amount"]=$cnode->nodeValue;
                            break;
                        case stristr($cnode->attributes[0]->nodeValue, "wprm-recipe-ingredient-unit"):
                            $partsarray["unit"]=$cnode->nodeValue;
                            break;
                        case stristr($cnode->attributes[0]->nodeValue, "wprm-recipe-ingredient-name"):
                            $partsarray["name"]=$cnode->nodeValue;
                            break;
                        case stristr($cnode->attributes[0]->nodeValue, "wprm-recipe-ingredient-notes"):
                            $partsarray["notes"]=$cnode->nodeValue;
                            break;
                    }
                }
                $s = preg_replace("/ {2,}/", " ", htmlentities($element->nodeValue));
                $stripped = trim(preg_replace('/(&nbsp;)+|\s\K\s+/','', $s));
                $stripped = str_replace(array("\r\n", "\n", "\r"), ' ', $stripped);
                $partsarray["full"] = $stripped;
                $resultarray[] = $partsarray;

            }
            //print_r($resultarray);
            //echo("finished ingreds");
            $recipe_parts["ingreds"] = $resultarray;
            //print_r($recipe_parts);
        }

        $xpathquery="//div[@class='wprm-recipe-instruction-text']";
        $elements = $xpath->query($xpathquery);
        if (!is_null($elements)) {  
            $ingredarray=array();
            foreach ($elements as $element) {
                $ingredarray[] = $element->nodeValue;
            }
            //print_r($ingredarray);
            $recipe_parts["instructs"] = $ingredarray;
            //print_r($recipe_parts);
        }
        return $recipe_parts;
    }
?>
