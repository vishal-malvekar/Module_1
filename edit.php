<?php 

require_once "pdo.php";
session_start();

// Handle cancel button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all fields are filled
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['headline']) || empty($_POST['summary'])) {
        $_SESSION['error'] = "All fields must be filled out.";
        header("Location: edit.php?profile_id=" . $_GET['profile_id']);
        return;
    }

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header("Location: edit.php?profile_id=" . $_GET['profile_id']);
        return;
    }

    // Update profile data
    $sql = "UPDATE profile 
            SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su 
            WHERE profile_id = :pi";
    
    $statement = $pdo->prepare($sql);
    $statement->execute([
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':pi' => $_GET['profile_id']
    ]);

    $_SESSION['success'] = "Profile has been successfully updated.";
    header("Location: index.php");
    return;
}

if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Profile not found.";
    header("Location: index.php");
    return;
}

$statement = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
$statement->execute([
    ':profile_id' => $_GET['profile_id']
]);

$row = $statement->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    $_SESSION['error'] = "Profile not found.";
    header("Location: index.php");
    return;
}

$first_name = htmlspecialchars($row['first_name']);
$last_name = htmlspecialchars($row['last_name']);
$email = htmlspecialchars($row['email']);
$headline = htmlspecialchars($row['headline']);
$summary = htmlspecialchars($row['summary']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>

    <h1>Edit Profile</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST">
        <p>
            First Name: <input type="text" name="first_name" value="<?= $first_name ?>" />
        </p>
        <p>
            Last Name: <input type="text" name="last_name" value="<?= $last_name ?>" />
        </p>
        <p>
            Email: <input type="text" name="email" value="<?= $email ?>" />
        </p>
        <p>
            Headline: <input type="text" name="headline" value="<?= $headline ?>" />
        </p>
        <p>
            Summary: <textarea name="summary" rows="8" cols="80"><?= $summary ?></textarea>
        </p>
        <input type="submit" value="Save Changes" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>

</body>
</html>
