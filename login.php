<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mb-4">Please Log In</h1>

                <?php
                    require "pdo.php";
                    session_start();

                    if (isset($_POST['email']) && isset($_POST['pass'])) {
                        $sql = "SELECT * FROM users WHERE email = :email AND password = :pass";

                        $statement = $pdo->prepare($sql);
                        $statement->execute([
                            ":email" => $_POST['email'],
                            ":pass" => $_POST['pass']
                        ]);

                        $row = $statement->fetch(PDO::FETCH_ASSOC);

                        if ($row == false) {
                            $_SESSION['error'] = "Incorrect Password";
                            header("Location: login.php");
                            return;
                        }

                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['name'] = $row['name'];
                        header("Location: index.php");
                        return;
                    }
                ?>

                <!-- Error message display -->
                <?php 
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">'.htmlspecialchars($_SESSION['error']).'</div>';
                        unset($_SESSION['error']);
                    }
                ?>

                <form method="post" onsubmit="return sub();">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" />
                    </div>

                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="password" class="form-control" id="pass" name="pass" />
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Log In</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Validation script -->
    <script>
        function sub() {
            try {
                let email = document.getElementById("email").value;
                let password = document.getElementById("pass").value;

                if (email == "" || password == "") {
                    alert("Both fields are necessary");
                    return false;
                }
                if (email.indexOf("@") === -1) {
                    alert("Enter a valid email");
                    return false;
                }
                return true;
            } catch (e) {
                alert(e);
                return false;
            }
        }
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js" integrity="sha384-3nrnDP4yShu2+jKye2wox7lW1LJk0CDE5DA1Hea2pFNk/Rjbkfr6ymQTVP40V9bx" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgQIQiuMZp4Imd7FevB1Wcl4EuvF5y7uC2hsUtEOBfB0vZF5SrZ" crossorigin="anonymous"></script>
</body>

</html>
