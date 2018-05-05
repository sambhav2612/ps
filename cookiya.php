<?php

$cookie_name = "VisitorX";
$cookie_value = "test"; 

setcookie($cookie_name, $cookie_value, time() + (86400*30), "/");

if (count($_COOKIE) > 0) {
    echo "<script> consoole.log(\" Cookie $cookie_name is working. \"); </script>";
}

?>