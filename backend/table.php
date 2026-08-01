<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';
$subject = $_GET['subject'] ?? '';
$id      = $_GET['id'] ?? '';
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if ($subject == "create" && empty($_GET['nb'])) {
        ?>
        <div class="flex justify-center items-center h-screen">
            <div class="bg-base-100 p-8 rounded-lg shadow-md text-center w-full max-w-sm">
                <h2 class="text-xl font-bold">Ajouter des tables</h2>
                <form method="GET" action="">
                    <input type="hidden" name="subject" value="create">
                    <div class="form-control flex items-center flex-col gap-2 mb-4">
                        <label class="label card-title">
                            <span class="label-text">Nombre de tables à ajouter</span>
                        </label>
                        <input type="number" 
                               name="nb" 
                               min="1" 
                               max="50" 
                               value="1" 
                               required
                               class="input input-bordered w-full text-center text-lg" 
                               autofocus>
                               <div class="flex gap-3 justify-center mt-6">
                                   <button type="submit" class="btn w-full btn-primary">
                                       Créer
                                   </button>
                                   <a href="../../../../restaurant/frontend/main/main.php?table=1" 
                                      class="btn btn-ghost">
                                       Annuler
                                   </a>
                               </div>
                            </div>
                </form>
            </div>
        </div>
        <?php
        exit(); 
    }
    if ($subject == "create" && !empty($_GET['nb'])) {
        $nb = (int) $_GET['nb'];
        
        if ($nb < 1) $nb = 1;
        if ($nb > 50) $nb = 50;

        $query = "SELECT COUNT(*) AS total_tables FROM restaurant_table";
        $result = mysqli_query($connect, $query);
        $row = mysqli_fetch_assoc($result);
        $totalTables = $row['total_tables'] ?? 0;

        $sql = "INSERT INTO restaurant_table (idtable, designation, occupation) VALUES (?, '', 0)";
        $stmt = $connect->prepare($sql);

        $success = true;
        for ($i = 1; $i <= $nb; $i++) {
            $newId = "T" . sprintf("%03d", ($totalTables + $i));
            $stmt->bind_param("s", $newId);
            if (!$stmt->execute()) {
                $success = false;
                break;
            }
        }

        if ($success) {
            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=create&nb=$nb");
            exit();
        } else {
            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=error");
            exit();
        }
    }

   
    if ($subject == "delete" && !empty($id)) {
        $connect->begin_transaction();
        try {
            $sql = "DELETE FROM restaurant_table WHERE idtable = ?";
            $stmt = $connect->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();

            $numSupprime = (int) substr($id, 1);

            $sqlUpdate = "UPDATE restaurant_table 
                         SET idtable = CONCAT('T', LPAD(CAST(SUBSTRING(idtable, 2) AS UNSIGNED) - 1, 3, '0'))
                         WHERE CAST(SUBSTRING(idtable, 2) AS UNSIGNED) > ?";

            $stmtUpdate = $connect->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $numSupprime);
            $stmtUpdate->execute();
            $connect->commit();

            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=delete");
            exit();
        } catch (Exception $e) {
            $connect->rollback();
            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=error");
            exit();
        }
    }
    if ($subject == "update" && !empty($id)) {
        $sql = "UPDATE restaurant_table SET occupation = 0, designation = '' WHERE idtable = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=updated");
            exit();
        } else {
            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=error");
            exit();
        }
    }
}

header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=error");
exit();