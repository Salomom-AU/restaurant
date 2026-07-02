<?php

include __DIR__ . '/../../../backend/db.php';

$status = '';
$statusColor = '';
if (isset($_GET['message'])) {
    $statusMessages = [
        'succes' => ['text' => 'Menu supprimé avec succès', 'color' => 'bg-green-500/80'],
        'error' => ['text' => 'Erreur lors de la suppression du menu', 'color' => 'bg-red-500/90'],
        'updated' => ['text' => 'Menu modifier avec succes', 'color' => 'bg-green-500/80']
    ];

    if (array_key_exists($_GET['message'], $statusMessages)) {
        $status = $statusMessages[$_GET['message']]['text'];
        $statusColor = $statusMessages[$_GET['message']]['color'];
    }
}
?>
<div class="w-full p-5 flex flex-col gap-5">
    <div class="flex justify-between items-center">
        <h1 class="text-5xl font-bold">Gestion des menus</h1>

        <div class="flex items-center gap-2">
            <p>Rechercher :</p>
            <input type="text" id="searchInput" placeholder="Rechercher..."
                class="w-80 input input-bordered">
        </div>
        <a href="../../../../restaurant/backend/menu.php?subject=create"
            class="btn btn-info w-xs">
            <i class="fas fa-plus"></i> Ajouter
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        $query = "SELECT * FROM menu ORDER BY idplat ASC";
        $result = mysqli_query($connect, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = htmlspecialchars($row['idplat']);
                $nom = htmlspecialchars($row['nomplat']);
                $prix = number_format($row['pu'], 2, ',', ' ');
        ?>
                <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                    <figure class="h-30 bg-base-200 flex items-center justify-center">
                        <i class="fas fa-utensils text-6xl text-base-content/30"></i>
                    </figure>

                    <div class="card-body">
                        <div class="flex justify-between items-center">
                            <span class="badge badge-info">#<?= $id ?></span>
                            <p class=" text-3xl font-bold text-info">
                                <?= $prix ?> Ar
                            </p>
                        </div>

                        <h2 class="card-title text-2xl font-bold">
                            <?= $nom ?>
                        </h2>

                        <div class="card-actions flex items-center gap-2">
                            <a class="btn btn-info btn-sm w-75" href="../../../../restaurant/backend/commande.php?commande=1&&idplat=<?= $row['idplat'] ?>">
                                <i class="fas fa-utensils"></i>
                                Commander
                            </a>

                            <a href="../../../../restaurant/backend/menu.php?subject=update&id=<?= $id ?>&plat=<?= $nom ?>&prix=<?= $row['pu'] ?>"
                                class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="../../../../restaurant/backend/menu.php?subject=delete&id=<?= $id ?>"
                                class="btn btn-error btn-sm"
                                onclick="return confirm('Supprimer ce menu ?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="col-span-full text-center py-12">
                <i class="fas fa-utensils text-6xl text-base-content/20"></i>
                <p class="text-xl mt-4">Aucun menu disponible</p>
            </div>
        <?php } ?>
    </div>
</div>

<?php if (!empty($status)): ?>
    <div id="statusMessage" class="min-w-80 rounded-box fixed right-5 bottom-5 <?= $statusColor ?> text-white p-5 flex items-center shadow-xl">
        <p><?= htmlspecialchars($status) ?></p>
    </div>
<?php endif; ?>

<script>
    (function() {
        const statusDiv = document.getElementById('statusMessage');

        if (statusDiv) {
            gsap.from(statusDiv, {
                opacity: 0,
                y: 30,
                duration: 0.5
            });

            setTimeout(() => {
                gsap.to(statusDiv, {
                    opacity: 0,
                    y: 30,
                    duration: 0.5,
                    onComplete: () => statusDiv.remove()
                });
            }, 4500);
        }
    })();


    (function() {
        const searchInput = document.getElementById('searchInput');
        const cards = document.querySelectorAll('.card');

        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    })();
</script>