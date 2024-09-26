<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Vishal Malvekar </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <h1 class="mb-4 text-center">Profiles</h1>

                <?php 
                    require "pdo.php";
                    session_start();

                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success" role="alert">'.$_SESSION['success'].'</div>';
                        unset($_SESSION['success']);
                    }

                    if (isset($_SESSION['name'])) {
                        echo '<p class="text-right"><a href="logout.php" class="btn btn-outline-primary">Logout</a></p>';
                    } else {
                        echo '<p class="text-right"><a href="login.php" class="btn btn-outline-primary">Please log in</a></p>';
                    }
                ?>

                <?php
                    $statement = $pdo->query("SELECT * FROM profile");
                    $row = $statement->fetch(PDO::FETCH_ASSOC);

                    if ($row != false) {
                        $statement = $pdo->query("SELECT * FROM profile");

                        echo '<table class="table table-bordered table-hover">';
                        echo '<thead class="thead-dark">';
                        echo '<tr>';
                        echo '<th>Name</th>';
                        echo '<th>Email</th>';
                        if (isset($_SESSION['name'])) {
                            echo '<th>Action</th>';
                        }
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>'.htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']).'</td>';
                            echo '<td>'.htmlspecialchars($row['email']).'</td>';
                            if (isset($_SESSION['name'])) {
                                echo '<td>';
                                echo '<a href="edit.php?profile_id='.htmlspecialchars($row['profile_id']).'" class="btn btn-warning btn-sm mr-2">Edit</a>';
                                echo '<a href="delete.php?profile_id='.htmlspecialchars($row['profile_id']).'" class="btn btn-danger btn-sm">Delete</a>';
                                echo '</td>';
                            }
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    }
                ?>

                <?php if (isset($_SESSION['name'])) : ?>
                    <div class="text-center mt-4">
                        <a href="add.php" class="btn btn-success">Add New Entry</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js" integrity="sha384-3nrnDP4yShu2+jKye2wox7lW1LJk0CDE5DA1Hea2pFNk/Rjbkfr6ymQTVP40V9bx" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgQIQiuMZp4Imd7FevB1Wcl4EuvF5y7uC2hsUtEOBfB0vZF5SrZ" crossorigin="anonymous"></script>
</body>

</html>
