<?php
/*
 * Educational example: Kara David's Wish Note
 * Configure the database connection below for your local classroom environment.
 * This example expects a table named main_corrupt_politicians with columns:
 * id (auto-increment primary key), Name, WishNote, Location.
 */

$message = '';
$error = '';

$dbHost = 'localhost';
$dbName = 'kara_david_wishnote';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    $pdo = null;
    $error = 'Database connection failed. Check your local database settings.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $name = trim($_POST['name'] ?? '');
    $wishNote = trim($_POST['wish_note'] ?? '');
    $location = trim($_POST['location'] ?? '');

    if ($name === '' || $wishNote === '' || $location === '') {
        $error = 'Please complete all fields.';
    } else {
        try {
            // The Location field is included in the INSERT and values below.
            $stmt = $pdo->prepare(
                'INSERT INTO main_corrupt_politicians (Name, WishNote, Location)
                 VALUES (:name, :wish_note, :location)'
            );
            $stmt->execute([
                ':name' => $name,
                ':wish_note' => $wishNote,
                ':location' => $location,
            ]);
            $message = 'Your wish note was saved successfully.';
        } catch (PDOException $e) {
            $error = 'The wish note could not be saved. Check the table structure.';
        }
    }
}

$records = [];
if ($pdo) {
    try {
        $records = $pdo->query(
            'SELECT Name, WishNote, Location FROM main_corrupt_politicians ORDER BY id DESC'
        )->fetchAll();
    } catch (PDOException $e) {
        // The table may not have been created yet; see the README setup notes.
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kara David's Wish Note</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; color: #182230; background: #f5f8fc; }
        main { background: #fff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 12px #00000012; }
        label { display: block; margin-top: 1rem; font-weight: 600; }
        input, textarea { width: 100%; box-sizing: border-box; margin-top: .35rem; padding: .7rem; border: 1px solid #b8c5d6; border-radius: 6px; font: inherit; }
        button { margin-top: 1rem; padding: .75rem 1rem; background: #174ea6; color: white; border: 0; border-radius: 6px; cursor: pointer; }
        .notice { padding: .8rem; border-radius: 6px; margin-bottom: 1rem; }
        .success { background: #e8f7ee; color: #14532d; }
        .error { background: #fdecec; color: #991b1b; }
        article { border-top: 1px solid #e2e8f0; padding: .8rem 0; overflow-wrap: anywhere; }
        small { color: #526174; }
    </style>
</head>
<body>
<main>
    <h1>Kara David's Wish Note</h1>
    <p>Write your wish note and include your location.</p>

    <?php if ($message !== ''): ?>
        <p class="notice success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p class="notice error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="name">Name</label>
        <input id="name" name="name" maxlength="100" required>

        <label for="wish_note">Wish note</label>
        <textarea id="wish_note" name="wish_note" rows="4" maxlength="2000" required></textarea>

        <label for="location">Location</label>
        <input id="location" name="location" maxlength="150" required>

        <button type="submit">Save Wish Note</button>
    </form>

    <h2>Submitted Wish Notes</h2>
    <?php if (!$records): ?>
        <p><small>No records found yet, or the database table has not been created.</small></p>
    <?php else: ?>
        <?php foreach ($records as $record): ?>
            <article>
                <strong><?= htmlspecialchars($record['Name'], ENT_QUOTES, 'UTF-8') ?></strong>
                <p><?= nl2br(htmlspecialchars($record['WishNote'], ENT_QUOTES, 'UTF-8')) ?></p>
                <small>Location: <?= htmlspecialchars($record['Location'], ENT_QUOTES, 'UTF-8') ?></small>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
</body>
</html>
