<?php
//https://stackoverflow.com/questions/14330713/converting-float-decimal-to-fraction
//https://stackoverflow.com/questions/1954018/php-convert-decimal-into-fraction-and-back

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

function float2rat($n, $tolerance = 1.e-6) {
    $w = 0;
    if($n > 1){
        $w = floor($n);
        $n = $n - $w;
    }
    if($n == 0){return $w;}
    $h1=1; $h2=0;
    $k1=0; $k2=1;
    $b = 1/$n;
    do {
        $b = 1/$b;
        $a = floor($b);
        $aux = $h1; $h1 = $a*$h1+$h2; $h2 = $aux;
        $aux = $k1; $k1 = $a*$k1+$k2; $k2 = $aux;
        $b = $b-$a;
    } while (abs($n-$h1/$k1) > $n*$tolerance);

    $sfrac = "";
    if($h1 == 333332 && $k1 == 999997){
        $h1 = 1;
        $k1 = 3;
    }
    if($h1 == 666665 && $k1 == 999997){
        $h1 = 2;
        $k1 = 3;
    }
    if($h1 == 33332 && $k1 == 99997){
        $h1 = 1;
        $k1 = 3;
    }
    if($h1 == 66665 && $k1 == 99997){
        $h1 = 2;
        $k1 = 3;
    }
    if($w > 0){
        $sfrac .= $w . " " . $h1 . "/" . $k1;
    } elseif($h1==$k1){
        $sfrac .= $h1;
    } else{
        $sfrac = $h1 . "/" . $k1;
    }
    return $sfrac;
}

function fracstring2float($s){
    $input = $s;
    //if(is_numeric($s)){return 0;}
    if (preg_match ("/^([0-9]+)$/", $input)) {return $input;}
    if (str_contains($input, ".")) {return $input;}
    $fraction = array('whole' => 0);
    preg_match('/^((?P<whole>\d+)(?=\s))?(\s*)?(?P<numerator>\d+)\/(?P<denominator>\d+)$/', $input, $fraction);
    if($fraction['whole']==""){$fraction['whole']=0;}
    $result = $fraction['whole'] + $fraction['numerator'] / $fraction['denominator'];
    return $result;
}