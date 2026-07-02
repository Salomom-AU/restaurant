<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';

@$commadeList = array() ;


if (isset($_GET['commande'])) {
    $idplat = $_GET['idplat'] ;
    $commande = $_GET['commande'];
}
$commandeCount = isset($_COOKIE['commander']) ? (int)$_COOKIE['commander'] : 0;
$commandeCount += $commande;
setcookie("commander", $commandeCount, time() + 3600);


?>


<div>
    <p>vous avez commande <?= $commandeCount ?> fois avec <?= $idplat ?></p>
    <input type="submit" class="btn" value=" supprimer la commande" name="sup">
</div>