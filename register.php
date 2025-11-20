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
    <title>Register | APIIT LOST & FOUND</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative min-h-screen bg-cover bg-center bg-no-repeat" 
      style="background-image: url('https://www.ncuk.ac.uk/wp-content/uploads/2021/05/APIIT-1-900x1018.jpg');">

<div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

<div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-slate-800/80 backdrop-blur-md p-6 rounded-2xl shadow-xl">

        <!-- Title updated -->
        <h2 class="text-3xl font-bold text-center mb-2 text-white">APIIT LOST & FOUND</h2>

        <p class="text-center text-slate-300 mb-6 text-sm sm:text-base">
            Create your account
        </p>

        <?php if ($success): ?>
            <div class="mb-4 bg-green-600 text-white p-3 rounded-lg text-sm">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 bg-red-600 text-white p-3 rounded-lg text-sm">
                <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <label class="text-sm text-white">Full Name</label>
                <input type="text" name="full_name" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-white placeholder-slate-400"
                       required>
            </div>

            <div>
                <label class="text-sm text-white">Email</label>
                <input type="email" name="email" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-white placeholder-slate-400"
                       required>
            </div>

            <div>
                <label class="text-sm text-white">Username</label>
                <input type="text" name="username" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-white placeholder-slate-400"
                       required>
            </div>

            <div>
                <label class="text-sm text-white">Password</label>
                <input type="password" name="password" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-white placeholder-slate-400"
                       required>
            </div>

            <div>
                <label class="text-sm text-white">Confirm Password</label>
                <input type="password" name="confirm_password" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-white placeholder-slate-400"
                       required>
            </div>

            <button class="w-full bg-emerald-600 py-3 rounded-lg hover:bg-emerald-700 text-white font-semibold">
                Register
            </button>
        </form>

        <p class="mt-5 text-center text-slate-300 text-sm">
            Already have an account?
            <a href="login.php" class="text-emerald-400 font-semibold hover:underline">Login</a>
        </p>

    </div>
</div>

</body>
</html>
