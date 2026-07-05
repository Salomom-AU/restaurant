<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';

// if (!isset($_SESSION)) session_start();
// if (!isset($_SESSION['commande'])) {
//     $_SESSION['commande'] = [];
// }

// $idplat = $_GET['idplat'] ?? '';

// if (empty($idplat)) {
//     die("Aucun plat sélectionné.");
// }


// $stmt = $connect->prepare("SELECT * FROM menu WHERE idplat = ?");
// $stmt->bind_param("s", $idplat);
// $stmt->execute();
// $result = $stmt->get_result();
// $plat = $result->fetch_assoc();

// if (!$plat) {
//     die("Le plat sélectionné n'existe pas.");
// }
?>

<div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
    <div class="w-full h-full flex items-center justify-center ">
        <div class="cardForm bg-base-100 max-w-5xl w-full rounded-box relative shadow-2xl p-8 ">
            <a href="../../../../restaurant/frontend/main/main.php?commande=1"
                class="absolute top-4 right-4 btn btn-circle btn-ghost text-2xl">
                <i class="fas fa-close "></i>
            </a>
            <h2 class="card-title text-3xl  mb-4">Nouvelle Commande</h2>
            <div class="divider my-1"></div>
            <form method="POST" class="space-y-6">
                <div class="flex flex items-center justify-between gap-5">
                    <div class="flex flex items-center justify-between gap-5">
                        <label class="label">
                            <span class="label-text flex items-center gap-2">
                                Nom du Client :
                            </span>
                        </label>
                        <input type="text" name="nomcli" required
                            class="input w-sm input-bordered "
                            placeholder="Ex: Jean Rakoto" />
                    </div>
                    <div class="flex flex items-center justify-between gap-5">
                        <label class="label">Type de Commande :</label>
                        <select name="idtable" class="select select-bordered ">
                            <option value="surtable">Surtable</option>
                            <option value="Emporter">Emporter</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex items-center justify-between gap-5">
                    <div class=" flex items-center justify-between gap-5">
                        <label class="label">
                            <span class="label-text flex items-center gap-2">
                                Numéro de Table :
                            </span>
                        </label>
                        <select name="idtable" class="select select-bordered ">
                            <option value="">Sélectionner une table</option>
                            <option value="T01">Table 01</option>
                            <option value="T02">Table 02</option>
                            <option value="T03">Table 03</option>
                        </select>
                    </div>
                    <div class="flex flex items-center justify-between gap-5">
                        <button class="btn btn-info w-sm"><i class="fas fa-add"></i> Ajouter un Plat</button>
    
                    </div>
                </div>

                <div>
                    <label class="label">Plat Commandé</label>
                    <div class="bg-base-200 mt-3 p-4 rounded-box flex justify-between items-center">
                        <div class="text-center w-full">

                        aucun plat commande
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center justify-between gap-4">
                        <label class="label">Quantité :</label>
                        <input type="number" name="qte" min="1" value="1"
                            class="input input-bordered text-center text-lg" />
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <label class="label">Total (Ar) :</label>
                        <input type="text" readonly name="qte" min="1" value="1000,00 Ar"
                            class="input input-bordered w-xl text-center text-lg" />
                    </div>
                </div>
                <div class="flex justify-end gap-4 pt-6 border-t border-base-300">
                    <button type="submit" name="valider_commande"
                        class="btn btn-info w-full gap-2">
                        Valider la Commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    gsap.from('.cardForm', {
        scale: 0.7,
        opacity: 0,
        duration: 0.5,
        ease: 'back.out(1.2)'
    });
</script>