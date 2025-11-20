<?php
session_start();

// Prevent access without login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | APIIT Lost & Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- MOBILE IMPORTANT -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative min-h-screen bg-cover bg-center bg-no-repeat" 
      style="background-image: url('https://www.ncuk.ac.uk/wp-content/uploads/2021/05/APIIT-1-900x1018.jpg');">

<!-- Dark Blur Background Overlay -->
<div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

<!-- MAIN WRAPPER -->
<div class="relative min-h-screen flex items-center justify-center p-4">

    <!-- DASHBOARD BOX -->
    <div class="w-full max-w-md bg-slate-800/80 backdrop-blur-md p-6 rounded-2xl shadow-xl relative">

        <!-- PROFILE ICON + CLICK DROPDOWN -->
        <div class="absolute top-4 right-4">

            <!-- Profile Button -->
            <button id="profileBtn"
                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-600 border border-slate-400 
                           flex items-center justify-center cursor-pointer focus:outline-none transition">
                <span class="text-white text-lg sm:text-xl">👤</span>
            </button>

            <!-- Dropdown Menu (Hidden by default) -->
            <div id="profileMenu" 
                 class="hidden absolute right-0 mt-2 w-44 sm:w-52 bg-slate-700 text-white rounded-lg 
                        shadow-lg p-4 z-20">

                <p class="text-sm mb-3 font-medium leading-tight">
                    Welcome,<br>
                    <span class="text-emerald-400 font-semibold">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </span>
                </p>

                <a href="logout.php"
                   class="block text-center bg-red-500 hover:bg-red-600 py-1.5 rounded-md 
                          text-white text-sm font-semibold transition">
                    Logout
                </a>
            </div>

        </div>

        <!-- MAIN TITLE -->
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-10 text-emerald-400">
            APIIT Lost & Found
        </h2>

        <!-- BUTTONS CONTAINER -->
        <div class="flex flex-col space-y-4 sm:space-y-5">

            <a href="report_lost.php"
               class="w-full text-center py-3 sm:py-3.5 bg-slate-700 hover:bg-slate-600 rounded-lg 
                      text-white font-semibold text-base sm:text-lg transition">
                Report Lost Item
            </a>

            <a href="report_found.php"
               class="w-full text-center py-3 sm:py-3.5 bg-slate-700 hover:bg-slate-600 rounded-lg 
                      text-white font-semibold text-base sm:text-lg transition">
                Report Found Item
            </a>

            <a href="browse_lost.php"
               class="w-full text-center py-3 sm:py-3.5 bg-slate-700 hover:bg-slate-600 rounded-lg 
                      text-white font-semibold text-base sm:text-lg transition">
                Browse Lost Items
            </a>

            <a href="browse_found.php"
               class="w-full text-center py-3 sm:py-3.5 bg-slate-700 hover:bg-slate-600 rounded-lg 
                      text-white font-semibold text-base sm:text-lg transition">
                Browse Found Items
            </a>

        </div>

    </div>
</div>

<!-- CLICK-DROPDOWN SCRIPT -->
<script>
document.getElementById("profileBtn").addEventListener("click", function () {
    const menu = document.getElementById("profileMenu");
    menu.classList.toggle("hidden");
});
</script>

</body>
</html>
