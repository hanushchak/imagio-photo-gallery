<?php
/*
I used Sam Scott's file as a template and was given inspiration on how to make one script work on both csunix and locally.
*/
try {
    if($_SERVER["SERVER_NAME"] === "csunix.mohawkcollege.ca"){
        $dbh = new PDO(
            "mysql:host=localhost;dbname=",
            "",
            "");
    }
    else{
        $dbh = new PDO(
            "mysql:host=localhost;dbname=imagio",
            "root",
            "");
    }
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}

?>