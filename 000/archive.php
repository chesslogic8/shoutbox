<?php
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");

include 'db.php';

$comments_per_page = 100;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $comments_per_page;

try {
    $db = new SQLite3('shoutbox.db');
    createTableIfNotExists($db);

    $stmt = $db->prepare("SELECT * FROM messages ORDER BY id ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $comments_per_page, SQLITE3_INTEGER);
    $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
    $results = $stmt->execute();

    $messages = [];
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {$messages[] = $row;
    }

    $total_comments = $db->querySingle("SELECT COUNT(*) FROM messages");
    $total_pages = ceil($total_comments / $comments_per_page);

} catch (Exception $e) {
    // Handle error, e.g., log the error message
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self';">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoutbox Archive</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Shoutbox Archive</h1>

    <div id="shoutbox">
        <?php foreach ($messages as $message): ?>
            <p><?php echo $message['message']; ?></p>
        <?php endforeach; ?>
    </div>

    <div id="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="archive.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>
</body>
</html>
