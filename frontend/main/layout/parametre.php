<?php
$email = $_SESSION['user_email'] ;
$nom = $_SESSION['user_name'];
$pseudo = $_SESSION['user_username'] ;
@$telephone =  $_SESSION['user_telephone'] ;
$profile = substr($nom , 0 , 2 ) ;  

?>

 <div class="overflow-x-auto w-full p-5 flex flex-col gap-2 h-150  max-w-screen-2xl mx-auto">
    <div class="card bg-base-200">
        <div class="card-body">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="avatar">
                    <div class="w-40 h-40 rounded-full ring ring-info ring-offset-2 ring-offset-base-200">
                        <div class="w-full h-full bg-info rounded-full flex items-center justify-center text-6xl text-white font-bold">
                            <?php echo strtoupper($profile) ?>
                        </div>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl font-bold text-info"><?php echo htmlspecialchars($nom) ?></h1>
                    <p class="text-lg text-base-content/70"><?php echo '@' . htmlspecialchars($pseudo) ?></p>
                    <div class="flex flex-wrap gap-4 mt-3 justify-center md:justify-start">
                        <div class="badge badge-success badge-lg gap-2">
                            <i class="fas fa-circle-check"></i>
                            Actif
                        </div>
                        <div class="badge badge-info badge-lg gap-2">
                            <i class="fas fa-envelope"></i>
                            <?php echo htmlspecialchars($email) ?>
                        </div>
                        <div class="badge badge-info badge-lg gap-2">
                            <i class="fas fa-phone"></i>
                            <?php echo htmlspecialchars($telephone) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <i class="fas fa-shopping-cart text-3xl"></i>
                </div>
                <div class="stat-title">Commandes</div>
                <div class="stat-value text-primary">0</div>
                <div class="stat-desc">Total commandes</div>
            </div>
        </div>
        
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-secondary">
                    <i class="fas fa-calendar-check text-3xl"></i>
                </div>
                <div class="stat-title">Réservations</div>
                <div class="stat-value text-secondary">0</div>
                <div class="stat-desc">Réservations actives</div>
            </div>
        </div>
        
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-success">
                    <i class="fas fa-coins text-3xl"></i>
                </div>
                <div class="stat-title">Total Dépenses</div>
                <div class="stat-value text-success">0 Ar</div>
                <div class="stat-desc">Ce mois</div>
            </div>
        </div>
        
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-warning">
                    <i class="fas fa-star text-3xl"></i>
                </div>
                <div class="stat-title">Fidélité</div>
                <div class="stat-value text-warning">0</div>
                <div class="stat-desc">Points de fidélité</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <div class="card bg-base-200">
            <div class="card-body">
                <h2 class="card-title">
                    <i class="fas fa-clock text-info"></i>
                    Dernières Commandes
                </h2>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Commande</th>
                                <th>Total</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-base-content/50 py-4">
                                    Aucune commande récente
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card bg-base-200">
            <div class="card-body">
                <h2 class="card-title">
                    <i class="fas fa-utensils text-info"></i>
                    Plats Favoris
                </h2>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Plat</th>
                                <th>Commandes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" class="text-center text-base-content/50 py-4">
                                    Aucun plat favori
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.stats').forEach(stat => {
    stat.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
        this.style.transition = 'transform 0.3s ease';
    });
    stat.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});
</script>