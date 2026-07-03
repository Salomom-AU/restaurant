<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';

$error = null;
$title = "";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $subject = $_GET['subject'] ?? '';
    $id      = $_GET['id'] ?? '';

    if ($subject == "delete") {
        $sql = "DELETE FROM menu WHERE idplat = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            header("Location: ../../../../restaurant/frontend/main/main.php?menu=1&message=succes");
        } else {
            header("Location: ../../../../restaurant/frontend/main/main.php?menu=1&message=error");
        }
        exit;
    } elseif ($subject == "create") {
        $title = "Ajouter un menu";
    } elseif ($subject == "update") {
        $title = "Modifier le menu";


        $sql = "SELECT nomplat, pu FROM menu WHERE idplat = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $platData = $result->fetch_assoc();

        $plat  = $platData['nomplat'] ?? '';
        $prix  = $platData['pu'] ?? '';
        $number = $id;
    }
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $plat   = trim($_POST['plat'] ?? '');
    $prix   = $_POST['prix'] ?? 0;
    $action = $_POST['action'] ?? '';
    $id     = $_POST['id'] ?? '';

    if ($action == "update" && !empty($id)) {
        $sql = "UPDATE menu SET nomplat = ?, pu = ? WHERE idplat = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("sds", $plat, $prix, $id);

        if ($stmt->execute()) {
            header("Location: ../../../../restaurant/frontend/main/main.php?menu=1&message=updated");
            exit;
        } else {
            $error = "Erreur lors de la mise à jour: " . $connect->error;
        }
    } elseif ($action == "create" || empty($action)) {
        $sql = "INSERT INTO menu (idplat, nomplat, pu) VALUES (?, ?, ?)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssd", $id, $plat, $prix);

        if ($stmt->execute()) {
            header("Location: ../../../../restaurant/frontend/main/main.php?menu=1&message=added");
            exit;
        } else {
            $error = "Erreur lors de l'ajout: " . $connect->error;
        }
    }
}

if (($subject ?? '') == "create" || (!isset($_GET['subject']) && empty($_GET['id']))) {

    $query = "SELECT COUNT(*) AS total_plats FROM menu";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    $totalPlats = $row['total_plats'] ?? 0;
    $nextId = "P" . ($totalPlats + 1);
?>
    <div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
        <div class="w-full h-full flex items-center justify-center">
            <a href="../../../../restaurant/frontend/main/main.php?menu=1"
                class="hide absolute top-10 right-10 btn btn-circle btn-ghost text-white text-3xl">
                <i class="fas fa-close text-error"></i>
            </a>

            <div class="cardForm bg-base-100 max-w-lg w-full rounded-box shadow-2xl p-8">
                <h1 class="text-4xl font-bold mb-6 text-center"><?= htmlspecialchars($title) ?></h1>

                <?php if ($error): ?>
                    <div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <input type="hidden" name="action" value="create">

                    <div>
                        <label class="label"><i class="fa-solid fa-burger"></i> Nom du plat</label>
                        <input type="text" name="plat" required
                            placeholder="Ex: Salade au thon, Pizza Margherita..."
                            class="input input-bordered w-full">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label"><i class="fa-solid fa-coins"></i> Prix (Ar)</label>
                            <input type="number" step="0.01" name="prix" required
                                placeholder="15000.00"
                                class="input input-bordered w-full">
                        </div>
                        <div>
                            <label class="label"><i class="fa-solid fa-tag"></i> Numéro</label>
                            <input type="text" name="id" value="<?= $nextId ?>" readonly
                                class="input input-bordered w-full">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-info w-full">Enregistrer </button>
                </form>
            </div>
        </div>
    </div>
<?php
} elseif (($subject ?? '') == "update" && !empty($_GET['id'])) {
?>
    <div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
        <div class="w-full h-full flex items-center justify-center">
            <a href="../../../../restaurant/frontend/main/main.php?menu=1"
                class="hide absolute top-10 right-10 btn btn-circle btn-ghost text-white text-3xl">
                <i class="fas fa-close text-error"></i>
            </a>

            <div class="cardForm bg-base-100 max-w-lg w-full rounded-box shadow-2xl p-8">
                <h1 class="text-4xl font-bold mb-6 text-center"><?= htmlspecialchars($title) ?></h1>

                <?php if ($error): ?>
                    <div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

                    <div>
                        <label class="label">Nom du plat</label>
                        <input type="text" name="plat" value="<?= htmlspecialchars($plat) ?>" required
                            class="input input-bordered w-full">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Prix (Ar)</label>
                            <input type="number" step="0.01" name="prix" value="<?= htmlspecialchars($prix) ?>" required
                                class="input input-bordered w-full">
                        </div>
                        <div>
                            <label class="label">Numéro</label>
                            <input type="text" value="<?= htmlspecialchars($number ?? $id) ?>" readonly
                                class="input input-bordered w-full">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-info w-full">Enregistrer les modifications</button>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>

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