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

$newStatus = $task['is_done'] ? 0 : 1;

$updateStatus = $pdo->prepare("UPDATE tasks SET is_done = :is_done WHERE id = :id");
$updateStatus->bindParam(':is_done', $newStatus);
$updateStatus->bindParam(':id', $taskId);
$updateStatus->execute();

header("Location: index.php");
exit;
?>