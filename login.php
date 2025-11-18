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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-white">

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-slate-800 p-6 rounded-2xl shadow-xl">

        <h2 class="text-3xl font-bold text-center mb-2">APIIT Lost & Found</h2>
        <p class="text-center text-slate-300 mb-6 text-sm sm:text-base">
            Login to continue
        </p>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 bg-red-600 p-3 rounded-lg text-sm">
                <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <label class="text-sm">Email or Username</label>
                <input type="text" name="identifier" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-sm sm:text-base"
                       required>
            </div>

            <div>
                <label class="text-sm">Password</label>
                <input type="password" name="password" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-sm sm:text-base"
                       required>
            </div>

            <button class="w-full bg-emerald-600 py-3 rounded-lg hover:bg-emerald-700 text-white font-semibold">
                Login
            </button>
        </form>

        <p class="mt-5 text-center text-slate-300 text-sm">
            Don’t have an account?
            <a href="register.php" class="text-emerald-400 font-semibold hover:underline">Register</a>
        </p>

    </div>
</div>

</body>
</html>