<?php

function clean_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function countDigit($inputNumber){
    $digits = str_split($inputNumber);
    $countedDigit = count($digits);
    return $countedDigit;
}
    