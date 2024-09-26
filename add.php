<?php
require "pdo.php";
session_start();

// Access denied if not logged in
checkSession();

// Handle cancel action
if (isset($_POST['cancel'])) {
    redirect("index.php");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    handleFormSubmission($pdo);
}

function checkSession() {
    if (!isset($_SESSION['name'])) {
        die("ACCESS DENIED");
    }
}

function redirect($location) {
    header("Location: " . $location);
    exit();
}

function handleFormSubmission($pdo) {
    // Validate form data
    if (validateFormData()) {
        // Insert the profile into the database
        $sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) 
                VALUES (:uid, :fn, :ln, :em, :he, :su)";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ":uid" => $_SESSION['user_id'],
            ":fn"  => $_POST['first_name'],
            ":ln"  => $_POST['last_name'],
            ":em"  => $_POST['email'],
            ":he"  => $_POST['headline'],
            ":su"  => $_POST['summary']
        ]);

        $_SESSION['success'] = "Profile added";
        redirect("index.php");
    }
}

function validateFormData() {
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || 
        empty($_POST['headline']) || empty($_POST['summary'])) {
        
        $_SESSION['error'] = "All fields are required";
        redirect("add.php");
        return false;
    }
    return true;
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Profile</title>
</head>

<body>

    <h1>Add a Profile </h1>

    <?php if (isset($_SESSION['error'])) : ?>
        <p style='color: red'><?= $_SESSION['error']; ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST">
        <p>
            First Name: <input type="text" name="first_name" />
        </p>
        <p>
            Last Name: <input type="text" name="last_name" />
        </p>
        <p>
            Email: <input type="text" name="email" />
        </p>
        <p>
            Headline: <input type="text" name="headline" />
        </p>
        <p>
            Summary: <textarea name="summary" rows="8" cols="80"></textarea>
        </p>
        <input type="submit" name="submit" value="Add" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>
</body>

</html>