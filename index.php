<?php
session_start();

if(isset($_SESSION['login'])){
    header("location: dashboard.php");
} else {
    header("location: login.php");
}
?>