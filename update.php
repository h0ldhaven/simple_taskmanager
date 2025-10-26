<?php
require_once './db/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_id");
    exit;
}

$taskId = isset($_GET['id']) ? (int) $_GET['id'] : null;

$fetch_task = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
$fetch_task->bindParam(':id', $taskId);
$fetch_task->execute();
$selected_task = $fetch_task->fetch(PDO::FETCH_ASSOC);

if (!$selected_task) {
    header("Location: update.php?error=not_found");
    exit;
}

$errorMsg = $_GET['error'] ?? null;
if ($errorMsg) {
    switch ($errorMsg) {
        case 'not_found':
            $displayMsg = "La tâche est introuvable ! (404)";
            break;
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
    $newDate = date('Y-m-d H:i:s');

    if (!isset($title)) {
        header("Location: update.php?error=empty_title");
        exit;
    }

    $createTask = $pdo->prepare("UPDATE tasks SET title = :title, description = :descr, updated_at = :ua WHERE id = :id");
    $createTask->bindParam(':title', $title);
    $createTask->bindParam(':descr', $description);
    $createTask->bindParam(':ua', $newDate);
    $createTask->bindParam(':id', $taskId);
    $createTask->execute();

    header("Location: index.php?success=updated");
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

    .btn-create {
        display: inline-block;
        background-color: #28a745; /* vert sympa */
        color: #fff;
        text-decoration: none;
        padding: 12px 25px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.2s, transform 0.1s;
        text-align: center;
    }

    .btn-create:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    .btn-create:active {
        transform: scale(0.98);
    }
</style>

<form actions="update.php?id=<?= $selected_task['id'] ?>" method="POST" class="task-form">
    <h2>Éditer une tâche</h2>

    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($selected_task['title']) ?>" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description"><?= htmlspecialchars(string: $selected_task['description']) ?></textarea>

    <button type="submit">Mettre à jour</button>
    <a href="index.php" class="btn-create">Annuler</a>
</form>