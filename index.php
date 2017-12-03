<?php

session_start();
if(isset($_SESSION['userId'])) {
    header("location: main.php");
} else {
    header("location: welcome.html");
}

die;