
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="card w-full max-w-2xl bg-base-100 shadow-2xl">
        <div class="card-body items-center text-center p-8">
            <div class="relative mb-6">
                <div class="text-9xl font-black text-info/20 select-none">
                    404
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fa-solid fa-utensils text-7xl text-info"></i>
                </div>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-base-content mb-2">
                Oups ! Page non trouvée
            </h1>
            <p class="text-lg text-base-content/70 mb-2">
                Désolé, ce plat n'est pas au menu aujourd'hui !
            </p>
            <p class="text-base text-base-content/50 mb-8 max-w-md">
                La page que vous cherchez a été déplacée, supprimée ou n'existe pas.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 w-full max-w-md">
                <a href="/" class="btn btn-info text-white flex-1 gap-2">
                    <i class="fa-solid fa-house"></i>
                    Accueil
                </a>
                <a href="./menu.php" class="btn btn-outline flex-1 gap-2">
                    <i class="fa-solid fa-book-open"></i>
                    Voir le menu
                </a>
            </div>

            <div class="divider text-base-content/30 my-6">
                <i class="fa-solid fa-utensil-spoon"></i>
            </div>
            <div class="flex flex-wrap gap-2 justify-center">
                <a href="./reservation.php" class="badge badge-info badge-lg gap-2 hover:scale-105 transition-transform">
                    <i class="fa-solid fa-calendar-check"></i>
                    Réservation
                </a>
                <a href="./contact.php" class="badge badge-info badge-lg gap-2 hover:scale-105 transition-transform">
                    <i class="fa-solid fa-phone"></i>
                    Contact
                </a>
                <a href="./about.php" class="badge badge-info badge-lg gap-2 hover:scale-105 transition-transform">
                    <i class="fa-solid fa-info-circle"></i>
                    À propos
                </a>
                <a href="./galerie.php" class="badge badge-info badge-lg gap-2 hover:scale-105 transition-transform">
                    <i class="fa-solid fa-images"></i>
                    Galerie
                </a>
            </div>

            <div class="mt-8 p-4 bg-base-200 rounded-lg w-full max-w-md">
                <p class="text-sm text-base-content/60">
                    <i class="fa-regular fa-face-smile-wink mr-2"></i>
                    Pendant que vous êtes là, pourquoi ne pas commander un plat ?
                </p>
            </div>
        </div>
    </div>
</div>


<style>
    .badge {
        cursor: pointer;
    }

    .badge:hover {
        transform: scale(1.05);
    }
</style>