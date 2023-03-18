<?php
function createTableIfNotExists($db)
{
    $query = "CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY AUTOINCREMENT, message TEXT NOT NULL)";
    $db->exec($query);
}

function insertMessage($db, $message)
{
    $stmt = $db->prepare("INSERT INTO messages (message) VALUES (:message)");
    $stmt->bindValue(':message', $message, SQLITE3_TEXT);
    $stmt->execute();
}

function getMessages($db)
{
    $results = $db->query("SELECT * FROM messages ORDER BY id DESC LIMIT 100");
    $messages = [];
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $messages[] = $row;
    }
    return $messages;
}
