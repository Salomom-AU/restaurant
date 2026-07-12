<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';

$subject = $_GET['subject'] ?? '';
$id      = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if ($subject == "create") {
        $query = "SELECT COUNT(*) AS total_tables FROM restaurant_table";
        $result = mysqli_query($connect, $query);
        $row = mysqli_fetch_assoc($result);
        $totalTables = $row['total_tables'] ?? 0;
        $newId = "T" . sprintf("%03d", ($totalTables + 1));

        $sql = "INSERT INTO restaurant_table (idtable, designation, occupation) VALUES (?, '', 0)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $newId);

        if ($stmt->execute()) {
            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=create");
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
