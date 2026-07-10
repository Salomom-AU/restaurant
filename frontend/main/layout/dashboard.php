    <?php
    $totalTables = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM restaurant_table"))['total'];
    $occupe = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM restaurant_table WHERE occupation IN (1,2)"))['total'];
    $libre = $totalTables - $occupe;
    $totalMenu = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM menu"))['total'];
    $caJour = "1245000";
    $totalReserver = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM reserver"))['total'];
    ?>
    <div class="">
    <div class="overflow-x-auto w-full p-5 flex flex-col gap-2 h-150  max-w-screen-2xl mx-auto">
      <div class="flex  items-center  justify-between gap-6 ">
        <div>
          <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Tableau de Bord</h1>
          <p class="text-base-content/60 mt-2 flex items-center gap-3">
            <span>Resto FOOD</span>
            <span class="text-xs px-3 py-1 bg-base-200 rounded-full">Aujourd'hui</span>
            <?= date('l d F Y') ?>
          </p>
        </div>
        <div class="card bg-base-100 border border-base-200 w-xl   shadow-xl">
          <div class="card-body">
            <div class="flex justify-between items-start">
              <div>
                <p class="uppercase text-xs tracking-widest text-base-content/50">CA Aujourd'hui</p>
                <p class="text-5xl font-bold text-info mt-2"><?= number_format($caJour, 0, ',', ' ') ?> <span class="text-2xl">Ar</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <div class="card bg-base-100 border border-base-200  shadow-xl">
          <div class="card-body">
            <div class="flex justify-between items-start">
              <div>
                <p class="uppercase text-xs tracking-widest text-base-content/50">Total des Tables</p>
                <p class="text-6xl font-bold text-primary mt-2"><?= $totalTables ?></p>
              </div>
            </div>
            <div class="mt-4 text-sm flex items-center justify-between ">
              <span class="text-success"> <i class="fas mr-2 fa-arrow-trend-up"></i> <?= $libre ?> disponibles</span>
              <span class="text-error"> <i class="fas mr-2 fa-arrow-trend-down"></i> <?= $occupe ?> occuper</span>
              <a href="../../../../restaurant/frontend/main/main.php?table=1" class="text-info link">voir </a>
            </div>
          </div>
        </div>

        <div class="card bg-base-100 border border-base-200   shadow-xl">
          <div class="card-body">
            <div class="flex justify-between items-start">
              <div>
                <p class="uppercase text-xs tracking-widest text-base-content/50">Totals des menus</p>
                <p class="text-6xl font-bold text-error mt-2"><?= $totalMenu ?></p>
              </div>
            </div>
            <div class="mt-4 text-sm flex items-center justify-between ">
              <span class="text-success"> <i class="fas mr-2 fa-arrow-trend-up"></i> <?= $totalMenu ?> disponibles</span>
              <a href="../../../../restaurant/frontend/main/main.php?menu=1" class="text-info link">voir tous les menus </a>
            </div>
          </div>
        </div>
        <div class="card bg-base-100 border border-base-200  shadow-xl">
          <div class="card-body">
            <div class="flex justify-between items-start">
              <div>
                <p class="uppercase text-xs tracking-widest text-base-content/50">Totals des reservations</p>
                <p class="text-6xl font-bold text-success mt-2"><?= $totalReserver ?></p>
              </div>
            </div>
             <div class="mt-4 text-sm flex items-center justify-between ">
              <span class="text-primary"> <i class="fas mr-2 fa-clock"></i> <?= $totalReserver ?> en attente</span>
              <a href="../../../../restaurant/frontend/main/main.php?reserver=1" class="text-info link">voir tous les reservations </a>
            </div>
          </div>
        </div>
        <div class="card bg-base-100 border border-base-200   shadow-xl">
          <div class="card-body">
            <div class="flex justify-between items-start">
              <div>
                <p class="uppercase text-xs tracking-widest text-base-content/50">Totals des commandes
                </p>
                <p class="text-6xl font-bold text-success mt-2"><?= $libre ?></p>
              </div>
            </div>
          </div>
        </div>



      </div>


      <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
        <div class="lg:col-span-5 card bg-base-100 shadow-xl border border-base-200">
          <div class="card-body">
            <h2 class="card-title mb-6">Évolution des Recettes - 6 Mois</h2>
            <div class="h-100">
              <canvas id="recetteChart"></canvas>
            </div>
          </div>
        </div>

        <div class="lg:col-span-2 card bg-base-100 shadow-xl border border-base-200">
          <div class="card-body h-full flex flex-col">
            
              
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
      new Chart(document.getElementById('recetteChart'), {
        type: 'bar',
        data: {
          labels: ['Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil'],
          datasets: [{
            label: 'Recettes',
            data: [980000, 1250000, 1670000, 1340000, 1890000, 2150000],
            backgroundColor: '#222ac5',
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                color: '#94a3b8'
              }
            },
            x: {
              ticks: {
                color: '#94a3b8'
              }
            }
          }
        }
      });
    </script>