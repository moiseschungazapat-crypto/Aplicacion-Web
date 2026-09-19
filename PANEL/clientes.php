<?php
session_start();

if(
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["nombre"])
){
    header("Location: ../LOGIN/login.php");
    exit();
}

$menu   = "clientes";
$titulo = "Clientes";

include("../CONTROLADOR/ClienteControlador.php");

$clienteCtrl = new ClienteControlador();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'guardar') {
        $nombre            = $_POST['nombre'] ?? '';
        $telefono          = $_POST['telefono'] ?? '';
        $correo            = $_POST['correo'] ?? '';
        $plataforma        = $_POST['plataforma'] ?? '';
        $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? date('Y-m-d');

        $clienteCtrl->RegistrarCliente($nombre, $telefono, $correo, $plataforma, $fecha_vencimiento);
        header("Location: clientes.php");
        exit();
    }

    if ($_POST['accion'] === 'editar') {
        $id                = $_POST['id_cliente'] ?? '';
        $nombre            = $_POST['nombre'] ?? '';
        $telefono          = $_POST['telefono'] ?? '';
        $correo            = $_POST['correo'] ?? '';
        $plataforma        = $_POST['plataforma'] ?? '';
        $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? date('Y-m-d');

        $clienteCtrl->ActualizarCliente($id, $nombre, $telefono, $correo, $plataforma, $fecha_vencimiento);
        header("Location: clientes.php");
        exit();
    }
}

if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    $clienteCtrl->EliminarCliente($_GET['id']);
    header("Location: clientes.php");
    exit();
}

$listaClientes   = $clienteCtrl->ObtenerClientes();
$metricasCliente = $clienteCtrl->ObtenerMetricas();

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
?>

<style>
.kpi-card {
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,.10);
    transition: .25s;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,.18);
}
.badge-netflix   { background-color: #e50914; color: #fff; }
.badge-disney    { background-color: #113ccf; color: #fff; }
.badge-prime     { background-color: #00a8e1; color: #fff; }
.badge-max       { background-color: #002be7; color: #fff; }
.badge-spotify   { background-color: #1db954; color: #fff; }
.badge-apple     { background-color: #000000; color: #fff; }
.badge-youtube   { background-color: #ff0000; color: #fff; }
.badge-paramount { background-color: #0064ff; color: #fff; }
.badge-default   { background-color: #6c757d; color: #fff; }

.status-activo  { background-color: #d1e7dd; color: #0f5132; font-weight: 600; }
.status-vencer  { background-color: #fff3cd; color: #664d03; font-weight: 600; }
.status-vencido { background-color: #f8d7da; color: #842029; font-weight: 600; }
</style>

<section class="content-header">
<div class="container-fluid">

    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1>Gestión de Clientes</h1>
            <p class="text-muted">Administración de clientes y membresías</p>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-danger btn-lg shadow-sm" data-toggle="modal" data-target="#modalCliente" onclick="prepararModalCrear()">
                <i class="fas fa-plus mr-1"></i> Nuevo Cliente
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-primary kpi-card">
                <div class="inner">
                    <h3><?php echo $metricasCliente['activos'] ?? 0; ?></h3>
                    <p>Clientes Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-warning kpi-card">
                <div class="inner">
                    <h3><?php echo $metricasCliente['por_vencer'] ?? 0; ?></h3>
                    <p>Por Vencer</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-danger kpi-card">
                <div class="inner">
                    <h3><?php echo $metricasCliente['vencidos'] ?? 0; ?></h3>
                    <p>Vencidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-xmark"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card border-0 shadow-sm">
        <div class="card-body">
            
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="inputBuscar" class="form-control border-left-0" placeholder="Buscar cliente o teléfono..." onkeyup="filtrarTabla()">
                    </div>
                </div>
                <div class="col-md-8 text-md-right mb-2">
                    <select id="filtroPlataforma" class="custom-select w-auto mr-1" onchange="filtrarTabla()">
                        <option value="">Todas las plataformas</option>
                        <option value="Netflix Premium">Netflix Premium</option>
                        <option value="Disney Plus">Disney Plus</option>
                        <option value="Prime Video">Prime Video</option>
                        <option value="HBO Max">HBO Max</option>
                        <option value="Spotify Premium">Spotify Premium</option>
                        <option value="Apple TV+">Apple TV+</option>
                        <option value="YouTube Premium">YouTube Premium</option>
                        <option value="Paramount+">Paramount+</option>
                    </select>
                    <select id="filtroEstado" class="custom-select w-auto" onchange="filtrarTabla()">
                        <option value="">Todos los estados</option>
                        <option value="Activo">Activo</option>
                        <option value="Por vencer">Por vencer</option>
                        <option value="Vencido">Vencido</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaClientes">
                    <thead class="bg-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Plataforma</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (is_array($listaClientes) && count($listaClientes) > 0):
                            foreach ($listaClientes as $fila):
                                $telefono = $fila['telefono'] ?? '';
                                $telLimpio = preg_replace('/[^0-9]/', '', $telefono);
                                $plataforma = $fila['plataforma'] ?? 'N/A';
                                
                                $hoy = date("Y-m-d");
                                $vence = $fila["fecha_vencimiento"] ?? $hoy;
                                $dias = floor((strtotime($vence) - strtotime($hoy)) / 86400);

                                if ($dias < 0) {
                                    $estadoTexto = "Vencido";
                                    $estadoClass = "status-vencido";
                                } elseif ($dias <= 3) {
                                    $estadoTexto = "Por vencer";
                                    $estadoClass = "status-vencer";
                                } else {
                                    $estadoTexto = "Activo";
                                    $estadoClass = "status-activo";
                                }

                                $badgePlat = 'badge-default';
                                $platLower = strtolower($plataforma);
                                if (strpos($platLower, 'netflix') !== false) $badgePlat = 'badge-netflix';
                                elseif (strpos($platLower, 'disney') !== false) $badgePlat = 'badge-disney';
                                elseif (strpos($platLower, 'prime') !== false) $badgePlat = 'badge-prime';
                                elseif (strpos($platLower, 'hbo') !== false || strpos($platLower, 'max') !== false) $badgePlat = 'badge-max';
                                elseif (strpos($platLower, 'spotify') !== false) $badgePlat = 'badge-spotify';
                                elseif (strpos($platLower, 'apple') !== false) $badgePlat = 'badge-apple';
                                elseif (strpos($platLower, 'youtube') !== false) $badgePlat = 'badge-youtube';
                                elseif (strpos($platLower, 'paramount') !== false) $badgePlat = 'badge-paramount';

                                $fechaFormateada = !empty($fila["fecha_vencimiento"]) ? date("d/m/Y", strtotime($fila["fecha_vencimiento"])) : '-';
                                $mensajeWa = "Hola " . ($fila['nombre'] ?? '') . " 😊, te recordamos que tu servicio de " . $plataforma . " vence el " . $fechaFormateada . ". Si deseas renovarlo, escríbenos. Te saluda EvyStream.";
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($fila["nombre"] ?? ''); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($telefono); ?></small><br>
                                <small class="text-primary">Vence: <?php echo $fechaFormateada; ?></small>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-pill p-2 <?php echo $badgePlat; ?>">
                                    <?php echo htmlspecialchars($plataforma); ?>
                                </span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-pill p-2 <?php echo $estadoClass; ?>">
                                    <?php echo htmlspecialchars($estadoTexto); ?>
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <a href="https://wa.me/<?php echo $telLimpio; ?>?text=<?php echo urlencode($mensajeWa); ?>" target="_blank" class="btn btn-sm btn-outline-success mr-1" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary mr-1" title="Editar" onclick='prepararModalEditar(<?php echo json_encode($fila); ?>)' data-toggle="modal" data-target="#modalCliente">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="clientes.php?accion=eliminar&id=<?php echo $fila['id_cliente']; ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Seguro de eliminar este cliente?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No se encontraron clientes registrados.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
</section>

<div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form action="clientes.php" method="POST">
                <input type="hidden" name="accion" id="modalAccion" value="guardar">
                <input type="hidden" name="id_cliente" id="modalIdCliente" value="">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalTitulo">Registrar Cliente</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre" id="modalNombre" class="form-control" required placeholder="Ej. Juan Pérez">
                    </div>
                    <div class="form-group">
                        <label>Teléfono / WhatsApp</label>
                        <input type="text" name="telefono" id="modalTelefono" class="form-control" required placeholder="Ej. +51 987654321">
                    </div>
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" id="modalCorreo" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="form-group">
                        <label>Plataforma</label>
                        <select name="plataforma" id="modalPlataforma" class="form-control" required>
                            <option value="">Seleccione una plataforma</option>
                            <option value="Netflix Premium">Netflix Premium</option>
                            <option value="Disney Plus">Disney Plus</option>
                            <option value="Prime Video">Prime Video</option>
                            <option value="HBO Max">HBO Max</option>
                            <option value="Spotify Premium">Spotify Premium</option>
                            <option value="Apple TV+">Apple TV+</option>
                            <option value="YouTube Premium">YouTube Premium</option>
                            <option value="Paramount+">Paramount+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="modalFechaVencimiento" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include("includes/footer.php");
include("includes/scripts.php");
?>

<script>
function prepararModalCrear() {
    document.getElementById('modalTitulo').innerText = 'Registrar Cliente';
    document.getElementById('modalAccion').value = 'guardar';
    document.getElementById('modalIdCliente').value = '';
    document.getElementById('modalNombre').value = '';
    document.getElementById('modalTelefono').value = '';
    document.getElementById('modalCorreo').value = '';
    document.getElementById('modalPlataforma').value = '';
    document.getElementById('modalFechaVencimiento').value = new Date().toISOString().split('T')[0];
}

function prepararModalEditar(cliente) {
    document.getElementById('modalTitulo').innerText = 'Editar Cliente';
    document.getElementById('modalAccion').value = 'editar';
    document.getElementById('modalIdCliente').value = cliente.id_cliente || '';
    document.getElementById('modalNombre').value = cliente.nombre || '';
    document.getElementById('modalTelefono').value = cliente.telefono || '';
    document.getElementById('modalCorreo').value = cliente.correo || '';
    document.getElementById('modalPlataforma').value = cliente.plataforma || '';
    document.getElementById('modalFechaVencimiento').value = cliente.fecha_vencimiento || new Date().toISOString().split('T')[0];
}

function filtrarTabla() {
    const texto = document.getElementById('inputBuscar').value.toLowerCase();
    const plataforma = document.getElementById('filtroPlataforma').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    
    const filas = document.querySelectorAll('#tablaClientes tbody tr');

    filas.forEach(fila => {
        const contenidoFila = fila.innerText.toLowerCase();
        const coincideTexto = contenidoFila.includes(texto);
        const coincidePlataforma = plataforma === "" || contenidoFila.includes(plataforma);
        const coincideEstado = estado === "" || contenidoFila.includes(estado);

        if (coincideTexto && coincidePlataforma && coincideEstado) {
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });
}
</script>