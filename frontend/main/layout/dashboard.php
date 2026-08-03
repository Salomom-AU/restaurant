<?php
$totalTables = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM restaurant_table"))['total'];
$occupe = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM restaurant_table WHERE occupation IN (1,2)"))['total'];
$libre = $totalTables - $occupe;
$totalMenu = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM menu"))['total'];
$caJour = "1245000";
$totalReserver = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM reservation"))['total'];
$totalCommande = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM commande"))['total'];
$surtable = (int) mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM commande WHERE typecom = 'surTable'"))['total'];
$emporter = $totalCommande - $surtable;
$platVendus = "SELECT 
    m.idplat,
    m.nomplat,
    m.pu,
    SUM(cd.quantite) AS total_vendu,
    SUM(cd.quantite * cd.prix_unitaire) AS chiffre_affaire
FROM commande_detail cd
JOIN menu m ON cd.idplat = m.idplat
GROUP BY m.idplat, m.nomplat, m.pu
ORDER BY total_vendu DESC
LIMIT 10";
$caTotal = (float) mysqli_fetch_assoc(mysqli_query($connect, "
    SELECT COALESCE(SUM(quantite * prix_unitaire), 0) as total 
    FROM commande_detail
"))['total'];

$moisLabels = [];
$moisData   = [];
for ($i = 5; $i >= 0; $i--) {
  $debut = date('Y-m-01', strtotime("-$i months"));
  $fin   = date('Y-m-t', strtotime("-$i months"));
  $label = date('M', strtotime("-$i months"));
  $q = mysqli_query($connect, "
        SELECT COALESCE(SUM(cd.quantite * cd.prix_unitaire), 0) as total
        FROM commande_detail cd
        JOIN commande c ON cd.idcom = c.idcom
        WHERE DATE(c.datecom) BETWEEN '$debut' AND '$fin'
    ");
  $row = mysqli_fetch_assoc($q);
  $moisLabels[] = $label;
  $moisData[]   = (float) $row['total'];
  $dateDebut = $_GET['debut'] ?? '';
$dateFin   = $_GET['fin'] ?? '';
$clients   = [];

if (!empty($dateDebut) || !empty($dateFin)) {
    $where = "1=1";
    if (!empty($dateDebut) && empty($dateFin)) {
        $where .= " AND DATE(c.datecom) = '" . mysqli_real_escape_string($connect, $dateDebut) . "'";
    }
    elseif (!empty($dateDebut) && !empty($dateFin)) {
        $where .= " AND DATE(c.datecom) BETWEEN '" . mysqli_real_escape_string($connect, $dateDebut) . "' 
                                             AND '" . mysqli_real_escape_string($connect, $dateFin) . "'";
    }
    // Seulement date de fin
    elseif (empty($dateDebut) && !empty($dateFin)) {
        $where .= " AND DATE(c.datecom) <= '" . mysqli_real_escape_string($connect, $dateFin) . "'";
    }

    $query = "
        SELECT 
            c.nomcli,
            COUNT(c.idcom) AS nb_commandes,
            MIN(c.datecom) AS premiere_visite,
            MAX(c.datecom) AS derniere_visite,
            SUM(cd.quantite * cd.prix_unitaire) AS total_depense
        FROM commande c
        LEFT JOIN commande_detail cd ON c.idcom = cd.idcom
        WHERE $where
        GROUP BY c.nomcli
        ORDER BY derniere_visite DESC
    ";

    $result = mysqli_query($connect, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $clients[] = $row;
        }
    }
}
}
?>


  <div class="overflow-x-auto w-full p-5 flex flex-col gap-2 h-148  max-w-screen-2xl mx-auto">
    <div class="flex  items-center  justify-between gap-6 ">
      <div class="gsap-header">
        <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Tableau de Bord</h1>
        <p class="text-base-content/60 mt-2 flex items-center gap-3">
          <span>Resto FOOD</span>
          <span class="text-xs px-3 py-1 bg-base-200 rounded-full">Aujourd'hui</span>
          <?= date('l d F Y') ?>
        </p>
      </div>
      <div class="card bg-base-100 border w-xl border-base-200 shadow-xl gsap-ca">
        <div class="card-body">
          <div class="flex justify-between items-center">
            <div>
              <p class="uppercase text-xs tracking-widest text-base-content/50">Recette totale accumulée</p>
              <p class="text-4xl font-bold text-success mt-1">
                <?= number_format($caTotal, 0, ',', ' ') ?> <span class="text-2xl">Ar</span>
              </p>
            </div>
            <div class="text-5xl text-success/20">
              <i class="fas fa-coins"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <div class="card bg-base-100 border border-base-200  shadow-xl gsap-card">
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
      <div class="card bg-base-100 border border-base-200   shadow-xl gsap-card">
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
      <div class="card bg-base-100 border border-base-200  shadow-xl gsap-card">
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
      <div class="card bg-base-100 border border-base-200   shadow-xl gsap-card">
        <div class="card-body">
          <div class="flex justify-between items-start">
            <div>
              <p class="uppercase text-xs tracking-widest text-base-content/50">Totals des commandes
              </p>
              <p class="text-6xl font-bold text-info mt-2"><?= $totalCommande ?></p>
            </div>
          </div>
          <div class="mt-4 text-sm flex items-center justify-between ">
            <span class="text-success"> <i class="fas mr-2 fa-arrow-trend-up"></i> <?= $surtable ?> sur table</span>
            <span class="text-error"> <i class="fas mr-2 fa-arrow-trend-down"></i> <?= $emporter ?> emporter</span>
            <a href="../../../../restaurant/frontend/main/main.php?commande=1" class="text-info link">voir </a>
          </div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
      <div class="lg:col-span-5 card bg-base-100 shadow-xl border border-base-200 gsap-chart">
        <div class="card-body">
          <h2 class="card-title mb-6">Évolution des Recettes - 6 Mois</h2>
          <div class="h-100">
            <canvas id="recetteChart"></canvas>
          </div>
        </div>
      </div>
      <div class="lg:col-span-2 card bg-base-100 shadow-xl border border-base-200 gsap-top">
        <div class="card-body h-full flex flex-col">
          <h2 class="card-title text-info font-bold mb-4"><i class="fas fa-gem"></i> Top 10 des plats les plus vendus </h2>
          <div class=" overflow-auto h-100 space-y-3">
            <?php
            $result = mysqli_query($connect, $platVendus);
            $rang = 1;
            while ($row = mysqli_fetch_assoc($result)):
            ?>
              <div class="flex items-center gap-3 p-3 rounded-lg bg-base-200/50 hover:bg-base-200 transition gsap-plat">
        
                <div class="flex-1 min-w-0">


                  <p class="font-medium truncate"><?= htmlspecialchars($row['nomplat']) ?></p>
                  <p class="text-xs text-base-content/60"><?= $row['total_vendu'] ?> vendus</p>
                </div>
                <div class="text-right">
                  <p class="font-semibold text-success text-sm">
                    <?= number_format($row['chiffre_affaire'], 0, ',', ' ') ?> Ar
                  </p>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </div>
  </div>


<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
  new Chart(document.getElementById('recetteChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode($moisLabels) ?>,
      datasets: [{
        label: 'Recettes',
        data: <?= json_encode($moisData) ?>,
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

  // ========== GSAP ENTER ANIMATIONS ==========
  gsap.set([".gsap-header", ".gsap-ca", ".gsap-card", ".gsap-chart", ".gsap-top", ".gsap-plat"], {
    opacity: 0,
    y: 40
  });

  const tl = gsap.timeline({ defaults: { ease: "power3.out" } });

  tl.to(".gsap-header", {
    opacity: 1,
    y: 0,
    duration: 0.7
  })
  .to(".gsap-ca", {
    opacity: 1,
    y: 0,
    duration: 0.6
  }, "-=0.4")
  .to(".gsap-card", {
    opacity: 1,
    y: 0,
    duration: 0.55,
    stagger: 0.12
  }, "-=0.3")
  .to(".gsap-chart", {
    opacity: 1,
    y: 0,
    duration: 0.7
  }, "-=0.25")
  .to(".gsap-top", {
    opacity: 1,
    y: 0,
    duration: 0.65
  }, "-=0.45")
  .to(".gsap-plat", {
    opacity: 1,
    y: 0,
    duration: 0.45,
    stagger: 0.08
  }, "-=0.3");
</script>