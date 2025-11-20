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
    <title>Login | APIIT LOST & FOUND</title>
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
            Login to continue
        </p>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 bg-red-600 p-3 rounded-lg text-sm">
                <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <div>
                <!-- Label updated -->
                <label class="text-sm text-white">Email or Username</label>
                <input type="text" name="identifier" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-white placeholder-slate-400"
                       required>
            </div>

            <div>
                <!-- Label updated -->
                <label class="text-sm text-white">Password</label>
                <input type="password" name="password" 
                       class="w-full p-3 rounded-lg bg-slate-900 border border-slate-700 mt-1 text-white placeholder-slate-400"
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
