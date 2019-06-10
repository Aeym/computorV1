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
    // echo $tmpStr ."\n";
    $tmpArr = explode('=', $tmpStr);
    $left = preg_split("/X\^/", $tmpArr[0], 0, PREG_SPLIT_NO_EMPTY);
    $right = preg_split("/X\^/", $tmpArr[1], 0, PREG_SPLIT_NO_EMPTY);

    $leftCoeff = array('pow0' => 0, 'pow1' => 0, 'pow2' => 0);
    $rightCoeff = array('pow0' => 0, 'pow1' => 0, 'pow2' => 0);

    // echo "\n" . $leftCoeff['pow0'] . "\n";
    $i = 0;
    while ($i < (count($left) - 1)) {
        if ($left[$i + 1][0] == '0') {
            $leftCoeff["pow0"] = substr($left[$i], 0, strlen($left[$i]) - 1);
        } else if ($left[$i + 1][0] == '1') {
            $leftCoeff["pow1"] = substr($left[$i], 1, strlen($left[$i]) - 2);
        } else if ($left[$i + 1][0] == '2') {
            $leftCoeff["pow2"] = substr($left[$i], 1, strlen($left[$i]) - 2);
        }
        $i++;
    }

    $i = 0;
    while ($i < (count($right) - 1)) {
        if ($right[$i + 1][0] == '0') {
            $rightCoeff["pow0"] = substr($right[$i], 0, strlen($right[$i]) - 1);
        } else if ($right[$i + 1][0] == '1') {
            $rightCoeff["pow1"] = substr($right[$i], 1, strlen($right[$i]) - 2);
        } else if ($right[$i + 1][0] == '2') {
            $rightCoeff["pow2"] = substr($right[$i], 1, strlen($right[$i]) - 2);
        }
        $i++;
    }

    echo   "left coeff :\n";
    print_r($left);

    
    echo "leftCoeff : \n";
    print_r($leftCoeff);

    echo   "right coeff :\n";
    print_r($right);

    echo "rightCoeef : \n";
    print_r($rightCoeff);
    reduceEqu($leftCoeff, $rightCoeff);
}

function reduceEqu($leftCoeff, $rightCoeff) {
    // print_r($leftCoeff);
    $coeff0 = floatval($leftCoeff['pow0']) - floatval($rightCoeff['pow0']);
    echo $coeff0 . "\n";
    // echo intval($leftCoeff['pow0']) . "\n";

    $coeff1 = floatval($leftCoeff['pow1']) - floatval($rightCoeff['pow1']);
    echo $coeff1 . "\n";

    $coeff2 = floatval($leftCoeff['pow2']) - floatval($rightCoeff['pow2']);
    echo $coeff2 . "\n\n";

    // echo "Reduced form : " . intval($leftCoeff[0]);
    $reduceStr = "Reduced form : ";
    $polyDegree = 0;
    $p = 0;
    if ($coeff0 != 0) {
        $polyDegree = 0;
        if ($coeff0 < 0) {
            $reduceStr .= " -";
            $coeff0 *= -1;
            $p = 1;
        }
        $reduceStr .= $coeff0 . " * X^0";
        if ($p == 1) {
            $coeff0 *= -1;
            $p = 0;
        }
    }
    if ($coeff1 != 0) {
        $polyDegree = 1;
        if ($coeff1 < 0) {
            $reduceStr .= " - ";
            $coeff1 *= -1;
            $p = 1;
        } else {
            $reduceStr .= " + ";
        }
        $reduceStr .= $coeff1 . " * X^1";
        if ($p == 1) {
            $coeff1 *= -1;
            $p = 0;
        }
    }
    if ($coeff2 != 0) {
        $polyDegree = 2;
        if ($coeff2 < 0) {
            $reduceStr .= " - ";
            $coeff2 *= -1;
            $p = 1;
        } else {
            $reduceStr .= " + ";
        }
        $reduceStr .= $coeff2 . " * X^2";
        if ($p == 1) {
            $coeff2 *= -1;
            $p = 0;
        }
    }
    $reduceStr .= " = 0\n";
    if ($coeff0 == 0 && $coeff1 == 0 && $coeff2 == 0) {
        echo "Tous les nombres réels sont solutions.\n";
    } else {
        echo $reduceStr;
    }
    echo "Polynomial degree: " . $polyDegree . "\n";

    solution($coeff0, $coeff1, $coeff2, $polyDegree);
}

function solution($coeff0, $coeff1, $coeff2, $polyDegree) {
    $result = '';
    $sol1 = 0;
    $sol2 = 0;
    if ($polyDegree == 0) {
        if ($coeff0 == 0) {
            $result .= "Il n'y a pas de solutions.\n";
        } else {
            $result .= "Tous les nombres réels sont solutions.\n";
        }
    } else if ($polyDegree == 1) {
        $sol1 = ($coeff0 / $coeff1) * (-1);
        // echo $sol1;
    } else if ($polyDegree == 2) {
        echo "on est dans poly = 2\n";
        echo $coeff1 . " * " . $coeff1 . "-4.0 * " . $coeff2 . " * " . $coeff0;
        echo "\n";
        $disc = $coeff1 * $coeff1 - 4.0 * $coeff2 * $coeff0;
        echo $disc . "\n";
        if ($disc < 0.0) {
            echo "Discriminant is strictly negative, the two complexes solutions are:\n";
            $result .= "complexe a faire";
        } else if ($disc == 0.0) {
            echo "Discriminant is 0, the unique solution is:\n";
            echo (-1) * $coeff1 / (2 * $coeff0);
        } else if ($disc > 0.0) {
            echo "Discriminant is strictly positive, the two solutions are:\n";
            echo ((-1) * $coeff1 - sqrt($disc)) / (2 * $coeff2);
            echo "\n";
            echo ((-1) * $coeff1 + sqrt($disc)) / (2 * $coeff2);            
        }
    } else {
        $result .= "The polynomial degree is stricly greater than 2, I can't solve.";
    }


}


// function racine($pow) {
//     $i = 1;
//     while ($i < $pow) {
//         if (($i * $i) == $pow) {
//             return $i;
//         }
//         $i++;
//     }
// }


// function racine($pow) {
//     echo "?????????";
//     echo $pow;
//     if ($pow == 0.0) {
//         return 0;
//     } else {
//         $tmp1 = $pow / 2.0;
//         while(1) {
//             $tmp2 = ($tmp1 + $pow / $tmp1) / 2;
//             if (abs($tmp2 - $tmp1) < 0.00001) {
//                 return $tmp2;
//             } else {
//                 $tmp1 = $tmp2;
//             }
//         }
//     }
// }
?>