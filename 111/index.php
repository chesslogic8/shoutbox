<!DOCTYPE html>
<html>
<head>
    <title>Shoutbox</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="shoutbox">
        <div class="messages">
            <?php
            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $message = $_POST['message'];

                // Append new message to text file
                $filename = "messages.txt";
                $file = fopen($filename, "a");
                fwrite($file, $message . "\n");
                fclose($file);
            }

            // Get messages from text file
            $filename = "messages.txt";
            $file = fopen($filename, "r");
            $messages = array_reverse(array_map('trim', file($filename)));
            fclose($file);

            // Display messages
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $perPage = 10;
            $start = ($page - 1) * $perPage;
            $end = $start + $perPage;
            $messagesOnPage = array_slice($messages, $start, $perPage);

            foreach ($messagesOnPage as $index => $message) {
                echo "<div class=\"message\">#" . ($start + $index + 1) . ": " . $message . "</div>";
            }

            // Display pagination links
            if ($end < count($messages)) {
                echo "<a href=\"?page=" . ($page + 1) . "\">Next page</a>";
            }
            ?>
        </div>
        <form method="post">
            <input type="text" name="message" placeholder="Type your message here...">
            <input type="submit" value="Shout">
        </form>
    </div>
</body>
</html>
