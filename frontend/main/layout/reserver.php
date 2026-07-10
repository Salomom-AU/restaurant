<?php
include __DIR__ . '/../../../backend/db.php';
$status = '';
$statusColor = '';
if (isset($_GET['message'])) {
    $statusMessages = [
        'succes' => ['text' => 'Reservation pris avec succès', 'color' => 'bg-green-500/80'],
        'error' => ['text' => 'Une erreur est survenu , veuillez resayer !', 'color' => 'bg-red-500/90'],
        'updated' => ['text' => 'Reservation modifier avec succes', 'color' => 'bg-green-500/80'],
        'added' => ['text' => 'Reservation ajouter avec succes', 'color' => 'bg-green-500/80'],
        'delete' => ['text' => 'Reservation supprimer avec succes', 'color' => 'bg-green-500/80']
    ];
    if (array_key_exists($_GET['message'], $statusMessages)) {
        $status = $statusMessages[$_GET['message']]['text'];
        $statusColor = $statusMessages[$_GET['message']]['color'];
    }
}
?>

<div class="w-full p-5 flex flex-col gap-5">
    <div class="flex justify-between items-center ">
        <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Réservations</h1>
        <div class="flex relative w-xl items-center gap-2">
            <input type="text" id="searchInput"
                placeholder="Rechercher une commande..."
                class="input input-bordered w-full pl-12">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-base-content/50"></i>

        </div>
        <a href="../../../../restaurant/backend/reservation.php?subject=create" class="btn btn-info gap-2">
            <i class="fas fa-plus"></i> Nouvelle Réservation
        </a>
    </div>
    <div class="overflow-y-auto rounded-box max-h-120 min-h-120 bg-base-100 ">
        <table class="table table-zebra relative  w-full">
            <thead class=" sticky z-100 top-0 ">
                <tr class="sticky top-0 bg-base-200 ">
                    <th><i class="fa-solid fa-tag"></i> ID Réservation</th>
                    <th><i class="fa-solid fa-user"></i> CLIENT</th>
                    <th><i class="fa-solid fa-chair"></i> TABLES</th>
                    <th><i class="fa-solid fa-calendar"></i> Date Réservation</th>
                    <th><i class="fas mr-2 fa-clock"></i>Status</th>
                    <th class="text-center"> ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM reserver ORDER BY date_reserve DESC";
                $result = mysqli_query($connect, $query);
                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <td class="font-bold"><?= $row['idreserv'] ?></td>
                            <td><?= $row['nomcli'] ?></td>
                            <td><?= $row['idtable'] ?></td>
                            <td><?= $row['date_reserve'] ?></td>
                            <td>
                                <p class="badge badge-error">Non payer</p>
                            </td>
                            <td class="text-center">
                                <a href="../../../../restaurant/backend/reservation.php?subject=delete&id=<?= $row['idreserv'] ?>" class="btn btn-error btn-sm"><i class="fas fa-trash"></i></a>
                                <a href="../../../../restaurant/backend/reservation.php?subject=update&id=<?= $row['idreserv'] ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                <?php
                    }
                else:
                    echo '<tr><td colspan="7" class="text-center">Aucune Réservation</td></tr>';
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>