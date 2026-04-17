<?php

    include "config.php";

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    if(!isset($_GET['id'])){
        header("Location: index.php");
        exit;
    }

    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM manutenzioni WHERE id=? AND user_id=?");
    $stmt->execute([$id, $user_id]);
    $data = $stmt->fetch();

    if(!$data){
        header("Location: index.php");
        exit;
    }

    if(isset($_POST['save'])){
        $stmt = $pdo->prepare("UPDATE manutenzioni SET data=?, km=?, descrizione=? WHERE id=? AND user_id=?");
        $stmt->execute([
            $_POST['data'],
            $_POST['km'],
            $_POST['descrizione'],
            $id,
            $user_id
        ]);

        header("Location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica manutenzione</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="icona.png">
</head>
<body>

<div class="bg">
    <div class="auth-box">
        <h1>Modifica manutenzione</h1>

        <form method="POST" id="editForm">
            <input type="date" name="data" id="eData" value="<?= htmlspecialchars($data['data']) ?>" required>
            <input type="number" name="km" id="eKm" value="<?= htmlspecialchars($data['km']) ?>" required>
            <input type="text" name="descrizione" value="<?= htmlspecialchars($data['descrizione']) ?>" placeholder="Descrizione">
            <button type="submit" name="save">Salva modifiche</button>
        </form>

        <a href="index.php">
            <button type="button" class="secondary">Torna indietro</button>
        </a>
    </div>
</div>

<script>
    document.getElementById("editForm").addEventListener("submit", function(e){
        let data = document.getElementById("eData").value.trim();
        let km = document.getElementById("eKm").value.trim();

        if(data === "" || km === ""){
            e.preventDefault();
            alert("Compila tutti i campi obbligatori");
        }
    });
</script>

</body>
</html>