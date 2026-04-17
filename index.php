<?php

    include "config.php";

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

  
    if(isset($_POST['update_profile'])){
        $stmt = $pdo->prepare("SELECT foto FROM utenti WHERE id=?");
        $stmt->execute([$user_id]);
        $oldUser = $stmt->fetch();

        $foto = $oldUser['foto'];

        if(!empty($_FILES['foto']['name'])){
            if(!is_dir("uploads")){
                mkdir("uploads", 0777, true);
            }

            $foto = "uploads/" . time() . "_" . basename($_FILES['foto']['name']);
            move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
        }

        $stmt = $pdo->prepare("UPDATE utenti SET nome=?, cognome=?, foto=? WHERE id=?");
        $stmt->execute([
            $_POST['nome'],
            $_POST['cognome'],
            $foto,
            $user_id
        ]);

        header("Location: index.php");
        exit;
    }


    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE id=?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();


    $stmt = $pdo->prepare("SELECT COUNT(*) FROM manutenzioni WHERE user_id=?");
    $stmt->execute([$user_id]);
    $count = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT km FROM manutenzioni WHERE user_id=? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$user_id]);
    $km = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT data FROM manutenzioni WHERE user_id=? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$user_id]);
    $data = $stmt->fetchColumn();


    $stmt = $pdo->prepare("SELECT * FROM manutenzioni WHERE user_id=? ORDER BY id DESC");
    $stmt->execute([$user_id]);
    $manutenzioni = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Maintenance Tracket</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="icona.png">
</head>
<body>

<div class="hero">
    <div class="navbar">
        <div class="logo">
            <img src="logo.png" alt="logo">
        </div>

        <div class="user-area">
            <img src="<?= htmlspecialchars($user['foto']) ?>" class="avatar" alt="profilo" onclick="toggleProfile(event)">

            <a href="logout.php" class="logout">
                <img src="logout.png" alt="logout">
            </a>

            <div id="profileBox" class="profile-menu">
                <form method="POST" enctype="multipart/form-data">
                    <h3>Profilo</h3>
                    <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>
                    <input type="text" name="cognome" value="<?= htmlspecialchars($user['cognome']) ?>" required>
                    <input type="file" name="foto">
                    <button type="submit" name="update_profile">Salva profilo</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="stats">
            <div class="card">
                <div class="gold"><?= $km ? htmlspecialchars($km) : 0 ?></div>
                <p>KM ULTIMA MANUTENZIONE</p>
            </div>

            <div class="card">
                <div class="gold"><?= htmlspecialchars($count) ?></div>
                <p>NUMERO MANUTENZIONI</p>
            </div>

            <div class="card">
                <div class="gold"><?= $data ? htmlspecialchars($data) : '-' ?></div>
                <p>DATA ULTIMA MANUTENZIONE</p>
            </div>
        </div>

        <button class="add" onclick="openForm()">+ AGGIUNGI MANUTENZIONE</button>

        <h1>MANUTENZIONI EFFETTUATE</h1>

        <?php foreach($manutenzioni as $row): ?>
            <div class="item">
                <div class="item-info">
                    <b><?= htmlspecialchars($row['data']) ?></b><br>
                    <?= htmlspecialchars($row['km']) ?> km<br>
                    <?= htmlspecialchars($row['descrizione']) ?>
                </div>

                <div class="actions">
                    <button type="button" class="view"
                        onclick="openView(
                            '<?= htmlspecialchars($row['data'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($row['km'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($row['descrizione'], ENT_QUOTES) ?>'
                        )">👁</button>

                    <a href="edit.php?id=<?= $row['id'] ?>" class="edit">✏</a>

                    <button type="button" class="delete"
                        onclick="openDelete('delete.php?id=<?= $row['id'] ?>')">🗑</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div id="formModal" class="modal">
    <div class="modal-box">
        <button class="close" type="button" onclick="closeForm()">×</button>
        <h2>Nuova manutenzione</h2>

        <form method="POST" action="save.php" id="maintenanceForm">
            <input type="date" name="data" id="mData" required>
            <input type="number" name="km" id="mKm" placeholder="KM" required>
            <textarea name="descrizione" id="mDescrizione" placeholder="Descrizione"></textarea>
            <button type="submit" class="save-btn">Salva manutenzione</button>
        </form>
    </div>
</div>


<div id="viewModal" class="modal">
    <div class="modal-box small">
        <button class="close" type="button" onclick="closeView()">×</button>
        <h2>Dettagli manutenzione</h2>

        <div class="detail">
            <p><span>Data:</span> <strong id="viewData"></strong></p>
            <p><span>KM:</span> <strong id="viewKm"></strong></p>
            <p><span>Descrizione:</span></p>
            <div class="desc-box" id="viewDesc"></div>
        </div>
    </div>
</div>


<div id="deleteModal" class="modal">
    <div class="modal-box small">
        <button class="close" type="button" onclick="closeDelete()">×</button>
        <h2>Conferma eliminazione</h2>
        <p class="delete-text">Vuoi davvero eliminare questa manutenzione?</p>

        <div class="delete-actions">
            <button type="button" class="secondary-btn" onclick="closeDelete()">Annulla</button>
            <a href="#" id="deleteLink" class="danger-btn">Elimina</a>
        </div>
    </div>
</div>

<script>
function openForm(){
    document.getElementById("formModal").style.display = "flex";
}

function closeForm(){
    document.getElementById("formModal").style.display = "none";
}

function toggleProfile(event){
    event.stopPropagation();
    const box = document.getElementById("profileBox");
    box.style.display = box.style.display === "block" ? "none" : "block";
}

document.addEventListener("click", function(e){
    const profileBox = document.getElementById("profileBox");
    const userArea = document.querySelector(".user-area");

    if(!userArea.contains(e.target)){
        profileBox.style.display = "none";
    }
});

function openView(data, km, descrizione){
    document.getElementById("viewData").textContent = data;
    document.getElementById("viewKm").textContent = km + " km";
    document.getElementById("viewDesc").textContent = descrizione || "Nessuna descrizione";
    document.getElementById("viewModal").style.display = "flex";
}

function closeView(){
    document.getElementById("viewModal").style.display = "none";
}

function openDelete(link){
    document.getElementById("deleteLink").href = link;
    document.getElementById("deleteModal").style.display = "flex";
}

function closeDelete(){
    document.getElementById("deleteModal").style.display = "none";
}

document.getElementById("maintenanceForm").addEventListener("submit", function(e){
    let data = document.getElementById("mData").value.trim();
    let km = document.getElementById("mKm").value.trim();


    if(data === "" || km === ""){
        e.preventDefault();
        alert("Compila tutti i campi obbligatori");
    }
});

window.onclick = function(e){
    const formModal = document.getElementById("formModal");
    const viewModal = document.getElementById("viewModal");
    const deleteModal = document.getElementById("deleteModal");

    if(e.target === formModal) closeForm();
    if(e.target === viewModal) closeView();
    if(e.target === deleteModal) closeDelete();
}
</script>

</body>
</html>