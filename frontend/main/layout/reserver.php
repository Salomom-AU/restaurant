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


    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Réservations</h1>
        
        <div class="flex flex-col sm:flex-row w-full lg:w-auto gap-3">
            <div class="flex relative w-full lg:w-80 items-center gap-2">
                <input type="text" id="searchInput"
                    placeholder="Rechercher..."
                    class="input input-bordered w-full pl-12">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-base-content/50"></i>
            </div>
            
            <a href="../../../../restaurant/backend/reservation.php?subject=create" class="btn btn-info gap-2 whitespace-nowrap">
                <i class="fas fa-plus"></i> Nouvelle Réservation
            </a>
        </div>
    </div>

    <div class="overflow-y-auto rounded-box max-h-120 min-h-120 bg-base-100">
        <table class="table table-zebra relative w-full">
            <thead class="sticky z-100 top-0">
                <tr class="sticky top-0 bg-base-200">
                    <th><i class="fa-solid fa-tag"></i> ID Réservation</th>
                    <th>CLIENT</th>
                    <th>TABLE</th>
                    <th> Date Réservation</th>
                    <th>Heure</th>
                    <th class="text-center"> ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM reservation ORDER BY date_reserve DESC";
                $result = mysqli_query($connect, $query);
                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)) {
                        $initials = '';
                        $words = explode(' ', $row['nomcli']);
                        foreach ($words as $word) {
                            if (!empty($word)) {
                                $initials .= strtoupper(substr($word, 0, 1));
                            }
                        }
                        $initials = substr($initials, 0, 2);
                        $colors = ['blue', 'green', 'purple', 'orange', 'pink', 'teal', 'indigo'];
                        $color = $colors[rand(0, count($colors) - 1)];
                ?>
                        <tr>
                            <td class="font-bold"><?= htmlspecialchars($row['idreserv']) ?></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-<?= $color ?>-100 text-<?= $color ?>-600 rounded-full flex items-center justify-center font-medium text-sm">
                                        <?= $initials ?>
                                    </div>
                                    <span><?= htmlspecialchars($row['nomcli']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-ghost"><?= htmlspecialchars($row['idtable']) ?></span>
                            </td>
                            <td><?= date('F j Y', strtotime($row['date_reserve'])) ?></td>
                            <td>
                                <span class="font-mono"><?= date('H:i', strtotime($row['date_reserve'])) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="flex justify-center gap-1">
                                    <a href="../../../../restaurant/backend/reservation.php?subject=update&id=<?= $row['idreserv'] ?>" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="../../../../restaurant/backend/reservation.php?subject=delete&id=<?= $row['idreserv'] ?>" 
                                       class="btn btn-error btn-sm"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                <?php
                    }
                else:
                    echo '<tr><td colspan="6" class="text-center py-12">
                            <i class="fas fa-calendar-times text-5xl text-base-content/20 mb-4"></i>
                            <p class="text-xl">Aucune Réservation</p>
                          </td></tr>';
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchInput')?.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr:not(:first-child)');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>