<!-- dashboard.php -->
<div class="p-6">
    <h1 class="text-4xl font-bold mb-8">Tableau de Bord - Restaurant</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-title">Commandes Aujourd'hui</div>
                <div class="stat-value text-primary">24</div>
            </div>
        </div>
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-title">Tables Occupées</div>
                <div class="stat-value text-warning">8/15</div>
            </div>
        </div>
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-title">Recette du Jour</div>
                <div class="stat-value text-success">1.2M Ar</div>
            </div>
        </div>
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-title">Plats Vendus</div>
                <div class="stat-value">87</div>
            </div>
        </div>
    </div>

    <!-- Liens rapides -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10">
        <a href="menu.php" class="btn btn-info h-24 flex-col">
            <i class="fas fa-utensils text-3xl"></i>
            <span>Menu</span>
        </a>
        <a href="tables.php" class="btn btn-warning h-24 flex-col">
            <i class="fas fa-chair text-3xl"></i>
            <span>Tables</span>
        </a>
        <a href="commandes.php" class="btn btn-success h-24 flex-col">
            <i class="fas fa-shopping-cart text-3xl"></i>
            <span>Commandes</span>
        </a>
        <a href="reservations.php" class="btn btn-secondary h-24 flex-col">
            <i class="fas fa-calendar-check text-3xl"></i>
            <span>Réservations</span>
        </a>
    </div>
</div>