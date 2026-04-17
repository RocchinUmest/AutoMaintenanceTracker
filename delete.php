<?php

    include "config.php";

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    }

    if(isset($_GET['id'])){
        $stmt = $pdo->prepare("DELETE FROM manutenzioni WHERE id=? AND user_id=?");
        $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    }

    header("Location: index.php");
    exit;

?>