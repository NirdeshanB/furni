<?php
$db='localhost';
$dbname='furni';
$user='root';
$pass='';

$conn=new mysqli($db,$user,$pass,$dbname);

if($conn->connect_error)
    { 
        die('Could not connect'.$conn->connect_error); 
    }