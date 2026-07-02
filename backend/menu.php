<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';
$error = null;
$title = "";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    @$subjet = $_GET['subject'];
    @$id = $_GET['id'];

    if ($subjet == "delete") {
        $sql = "DELETE FROM menu WHERE idplat = '$id'";
        $result = $connect->query($sql);
        if ($result) {
            header("Location: ../../../../restaurant/frontend/main/main.php?menu=1&message=succes");
            exit;
        } else {
            header("Location: ../../../../restaurant/frontend/main/main.php?menu=1&message=error");
            exit;
        }
    } elseif ($subjet == "create") {
        $title = "Ajouter un menu";
    } elseif ($subjet == "update") {
        $title = "Modifier le menu";
        @$plat = $_GET['plat'];
        @$prix = $_GET['prix'];
        @$number = $_GET['id'];
    }
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    @$plat = trim($_POST['plat']);
    @$prix = $_POST['prix'];
    @$action = $_POST['action'];
    @$id = $_POST['id'];
    if ($action == "update" && !empty($id)) {
        $sql = "UPDATE menu SET nomplat = '$plat', pu = $prix WHERE idplat = '$id'";
        if (mysqli_query($connect, $sql)) {
            header("Location: ../../../../restaurant/frontend/main/main.php?menu=1&message=updated");
            exit;
        } else {
            $error = "Erreur lors de la mise à jour";
        }
    } elseif ($action == "create" || empty($action)) {
        $sql = "INSERT INTO menu (nomplat, pu) VALUES ('$plat', '$prix')";
        if (mysqli_query($connect, $sql)) {
            header("Location: ../../../../restaurant/frontend/main/main.php?menu=1&message=added");
            exit;
        } else {
            $error = "Erreur lors de l'ajout";
        }
    }
}


if ($_GET['subject'] == "create" || (!isset($_GET['subject']) && empty($_GET['id']))) {
?>

    <div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
        <div class="w-full h-full flex items-center justify-center">
            <a href="../../../../restaurant/frontend/main/main.php?menu=1" class="hide absolute top-10 right-10 btn btn-circle btn-ghost text-white text-3xl">
                <i class="fas fa-close text-error"></i>
            </a>
            <div class="cardForm bg-base-100 max-w-lg w-full rounded-box shadow-2xl p-8">
                <h1 class="text-4xl font-bold mb-6 text-center"><?= $title ?></h1>
                <?php if ($error): ?>
                    <div class="alert alert-error mb-4"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="action" value="create">
                    <div>
                        <label class="label">Nom du plat</label>
                        <input type="text" name="plat" required
                            placeholder="Ex: Salade au thon, Pizza Margherita..."
                            class="input input-bordered w-full">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Prix (Ar)</label>
                            <input type="text" name="prix" required
                                placeholder="15000.00"
                                class="input input-bordered w-full">
                        </div>
                        <div>
                            <label class="label">Numéro</label>
                            <input type="text" name="number" class="input input-bordered w-full" readonly required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info w-full">Ajouter le menu</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        gsap.from('.cardForm', {
            scale: 0.7,
            opacity: 0,
            duration: 0.5,
            ease: 'back.out(1.2)'
        });
    </script>
    </body>

    </html>
<?php
} elseif ($_GET['subject'] == "update" && !empty($_GET['id'])) {
?>

    <div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
        <div class="w-full h-full flex items-center justify-center">
            <a href="../../../../restaurant/frontend/main/main.php?menu=1" class="hide absolute top-10 right-10 btn btn-circle btn-ghost text-white text-3xl">
                <i class="fas fa-close text-error"></i>
            </a>
            <div class="cardForm bg-base-100 max-w-lg w-full rounded-box shadow-2xl p-8">
                <h1 class="text-4xl font-bold mb-6 text-center"><?= $title ?></h1>
                <?php if ($error): ?>
                    <div class="alert alert-error mb-4"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <div>
                        <label class="label">Nom du plat</label>
                        <input value="<?= htmlspecialchars($plat) ?>" type="text" name="plat" required
                            placeholder="Ex: Salade au thon, Pizza Margherita..."
                            class="input input-bordered w-full">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Prix (Ar)</label>
                            <input value="<?= htmlspecialchars($prix) ?>" type="text" name="prix" required
                                placeholder="15000.00"
                                class="input input-bordered w-full">
                        </div>
                        <div>
                            <label class="label">Numéro</label>
                            <input value="<?= htmlspecialchars($number) ?>" type="text" name="number" class="input input-bordered w-full" readonly required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info w-full">Enregistrer le menu</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        gsap.from('.cardForm', {
            scale: 0.7,
            opacity: 0,
            duration: 0.5,
            ease: 'back.out(1.2)'
        });
    </script>
    </body>

    </html>
<?php
}
?>