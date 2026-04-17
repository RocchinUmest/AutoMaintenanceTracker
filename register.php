<?php

    include "config.php";

    if(isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit;
    }

    $error = "";
    $success = "";

    if(isset($_POST['register'])){
        $nome = trim($_POST['nome']);
        $cognome = trim($_POST['cognome']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT id FROM utenti WHERE email=?");
        $stmt->execute([$email]);

        if($stmt->fetch()){
            $error = "Email già registrata";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $foto = "logo.png";

            $stmt = $pdo->prepare("INSERT INTO utenti(nome, cognome, email, password, foto) VALUES(?,?,?,?,?)");
            $stmt->execute([$nome, $cognome, $email, $hash, $foto]);

            $success = "Registrazione completata. Ora puoi fare login.";
        }
    }

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="icona.png">
</head>
<body>

<div class="bg">
    <div class="auth-box">
        <h1>Registrazione</h1>

        <?php if($error): ?>
            <p class="msg error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if($success): ?>
            <p class="msg success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" id="registerForm">
            <input type="text" name="nome" id="nome" placeholder="Nome" required>
            <input type="text" name="cognome" id="cognome" placeholder="Cognome" required>
            <input type="email" name="email" id="registerEmail" placeholder="Email" required>
            <input type="password" name="password" id="registerPassword" placeholder="Password" required>
            <button type="submit" name="register">Registrati</button>
        </form>

        <a href="login.php">
            <button type="button" class="secondary">Torna al login</button>
        </a>
    </div>
</div>

<script>

    document.getElementById("registerForm").addEventListener("submit", function(e){
        let nome = document.getElementById("nome").value.trim();
        let cognome = document.getElementById("cognome").value.trim();
        let email = document.getElementById("registerEmail").value.trim();
        let password = document.getElementById("registerPassword").value.trim();

        if(nome === "" || cognome === "" || email === "" || password === ""){
            e.preventDefault();
            alert("Compila tutti i campi");
            return;
        }

        if(!email.includes("@") || !email.includes(".")){
            e.preventDefault();
            alert("Inserisci una email valida");
            return;
        }

        if(password.length < 4){
            e.preventDefault();
            alert("La password deve avere almeno 4 caratteri");
        }
    });

</script>

</body>
</html>