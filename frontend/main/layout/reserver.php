<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Réservations</h1>
        <button class="btn btn-info gap-2">
            <i class="fas fa-plus"></i> Nouvelle Réservation
        </button>
    </div>

    <div class="overflow-x-auto bg-base-100 rounded-box shadow">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>ID Réservation</th>
                    <th>Client</th>
                    <th>Table</th>
                    <th>Date Réservation</th>
                    <th>Heure</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-bold">RES-001</td>
                    <td>Marie Andrian</td>
                    <td>Table 03</td>
                    <td>05 Juillet 2026</td>
                    <td>19:30</td>
                    <td><span class="badge badge-warning">Confirmée</span></td>
                    <td class="text-center">
                        <button class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-ghost btn-sm text-error"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>