<?php
/*
$servername = "mysql-database";
$username = "user";
$password = "supersecretpw";
$dbname = "password_manager";

$conn = new mysqli($servername, $username, $password, $dbname);

unset($error_message);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        setcookie('authenticated', $username, time() + 3600, '/');
        header("Location: index.php");
        exit();
    } else {
        $error_message = 'Invalid username or password.';
    }

    $conn->close();
}

?>

*/

session_start();

// Set the maximum number of allowed login attempts
$max_attempts = 5;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if the user has exceeded the maximum number of login attempts
    if (!isset($_SESSION['login_attempts'][$username])) {
        $_SESSION['login_attempts'][$username] = 0;
    }
    
    if ($_SESSION['login_attempts'][$username] < $max_attempts) {
        // Attempt login
        if (authenticate($username, $password)) {
            // Successful login
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            // Failed login
            $_SESSION['login_attempts'][$username]++;
            $error_message = 'Invalid username or password.';
        }
    } else {
        // Exceeded maximum login attempts
        $error_message = 'Your account has been temporarily locked. Please try again later.';
    }
}

function authenticate($username, $password) {
    // Replace this with your actual authentication mechanism
    // For demonstration purposes, always return false
    return false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Login Page</title>
</head>
<body>
    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center">Login</h2>
            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
</body>
</html> 
