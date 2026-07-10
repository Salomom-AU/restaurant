<?php
session_start();
include __DIR__ . '/../../backend/db.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== "accepter") {
    header('Location: /restaurant/frontend/forms/404.php');
    exit();
} else {
    $visible = "";
    $pseudo = $_SESSION['user_username'];
    $path = "dashboard";
    if (isset($_GET['dashbord'])) {
        $path = "dashboard";
    }
    if (isset($_GET['commande'])) {
        $path = "commande";
        $visible = "hidden";
    }
    if (isset($_GET['reserver'])) {
        $path = "reserver";
    }
    if (isset($_GET['table'])) {
        $path = "table";
    }

    if (isset($_GET['menu'])) {
        $path = "menu";
    }
    if (isset($_GET['parametres'])) {
        $path = "parametre";
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: /restaurant/frontend/forms/connexion.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="synthwave">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

</head>
<style>
    @keyframes show {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

    }

    .asideTile {
        animation: show 0.3s ease-in-out forwards;
        font-weight: bold;
    }
</style>

<header class="gap-4 sticky top-0 z-1000 h-[10vh] px-10 flex items-center w-full justify-between">
    <div class="flex w-full items-center gap-5">
        <button id="asideBtn" class="btn btn-info btn-sm"> <i class="fas fa-bars"></i></button>
        <div class="text-2xl font-bold">
            <span class="text-info">R</span>esto <span class="text-info">FOOD</span>
        </div>
        <div class="w-20 h-20">
            <img src="/restaurant/frontend/asset/food.gif" class="w-full h-full" alt="logo.gif">
        </div>
    </div>
    <div class="flex items-center gap-5">
        <button class="btn">
            <i class="fas fa-bell text-xl"></i>
        </button>
        <form method="GET">
            <button type="submit" name="logout" class="btn btn-error  gap-2">
                <i class="fa-solid fa-right-from-bracket"></i>
                Déconnexion
            </button>
        </form>
        <p>
            <?php
            echo '<span class="text-info font-extrabold text-xl">' . $pseudo . '</span>';
            $profile = substr($pseudo, 0, 2);
            ?>
        </p>
        <div class="avatar">
            <div class="w-10 h-10 rounded-full ring ring-info ring-offset-2 ring-offset-base-200">
                <div class="w-full h-full bg-info rounded-full  flex items-center justify-center text-6xl text-white font-bold">
                    <?php echo $profile ?>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="w-full h-[90vh] flex">
    <aside class=" enter flex items-center justify-center p-5 h-full">
        <div class="flex flex-col justify-center items-center relative transition-all duration-[450ms] ease-in-out ">
            <ul class="menu bg-base-100 rounded-box shadow-lg shadow-black/15 transition-all duration-300 ease-in-out  min-w-16 p-2 gap-1">
                <li>
                    <a href="?dashbord=1" class="tooltip text-nowrap tooltip-right" data-tip="Tableau de bord">
                        <div class="flex items-center justify-center w-full h-12 rounded-xl hover:bg-primary/10 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-chart-pie text-xl"></i>
                        </div>
                        <label class="asideTile hidden label" for="">Tableau de bord</label>
                    </a>
                </li>
                <li>
                    <a href="?table=1" class="tooltip tooltip-right" data-tip="table">
                        <div class="flex items-center justify-center w-full h-12 rounded-xl hover:bg-primary/10 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-table text-xl"></i>
                        </div>
                        <label class="asideTile hidden label" for="">Tables</label>
                    </a>
                </li>
                <li class="relative ">
                    <a href="?commande=1" class="tooltip tooltip-right" data-tip="commande">
                        <div class="flex items-center justify-center w-full h-12 rounded-xl hover:bg-primary/10 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </div>
                        <label class="asideTile hidden label" for="">Commandes</label>
                    </a>
                </li>
                <li>
                    <a href="?menu=1" class=" tooltip tooltip-right" data-tip="Menu">
                        <div class="flex items-center justify-center w-full h-12 rounded-xl hover:bg-primary/10 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-utensils text-xl"></i>
                        </div>
                        <label class="asideTile hidden label" for="">Menus</label>
                    </a>
                </li>
                <li>
                    <a href="?reserver=1" class="tooltip tooltip-right" data-tip="reserver">
                        <div class="flex items-center justify-center w-full h-12 rounded-xl hover:bg-primary/10 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-bookmark text-xl"></i>
                        </div>
                        <label id="asideTile" class="asideTile hidden label" for="">Reservations</label>
                    </a>
                </li>



                <div class="divider my-1"></div>
                <li>
                    <a href="?parametres=1" class="tooltip tooltip-right" data-tip="parametres">
                        <div class="flex items-center justify-center w-full h-12 rounded-xl hover:bg-primary/10 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-gear text-xl"></i>
                        </div>
                        <label class="asideTile hidden label" for="">Parametres</label>
                    </a>

                </li>
            </ul>
        </div>
    </aside>

    <main class="w-full h-full  ">
        <div class="w-full ">
            <?php

            $file_path = __DIR__ . '/../../frontend/main/layout/' . $path . '.php';
            if (file_exists($file_path)) {
                include $file_path;
            } else {
                echo '<div class="alert alert-error">Fichier non trouvé : ' . htmlspecialchars($path) . '</div>';
            }
            ?>
        </div>
        <footer class="w-full justify-center flex text-sm p-2 bottom-5 items-center">
            <div class="flex wrap gap-2">
                <p><i class="fa-regular fa-copyright"></i> copyright, tout droit reserver</p>
                <p>|</p>
                <p>in Madagasikara, Tulear 2026</p>
                <p>|</p>
                <p>politique et confidualiter</p>
            </div>
        </footer>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<?php if (!empty($status)): ?>
    <div id="statusMessage" class="rounded-2xl fixed right-130 top-5 <?= $statusColor ?> text-white p-3 flex items-center gap-3 shadow-2xl">
        <p><?= htmlspecialchars($status) ?></p>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const asideBtn = document.getElementById('asideBtn');
        const asideLabels = document.querySelectorAll('.asideTile');

        if (asideBtn) {
            asideBtn.addEventListener('click', function() {
                asideLabels.forEach(label => {
                    label.classList.toggle('hidden');
                });
            });
        }
        const rows = document.querySelectorAll('tbody tr');
        if (rows.length > 0) {
            gsap.from(rows, {
                opacity: 0,
                y: 40,
                duration: 0.5,
                ease: "back.out(1.2)",
                stagger: 0.06,
            });
        }
        const statusDiv = document.getElementById('statusMessage');
        if (statusDiv) {
            gsap.from(statusDiv, {
                opacity: 0,
                y: 40,
                duration: 0.6,
                ease: "back.out(1.2)"
            });
            setTimeout(() => {
                gsap.to(statusDiv, {
                    opacity: 0,
                    y: 30,
                    duration: 0.5,
                    ease: "power2.in",
                    onComplete: function() {
                        statusDiv.remove();
                    }
                });
            }, 4000);
        }
    });
</script>

</body>

</html>