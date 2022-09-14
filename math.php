<?php

$NUMBERS = "0123456789.";

$operations = [
    '*' => function ($string) {
        $temp = explode("*", $string);
        return strval((float)$temp[0] * (float)$temp[1]);
    },
    '+' => function ($string) {
        $temp = explode("+", $string);
        return strval((float)$temp[0] + (float)$temp[1]);
    },
    '-' => function ($string) {
        $temp = explode("-", $string);
        return $temp[0] - $temp[1];
    },
    '/' => function ($string) {
        $temp = explode("/", $string);
        if ($temp[1] == 0) {
            throw new Exception("Division by zero");
        }
        return strval($temp[0] / $temp[1]);
    }
];

# Just a few tests
function tests(): void
{
    assert(compute("50+2") == 52);
    assert(compute("50*56/100*50") == "1400");
    assert(compute("50+2*3") == "56");
    assert(compute("5*(20+3-(9+2)*2)+3") == 8);
    assert(compute("5*9+10/2-3*2-2") == "42");
    assert(compute("10+8*9-6/2+3-5*4") == "62");
}

#Main function, calling all validating functions
function compute($string): array|string|null
{
    if (validate_numbers($string) && brackets($string) && validate_operators($string)) {
        $string = str_replace(',', '.', $string);
        $string = str_replace(' ', '', $string);
        $string = preg_replace('[^0-9\.\+\-\*\/\(\)]', '', $string);
        try {
            return separate($string);
        } catch (Exception $e) {
            return "Division by zero";
        }
    }
    return "";
}

# Handles separation of individual mathematical operations in right order: parenthesis, multiplication and division, addition and subtraction
function separate($string): array|string|null
{
    while (strpos($string, "*") || strpos($string, "/") || strpos($string, "+") || strpos($string, "-")) {

        while (strpos($string, "(") || strpos($string, ")")) {
            $left_bracket = strpos_all($string, "(");
            if (count($left_bracket) > 0) {
                $temp = strpos_all($string, ")");
                $right_bracket = end($temp);
                $temp1 = substr($string, $left_bracket[0], $right_bracket - $left_bracket[0] + 1);
                $string = str_replace($temp1, evaluate($temp1), $string);
            }
        }
        while (strpos($string, "*") || strpos($string, "/")) {
            $temp1 = strpos($string, "*");
            $temp2 = strpos($string, "/");
            if ($temp1 !== false && $temp2 !== false) {
                if ($temp1 < $temp2) {
                    $string = get_substring_around_operator(string: $string, operator: "*");
                } else {
                    $string = get_substring_around_operator(string: $string, operator: "/");
                }
            } elseif ($temp1 !== false) {
                $string = get_substring_around_operator(string: $string, operator: "*");
            } elseif ($temp2 !== false) {
                $string = get_substring_around_operator(string: $string, operator: "/");
            }
        }
        while (strpos($string, "+") || strpos($string, "-")) {
            $temp1 = strpos($string, "+");
            $temp2 = strpos($string, "-");
            if ($temp1 !== false && $temp2 !== false) {
                if ($temp1 < $temp2) {
                    $string = get_substring_around_operator(string: $string, operator: "+");
                } else {
                    $string = get_substring_around_operator(string: $string, operator: "-");
                }
            } elseif ($temp1 !== false) {
                $string = get_substring_around_operator(string: $string, operator: "+");
            } elseif ($temp2 !== false) {
                $string = get_substring_around_operator(string: $string, operator: "-");
            }
        }
    }
    return $string;
}

# Returns a substring around the given operator
function get_substring_around_operator($string, $operator): string|array|null
{
    global $operations;
    $temp = strpos_all($string, $operator);
    $operator_pos = reset($temp);

    if ($operator_pos !== FALSE) {
        $last = get_right_substring($string, $operator_pos);
        $first = get_left_substring($string, $operator_pos);
        $temp = substr($string, $first, $last - $first);
        return str_replace($temp, $operations[$operator]($temp), $string);
    }
    return $string;
}

# Returns a left substring from a given operator
function get_left_substring($string, $operator_pos): int
{
    global $NUMBERS;
    $first = 0;
    for ($x = $operator_pos - 1; $x > 0; $x--) {
        if (!(str_contains($NUMBERS, str_split($string)[$x]))) {
            $first = $x + 1;
            break;
        }
    }
    return $first;
}

# Returns a right substring from a given operator
function get_right_substring($string, $operator_pos): int
{
    global $NUMBERS;
    $last = strlen($string);
    for ($x = $operator_pos + 1; $x < strlen($string); $x++) {
        if (!(str_contains($NUMBERS, str_split($string)[$x]))) {
            $last = $x;
            break;
        }
    }
    return $last;
}

# Handling parenthesis, recursively calling nested parenthesis
function evaluate($string): string|array|null
{
    if (strlen($string) > 0 && str_contains($string, ")") && str_contains($string, "(")) {
        $string = substr($string, 1, -1);
        return separate($string);
    }
    return $string;
}

# Returns true if all parenthesis are valid
function brackets($string): bool
{
    $i = 0;
    foreach (str_split($string) as $char) {
        if (str_contains($char, "(")) {
            $i++;
        } elseif (str_contains($char, ")")) {
            $i--;
        }
        if ($i < 0) {
            return FALSE;
        }
    }
    if ($i === 0) {
        return TRUE;
    }
    return FALSE;
}

# Returns true if all characters are numbers or operators
function validate_numbers($string)
{
    global $NUMBERS;
    foreach (str_split($string) as $char) {
        if (!(str_contains($NUMBERS . "/*-+", $char))) {
            return false;
        }
    }
    return true;
}

# Looking for following operators
# Returns TRUE if is every operator surrounded by number, otherwise FALSE
function validate_operators($string): bool
{
    $last = str_split($string)[0];
    foreach (str_split($string) as $char) {
        if (str_contains("/*-+", $last) && str_contains("/*-+", $char)) {
            return false;
        }
        $last = $char;
    }
    return true;
}

# Returns array of positions of needle in give haystack
function strpos_all($haystack, $needle): array
{
    $positions = array();
    $pos_last = 0;
    while (($pos_last = strpos($haystack, $needle, $pos_last)) !== false) {
        $positions[] = $pos_last;
        $pos_last = $pos_last + strlen($needle);
    }
    return $positions;
}

