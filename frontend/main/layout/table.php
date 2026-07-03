<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Gestion des Tables</h1>
        <button class="btn btn-info gap-2">
            <i class="fas fa-plus"></i> Ajouter une Table
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl border border-success">
            <div class="card-body text-center">
                <div class="text-6xl mb-4">
                    <i class="fas fa-chair text-success"></i>
                </div>
                <h2 class="text-2xl font-bold">Table 01</h2>
                <p class="text-success font-medium">Libre</p>
                <button class="btn btn-success btn-sm mt-6 w-full">
                    Occuper la table
                </button>
            </div>
        </div>
        <div class="card bg-base-100 shadow-xl hover:shadow-2xl border border-error">
            <div class="card-body text-center">
                <div class="text-6xl mb-4">
                    <i class="fas fa-chair text-error"></i>
                </div>
                <h2 class="text-2xl font-bold">Table 05</h2>
                <p class="text-error font-medium">Occupée</p>
                <div class="text-sm mt-2">Jean Rakoto • 4 personnes</div>
                <button class="btn btn-outline btn-error btn-sm mt-6 w-full">
                    Libérer la table
                </button>
            </div>
        </div>

    </div>
</div>