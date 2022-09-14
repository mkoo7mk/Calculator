<?php
session_start();

?>
<html lang="en">
<!DOCTYPE html>
<htmllang
="en">
<head>
    <title>Calculator</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
require("math.php");

$_SESSION['last_operator'] = '';
$_SESSION['last_number']   = '';

function editInput(): void
{

    $out = "";

    if (isset($_GET["display"])) {
        if (strlen($_GET["display"]) === 0) {
            $out .= "";
            # TODO can be rewritten
        } else {
            $out .= $_GET["display"];
        }
    }

    for ($x = 0; $x < 10; $x++) {
        if (isset($_GET["b$x"])) {
            $out .= $_GET["b$x"];
        }
    }
    for ($x = 1; $x < 5; $x++) {
        if (isset($_GET["op$x"])) {
            $out .= $_GET["op$x"];
            $_SESSION["last_number"] = $_GET["op$x"];
        }
    }
    if (isset($_GET["leftbrace"])) {
        $out .= $_GET["leftbrace"];
    } else if (isset($_GET["rightbrace"])) {
        $out .= $_GET["rightbrace"];
    } else if (isset($_GET["dot"])) {
        $out .= $_GET["dot"];
    } else if (isset($_GET["submit"])) {
        $out = compute($out);
    }
    echo $out;
}

?>

<div class="container">
    <form method="get">
        <div class="display">
            <label>
                <input id="display" type="text" placeholder="0" name="display" class="input" autofocus value="<?php editInput() ?>">
                <script src="script.js"></script>
                <input type="submit" class="button submit" name="submit" value="=" tabindex="0"/>

            </label>
        </div>
        <div>
            <div class="row">
                <input type="submit" class="parentheses" name="leftbrace" value="("/>
                <input type="submit" class="parentheses" name="rightbrace" value=")"/>
            </div>
        </div>
        <div class="row">
            <div class="row">
                <input type="submit" class="button" name="b7" value="7"/>
                <input type="submit" class="button" name="b8" value="8"/>
                <input type="submit" class="button" name="b9" value="9"/>
                <input type="submit" class="button" name="op1" value="/"/>
            </div>
        </div>
        <div class="row">
            <input type="submit" class="button" name="b4" value="4"/>
            <input type="submit" class="button" name="b5" value="5"/>
            <input type="submit" class="button" name="b6" value="6"/>
            <input type="submit" class="button" name="op2" value="*"/>
        </div>
        <div class="row">
            <input type="submit" class="button" name="b1" value="1"/>
            <input type="submit" class="button" name="b2" value="2"/>
            <input type="submit" class="button" name="b3" value="3"/>
            <input type="submit" class="button" name="op3" value="-"/>
        </div>
        <div class="row">
            <input type="submit" class="button" name="b0" value="0"/>
            <input type="submit" class="button" name="dot" value="."/>
            <input type="submit" class="button submit" name="submit" value="="/>
            <input type="submit" class="button" name="op4" value="+"/>
        </div>
</div>
</body>
</html>