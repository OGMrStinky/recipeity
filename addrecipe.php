<?php

require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
       $recipe = new Recipe();

        try {
          print "<pre>";
          print_r($_POST);
          print "</pre>";

          $recipe->create(array(
              'RecipeName' => Input::get('name'),
              'RecipePartsCnt' => 1
            ),$user->data()->id);
          $recipe->addingreds(
            Input::get('ingIng'),
            Input::get('ingUnit'),
            Input::get('ingAmt'),
            Input::get('ingIsDivided')
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
<style>
* {
  box-sizing: border-box;
}

body {
  font: 16px Arial;  
}

/*the container must be positioned relative:*/
.autocomplete {
  position: relative;
  display: inline-block;
  padding: 2px;
}

input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}

input[type=text] {
  background-color: #f1f1f1;
  
}

input[type=submit] {
  background-color: DodgerBlue;
  color: #fff;
  cursor: pointer;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}

/*when hovering an item:*/
.autocomplete-items div:hover {
  background-color: #e9e9e9; 
}

/*when navigating through the items using the arrow keys:*/
.autocomplete-active {
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}

.AmntInput{
    width: 80px;
}
.UnitsInput{
    width: 120px;
}
.IngredInput{
    width: 25opx;
}

.RecName{
    width: 428px;
}

</style>
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
    document.getElementById('moreIngred').onclick = dupIngred;
    //document.getElementById('moreInstruct').onclick = dupInstruct;


    var i = 0;
    var ogIngred = document.getElementById('duplicater');

    function dupIngred() {
        var clone = ogIngred.cloneNode(true); // "deep" clone
        clone.id = "duplicator" + ++i; // there can only be one element with an ID
        ogIngred.parentNode.appendChild(clone);

        //clear values and attach autocomplete to units and ingred inputs
        var eAmnts = document.getElementsByClassName("AmntInput");
        eAmnts[eAmnts.length -1].value = "";

        var eUnits = document.getElementsByClassName("UnitsInput");
        var ii;
        for (ii in eUnits){
            if ( (typeof eUnits[ii] === "object") ){
                autocomplete(eUnits[ii], aUnits);
            }    
        }
        eUnits[eUnits.length -1].value = "";

        var eIngreds = document.getElementsByClassName("IngredInput");
        var nn;
        for (nn in eIngreds){
            if ( (typeof eIngreds[nn] === "object") ){
                autocomplete(eIngreds[nn], aIngreds);
            } 
        }
        eIngreds[eIngreds.length - 1].value = "";

        var eAmnts = document.getElementsByClassName("IsDivided");
        eAmnts[eAmnts.length -1].value = i;
        eAmnts[eAmnts.length -1].checked = false;
    }

    //https://stackoverflow.com/questions/4355868/php-get-a-dynamic-number-of-html-input-fields/4355893
    //https://www.w3schools.com/jsref/tryit.asp?filename=tryjsref_text_create
    //http://jsfiddle.net/kXmpY/1/
    //https://github.com/attilaantal/PHP-OOP-Login-Register-System

</script>

<script>
    function autocomplete(inp, arr) {
      /*the autocomplete function takes two arguments,
      the text field element and an array of possible autocompleted values:*/
      var currentFocus;
      /*execute a function when someone writes in the text field:*/
      inp.addEventListener("input", function(e) {
          var a, b, i, val = this.value;
          /*close any already open lists of autocompleted values*/
          closeAllLists();
          if (!val) { return false;}
          currentFocus = -1;
          /*create a DIV element that will contain the items (values):*/
          a = document.createElement("DIV");
          a.setAttribute("id", this.id + "autocomplete-list");
          a.setAttribute("class", "autocomplete-items");
          /*append the DIV element as a child of the autocomplete container:*/
          this.parentNode.appendChild(a);
          /*for each item in the array...*/
          for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
              /*create a DIV element for each matching element:*/
              b = document.createElement("DIV");
              /*make the matching letters bold:*/
              b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
              b.innerHTML += arr[i].substr(val.length);
              /*insert a input field that will hold the current array item's value:*/
              b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
              /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
                  /*insert the value for the autocomplete text field:*/
                  inp.value = this.getElementsByTagName("input")[0].value;
                  /*close the list of autocompleted values,
                  (or any other open lists of autocompleted values:*/
                  closeAllLists();
              });
              a.appendChild(b);
            }
          }
      });
      /*execute a function presses a key on the keyboard:*/
      inp.addEventListener("keydown", function(e) {
          var x = document.getElementById(this.id + "autocomplete-list");
          if (x) x = x.getElementsByTagName("div");
          if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
              /*and simulate a click on the "active" item:*/
              if (x) x[currentFocus].click();
            }
          }
      });
      function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
      }
      function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
        }
      }
      function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
          if (elmnt != x[i] && elmnt != inp) {
            x[i].parentNode.removeChild(x[i]);
          }
        }
      }
      /*execute a function when someone clicks in the document:*/
      document.addEventListener("click", function (e) {
          closeAllLists(e.target);
      });
    }
    
    /*An array containing all the country names in the world:*/
    var aUnits = ["oz", "tbsp", "lb", "cup"];
    var aIngreds = ["beef", "paprika", "celery"];
    /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
    
    var eUnits = document.getElementsByClassName("UnitsInput");
    var ii;
    for (ii in eUnits){
        if ( (typeof eUnits[ii] === "object") ){
            autocomplete(eUnits[ii], aUnits);
        }    
    }

    var eIngreds = document.getElementsByClassName("IngredInput");
    var nn;
    for (nn in eIngreds){
        if ( (typeof eIngreds[nn] === "object") ){
            autocomplete(eIngreds[nn], aIngreds);
        } 
    }
    //autocomplete(document.getElementById("Units"), aUnits);
    //autocomplete(document.getElementById("Ingred"), aIngreds);
    /*https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_autocomplete*/

</script>
    


</html>