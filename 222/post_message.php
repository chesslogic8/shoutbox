<?php
header('Content-Type: application/json');

function getRandomColor($excludeColors = []) {
    $colors = [
        '#ffe4e1', '#e6e6fa', '#e0ffff', '#fff0f5', '#f0e68c', '#fafad2', '#d3d3d3', '#f5f5dc', '#f0ffff', '#f5f5f5',
        '#add8e6', '#ffc0cb', '#90ee90', '#ffb6c1', '#87cefa', '#b0e0e6', '#f0e68c', '#98fb98', '#87ceeb', '#afeeee',
    ];
    $availableColors = array_diff($colors, $excludeColors);
    return $availableColors[array_rand($availableColors)];
}

if (isset($_POST['message'])) {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $db = new SQLite3('shoutbox.db');

        // Get the last two colors from the database
        $result = $db->query("SELECT color FROM shoutbox_messages ORDER BY id DESC LIMIT 2");
        $lastTwoColors = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $lastTwoColors[] = $row['color'];
        }

        $color = getRandomColor($lastTwoColors);
        
        $stmt = $db->prepare("INSERT INTO shoutbox_messages (message, color) VALUES (:message, :color)");
        $stmt->bindValue(':message', $message, SQLITE3_TEXT);
        $stmt->bindValue(':color', $color, SQLITE3_TEXT);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
