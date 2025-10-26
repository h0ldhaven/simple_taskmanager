<?php
require_once './db/config.php';

$fetch_tasks = $pdo->query("SELECT * FROM TASKS ORDER BY created_at ASC");
$task_list = $fetch_tasks->fetchAll(PDO::FETCH_ASSOC);

$errorMsg = $_GET['error'] ?? null;
$successMsg = $_GET['success'] ?? null;
if ($errorMsg) {
    switch ($errorMsg) {
        case 'not_found':
            $displayMsg = "La tâche demandée est introuvable !";
            break;
        case 'invalid_id':
            $displayMsg = "ID de tâche invalide.";
            break;
        case 'db_error':
            $displayMsg = "Erreur lors de la connexion à la base de données.";
            break;
        default:
            $displayMsg = "Une erreur est survenue, merci de réessayer.";
    }
    echo "<div class='errorMsg'>{$displayMsg}</div>";
}

if ($successMsg) {
    switch ($successMsg) {
        case 'delete_succes':
            $displayMsg = "Tâche supprimée avec succès !";
            break;
        case 'created':
            $displayMsg = "Tâche créée avec succès !";
            break;
        case 'updated':
            $displayMsg = "Tâche mise à jour avec succès !";
            break;
        default:
            $displayMsg = "OK.";
    }
    echo "<div class='successMsg'>{$displayMsg}</div>";
}

$ip = $_SERVER['REMOTE_ADDR'];
echo "Bonjour : " . $ip . ",<br>Vous êtes sur la page : " . $_SERVER['SCRIPT_NAME'];
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        padding: 20px;
    }

    .errorMsg {
        background-color: #f44336; /* rouge vif */
        color: white;
        text-align: center;
        padding: 12px 20px;
        margin: 20px auto;
        border-radius: 8px;
        max-width: 600px; /* pas trop large */
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        font-weight: bold;
        font-size: 16px;
    }

    .successMsg {
        background-color: #29e433ff; /* rouge vif */
        color: white;
        text-align: center;
        padding: 12px 20px;
        margin: 20px auto;
        border-radius: 8px;
        max-width: 600px; /* pas trop large */
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        font-weight: bold;
        font-size: 16px;
    }

    /* Responsive : s'adapte sur petits écrans */
    @media (max-width: 500px) {
        .errorMsg {
            font-size: 14px;
            padding: 10px 15px;
            max-width: 90%;
        }
        .successMsg {
            font-size: 14px;
            padding: 10px 15px;
            max-width: 90%;
        }
    }

    h1 { color: #333; }

    ul { list-style: none; padding: 0; }

    li.task-card {
        display: flex;
        flex-direction: column;
        background-color: #fff;
        padding: 50px 20px 15px 20px; 
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        position: relative;
    }

    li.task-card .content.done-task {
        text-decoration: line-through;
        color: #888;
    }

    li.task-card p { margin: 5px 0; }

    li.task-card .status {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 12px;
        font-weight: bold;
        padding: 10px 15px;
        border-radius: 4px;
        color: #fff;
        white-space: nowrap;
        max-width: 50%;
        text-align: center;
        box-sizing: border-box;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    li.task-card .status.todo { background-color: #ffc107; color: #000; }
    li.task-card .status.done { background-color: #28a745; }

    .task-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 10px;
        justify-content: flex-end;
    }

    .task-actions a {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        text-decoration: none;
        font-size: 14px;
        padding: 4px 8px;
        border-radius: 4px;
        color: #fff;
        white-space: nowrap;
        box-sizing: border-box;
        position: relative; /* nécessaire pour ::before */
    }

    /* Couleurs des boutons */
    .task-actions a.done { background-color: #28a745; }
    .task-actions a.cancel { background-color: #ffc107; color: #000; }
    .task-actions a.edit { background-color: #007bff; }
    .task-actions a.delete { background-color: #dc3545; }

    /* Icônes pour tous */
    .task-actions a.done::before { content: "✔"; }
    .task-actions a.cancel::before { content: "↩︎"; }
    .task-actions a.edit::before { content: "✏"; }
    .task-actions a.delete::before { content: "🗑"; }

    /* Responsive : petit écran */
    @media (max-width: 500px) {
        .task-actions a {
            font-size: 0;      /* masque le texte */
            width: 32px;
            height: 32px;
            padding: 0;
        }

        /* icône centrée dans le bouton */
        .task-actions a::before {
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            line-height: 1; /* pour éviter décalage vertical */
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
    }

    .btn-create:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    .btn-create:active {
        transform: scale(0.98);
    }

    .btn-refresh {
        display: inline-block;
        background-color: #2889a7ff; /* vert sympa */
        color: #fff;
        text-decoration: none;
        padding: 12px 25px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.2s, transform 0.1s;
    }

    .btn-refresh:hover {
        background-color: #217388ff;
        transform: scale(1.05);
    }

    .btn-refresh:active {
        transform: scale(0.98);
    }
</style>

<hr />

<a href="create.php" class="btn-create">Créer une tâche</a>
<a href="index.php" class="btn-refresh">Actualiser la page</a>

<hr />

<?php 
    if (count($task_list) === 0){
        echo "Il n'y a pas de tâche pour le moment.";
    } else { ?>
        <h1 style="text-align:center;">Liste des tâches</h1>
<?php
    } 

    foreach($task_list as $task):
?>
    <ul>
        <li class="task-card">
            <!-- Status responsive -->
            <span class="status <?= $task['is_done'] ? 'done' : 'todo' ?>">
                <?= $task['is_done'] ? 'Terminée' : 'À faire' ?>
            </span>

            <!-- Contenu barré seulement si terminé -->
            <div class="content <?= $task['is_done'] ? 'done-task' : '' ?>">
                <strong><?= htmlspecialchars($task['title']) ?></strong>
                <?php if (!empty($task['description'])): ?>
                    <p><?= nl2br(htmlspecialchars($task['description']))?></p>
                <?php endif ?>
                <p>Créée le : <?= date('d-m-Y à H:i:s', strtotime($task['created_at'])) ?></p>
                <?php if (!empty($task['updated_at'])): ?>
                    <p>Mis à jour le : <?= date('d-m-Y à H:i:s', strtotime($task['updated_at'])) ?></p>
                <?php endif ?>
            </div>

            <!-- Actions en bas à droite -->
            <div class="task-actions">
                <?php if (!$task['is_done']): ?>
                    <a href="done.php?id=<?= $task['id'] ?>" class="done">Terminer</a>
                    <a href="update.php?id=<?= $task['id'] ?>" class="edit">Modifier</a>
                    <a href="delete.php?id=<?= $task['id'] ?>" class="delete">Supprimer</a>
                <?php else: ?>
                    <a href="undone.php?id=<?= $task['id'] ?>" class="cancel">Réouvrir</a>
                    <a href="delete.php?id=<?= $task['id'] ?>" class="delete">Supprimer</a>
                <?php endif; ?>
            </div>
        </li>
    </ul>
<?php endforeach; ?>
<hr />