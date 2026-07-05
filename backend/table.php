<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';

$subject = $_GET['subject'] ?? '';
if ($subject == "create") {
    $id = "";
    $occupation = 0;
    $designation = "";
}
if (!isset($_GET['id'])) {
    die("Aucun id selctionner");
} else {
    $id = $_GET['id'];
    if ($subject == "delete") {
        $sql = "DELETE FROM restaurant_table WHERE idtable = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=delete");
        }
    } elseif ($subject == "update") {
        $occupation = 0;
        $designation = "";
        $sql = "UPDATE restaurant_table SET designation = ?, occupation = ? WHERE idtable = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("sss", $designation, $occupation, $id);
        if ($stmt->execute()) {
            header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=updated");
        }
    } else {
        header("Location: ../../../../restaurant/frontend/main/main.php?table=1&message=error");
    }
}
