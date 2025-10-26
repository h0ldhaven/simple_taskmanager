<?php
require_once './db/config.php';

$errorMsg = $_GET['error'] ?? null;
if ($errorMsg) {
    switch ($errorMsg) {
        case 'empty_title':
            $displayMsg = "Le titre est obligatoire.";
            break;
        case 'unknown':
            $displayMsg = "Erreur inconnue.";
            break;
        default:
            $displayMsg = "Une erreur est survenue, merci de réessayer.";
    }
    echo "<div class='errorMsg'>{$displayMsg}</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']) ?? '';
    $description = trim($_POST['description']) ?? '';
    $isDone = 0;
    $dateNow = date('Y-m-d H:i:s');

    if (!isset($title)) {
        header("Location: create.php?error=empty_title");
        exit;
    }

    $createTask = $pdo->prepare("INSERT INTO tasks (title, description, is_done, created_at) VALUES (:title, :description, :is_done, :dateNow)");
    $createTask->bindParam(':title', $title);
    $createTask->bindParam(':description', $description);
    $createTask->bindParam(':is_done', $isDone);
    $createTask->bindParam(':dateNow', $dateNow);
    $createTask->execute();

    header("Location: index.php?success=created");
    exit;   

}
?>

<style>
    .task-form {
        background-color: #fff;
        padding: 25px 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: 20px auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
        font-family: Arial, sans-serif;
    }

    .task-form h2 {
        text-align: center;
        color: #333;
    }

    .task-form label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .task-form input,
    .task-form textarea {
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
        width: 100%;
        box-sizing: border-box;
        transition: border 0.2s;
    }

    .task-form input:focus,
    .task-form textarea:focus {
        border-color: #007bff;
        outline: none;
    }

    .task-form button {
        background-color: #007bff;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.2s;
    }

    .task-form button:hover {
        background-color: #0056b3;
    }

    @media (max-width: 500px) {
        .task-form {
            padding: 20px 15px;
        }

        .task-form button {
            font-size: 14px;
            padding: 10px;
        }
    }
</style>

<form actions="create.php" method="POST" class="task-form">
    <h2>Créer une nouvelle tâche</h2>

    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" placeholder="Titre de la tâche" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" placeholder="Détails de la tâche..."></textarea>

    <button type="submit">Créer la tâche</button>
</form>