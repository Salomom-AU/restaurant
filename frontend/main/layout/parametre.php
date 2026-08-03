<?php
include __DIR__ . '/../../../backend/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email     = $_SESSION['user_email'] ?? '';
$nom       = $_SESSION['user_name'] ?? '';
$pseudo    = $_SESSION['user_username'] ?? '';
$telephone = $_SESSION['user_telephone'] ?? '';
$profile   = strtoupper(substr($nom, 0, 2));

$search  = trim($_GET['search'] ?? '');
$clients = [];

$where = "1=1";

if (!empty($search)) {
    $where .= " AND c.nomcli LIKE '%" . mysqli_real_escape_string($connect, $search) . "%'";
}

$query = "
    SELECT 
        c.nomcli,
        COUNT(DISTINCT c.idcom) AS nb_commandes,
        MIN(c.datecom) AS premiere_visite,
        MAX(c.datecom) AS derniere_visite,
        COALESCE(SUM(cd.quantite * cd.prix_unitaire), 0) AS total_depense
    FROM commande c
    LEFT JOIN commande_detail cd ON c.idcom = cd.idcom
    WHERE $where
    GROUP BY c.nomcli
    ORDER BY derniere_visite DESC
";

$result = mysqli_query($connect, $query);

if (!$result) {
    echo "<div class='alert alert-error'>Erreur SQL : " . mysqli_error($connect) . "</div>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $clients[] = $row;
    }
}
?>
  <div class="overflow-x-auto w-full p-5 flex flex-col gap-2 h-148  max-w-screen-2xl mx-auto">
<div class="overflow-x-auto w-full p-5 flex flex-col gap-6 max-w-screen-2xl mx-auto">
    <div class="card bg-base-200">
        <div class="card-body">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="avatar">
                    <div class="w-40 h-40 rounded-full ring ring-info ring-offset-2 ring-offset-base-200">
                        <div class="w-full h-full bg-info rounded-full flex items-center justify-center text-6xl text-white font-bold">
                            <?= $profile ?>
                        </div>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl font-bold text-info"><?= htmlspecialchars($nom) ?></h1>
                    <p class="text-lg text-base-content/70">@<?= htmlspecialchars($pseudo) ?></p>
                    <div class="flex flex-wrap gap-4 mt-3 justify-center md:justify-start">
                        <div class="badge badge-success badge-lg gap-2">
                            <i class="fas fa-circle-check"></i>
                            Actif
                        </div>
                        <div class="badge badge-info badge-lg gap-2">
                            <i class="fas fa-envelope"></i>
                            <?= htmlspecialchars($email) ?>
                        </div>
                        <div class="badge badge-info badge-lg gap-2">
                            <i class="fas fa-phone"></i>
                            <?= htmlspecialchars($telephone) ?>
                        </div>
                        <div class="badge badge-error">Admin</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl border border-base-200">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                <h2 class="card-title text-2xl">
                    <i class="fas fa-users"></i> Clients passés
                </h2>

                <form method="GET" class="flex flex-wrap gap-3 items-center">
                    <?php if (isset($_GET['profile'])): ?>
                        <input type="hidden" name="profile" value="1">
                    <?php endif; ?>

                    <div class="relative">
                        
                        <input type="text" 
                        id="searchInput"
                               name="search" 
                               value="<?= htmlspecialchars($search) ?>"
                               placeholder="Rechercher un client..."
                               class="input input-bordered w-xl input-sm w-64 pl-10">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-base-content/50 text-sm"></i>
                    </div>

           

                    <?php if (!empty($search)): ?>
                        <a href="?profile=1" class="btn btn-ghost btn-sm">Réinitialiser</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="overflow-x-auto h-100 bg-base-100 rounded-box">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th>#</th>
                            <th>Client</th>
                            <th class="text-center">Nb commandes</th>
                            <th>Première visite</th>
                            <th>Dernière visite</th>
                            <th class="text-right">Total dépensé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($clients) > 0): ?>
                            <?php $i = 1; foreach ($clients as $client): ?>
                                <tr>
                                    <td class="font-bold"><?= $i++ ?></td>
                                    <td class="font-medium"><?= htmlspecialchars($client['nomcli']) ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-info"><?= $client['nb_commandes'] ?></span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($client['premiere_visite'])) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($client['derniere_visite'])) ?></td>
                                    <td class="text-right font-semibold text-success">
                                        <?= number_format($client['total_depense'], 0, ',', ' ') ?> Ar
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-12 text-base-content/50">
                                    <?= !empty($search) ? 'Aucun client trouvé pour cette recherche' : 'Aucun client trouvé' ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (count($clients) > 0): ?>
                <div class="mt-3 text-sm text-base-content/60">
                    <?= count($clients) ?> client(s) trouvé(s)
                </div>
            <?php endif; ?>
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