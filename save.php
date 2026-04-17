<?php

    include "config.php";

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    }

    if(isset($_POST['data'], $_POST['km'])){
        $stmt = $pdo->prepare("INSERT INTO manutenzioni(user_id, data, km, descrizione) VALUES(?,?,?,?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $_POST['data'],
            $_POST['km'],
            $_POST['descrizione']
        ]);
    }

    header("Location: index.php");
    exit;

?>