<?php

include __DIR__ . '/../../../backend/db.php';
$status = '';
$statusColor = '';
if (isset($_GET['message'])) {
    $statusMessages = [
        'delete' => ['text' => 'vous venez de supprimé un table avec succès', 'color' => 'bg-green-500/80'],
        'error' => ['text' => 'une erreur est produit veuillez reassayer ', 'color' => 'bg-red-500/90'],
        'updated' => ['text' => 'vous venez de liberer un table avec succes', 'color' => 'bg-green-500/80'],
        'added' => ['text' => 'Menu ajouter avec succes', 'color' => 'bg-green-500/80']
    ];

    if (array_key_exists($_GET['message'], $statusMessages)) {
        $status = $statusMessages[$_GET['message']]['text'];
        $statusColor = $statusMessages[$_GET['message']]['color'];
    }
}
?>



<div class="p-5 flex flex-col gap-5">
    <div class="flex justify-between items-center">
        <h1 class="text-4xl font-bold">Gestion des Tables</h1>
        <div class="flex items-center gap-3">
            <label for="" class="label">
                rechercher :
            </label>
            <input type="text" id="searchInput" placeholder="Rechercher..."
                class="w-xl input input-bordered">
        </div>
        <a href="../../../../restaurant/backend/table.php?subject=create" class="btn btn-info gap-2">
            <i class="fas fa-plus"></i> Ajouter une Table
        </a>
    </div>
    <div class="flex text-slate-800 gap-5">
        <?php
        $tableNomOccuper = "SELECT COUNT(*) AS table_Non_occuper FROM restaurant_table WHERE occupation = 0  ";
        $total = mysqli_query($connect, $tableNomOccuper);
        if ($total && mysqli_num_rows($total) > 0) {
            $row  = mysqli_fetch_assoc($total);
            $Totals = $row['table_Non_occuper'];
        }


        ?>
        <div class=" p-5 rounded-box shadow w-sm bg-success">
            <h1 class="text-3xl  font-bold">
                Table non occuper
            </h1>
            <div class="flex text-2xl items-center gap-2">
                <h2 class="font-bold">
                    total :
                </h2>
                <p><?= $Totals . " Tables" ?></p>
            </div>
        </div>
        <?php
        $tableNomOccuper = "SELECT COUNT(*) AS table_Non_occuper FROM restaurant_table WHERE occupation = 1 OR occupation = 2  ";
        $total = mysqli_query($connect, $tableNomOccuper);
        if ($total && mysqli_num_rows($total) > 0) {
            $row  = mysqli_fetch_assoc($total);
            $Totals = $row['table_Non_occuper'];
        }


        ?>
        <div class=" p-5 rounded-box shadow w-xl bg-error">
            <h1 class="text-3xl  font-bold">
                Table occuper
            </h1>
            <div class="flex text-2xl items-center gap-2">
                <h2 class="font-bold">
                    total :
                </h2>
                <p><?= $Totals . " Tables" ?></p>
            </div>
        </div>


    </div>
    <div class="flex gap-10 justify-between">
        <div class=" w-xl flex flex-col gap-5 overflow-y-auto  min-h-80 max-h-80">

            <?php
            $libre = "SELECT * FROM restaurant_table WHERE occupation = 0  ORDER BY idtable ASC";
            $result = mysqli_query($connect, $libre);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>

                    <div class=" flex bg-base-200 p-5 justify-between shadow-2xl items-center rounded-box  text-center">
                        <div class="text-6xl ">
                            <i class="fas fa-chair text-success"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">TABLE</h2>
                            <p class="badge-success badge">#<?= $row['idtable'] ?></p>
                        </div>
                        <div>
                            <a href="../../../../restaurant/backend/table.php?subject=delete&&id=<?= $row['idtable'] ?>" class="btn btn-error"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>




                <?php
                }
            } else {
                ?>
                <div class=" flex bg-base-200 p-5 justify-center items-center rounded-box  text-center">
                    <p>Aucun table libre </p>
                </div>
            <?php
            }
            ?>
        </div>




        <div class="w-full rounded-box  overflow-y-auto bg  min-h-80 max-h-80 ">

            <table class="table shadow-2xl rounded-box text-center w-full table-zebra ">
                <thead>
                    <tr class="bg-base-200">
                        <th>#</th>
                        <th>TABLE</th>
                        <th>CLIENT</th>
                        <th class="text-center w-32">ACTIONS</th>
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
                                <p class="badge-error badge"><?= $row['idtable'] ?></p>
                            </td>
                            <td>
                                <h2 class="">05</h2>
                            </td>
                            <td>

                                <span><?= $row['designation'] ?></span>

                            </td>
                            <td>
                                <a href="../../../../restaurant/backend/table.php?subject=update&id=<?= $row['idtable'] ?>" class="btn btn-success">Liberer</a>
                            </td>
                            </tr>

                        <?php
                        }
                    } else {
                        ?>
                        <td>
                            <p>Aucun Table occuper </p>
                        </td>

                    <?php
                    }

                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    (function() {
        const searchInput = document.getElementById('searchInput');
        const cards = document.querySelectorAll('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    })();
</script>