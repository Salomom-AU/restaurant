<?php
include __DIR__ . '/../../../backend/db.php';
if (!isset($_SESSION)) {
    session_start();
}

// Récupération des paramètres de filtrage
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$type = isset($_GET['type']) ? mysqli_real_escape_string($connect, $_GET['type']) : '';
$statut = isset($_GET['statut']) ? mysqli_real_escape_string($connect, $_GET['statut']) : '';

// Construction de la requête
$where = "1=1";
if (!empty($search)) {
    $where .= " AND (c.idcom LIKE '%$search%' OR c.nomcli LIKE '%$search%')";
}
if (!empty($type)) {
    $where .= " AND c.typecom = '$type'";
}
if (!empty($statut)) {
    $where .= " AND c.statut = '$statut'";
}

$query = "SELECT c.*, 
          GROUP_CONCAT(CONCAT(m.nomplat, ' (', cd.quantite, ')') SEPARATOR ', ') as plats,
          SUM(cd.quantite) as total_qte,
          SUM(cd.quantite * cd.prix_unitaire) as total_prix
          FROM commande c 
          LEFT JOIN commande_detail cd ON c.idcom = cd.idcom 
          LEFT JOIN menu m ON cd.idplat = m.idplat 
          WHERE $where
          GROUP BY c.idcom 
          ORDER BY c.datecom ASC";

$result = mysqli_query($connect, $query);
?>

<div class="p-5 max-w-screen-2xl mx-auto">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
        <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Gestion des Commandes</h1>
        <a href="../../../../restaurant/backend/commande.php?subject=create"
            class="btn btn-info gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i>
            Nouvelle commande
        </a>
    </div>


    <form method="GET" class="flex flex-col lg:flex-row gap-4 mb-8">
        <div class="relative flex-1">
            <input type="text" id="searchInput" name="search"
                placeholder="Rechercher une commande..."
                class="input input-bordered w-full pl-12"
                value="<?= htmlspecialchars($search) ?>">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-base-content/50"></i>
        </div>

        <select name="type" class="select select-bordered w-full lg:w-52">
            <option value="">Tous les types</option>
            <option value="surTable" <?= $type == 'surTable' ? 'selected' : '' ?>>Sur table</option>
            <option value="Emporter" <?= $type == 'Emporter' ? 'selected' : '' ?>>À emporter</option>
        </select>
        <select name="statut" class="select select-bordered w-full lg:w-52">
            <option value="">Tous les statuts</option>
            <option value="en_cours" <?= $statut == 'en_cours' ? 'selected' : '' ?>>En cours</option>
            <option value="paye" <?= $statut == 'paye' ? 'selected' : '' ?>>Payée</option>
            <option value="annule" <?= $statut == 'annule' ? 'selected' : '' ?>>Annulée</option>
        </select>

        <button type="submit" class="btn btn-outline">
            <i class="fas fa-filter"></i>
            Filtrer
        </button>

        <?php if (!empty($search) || !empty($type) || !empty($statut)): ?>
            <a href="?" class="btn btn-ghost">
                <i class="fas fa-times"></i>
                Réinitialiser
            </a>
        <?php endif; ?>
    </form>


    <div class="overflow-x-auto h-100 bg-base-100 rounded-box">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200">
                    <th># CODE</th>
                    <th>CLIENT</th>
                    <th>PLAT(S)</th>
                    <th class="text-center">QTÉ</th>
                    <th class="text-right">TOTAL</th>
                    <th>TYPE</th>
                    <th>DATE</th>
                    <th>HEURE</th>
                    <th>STATUT</th>
                    <th class="text-center w-32">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)):

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
                            <td class="font-bold"><?= htmlspecialchars($row['idcom']) ?></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-<?= $color ?>-100 text-<?= $color ?>-600 rounded-full flex items-center justify-center font-medium text-sm">
                                        <?= $initials ?>
                                    </div>
                                    <span><?= htmlspecialchars($row['nomcli']) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="max-w-xs truncate" title="<?= htmlspecialchars($row['plats'] ?? 'Aucun plat') ?>">
                                    <?= htmlspecialchars($row['plats'] ?? 'Aucun plat') ?>
                                </div>
                            </td>
                            <td class="text-center font-medium"><?= $row['total_qte'] ?? 0 ?></td>
                            <td class="text-right font-semibold text-info">
                                <?= number_format($row['total_prix'] ?? 0, 0, ',', ' ') ?> Ar
                            </td>

                            <td>
                                <span class="badge <?= $row['typecom'] == 'surTable' ? 'badge-info' : 'badge-warning' ?>">
                                    <?= $row['typecom'] == 'surTable' ? 'Sur table' : 'À emporter' ?>
                                </span>
                            </td>
                            <td><?= date('H:i', strtotime($row['datecom'])) ?></td>
                            <td>
                            <td><?= date('d/m/Y', strtotime($row['datecom'])) ?></td>

                            <td class="text-center">
                                <div class="flex justify-center gap-1">
                                    <button onclick="imprimerFacture('<?= $row['idcom'] ?>')"
                                        class="btn btn-sm  btn-info">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button onclick="supprimerCommande('<?= $row['idcom'] ?>')"
                                        class="btn btn-sm  btn-error">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-12">
                            <i class="fas fa-shopping-cart text-5xl text-base-content/20 mb-4"></i>
                            <p class="text-xl">Aucune commande trouvée</p>
                            <p class="text-base-content/60 mt-2">
                                <?= (!empty($search) || !empty($type) || !empty($statut)) ?
                                    'Aucune commande ne correspond à vos filtres' :
                                    'Commencez par créer une nouvelle commande' ?>
                            </p>
                            <?php if (!empty($search) || !empty($type) || !empty($statut)): ?>
                                <a href="?" class="btn btn-sm btn-info mt-4">
                                    <i class="fas fa-times"></i> Réinitialiser les filtres
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });


    function supprimerCommande(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../../../restaurant/backend/commande.php';

            const input1 = document.createElement('input');
            input1.type = 'hidden';
            input1.name = 'action';
            input1.value = 'delete';

            const input2 = document.createElement('input');
            input2.type = 'hidden';
            input2.name = 'idcom';
            input2.value = id;

            form.appendChild(input1);
            form.appendChild(input2);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function imprimerFacture(id) {
        window.open('facture.php?id=' + id, '_blank', 'width=800,height=600');
    }
</script>