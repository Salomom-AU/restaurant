<?php

include __DIR__ . '/../../../backend/db.php';
$status = '';
$statusColor = '';
if (isset($_GET['message'])) {
    $statusMessages = [
        'delete' => ['text' => 'vous venez de supprimé un table avec succès', 'color' => 'bg-green-500/80'],
        'error' => ['text' => 'une erreur est produit veuillez reassayer ', 'color' => 'bg-red-500/90'],
        'updated' => ['text' => 'vous venez de liberer un table avec succes', 'color' => 'bg-green-500/80'],
        'create' => ['text' => "Un table vient d'etre ajouter avec succes ", 'color' => 'bg-green-500/80']
    ];

    if (array_key_exists($_GET['message'], $statusMessages)) {
        $status = $statusMessages[$_GET['message']]['text'];
        $statusColor = $statusMessages[$_GET['message']]['color'];
    }
}
?>

<div class="p-5 flex flex-col gap-5">
    <div class="flex justify-between items-center">
        <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Gestion des Tables</h1>
        <div class="flex relative w-xl items-center gap-2">
            <input type="text" id="searchInput"
                placeholder="Rechercher une commande..."
                class="input input-bordered w-full pl-12">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-base-content/50"></i>

        </div>
        <a href="../../../../restaurant/backend/table.php?subject=create" onclick="return confirm('voulez-vous Ajouter une table ?')" class="btn btn-info gap-2">
            <i class="fas fa-plus"></i> Ajouter une Table
        </a>
    </div>
    <div class="flex gap-10 justify-between">
        <div class="w-full rounded-box  ">

            <div class="overflow-x-auto h-125 bg-base-100 rounded-box ">
                <table class="table text-center table-zebra relative  w-full">
                    <thead class="sticky z-100 top-0 ">
                        <tr class="bg-base-200">
                            <th><i class="fa-solid fa-tag"></i> CODE</th>
                            <th><i class="fa-solid fa-chair"></i> TABLES</th>
                            <th class="text-center"> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        <?php
                        $libre = "SELECT * FROM restaurant_table WHERE occupation = 0  ORDER BY idtable ASC";
                        $result = mysqli_query($connect, $libre);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $tableNumber = (int) substr($row['idtable'], 1);
                        ?>
                                <tr>
                                    <td>
                                        <p class="badge badge-success"><?= "#" . $row['idtable'] ?></p>
                                    </td>
                                    <td>
                                        <?= "TABLE " . sprintf("%02d", $tableNumber) ?>
                                    </td>
                                    <td>
                                        <a href="../../../../restaurant/backend/table.php?subject=delete&id=<?= $row['idtable'] ?>" class="btn btn-sm btn-error"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>

                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="w-full  ">

            <div class="overflow-x-auto h-125 bg-base-100 rounded-box ">
                <table class="table text-center table-zebra relative  w-full">
                    <thead class="sticky z-100 top-0 ">
                        <tr class="bg-base-200">
                            <th><i class="fa-solid fa-tag"></i> CODE</th>
                            <th><i class="fa-solid fa-chair"></i> TABLE</th>
                            <th><i class="fa-solid fa-user"></i> CLIENT</th>
                            <th class="text-center w-32"> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $occuper = "SELECT * FROM restaurant_table WHERE occupation = 1 OR occupation = 2 ORDER BY idtable ASC";
                        $result = mysqli_query($connect, $occuper);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <td>
                                    <p class="badge-error badge"><?= "#" . $row['idtable'] ?></p>
                                </td>
                                <td>
                                    <h2 class="">05</h2>
                                </td>
                                <td>

                                    <span><?= $row['designation'] ?></span>

                                </td>
                                <td>
                                    <a href="../../../../restaurant/backend/table.php?subject=update&id=<?= $row['idtable'] ?>" class="btn btn-sm btn-success">Liberer</a>
                                </td>
                                </tr>

                            <?php
                            }
                        } else {
                            ?>


                        <?php
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    (function() {
        const searchInput = document.getElementById('searchInput');
        const cards = document.querySelectorAll('tbody tr');
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    })();
</script>