<?php
include __DIR__ . '/../backend/db.php';
include __DIR__ . '/../backend/header.php';
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nomCli = mysqli_real_escape_string($connect, $_POST['nomCli'] ?? '');
    $type = mysqli_real_escape_string($connect, $_POST['Type'] ?? '');
    $idtable = mysqli_real_escape_string($connect, $_POST['idtable'] ?? '');
    $panierData = json_decode($_POST['panier'] ?? '[]', true);
    
    if (empty($nomCli)) {
        $error = "Le nom du client est requis";
    } elseif (empty($panierData)) {
        $error = "Le panier est vide";
    } elseif ($type == "surTable" && empty($idtable)) {
        $error = "Veuillez sélectionner une table";
    } else {
        $query = "SELECT COUNT(*) AS total_commande FROM commande";
        $result = mysqli_query($connect, $query);
        $row = mysqli_fetch_assoc($result);
        $totalCom = $row['total_commande'] ?? 0;
        $idCommande = "C" . sprintf("%04d", ($totalCom + 1));
        
        $typecom = ($type == "surTable") ? "surTable" : "Emporter";
        
        $queryCommande = "INSERT INTO commande (idcom, nomcli, typecom, idtable, datecom) 
                          VALUES ('$idCommande', '$nomCli', '$typecom', " . ($type == "surTable" ? "'$idtable'" : "NULL") . ", NOW())";
        
        if (mysqli_query($connect, $queryCommande)) {
            foreach ($panierData as $item) {
                $idplat = mysqli_real_escape_string($connect, $item['id']);
                $qte = (int)$item['qte'];
                $prix = (float)$item['prix'];
                
                $queryDetail = "INSERT INTO commande_detail (idcom, idplat, quantite, prix_unitaire) 
                                VALUES ('$idCommande', '$idplat', '$qte', '$prix')";
                mysqli_query($connect, $queryDetail);
            }
            
            if ($type == "surTable" && !empty($idtable)) {
                $updateTable = "UPDATE restaurant_table SET occupation = 1 WHERE idtable = '$idtable'";
                mysqli_query($connect, $updateTable);
            }
            
            header("Location: ../../../../restaurant/frontend/main/main.php?commande=1&success=1");
            exit();
        } else {
            $error = "Erreur lors de l'enregistrement : " . mysqli_error($connect);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $subject = $_GET['subject'] ?? '';
    $id = $_GET['id'] ?? '';

    if ($subject == "create") {
        $query = "SELECT COUNT(*) AS total_commande FROM commande";
        $result = mysqli_query($connect, $query);
        $row = mysqli_fetch_assoc($result);
        $totalCom = $row['total_commande'] ?? 0;
        $nextId = "C" . sprintf("%04d", ($totalCom + 1));
        $id = $nextId;
?>
        <div class="box w-full fixed top-0 left-0 z-[10000] backdrop-blur-3xl h-full">
            <div class="w-full h-full flex items-center justify-center">
                <div class="cardForm bg-base-100 max-w-7xl w-full rounded-box relative shadow-2xl p-8">
                    <a href="../../../../restaurant/frontend/main/main.php?commande=1"
                        class="absolute top-4 right-4 btn btn-circle btn-ghost text-2xl">
                        <i class="fas fa-close"></i>
                    </a>
                    <div class="flex items-center mb-4 gap-4 w-full">
                        <h2 class="card-title text-3xl">Nouvelle Commande</h2>
                        <span class="badge font-bold badge-success badge-xl"><i class="fa-solid fa-tag"></i> <?= $id ?></span>
                    </div>
                    <div class="divider my-1"></div>
                    
                    <?php if ($error): ?>
                    <div class="alert alert-error mb-4">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex gap-5">
                        <form method="POST" class="w-[50%] space-y-6" id="commandeForm" onsubmit="return preparerEnvoi()">
                            <div class="flex w-full justify-between items-center">
                                <label for="nomCli" class="label">Nom du client :</label>
                                <input id="nomCli" type="text" class="input w-sm" placeholder="Ex:Rakoto solanga" name="nomCli" required>
                            </div>
                            <div class="flex w-full justify-between items-center">
                                <label for="nomCli" class="label">Type de commande :</label>
                                <div class="flex justify-between w-sm">
                                    <div>
                                        <label for="checkRest">sur table</label>
                                        <input id="checkRest" type="radio" value="surTable" name="Type" class="radio radio-info" checked>
                                    </div>
                                    <div>
                                        <label for="checkPart">A emporter</label>
                                        <input id="checkPart" type="radio" value="Emporter" name="Type" class="radio radio-warning">
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full justify-between items-center" id="tableSection">
                                <label class="label mb-2">Numero de table :</label>
                                <select name="idtable" required class="select select-bordered w-sm">
                                    <option value="">-- Sélectionner une table --</option>
                                    <?php
                                    $libre = "SELECT * FROM restaurant_table WHERE occupation = 0 ORDER BY idtable ASC";
                                    $result = mysqli_query($connect, $libre);
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $tableNumber = (int) substr($row['idtable'], 1);
                                    ?>
                                            <option value="<?= $row['idtable'] ?>">TABLE <?= sprintf("%02d", $tableNumber) ?></option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option class="option bg-error desible" value="">Aucun table libre pour ce moment ...</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="flex w-full gap-4 justify-between">
                                <label class="" for=""> <i class="fas fa-shopping-cart text-xl"></i></label>
                                <div id="panier" class="w-xl bg-base-100 border border-base-200 overflow-y-auto max-h-40 rounded-box shadow-xl h-screen p-2">
                                    <div class="text-center text-base-content/50 py-4">
                                        <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                                        <p>Aucun plat dans le panier</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4 flex-col w-full">
                                <label class="label" for="">Total</label>
                                <div class="flex items-center gap-2 bg-base-100 border border-base-200 p-5 shadow-xl">
                                    <div class="flex gap-2 items-center">
                                        <label for="">Qte</label>
                                        <input id="totalQte" type="number" class="input w-30" readonly value="0">
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <label for="">Prix</label>
                                        <input id="totalPrix" type="number" class="input w-sm" readonly value="0">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="panier" id="panierData">

                            <button type="submit" class="btn btn-info w-full">Enregistrer la commande</button>
                        </form>

                        <div class="w-[50%]">
                            <div class="flex relative mb-4 items-center gap-2">
                                <input type="text" id="searchInput"
                                    placeholder="Rechercher un menu..."
                                    class="input input-bordered w-full pl-12">
                                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-base-content/50"></i>
                            </div>
                            <div class="overflow-x-auto w-full h-125 bg-base-100 rounded-box">
                                <table class="table table-zebra relative w-full">
                                    <thead class="sticky z-100 top-0">
                                        <tr class="bg-base-200">
                                            <th><i class="fa-solid fa-tag"></i> CODE</th>
                                            <th><i class="fa-solid fa-burger"></i> PLAT</th>
                                            <th><i class="fa-solid fa-coins"></i> Prix</th>
                                            <th class="text-center w-32">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bodyMenu">
                                        <?php
                                        $query = "SELECT * FROM menu ORDER BY idplat ASC";
                                        $result = mysqli_query($connect, $query);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $id = htmlspecialchars($row['idplat']);
                                                $nom = htmlspecialchars($row['nomplat']);
                                                $prix = number_format($row['pu'], 2, ',', ' ');
                                        ?>
                                                <tr data-id="<?= $id ?>" data-nom="<?= $nom ?>" data-prix="<?= $row['pu'] ?>">
                                                    <td class="font-bold"><?= $id ?></td>
                                                    <td class="font-bold"><?= $nom ?></td>
                                                    <td class="font-bold"><?= $prix ?> Ar</td>
                                                    <td>
                                                        <button onclick="ajouterPlat(this)" class="btn btn-sm btn-info">Ajouter</button>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-12">
                                                    <i class="fas fa-utensils text-6xl text-base-content/20"></i>
                                                    <p class="text-xl mt-4">Aucun menu disponible</p>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
    }
}
?>

<style>
    input[type="radio"] {
        width: 15px;
        height: 15px;
    }
</style>

<script>
    let panier = [];

    const checkRest = document.getElementById('checkRest');
    const checkPart = document.getElementById('checkPart');
    const tableSection = document.getElementById('tableSection');

    function updateTypeCommande() {
        if (checkRest.checked) {
            checkPart.checked = false;
            tableSection.style.display = 'flex';
            document.querySelector('select[name="idtable"]').required = true;
        }
        if (checkPart.checked) {
            checkRest.checked = false;
            tableSection.style.display = 'none';
            document.querySelector('select[name="idtable"]').required = false;
        }
    }

    updateTypeCommande();
    checkRest.addEventListener('change', updateTypeCommande);
    checkPart.addEventListener('change', updateTypeCommande);

    function ajouterPlat(btn) {
        const tr = btn.closest('tr');
        const id = tr.dataset.id;
        const nom = tr.dataset.nom;
        const prix = parseFloat(tr.dataset.prix);

        const existant = panier.find(p => p.id === id);
        if (existant) {
            existant.qte++;
        } else {
            panier.push({
                id: id,
                nom: nom,
                prix: prix,
                qte: 1
            });
        }
        afficherPanier();
    }

    function afficherPanier() {
        const panierDiv = document.getElementById('panier');
        panierDiv.innerHTML = '';
        let total = 0;
        let qteTotal = 0;

        if (panier.length === 0) {
            panierDiv.innerHTML = `
                <div class="text-center text-base-content/50 py-4">
                    <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                    <p>Aucun plat dans le panier</p>
                </div>
            `;
        } else {
            panier.forEach((item, index) => {
                const itemTotal = item.prix * item.qte;
                total += itemTotal;
                qteTotal += item.qte;

                const row = document.createElement('div');
                row.className = "flex justify-between items-center p-3 rounded-xl mb-2 bg-base-200";
                row.innerHTML = `
                    <div class="flex-1">
                        <span class="font-medium">${item.nom}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="changerQte(${index}, -1)" class="btn btn-xs btn-error"><i class="fas fa-minus"></i></button>
                        <span class="font-bold w-6 text-center">${item.qte}</span>
                        <button onclick="changerQte(${index}, 1)" class="btn btn-xs btn-success"><i class="fas fa-plus"></i></button>
                        <span class="font-semibold ml-4">${itemTotal} Ar</span>
                        <button onclick="supprimerPlat(${index})" class="btn btn-xs btn-ghost text-error"><i class="fas fa-close"></i></button>
                    </div>
                `;
                panierDiv.appendChild(row);
            });
        }

        document.getElementById('totalQte').value = qteTotal;
        document.getElementById('totalPrix').value = total;
        document.getElementById('panierData').value = JSON.stringify(panier);
    }

    function changerQte(index, delta) {
        panier[index].qte += delta;
        if (panier[index].qte < 1) {
            panier.splice(index, 1);
        }
        afficherPanier();
    }

    function supprimerPlat(index) {
        panier.splice(index, 1);
        afficherPanier();
    }

    function preparerEnvoi() {
        if (panier.length === 0) {
            alert('Veuillez ajouter au moins un plat au panier');
            return false;
        }

        const nomCli = document.getElementById('nomCli').value.trim();
        if (nomCli === '') {
            alert('Veuillez entrer le nom du client');
            return false;
        }

        document.getElementById('panierData').value = JSON.stringify(panier);

        const isRestaurant = document.getElementById('checkRest').checked;
        if (isRestaurant) {
            const tableSelect = document.querySelector('select[name="idtable"]');
            if (tableSelect.value === '') {
                alert('Veuillez sélectionner une table');
                return false;
            }
        }

        return true;
    }

    (function() {
        const searchInput = document.getElementById('searchInput');
        const cards = document.querySelectorAll('.bodyMenu tr');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase().trim();
                cards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    card.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }
    })();

    if (typeof gsap !== 'undefined') {
        gsap.from('.cardForm', {
            scale: 0.7,
            opacity: 0,
            duration: 0.5,
            ease: 'back.out(1.2)'
        });
    }
</script>