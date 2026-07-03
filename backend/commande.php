<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';

if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['commande'])) {
    $_SESSION['commande'] = [];
}

$idplat = $_GET['idplat'] ?? '';

if (empty($idplat)) {
    die("Aucun plat sélectionné.");
}


$stmt = $connect->prepare("SELECT * FROM menu WHERE idplat = ?");
$stmt->bind_param("s", $idplat);
$stmt->execute();
$result = $stmt->get_result();
$plat = $result->fetch_assoc();

if (!$plat) {
    die("Le plat sélectionné n'existe pas.");
}
?>

<div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
    <div class="w-full h-full flex items-center justify-center">
        <div class="cardForm bg-base-100 max-w-xl w-full rounded-box shadow-2xl p-8 relative">

            <a href="../../../../restaurant/frontend/main/main.php?menu=1"
                class="absolute top-4 right-4 btn btn-circle btn-ghost text-2xl">
                <i class="fas fa-close text-error"></i>
            </a>

            <h2 class="card-title text-3xl mb-6 text-center">Nouvelle Commande</h2>

            <form method="POST" class="space-y-6">
                <input type="hidden" name="idplat" value="<?= htmlspecialchars($idplat) ?>">

                <div>
                    <label class="label">
                        <span class="label-text flex items-center gap-2">
                            <i class="fas fa-user text-info"></i>
                            Nom du Client
                        </span>
                    </label>
                    <input type="text" name="nomcli" required
                        class="input mt-3 input-bordered w-full"
                        placeholder="Ex: Jean Rakoto" />
                </div>
                <div>
                    <label class="label">Type de Commande</label>
                    <div class=" mt-3 flex gap-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="typecom" value="Sur Table" class="radio radio-info" checked />
                            <span>Sur Table</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="typecom" value="À Emporter" class="radio radio-info" />
                            <span>À Emporter</span>
                        </label>
                    </div>
                </div>

                <div id="tableSection">
                    <label class="label">
                        <span class="label-text flex items-center gap-2">
                            <i class="fas fa-chair text-info"></i>
                            Numéro de Table
                        </span>
                    </label>
                    <select name="idtable" class="select mt-3 select-bordered w-full">
                        <option value="">Sélectionner une table</option>
                        <option value="T01">Table 01</option>
                        <option value="T02">Table 02</option>
                        <option value="T03">Table 03</option>
                    </select>
                </div>

                <div>
                    <label class="label">Plat Commandé</label>
                    <div class="bg-base-200 mt-3 p-4 rounded-box flex justify-between items-center">
                        <div>
                            <span class="font-semibold"><?= htmlspecialchars($plat['nomplat']) ?></span>
                            <span class="block text-sm text-base-content/60">#<?= htmlspecialchars($plat['idplat']) ?></span>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold text-info">
                                <?= number_format($plat['pu'], 0) ?> Ar
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <label class="label">Quantité :</label>
                    <input type="number" name="qte" min="1" value="1"
                        class="input input-bordered text-center text-lg" />
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