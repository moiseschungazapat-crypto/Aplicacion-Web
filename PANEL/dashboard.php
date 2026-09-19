<?php

session_start();

if(
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["nombre"])
){
    header("Location: ../LOGIN/login.php");
    exit();
}

$menu   = "dashboard";
$titulo = "Dashboard";

include("../CONTROLADOR/DashboardControlador.php");

$dashboard = new DashboardControlador();

$totalProductos = $dashboard->TotalProductos();
$totalUsuarios  = $dashboard->TotalUsuarios();
$totalVentas    = $dashboard->TotalVentas();
$totalIngresos  = $dashboard->TotalIngresos();

$stockBajo      = $dashboard->StockBajo();
$ventasAnuladas = $dashboard->VentasAnuladas();

$ultimasVentas  = $dashboard->UltimasVentas();

$ventasMes      = $dashboard->VentasPorMes();
$topProductos   = $dashboard->TopPlataformas();

$meses   = [];
$totales = [];

if (is_array($ventasMes)) {
    foreach ($ventasMes as $fila) {
        $meses[]   = $fila["mes"];
        $totales[] = $fila["total"];
    }
} elseif ($ventasMes) {
    while ($fila = pg_fetch_assoc($ventasMes)) {
        $meses[]   = $fila["mes"];
        $totales[] = $fila["total"];
    }
}

$productos = [];
$cantidades = [];

if (is_array($topProductos)) {
    foreach ($topProductos as $fila) {
        if(isset($fila["nombre"])){
            $productos[]  = $fila["nombre"];
            $cantidades[] = (int)($fila["vendidos"] ?? $fila["cantidad"] ?? 1);
        }
    }
} elseif ($topProductos) {
    while ($fila = pg_fetch_assoc($topProductos)) {
        if(isset($fila["nombre"])){
            $productos[]  = $fila["nombre"];
            $cantidades[] = (int)($fila["vendidos"] ?? $fila["cantidad"] ?? 1);
        }
    }
}

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

?>

<style>

.dashboard-card{
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,.10);
    transition:.25s;
}

.dashboard-card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 25px rgba(0,0,0,.18);
}

.chart-card{
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,.10);
}

.chart-container{
    position:relative;
    height:340px;
}

.table-card{
    border-radius:15px;
    box-shadow:0 3px 10px rgba(0,0,0,.10);
}

.small-box{
    border-radius:15px !important;
}

</style>

<section class="content-header">

<div class="container-fluid">

<div class="row mb-3">

<div class="col-md-12">

<h1>

Bienvenido,
<strong><?php echo $_SESSION["nombre"]; ?></strong>
👋

</h1>

<p class="text-muted">

Panel Administrativo de EvyStream

</p>

</div>

</div>

<div class="row">

<div class="col-lg-3 col-md-6">

<div class="small-box bg-primary dashboard-card">

<div class="inner">

<h3>

<?php echo $totalUsuarios["total"] ?? 0; ?>

</h3>

<p>

Clientes

</p>

</div>

<div class="icon">

<i class="fas fa-users"></i>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="small-box bg-success dashboard-card">

<div class="inner">

<h3>

<?php echo $totalProductos["total"] ?? 0; ?>

</h3>

<p>

Plataformas

</p>

</div>

<div class="icon">

<i class="fas fa-tv"></i>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

    <div class="small-box bg-warning dashboard-card">

        <div class="inner">

            <h3>

                <?php echo $totalVentas["total"] ?? 0; ?>

            </h3>

            <p>

                Ventas

            </p>

        </div>

        <div class="icon">

            <i class="fas fa-shopping-cart"></i>

        </div>

    </div>

</div>

<div class="col-lg-3 col-md-6">

    <div class="small-box bg-danger dashboard-card">

        <div class="inner">

            <h3>

                S/. <?php echo number_format($totalIngresos["total"] ?? 0, 2); ?>

            </h3>

            <p>

                Ingresos

            </p>

        </div>

        <div class="icon">

            <i class="fas fa-wallet"></i>

        </div>

    </div>

</div>

</div>

<div class="row mt-3">

<div class="col-lg-8">

<div class="card chart-card">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-chart-line mr-2"></i>

Ventas por Mes

</h3>

</div>

<div class="card-body">

<div class="chart-container">

<canvas id="graficoVentas"></canvas>

</div>

</div>

</div>

</div>

<div class="col-lg-4">

<div class="card chart-card">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-crown mr-2"></i>

Plataformas Más Vendidas

</h3>

</div>

<div class="card-body">

<div class="chart-container d-flex align-items-center justify-content-center">

<?php if (!empty($productos)): ?>

<canvas id="graficoTop"></canvas>

<?php else: ?>

<div class="text-center text-muted">

<i class="fas fa-chart-pie fa-3x mb-2 text-secondary"></i>

<p class="mb-0">Sin ventas registradas aún</p>

</div>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="small-box bg-secondary dashboard-card">

<div class="inner">

<h3>

<?php echo $stockBajo["total"] ?? 0; ?>

</h3>

<p>

Stock Bajo

</p>

</div>

<div class="icon">

<i class="fas fa-exclamation-triangle"></i>

</div>

</div>

</div>

<div class="col-md-6">

<div class="small-box bg-dark dashboard-card">

<div class="inner">

<h3>

<?php echo $ventasAnuladas["total"] ?? 0; ?>

</h3>

<p>

Ventas Anuladas

</p>

</div>

<div class="icon">

<i class="fas fa-ban"></i>

</div>

</div>

</div>

</div>

<div class="row mt-3">

<div class="col-12">

<div class="card table-card">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-history mr-2"></i>

Últimas Ventas

</h3>

</div>

<div class="card-body p-0">

<table class="table table-hover table-striped mb-0">

<thead class="bg-light">

<tr>

<th style="width:40%">

Cliente

</th>

<th style="width:20%">

Fecha

</th>

<th style="width:20%">

Total

</th>

<th style="width:20%">

Estado

</th>

</tr>

</thead>

<tbody>

<?php

$filasVentas = [];

if (is_array($ultimasVentas)) {
    $filasVentas = $ultimasVentas;
} elseif ($ultimasVentas) {
    while ($f = pg_fetch_assoc($ultimasVentas)) {
        $filasVentas[] = $f;
    }
}

foreach ($filasVentas as $fila) {

?>

<tr>

<td>

<i class="fas fa-user text-primary mr-2"></i>

<?php echo $fila["cliente"]; ?>

</td>

<td>

<?php echo date("d/m/Y", strtotime($fila["fecha"])); ?>

</td>

<td>

<strong>

S/. <?php echo number_format($fila["total"], 2); ?>

</strong>

</td>

<td>

<span class="badge badge-<?php echo ($fila["estado"] == "t" || $fila["estado"] == "1" || $fila["estado"] === true) ? "success" : "danger"; ?>">

    <?php echo ($fila["estado"] == "t" || $fila["estado"] == "1" || $fila["estado"] === true) ? "Activa" : "Anulada"; ?>

</span>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</section>

<?php

include("includes/footer.php");

include("includes/scripts.php");

?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctxVentas = document.getElementById("graficoVentas");

if(ctxVentas){

new Chart(ctxVentas,{

type:"bar",

data:{

labels: <?php echo json_encode(array_map(fn($m) => "Mes " . $m, $meses)); ?>,

datasets:[{

label:"Ventas",

data: <?php echo json_encode(array_map('floatval', $totales)); ?>,

backgroundColor:"#dc3545",

hoverBackgroundColor:"#c82333",

borderRadius:8,

borderSkipped:false,

maxBarThickness:45

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

interaction:{

mode:"index",

intersect:false

},

plugins:{

legend:{

display:false

},

tooltip:{

backgroundColor:"#343a40",

titleColor:"#ffffff",

bodyColor:"#ffffff",

padding:12,

displayColors:false

}

},

scales:{

x:{

grid:{

display:false

},

ticks:{

font:{

size:12

}

}

},

y:{

beginAtZero:true,

grid:{

color:"rgba(0,0,0,.08)"

},

ticks:{

precision:0,

font:{

size:12

}

}

}

},

animation:{

duration:1200

}

}

});

}

const ctxTop = document.getElementById("graficoTop");

if(ctxTop){

new Chart(ctxTop,{

type:"doughnut",

data:{

labels: <?php echo json_encode($productos); ?>,

datasets:[{

data: <?php echo json_encode(array_map('intval', $cantidades)); ?>,

backgroundColor:[

"#007bff",
"#28a745",
"#ffc107",
"#dc3545",
"#6f42c1",
"#17a2b8",
"#fd7e14",
"#20c997"

],

hoverOffset:15,

borderColor:"#ffffff",

borderWidth:3

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

cutout:"65%",

plugins:{

legend:{

position:"bottom",

labels:{

padding:20,

boxWidth:15,

font:{

size:12

}

}

},

tooltip:{

backgroundColor:"#343a40",

titleColor:"#ffffff",

bodyColor:"#ffffff",

padding:12

}

},

animation:{

animateRotate:true,

animateScale:true,

duration:1500

}

}

});

}

window.addEventListener("resize",function(){

    if(window.graficoVentas){

        window.graficoVentas.resize();

    }

    if(window.graficoTop){

        window.graficoTop.resize();

    }

});

document.addEventListener("DOMContentLoaded",function(){

    const tarjetas=document.querySelectorAll(".dashboard-card");

    tarjetas.forEach(function(card,index){

        card.style.opacity="0";
        card.style.transform="translateY(20px)";

        setTimeout(function(){

            card.style.transition=".45s";
            card.style.opacity="1";
            card.style.transform="translateY(0px)";

        },index*120);

    });

});

</script>