<?php
include __DIR__ . '/../../../backend/db.php';
$status = '';
$statusColor = '';
if (isset($_GET['message'])) {
    $statusMessages = [
        'succes' => ['text' => 'Menu supprimé avec succès', 'color' => 'bg-green-500/80'],
        'error' => ['text' => 'Erreur lors de la suppression du menu', 'color' => 'bg-red-500/90'],
        'updated' => ['text' => 'Menu modifier avec succes', 'color' => 'bg-green-500/80'],
        'added' => ['text' => 'Menu ajouter avec succes', 'color' => 'bg-green-500/80'],
        'delete' => ['text' => 'Menu supprimer avec succes', 'color' => 'bg-green-500/80']
    ];

    if (array_key_exists($_GET['message'], $statusMessages)) {
        $status = $statusMessages[$_GET['message']]['text'];
        $statusColor = $statusMessages[$_GET['message']]['color'];
    }
}
?>
<div class="w-full p-5 flex flex-col gap-5">
    <div class="flex justify-between items-center">
        <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Gestion des Menus</h1>
        <div class="flex relative w-xl items-center gap-2">
            <input type="text" id="searchInput"
                placeholder="Rechercher un menu..."
                class="input input-bordered w-full pl-12">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-base-content/50"></i>

        </div>
        <a href="../../../../restaurant/backend/menu.php?subject=create"
            class="btn btn-info ">
            <i class="fas fa-plus"></i> Nouveau menu
        </a>
    </div>
    <div class="overflow-x-auto h-125 bg-base-100 rounded-box ">
        <table class="table table-zebra relative  w-full">
            <thead class="sticky z-100 top-0 ">
                <tr class="bg-base-200">
                    <th><i class="fa-solid fa-tag"></i> CODE</th>
                    <th> PLAT</th>
                    <th> Prix</th>
                    <th class="text-center w-32">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM menu ORDER BY idplat ASC";
                $result = mysqli_query($connect, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {

                        $id = htmlspecialchars($row['idplat']);
                        $nom = htmlspecialchars($row['nomplat']);
                        $prix = number_format($row['pu'], 2, ',', ' ');

                        $initials = '';
                        $words = explode(' ', $row['nomplat']);
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
                            <td class="font-bold"><?= $id ?></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-<?= $color ?>-100 text-<?= $color ?>-600 rounded-full flex items-center justify-center font-medium text-sm">
                                        <?= $initials ?>
                                    </div>
                                    <span><?= $nom ?></span>
                                </div>
                            </td>
                            <td class="font-bold"><?= $prix ?> Ar</td>
                            <td>
                                <a href="../../../../restaurant/backend/menu.php?subject=update&id=<?= $id ?>&plat=<?= $nom ?>&prix=<?= $row['pu'] ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="../../../../restaurant/backend/menu.php?subject=delete&id=<?= $id ?>"
                                    class="btn btn-error btn-sm"
                                    onclick="return confirm('voulez-vous Supprimer ce menu ?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-utensils text-6xl text-base-content/20"></i>
                        <p class="text-xl mt-4">Aucun menu disponible</p>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<script>
    (function() {
        const searchInput = document.getElementById('searchInput');
        const cards = document.querySelectorAll('tbody tr');
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    })();
</script>