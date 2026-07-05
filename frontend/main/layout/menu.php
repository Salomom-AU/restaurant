<?php
include __DIR__ . '/../../../backend/db.php';
$status = '';
$statusColor = '';
if (isset($_GET['message'])) {
    $statusMessages = [
        'succes' => ['text' => 'Menu supprimé avec succès', 'color' => 'bg-green-500/80'],
        'error' => ['text' => 'Erreur lors de la suppression du menu', 'color' => 'bg-red-500/90'],
        'updated' => ['text' => 'Menu modifier avec succes', 'color' => 'bg-green-500/80'],
        'added' => ['text' => 'Menu ajouter avec succes', 'color' => 'bg-green-500/80']
    ];

    if (array_key_exists($_GET['message'], $statusMessages)) {
        $status = $statusMessages[$_GET['message']]['text'];
        $statusColor = $statusMessages[$_GET['message']]['color'];
    }
}
?>
<div class="w-full p-5 flex flex-col gap-5">
    <div class="flex justify-between items-center">
        <h1 class="text-4xl font-bold">Gestion des Menus</h1>
        <div class="flex items-center gap-2">
            <p>Rechercher :</p>
            <input type="text" id="searchInput" placeholder="Rechercher..."
                class="w-xl input input-bordered">
        </div>
        <a href="../../../../restaurant/backend/menu.php?subject=create"
            class="btn btn-info w-xs">
            <i class="fas fa-plus"></i> Nouveau menu
        </a>
    </div>
    <div class="overflow-x-auto h-130 bg-base-100 rounded-box shadow-2xl">
        <table class="table table-zebra relative  w-full">
            <thead class="sticky z-100 top-0 ">
                <tr class="bg-base-200">
                    <th><i class="fa-solid fa-tag"></i> CODE</th>
                    <th><i class="fa-solid fa-burger"></i> PLAT</th>
                    <th><i class="fa-solid fa-coins"></i> Prix</th>
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
                ?>
                        <tr>
                            <td class="font-bold"><?= $id ?></td>
                            <td class="font-bold"><?= $nom ?></td>
                            <td class="font-bold"><?= $prix ?> Ar</td>
                            <td>
                                <a href="../../../../restaurant/backend/menu.php?subject=update&id=<?= $id ?>&plat=<?= $nom ?>&prix=<?= $row['pu'] ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="../../../../restaurant/backend/menu.php?subject=delete&id=<?= $id ?>"
                                    class="btn btn-error btn-sm"
                                    onclick="return confirm('Supprimer ce menu ?')">
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
        const cards = document.querySelectorAll('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    })();
</script>