<?php

$dbFilename = 'shoutbox.db';

if (!file_exists($dbFilename)) {
    $db = new SQLite3($dbFilename);

    $query = <<<SQL
CREATE TABLE IF NOT EXISTS shoutbox_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    message TEXT NOT NULL,
    color TEXT NOT NULL
);
SQL;

    $db->exec($query);

    // Set the file permissions
    chmod($dbFilename, 0666);
    echo "Database and table created successfully.";
} else {
    echo "Database already exists. No action taken.";
}

// Set the file permissions
chmod('index.php', 0644);
chmod('get_messages.php', 0644);
chmod('post_message.php', 0644);
chmod('style.css', 0644);

echo " Permissions set for all files.";
