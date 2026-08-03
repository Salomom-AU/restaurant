<?php
include __DIR__ . '/../../../backend/db.php';

$status = '';
$statusColor = '';
if (isset($_GET['message'])) {
    $statusMessages = [
        'delete'  => ['text' => 'Vous venez de supprimer une table avec succès', 'color' => 'bg-green-500/80'],
        'error'   => ['text' => 'Une erreur est survenue, veuillez réessayer', 'color' => 'bg-red-500/90'],
        'updated' => ['text' => 'Vous venez de libérer une table avec succès', 'color' => 'bg-green-500/80'],
        'create'  => ['text' => "Une table vient d'être ajoutée avec succès", 'color' => 'bg-green-500/80']
    ];

    if (array_key_exists($_GET['message'], $statusMessages)) {
        $status = $statusMessages[$_GET['message']]['text'];
        $statusColor = $statusMessages[$_GET['message']]['color'];
    }
}
?>

<div class="overflow-x-auto w-full p-5 flex flex-col gap-2 h-148  max-w-screen-2xl mx-auto">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Gestion des Tables</h1>

        <div class="flex relative w-full lg:w-xl items-center gap-2">
            <input type="text" id="searchInput"
                placeholder="Rechercher une table..."
                class="input input-bordered w-full pl-12">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-base-content/50"></i>
        </div>

        <a href="../../../../restaurant/backend/table.php?subject=create" class="btn btn-info gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i> Ajouter une Table
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 justify-between">
        <div class="w-full">
            <h2 class="text-xl font-semibold mb-3 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-success"></span>
                Tables libres
            </h2>

            <div class="overflow-x-auto h-110 bg-base-100 rounded-box">
                <table class="table text-center table-zebra w-full">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-base-200">
                            <th><i class="fa-solid fa-tag"></i> CODE</th>
                            <th>TABLE</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $libre = "SELECT * FROM restaurant_table WHERE occupation = 0 ORDER BY idtable ASC";
                        $result = mysqli_query($connect, $libre);

                        if (mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                                $tableNumber = (int) substr($row['idtable'], 1);
                        ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-success">#<?= htmlspecialchars($row['idtable']) ?></span>
                                    </td>
                                    <td class="font-medium">
                                        TABLE <?= sprintf("%02d", $tableNumber) ?>
                                    </td>
                                    <td>
                                        <a href="../../../../restaurant/backend/table.php?subject=delete&id=<?= urlencode($row['idtable']) ?>"
                                            class="btn btn-sm btn-error"
                                            onclick="return confirm('Supprimer cette table ?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="3" class="py-10 text-base-content/50">
                                    Aucune table libre
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="w-full">
            <h2 class="text-xl font-semibold mb-3 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-error"></span>
                Tables occupées / réservées
            </h2>

            <div class="overflow-x-auto h-125 bg-base-100 rounded-box">
                <table class="table text-center table-zebra w-full">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-base-200">
                            <th><i class="fa-solid fa-tag"></i> CODE</th>
                            <th>TABLE</th>
                            <th>CLIENT</th>
                            <th>STATUT</th>
                            <th class="text-center w-32">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $occuper = "
                            SELECT 
                                t.idtable,
                                t.occupation,
                                t.designation,
                                (
                                    SELECT c.nomcli 
                                    FROM commande c 
                                    WHERE c.idtable = t.idtable 
                                    ORDER BY c.datecom DESC 
                                    LIMIT 1
                                ) AS nom_client
                            FROM restaurant_table t
                            WHERE t.occupation = 1 OR t.occupation = 2
                            ORDER BY t.idtable ASC
                        ";
                        $result = mysqli_query($connect, $occuper);

                        if (mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                                $tableNumber = (int) substr($row['idtable'], 1);
                                $isReserve = ($row['occupation'] == 2);
                                $nomClient = !empty($row['designation'])
                                    ? $row['designation']
                                    : ($row['nom_client'] ?? '—');
                        ?>
                                <tr>
                                    <td>
                                        <span class="badge <?= $isReserve ? 'badge-warning' : 'badge-error' ?>">
                                            #<?= htmlspecialchars($row['idtable']) ?>
                                        </span>
                                    </td>
                                    <td class="font-medium">
                                        TABLE <?= sprintf("%02d", $tableNumber) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($nomClient) ?>
                                    </td>
                                    <td>
                                        <?php if ($isReserve): ?>
                                            <span class="badge badge-warning gap-1">Réservée</span>
                                        <?php else: ?>
                                            <span class="badge badge-error gap-1">Occupée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="../../../../restaurant/backend/table.php?subject=update&id=<?= urlencode($row['idtable']) ?>"
                                            class="btn btn-sm btn-info"
                                            title="Libérer la table">
                                            <i class="fas fa-unlock"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="5" class="py-10 text-base-content/50">
                                    Aucune table occupée ou réservée
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    })();
</script>