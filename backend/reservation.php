<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';

$allowedSubjects = ['create', 'update', 'delete'];
$subject = isset($_GET['subject']) && in_array($_GET['subject'], $allowedSubjects)
    ? $_GET['subject']
    : '';

if ($subject == "create") {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $nomCli = trim($_POST['nomcli'] ?? '');
            $idTable = trim($_POST['idtable'] ?? '');
            $dateReserv = trim($_POST['date_reserver'] ?? '');

            if (!empty($dateReserv) && !strtotime($dateReserv)) {
                throw new Exception("Date de réservation invalide");
            }

            if (empty($nomCli) || empty($idTable) || empty($dateReserv)) {
                throw new Exception("Tous les champs sont obligatoires");
            }

            $checkTable = "SELECT occupation FROM restaurant_table WHERE idtable = ?";
            $stmt = $connect->prepare($checkTable);
            $stmt->bind_param("s", $idTable);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if ($row['occupation'] != 0) {
                    throw new Exception("Cette table est déjà occupée");
                }
            } else {
                throw new Exception("Table non trouvée");
            }

            $query = "SELECT COUNT(*) AS total FROM reservation";
            $result = mysqli_query($connect, $query);
            $row = mysqli_fetch_assoc($result);
            $total = $row['total'] ?? 0;
            $idreserv = "RES" . sprintf("%03d", $total + 1);

            mysqli_begin_transaction($connect);
            try {
                $sql = "INSERT INTO reservation (idreserv, idtable, date_de_reserv , date_reserve , nomcli) 
                        VALUES (?, ?, NOW(), ?, ?)";
                $stmt = $connect->prepare($sql);
                $stmt->bind_param("ssss", $idreserv, $idTable, $dateReserv, $nomCli);
                $stmt->execute();

                $update = "UPDATE restaurant_table SET occupation = 1, designation = ? WHERE idtable = ?";
                $stmt2 = $connect->prepare($update);
                $stmt2->bind_param("ss", $nomCli, $idTable);
                $stmt2->execute();

                mysqli_commit($connect);

                header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=succes");
                exit();
            } catch (Exception $e) {
                mysqli_rollback($connect);
                throw $e;
            }
        } catch (Exception $e) {
            error_log("Reservation error: " . $e->getMessage());
            echo $e->getMessage();
            header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=error");
            exit();
        }
    }
?>
    <div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
        <div class="w-full h-full flex items-center justify-center">
            <a href="../../../../restaurant/frontend/main/main.php?reserver=1"
                class="absolute top-10 right-10 btn btn-circle btn-ghost text-white text-3xl">
                <i class="fas fa-close"></i>
            </a>
            <div class="cardForm bg-base-100 max-w-lg w-full rounded-box shadow-2xl p-8">
                <h1 class="text-4xl font-bold mb-8 text-center">Nouvelle Réservation</h1>
                <form method="POST" class="space-y-6" id="reservationForm">
                    <div>
                        <label class="label">Nom du Client</label>
                        <input type="text" name="nomcli" required
                            class="input input-bordered w-full"
                            placeholder="Ex: Jean Rakoto"
                            minlength="2"
                            maxlength="100" />
                    </div>

                    <div>
                        <label class="label">Table à Réserver</label>
                        <select name="idtable" required class="select select-bordered w-full">
                            <option value="">-- Choisir une table libre --</option>
                            <?php
                            $libre = "SELECT * FROM restaurant_table WHERE occupation = 0 ORDER BY idtable ASC";
                            $result = mysqli_query($connect, $libre);
                            while ($row = mysqli_fetch_assoc($result)):
                                $num = (int) substr($row['idtable'], 1);
                            ?>
                                <option value="<?= htmlspecialchars($row['idtable']) ?>">Table <?= $num ?> (<?= htmlspecialchars($row['idtable']) ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="label">Date et Heure de Réservation</label>
                        <input type="datetime-local" name="date_reserver" required
                            class="input input-bordered w-full"
                            min="<?= date('Y-m-d\TH:i') ?>" />
                    </div>

                    <button type="submit" class="btn btn-info w-full" id="submitBtn">
                        Confirmer la Réservation
                    </button>
                </form>
            </div>
        </div>
    </div>

<?php
} elseif ($subject == "update") {
    $idreserv = $_GET['id'] ?? '';
    if (empty($idreserv) || !preg_match('/^RES\d{3}$/', $idreserv)) {
        header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=error");
        exit();
    }

    $query = "SELECT * FROM reservation WHERE idreserv = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $idreserv);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=error");
        exit();
    }
    $reservation = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $nomCli = trim($_POST['nomcli'] ?? '');
            $idTable = trim($_POST['idtable'] ?? '');
            $dateReserv = trim($_POST['date_reserver'] ?? '');

            if (empty($nomCli) || empty($idTable) || empty($dateReserv)) {
                throw new Exception("Tous les champs sont obligatoires");
            }

            if (!strtotime($dateReserv)) {
                throw new Exception("Date invalide");
            }
            $oldTable = $reservation['idtable'];
            mysqli_begin_transaction($connect);
            try {
                if ($oldTable != $idTable) {
                    $updateOld = "UPDATE restaurant_table SET occupation = 0, designation = '' WHERE idtable = ?";
                    $stmt = $connect->prepare($updateOld);
                    $stmt->bind_param("s", $oldTable);
                    $stmt->execute();
                    $updateNew = "UPDATE restaurant_table SET occupation = 1, designation = ? WHERE idtable = ?";
                    $stmt = $connect->prepare($updateNew);
                    $stmt->bind_param("ss", $nomCli, $idTable);
                    $stmt->execute();
                }

                $sql = "UPDATE reservation SET idtable = ?, date_reserve = ?, nomcli = ? WHERE idreserv = ?";
                $stmt = $connect->prepare($sql);
                $stmt->bind_param("ssss", $idTable, $dateReserv, $nomCli, $idreserv);
                $stmt->execute();

                mysqli_commit($connect);

                header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=updated");
                exit();
            } catch (Exception $e) {
                mysqli_rollback($connect);
                throw $e;
            }
        } catch (Exception $e) {
            error_log("Update error: " . $e->getMessage());
            header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=error");
            exit();
        }
    }
?>
    <div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
        <div class="w-full h-full flex items-center justify-center">
            <a href="../../../../restaurant/frontend/main/main.php?reserver=1"
                class="absolute top-10 right-10 btn btn-circle btn-ghost text-white text-3xl">
                <i class="fas fa-close"></i>
            </a>
            <div class="cardForm bg-base-100 max-w-lg w-full rounded-box shadow-2xl p-8">
                <h1 class="text-4xl font-bold mb-8 text-center">Modifier Réservation</h1>
                <form method="POST" class="space-y-6">
                    <div>
                        <label class="label">Nom du Client</label>
                        <input type="text" name="nomcli" required
                            class="input input-bordered w-full"
                            value="<?= htmlspecialchars($reservation['nomcli']) ?>" />
                    </div>

                    <div>
                        <label class="label">Table à Réserver</label>
                        <select name="idtable" required class="select select-bordered w-full">
                            <option value="">-- Choisir une table --</option>
                            <?php
                            $tables = "SELECT * FROM restaurant_table WHERE occupation = 0 OR idtable = ? ORDER BY idtable ASC";
                            $stmt = $connect->prepare($tables);
                            $stmt->bind_param("s", $reservation['idtable']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = mysqli_fetch_assoc($result)):
                                $num = (int) substr($row['idtable'], 1);
                                $selected = $row['idtable'] == $reservation['idtable'] ? 'selected' : '';
                            ?>
                                <option value="<?= htmlspecialchars($row['idtable']) ?>" <?= $selected ?>>
                                    Table <?= $num ?> (<?= htmlspecialchars($row['idtable']) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="label">Date et Heure de Réservation</label>
                        <input type="datetime-local" name="date_reserver" required
                            class="input input-bordered w-full"
                            value="<?= date('Y-m-d\TH:i', strtotime($reservation['date_reserve'])) ?>"
                            min="<?= date('Y-m-d\TH:i') ?>" />
                    </div>

                    <button type="submit" class="btn btn-info w-full">
                        Modifier la Réservation
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php
} elseif ($subject == "delete") {
    $idreserv = $_GET['id'] ?? '';
    if (empty($idreserv) || !preg_match('/^RES\d{3}$/', $idreserv)) {
        header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=error");
        exit();
    }

    $query = "SELECT idtable FROM reservation WHERE idreserv = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $idreserv);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=error");
        exit();
    }
    $idTable = $row['idtable'];
    mysqli_begin_transaction($connect);
    try {
        $sql = "DELETE FROM reservation WHERE idreserv = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $idreserv);
        $stmt->execute();
        $update = "UPDATE restaurant_table SET occupation = 0, designation = '' WHERE idtable = ?";
        $stmt2 = $connect->prepare($update);
        $stmt2->bind_param("s", $idTable);
        $stmt2->execute();

        mysqli_commit($connect);
        header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=delete");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($connect);
        error_log("Delete error: " . $e->getMessage());
        echo $e->getMessage();
        header("Location: ../../../../restaurant/frontend/main/main.php?reserver=1&message=error");
        exit();
    }
}
?>

<script>
    gsap.from('.cardForm', {
        scale: 0.7,
        opacity: 0,
        duration: 0.5,
        ease: 'back.out(1.2)'
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reservationForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Traitement...';
            });
        }
    });
</script>