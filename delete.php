<?php
require_once './db/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_id");
    exit;
}

$taskId = (int) $_GET['id'];

$fetch_task = $pdo->prepare("SELECT is_done FROM tasks WHERE id = :id");
$fetch_task->bindParam(':id', $taskId);
$fetch_task->execute();
$selected_task = $fetch_task->fetch(PDO::FETCH_ASSOC);

if (!$selected_task) {
    header("Location: index.php?error=not_found");
    exit;
}

$delete_task = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
$delete_task->bindParam(':id', $taskId);
$delete_task->execute();

header("Location: index.php?success=delete_succes");
exit;
?>