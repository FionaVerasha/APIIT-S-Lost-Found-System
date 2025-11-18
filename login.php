<?php
session_start();
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identifier = trim($_POST['identifier']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

        if (password_verify($password, $user['password_hash'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php");
            exit;

        } else {
            $errors[] = "Incorrect password.";
        }

    } else {
        $errors[] = "No account found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | APIIT Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-white flex justify-center items-center min-h-screen">

<div class="w-full max-w-md bg-slate-800 p-6 rounded-xl shadow-lg">

    <h2 class="text-2xl font-bold text-center mb-4">APIIT Lost & Found</h2>
    <p class="text-center text-slate-300 mb-4">Login to continue</p>

    <?php if (!empty($errors)): ?>
        <div class="mb-4 bg-red-700 p-3 rounded">
            <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

        <div>
            <label>Email or Username</label>
            <input type="text" name="identifier" class="w-full p-2 rounded bg-slate-900">
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password" class="w-full p-2 rounded bg-slate-900">
        </div>

        <button class="w-full bg-emerald-600 py-2 rounded hover:bg-emerald-700">
            Login
        </button>

    </form>

    <p class="mt-4 text-center text-slate-300">
        Don’t have an account?
        <a href="register.php" class="text-emerald-400">Register</a>
    </p>

</div>

</body>
</html>
