<?php

    include "config.php";

    if(isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit;
    }

    $error = "";

    if(isset($_POST['login'])){
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM utenti WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Email o password errati";
        }
    }

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="icona.png">
</head>
<body>

<div class="bg">
    <div class="auth-box">
        <h1>Login</h1>

        <?php if($error): ?>
            <p class="msg error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" id="loginForm">
            <input type="email" name="email" id="loginEmail" placeholder="Email" required>
            <input type="password" name="password" id="loginPassword" placeholder="Password" required>
            <button type="submit" name="login">Accedi</button>
        </form>

        <a href="register.php">
            <button type="button" class="secondary">Registrati</button>
        </a>
    </div>
</div>

<script>

    document.getElementById("loginForm").addEventListener("submit", function(e){
        let email = document.getElementById("loginEmail").value.trim();
        let password = document.getElementById("loginPassword").value.trim();

        if(email === "" || password === ""){
            e.preventDefault();
            alert("Compila tutti i campi");
            return;
        }

        if(!email.includes("@") || !email.includes(".")){
            e.preventDefault();
            alert("Inserisci una email valida");
        }
    });

</script>

</body>
</html>