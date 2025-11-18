<?php
session_start();
require 'db.php';

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {

        $check = $conn->prepare("SELECT * FROM users WHERE email=? OR username=?");
        if (!$check) {
            die("Prepare failed: " . $conn->error);
    }
        $check->bind_param("ss", $email, $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Email or Username already registered.";
        } else {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users(full_name, email, username, password_hash) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $username, $password_hash);

            if ($stmt->execute()) {
                $success = "Registration successful. You can now login.";
            } else {
                $errors[] = "Error creating account.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register | APIIT Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-white flex justify-center items-center min-h-screen">

<div class="w-full max-w-md bg-slate-800 p-6 rounded-xl shadow-lg">

    <h2 class="text-2xl font-bold text-center mb-4">APIIT Lost & Found</h2>
    <p class="text-center text-slate-300 mb-4">Create an account</p>

    <?php if ($success): ?>
        <div class="mb-4 bg-green-700 text-white p-3 rounded"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="mb-4 bg-red-700 text-white p-3 rounded">
            <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

        <div>
            <label>Full Name</label>
            <input type="text" name="full_name" class="w-full p-2 rounded bg-slate-900">
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" class="w-full p-2 rounded bg-slate-900">
        </div>

        <div>
            <label>Username</label>
            <input type="text" name="username" class="w-full p-2 rounded bg-slate-900">
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password" class="w-full p-2 rounded bg-slate-900">
        </div>

        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="w-full p-2 rounded bg-slate-900">
        </div>

        <button class="w-full bg-emerald-600 py-2 rounded hover:bg-emerald-700">
            Register
        </button>

    </form>

    <p class="mt-4 text-center text-slate-300">
        Already have an account?
        <a href="login.php" class="text-emerald-400">Login</a>
    </p>

</div>

</body>
</html>
