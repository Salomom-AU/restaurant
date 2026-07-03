<?php
include __DIR__ . '/../../../backend/db.php';
if (!isset($_SESSION)) {
    session_start();
}
?>

<div class="p-6 max-w-screen-2xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
        <div class="flex items-center gap-3">
            <i class="fas fa-shopping-cart text-4xl text-info"></i>
            <div>
                <h1 class="text-4xl font-bold">Commandes</h1>
                <p class="text-base-content/60">Gérez toutes les commandes du restaurant</p>
            </div>
        </div>

        <!-- Stat du jour -->
        <div class="stats shadow bg-base-100 border-l-4 border-info w-full lg:w-auto">
            <div class="stat">
                <div class="stat-title">Commandes aujourd'hui</div>
                <div class="stat-value text-info"><?= array_sum($_SESSION['commande'] ?? [0]) ?></div>
                <div class="stat-desc flex items-center gap-2">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>

        <a href="../../../../restaurant/frontend/main/main.php?menu=1" 
           class="btn btn-info gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i>
            Nouvelle commande
        </a>
    </div>

    <!-- Filtres -->
    <div class="flex flex-col lg:flex-row gap-4 mb-8">
        <div class="relative flex-1">
            <input type="text" id="searchInput"
                placeholder="Rechercher une commande..."
                class="input input-bordered w-full pl-12">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-base-content/50"></i>
        </div>

        <select class="select select-bordered w-full lg:w-52">
            <option value="">Tous les types</option>
            <option>Sur table</option>
            <option>À emporter</option>
            <option>Livraison</option>
        </select>

        <select class="select select-bordered w-full lg:w-52">
            <option value="">Tous les statuts</option>
            <option>En cours</option>
            <option>Terminée</option>
            <option>Annulée</option>
        </select>

        <button class="btn btn-outline">
            <i class="fas fa-filter"></i>
            Filtrer
        </button>
    </div>

    <div class="overflow-x-auto bg-base-100 rounded-box shadow">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200">
                    <th># CODE</th>
                    <th>CLIENT</th>
                    <th>PLAT</th>
                    <th class="text-center">QTÉ</th>
                    <th class="text-right">TOTAL</th>
                    <th>TYPE</th>
                    <th>HEURE</th>
                    <th class="text-center w-32">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-bold">CMD001</td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-medium">JR</div>
                            <span>Jean Rakoto</span>
                        </div>
                    </td>
                    <td>Pizza au poulet</td>
                    <td class="text-center font-medium">2</td>
                    <td class="text-right font-semibold text-info">32 000 Ar</td>
                    <td><span class="badge badge-info">Sur table</span></td>
                    <td>12:30</td>
                    <td class="text-center">
                        <div class="flex justify-center gap-1">
                            <button class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-ghost btn-sm text-success"><i class="fas fa-check"></i></button>
                            <button class="btn btn-ghost btn-sm text-error"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="font-bold">CMD002</td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center font-medium">MA</div>
                            <span>Marie Andrian</span>
                        </div>
                    </td>
                    <td>Salade César</td>
                    <td class="text-center font-medium">1</td>
                    <td class="text-right font-semibold text-info">18 500 Ar</td>
                    <td><span class="badge badge-warning">À emporter</span></td>
                    <td>13:15</td>
                    <td class="text-center">
                        <div class="flex justify-center gap-1">
                            <button class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-ghost btn-sm text-success"><i class="fas fa-check"></i></button>
                            <button class="btn btn-ghost btn-sm text-error"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>