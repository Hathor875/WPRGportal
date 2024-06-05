<?php
session_start();
include './DatabaseHandle/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password_hash);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            $_SESSION['user_id'] = $id;
            setcookie('user_id', $id, time() + (86400 * 30), "/"); // 30 days

            header("Location: /portal/admin_panel.php");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../portal/css/style.css">
</head>
<body>
<?php include './layout/header.php'; ?>
<main>
    <form method="post" action="">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <input type="submit" value="Login">
    </form>
</main>
<?php include './layout/footer.php'; ?>
</body>
</html>
