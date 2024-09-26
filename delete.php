<?php 
require_once "pdo.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_id'])) {
    $sql = "DELETE FROM profile WHERE profile_id = :pi";
    $statement = $pdo->prepare($sql);
    
    $statement->execute([
        ":pi" => $_POST['profile_id']
    ]);

    $_SESSION['success'] = "Profile deleted";
    header("Location: index.php");
    return;
}

if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Could not load profile";
    header("Location: index.php");
    return;
}

$statement = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
$statement->execute([
    ":profile_id" => $_GET['profile_id']
]);

$row = $statement->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    $_SESSION['error'] = "Could not load profile";
    header("Location: index.php");
    return;
}

$first_name = htmlspecialchars($row['first_name']);
$last_name = htmlspecialchars($row['last_name']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Profile</title>
</head>
<body>

    <h1>Deleting Profile</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <p>First Name: <?= $first_name ?></p>
    <p>Last Name: <?= $last_name ?></p>

    <form method="POST">
        <input type="hidden" name="profile_id" value="<?= htmlspecialchars($_GET['profile_id']) ?>" />
        <input type="submit" name="delete" value="Delete" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>

</body>
</html>
