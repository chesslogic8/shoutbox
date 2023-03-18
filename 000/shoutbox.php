
<?php
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");

include 'db.php';

try {
    $db = new SQLite3('shoutbox.db');
    createTableIfNotExists($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
        insertMessage($db, $message);
    }

    $messages = getMessages($db);
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
    <title>Shoutbox</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Shoutbox</h1>

    <div id="shoutbox">
        <?php foreach ($messages as $message): ?>
            <p><?php echo $message['message']; ?></p>
        <?php endforeach; ?>
    </div>

    <form action="shoutbox.php" method="post">
        <textarea name="message" placeholder="Type your message..." required></textarea>
        <input type="submit" value="Send">
    </form>
</div>
</body>
</html>
