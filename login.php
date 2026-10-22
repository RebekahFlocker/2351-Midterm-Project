<?php
session_start();

// Your exact user array
$users = [ 
    ["id" => 0, "firstName" => "Macrae", "lastName" => "Cain", "userName" => "mcain", "password" => "123", "eMail" => "mcain@gwinnetttech.edu"], 
    ["id" => 1, "firstName" => "Bob", "lastName" => "Smith", "userName" => "bsmith681", "password" => "123", "eMail" => "bsmith681@gmail"], 
    ["id" => 2, "firstName" => "Sally", "lastName" => "Sue", "userName" => "ssue1986", "password" => "123", "eMail" => "ssue1986@yahoo"],
    ["id" => 3, "firstName" => "Jenn", "lastName" => "Jones", "userName" => "jjones1975", "password" => "123", "eMail" => "jjones@gwinnetttech.edu"],
    ["id" => 4, "firstName" => "Olivia", "lastName" => "Cain", "userName" => "occain2015", "password" => "123", "eMail" => "occain@gwinnetttech.edu"],
    ["id" => 900393809, "firstName" => "Rebekah", "lastName" => "Flocker", "userName" => "rflock", "password" => "305", "eMail" => "rFlocke3809@student.gwinnetttech.edu"]
];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUser = trim($_POST['username'] ?? '');
    $inputPass = trim($_POST['password'] ?? '');

    $authenticatedUser = null;

    // Loop through users to verify credentials
    foreach ($users as $user) {
        if ($user['userName'] === $inputUser && $user['password'] === $inputPass) {
            $authenticatedUser = $user;
            break;
        }
    }

    if ($authenticatedUser !== null) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $authenticatedUser;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Gradebook</title>
    <link rel="stylesheet" href="css/loginstyles.css">
</head>
<body class="login-body">

<div class="login-card">
    <h2>Gradebook Login</h2>
    <?php if (!empty($error)): ?>
        <div style="color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-size: 14px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <div class="login-form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="login-form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="login-btn">Log In</button>
    </form>
</div>

</body>
</html>