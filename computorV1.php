<?php

if ($argc < 2) {
    echo "Passez un argument";
} else if ($argc > 2) {
    echo "Passez un seul argument";
} else {
    parseArg($argv[1]);
}

function parseArg($str) {
    $tmpStr = preg_replace("/\s+/", '', $str);
    $tmpArr = explode('=', $tmpStr);
    $left = preg_split("/X\^/", $tmpArr[0], 0, PREG_SPLIT_NO_EMPTY);
    $right = preg_split("/X\^/", $tmpArr[1], 0, PREG_SPLIT_NO_EMPTY);

    $leftCoeff = array();
    $rightCoeff = array();

    $i = 0;
    $pow = 'pow';
    while ($i < (count($left) - 1)) {
        $pow .= $i;
        if ($left[$i + 1][0] == '0') {
            $leftCoeff[$pow] = substr($left[$i], 0, -1);
        } else {
            $leftCoeff[$pow] = substr($left[$i], 1, -1);
        }
        $pow = substr($pow, 0, -1);
        $i++;
    }

    $i = 0;
    $pow = 'pow';
    while ($i < (count($right) - 1)) {
        $pow .= $i;
        if ($right[$i + 1][0] == '0') {
            $rightCoeff[$pow] = substr($right[$i], 0, -1);
        } else {
            $rightCoeff[$pow] = substr($right[$i], 1, -1);
        }
        $pow = substr($pow, 0, -1);
        $i++;
    }
    reduceEqu($leftCoeff, $rightCoeff);
}

function reduceEqu($leftCoeff, $rightCoeff) {
    $coeffs = array();
    $c = (count($leftCoeff) > count($rightCoeff)) ? count($leftCoeff) : count($rightCoeff);
    $i = 0;
    while ($i < $c) {
        $l = 0;
        $r = 0;
        if (array_key_exists('pow' . $i, $leftCoeff)) {
            $l = floatval($leftCoeff['pow' . $i]);
        }
        if (array_key_exists('pow' . $i, $rightCoeff)) {
            $r = floatval($rightCoeff['pow' . $i]);
        }
        $coeffs[$i] = $l - $r;
        $i++;
    }
    $reduceStr = "Reduced form : ";
    $polyDegree = 0;
    $p = 0;
    $i = 0;
    while ($i < count($coeffs)) {
        if ($coeffs[$i] != 0) {
            $polyDegree = $i;
            if ($coeffs[$i] < 0) {
                $reduceStr .= " - ";
                $coeffs[$i] *= -1;
                $p = 1;
            } else if ($i != 0) {
                $reduceStr .= " + ";
            }
            $reduceStr .= $coeffs[$i] . " * X^" . $i;
            if ($p == 1) {
                $coeffs[$i] *= -1;
                $p = 0;
            }
        }
        $i++;
    }
    if ($reduceStr == "Reduced form : ") {
        $reduceStr .= '0';
    }
    $reduceStr .= " = 0\n";
    echo $reduceStr;
    echo "Polynomial degree: " . $polyDegree . "\n";
    solution($coeffs, $polyDegree);
}

function solution($coeffs, $polyDegree) {
    $result = '';
    if ($polyDegree == 0) {
        if ($coeffs[0] != 0) {
            $result .= "There is no solution.\n";
        } else {
            $result .= "All real numbers are solution.\n";
        }
    } else if ($polyDegree == 1) {
        $result .= "The solution is :\n";
        $result .= ($coeffs[0] / $coeffs[1]) * (-1);
    } else if ($polyDegree == 2) {
        $disc = $coeffs[1] * $coeffs[1] - 4.0 * $coeffs[2] * $coeffs[0];
        if ($disc < 0.0) {
            $result .= "Discriminant is strictly negative, the two complexes solutions are:\n";
            if ($coeffs[1] != 0.0) {
                $result .= '(-' .  $coeffs[1] . ' - iV(' . (-1) * $disc . ')) / ' . 2 * $coeffs[2] . "\n"; 
                $result .= '(-' .  $coeffs[1] . ' + iV(' . (-1) * $disc . ')) / ' . 2 * $coeffs[2]; 
            } else {
                $result .= '(-iV(' . (-1) * $disc . ')) / (' . 2 * $coeffs[2] . ")\n"; 
                $result .= '(iV(' . (-1) * $disc . ')) / (' . 2 * $coeffs[2] .')'; 
            }
        } else if ($disc == 0.0) {
            $result .= "Discriminant is 0, the unique solution is:\n";
            $result .= (-1) * $coeffs[1] / (2 * $coeffs[0]);
        } else if ($disc > 0.0) {
            $result .= "Discriminant is strictly positive, the two solutions are:\n";
            $result .= ((-1) * $coeffs[1] - sqrtMy($disc)) / (2 * $coeffs[2]);
            $result .= "\n";
            $result .= ((-1) * $coeffs[1] + sqrtMy($disc)) / (2 * $coeffs[2]);            
        }
    } else {
        $result .= "The polynomial degree is stricly greater than 2, I can't solve.";
    }
    echo $result;


}


// fonction sqrt bakhshali method
function sqrtMy($f)
{
   $i = 0; 
   while( ($i * $i) <= $f)
          $i++;
    $i--; 
    $d = $f - $i * $i; 
    $p = $d/(2*$i); 
    $a = $i + $p; 
    return $a-($p*$p)/(2*$a);
}  
?>