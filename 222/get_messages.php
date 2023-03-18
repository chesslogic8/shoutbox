<?php
header('Content-Type: application/json');

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$messagesPerPage = isset($_GET['messagesPerPage']) ? intval($_GET['messagesPerPage']) : 10;

$db = new SQLite3('shoutbox.db');

// Get the total number of messages
$result = $db->query("SELECT COUNT(*) as count FROM shoutbox_messages");
$count = $result->fetchArray(SQLITE3_ASSOC)['count'];
$totalPages = ceil($count / $messagesPerPage);

// Get the messages for the current page
$offset = ($page - 1) * $messagesPerPage;
$result = $db->query("SELECT * FROM shoutbox_messages ORDER BY id DESC LIMIT {$messagesPerPage} OFFSET {$offset}");

$messages = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $messages[] = [
        'id' => $row['id'],
        'message' => htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8'),
        'color' => $row['color'],
    ];
}

echo json_encode(['messages' => $messages, 'totalPages' => $totalPages]);
