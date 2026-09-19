<?php

include("CONFIG/conexion.php");
include("CONTROLADOR/ProductoControlador.php");

$controlador = new ProductoControlador();

$productos = $controlador->MostrarProductos();

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>EvyStream | Streaming Premium</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<link rel="stylesheet" href="CSS/estilo.css?v=6">

<style>
.search-box-custom {
    background-color: #141720;
    border: 1px solid #232734;
    border-radius: 12px;
    overflow: hidden;
}

.search-box-custom .input-group-text {
    background-color: transparent;
    border: none;
    color: #ff3131;
}

.search-box-custom .form-control {
    background-color: transparent;
    border: none;
    color: #ffffff;
    box-shadow: none;
}

.search-box-custom .form-control::placeholder {
    color: #6c757d;
}

.filter-btn {
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 16px;
    transition: all 0.3s ease;
    border: 1px solid #232734;
    color: #a0a7b5;
    background-color: #141720;
}

.filter-btn:hover {
    background-color: #202432;
    color: #ffffff;
    border-color: #ff3131;
}

.filter-btn.active {
    background-color: #ff3131 !important;
    color: #ffffff !important;
    border-color: #ff3131 !important;
}

.catalogo-grid-compact {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 20px !important;
    justify-content: center !important;
}

@media (max-width: 1200px) {
    .catalogo-grid-compact {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}

@media (max-width: 850px) {
    .catalogo-grid-compact {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 550px) {
    .catalogo-grid-compact {
        grid-template-columns: repeat(1, 1fr) !important;
    }
}

.producto-card-compact {
    background: linear-gradient(180deg, #161922, #11131a);
    border: 1px solid #232734;
    border-radius: 18px;
    padding: 22px 18px;
    text-align: center;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.producto-card-compact:hover {
    transform: translateY(-6px);
    border-color: #ff3131;
    box-shadow: 0 12px 30px rgba(255, 49, 49, 0.22);
}

.badge-compact {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #ff3131;
    color: #fff;
    font-size: 10px;
    font-weight: bold;
    padding: 4px 10px;
    border-radius: 12px;
}

.logo-box-compact {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
    margin-bottom: 12px;
}

.logo-box-compact img {
    max-height: 58px;
    max-width: 150px;
    object-fit: contain;
}

.card-title-compact {
    font-size: 17px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.stock-indicator-compact {
    font-size: 12px;
    color: #25d366;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-bottom: 12px;
}

.stock-dot {
    width: 7px;
    height: 7px;
    background-color: #25d366;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 6px rgba(37, 211, 102, 0.8);
}

.price-box-compact {
    font-size: 13px;
    color: #a0a7b5;
    margin-bottom: 15px;
}

.price-box-compact strong {
    font-size: 19px;
    color: #ffffff;
    display: inline-block;
    margin-left: 4px;
}

.btn-comprar-compact {
    width: 100%;
    padding: 10px 16px;
    background: #ff3131;
    border: none;
    border-radius: 25px;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    transition: all 0.2s ease;
}

.btn-comprar-compact:hover {
    background: #e02828;
    transform: scale(1.02);
}

.btn-ver-mas-container {
    text-align: center;
    margin-top: 35px;
}

.btn-ver-mas {
    background-color: #141720;
    border: 1px solid #232734;
    color: #ffffff;
    padding: 12px 28px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-ver-mas:hover {
    background-color: #ff3131;
    border-color: #ff3131;
    color: #ffffff;
}

.card-item-oculto {
    display: none !important;
}

.modal-plan-content {
    background-color: #121318;
    border: 1px solid #2a2d37;
    border-radius: 18px;
}

.option-btn, .duration-btn {
    background-color: #1a1c23;
    border: 1px solid #2a2d37;
    color: #ffffff;
    transition: all 0.2s ease;
}

.option-btn:hover, .duration-btn:hover {
    background-color: #252832;
    border-color: #3b3f4e;
    color: #ffffff;
}

.option-btn.active, .duration-btn.active {
    background-color: rgba(255, 49, 49, 0.15) !important;
    border-color: #ff3131 !important;
    color: #ffffff !important;
}

.summary-card {
    background-color: #1a1c23;
    border: 1px solid #2a2d37;
    border-radius: 12px;
}

.combo-card {
    background: linear-gradient(180deg, #161922, #11131a);
    border: 1px solid #232734;
    border-radius: 18px;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.combo-card:hover {
    transform: translateY(-6px);
    border-color: #ff3131;
    box-shadow: 0 12px 30px rgba(255, 49, 49, 0.22);
}

.popular-card {
    border: 1.5px solid #ff3131 !important;
    box-shadow: 0 0 18px rgba(255, 49, 49, 0.25);
}

.popular-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(90deg, #ff3131, #ff6b6b);
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 12px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(255, 49, 49, 0.4);
}

.combo-logos {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 50px;
}

.img-combo {
    width: 38px;
    height: 38px;
    object-fit: contain;
}

.plus-sign {
    color: #ff3131;
    font-weight: 800;
    font-size: 1.2rem;
}

.combo-price {
    font-size: 1.8rem;
    font-weight: 900;
    color: #ffffff;
}

.savings-badge {
    background-color: rgba(255, 49, 49, 0.15);
    color: #ff6b6b;
    border: 1px solid rgba(255, 49, 49, 0.4);
    border-radius: 12px;
    padding: 4px 10px;
    font-weight: 700;
    font-size: 11px;
}

.btn-combo {
    background: #ff3131;
    color: #ffffff;
    border: none;
    border-radius: 25px;
    padding: 10px;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.2s ease;
}

.btn-combo:hover {
    background: #e02828;
    color: #ffffff;
    transform: scale(1.02);
}

.card-arma-opcion {
    background: #141720;
    border: 1px solid #232734;
    border-radius: 12px;
    padding: 12px 15px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-arma-opcion:hover {
    border-color: #ff3131;
    background: #1a1e2b;
}

.card-arma-opcion.selected {
    border-color: #ff3131 !important;
    background: rgba(255, 49, 49, 0.12) !important;
}

.card-arma-opcion .form-check-input:checked {
    background-color: #ff3131;
    border-color: #ff3131;
}

.btn-desplegable-combo {
    background: linear-gradient(90deg, #161922, #1c202d);
    border: 1px solid #ff3131;
    color: #ffffff;
    font-weight: 700;
    border-radius: 30px;
    padding: 14px 28px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(255, 49, 49, 0.2);
}

.btn-desplegable-combo:hover {
    background: #ff3131;
    color: #ffffff;
    box-shadow: 0 6px 20px rgba(255, 49, 49, 0.4);
}
</style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">

<div class="container">

<a class="navbar-brand logo-text" href="#inicio">
    <img src="IMG/logo.png" class="logo-img">
    <span>EvyStream</span>
</a>

<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse justify-content-end" id="menu">
<ul class="navbar-nav ms-auto">
<li class="nav-item">
<a class="nav-link" href="#inicio">Inicio</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#productos">Plataformas</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#combos">Combos</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#beneficios">Beneficios</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#opiniones">Opiniones</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#contacto">Contacto</a>
</li>
<li class="nav-item ms-3">
<a href="LOGIN/login.php" class="btn btn-danger">Iniciar Sesión</a>
</li>
</ul>
</div>

</div>

</nav>

<section class="hero" id="inicio">
<div class="container">
<div class="row align-items-center">
<div class="col-md-6">
<h1>Todas tus plataformas favoritas en un solo lugar.</h1>
<p>Netflix, Disney+, HBO Max, Prime Video, Youtube Premium, Spotify Premium y mucho más.</p>
<a href="#productos" class="btn btn-lg btn-danger">Ver Productos</a>
</div>
<div class="col-md-6">
    <div class="hero-cards">
        <a href="#netflix-card" class="stream-card netflix">
            <img src="IMG/netflix.png" alt="Netflix">
        </a>
        <a href="#disney-card" class="stream-card disney">
            <img src="IMG/disney.png" alt="Disney">
        </a>
        <a href="#prime-card" class="stream-card prime">
            <img src="IMG/prime.png" alt="Prime">
        </a>
        <a href="#hbo-card" class="stream-card hbo">
            <img src="IMG/iptv.png" alt="HBO">
        </a>
    </div>
</div>
</div>
</div>
</section>

<section class="container py-5" id="productos">
<div class="section-title">
<h5 class="subtitle">🔥 ENTRETENIMIENTO PREMIUM</h5>
<h2>Catálogo de plataformas</h2>
<p>Entrega inmediata, cuentas premium y soporte garantizado al mejor precio.</p>
</div>

<div class="row justify-content-center mb-4">
    <div class="col-md-6 mb-3">
        <div class="input-group search-box-custom">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="buscarPlataforma" class="form-control" placeholder="Buscar plataforma (ej. Netflix, Disney, Spotify)...">
        </div>
    </div>
    <div class="col-12 text-center">
        <div class="d-flex justify-content-center flex-wrap gap-2" id="filtrosCategorias">
            <button class="btn filter-btn active" data-categoria="todos">Todas</button>
            <button class="btn filter-btn" data-categoria="streaming">Streaming</button>
            <button class="btn filter-btn" data-categoria="musica">Música</button>
            <button class="btn filter-btn" data-categoria="tv">TV / IPTV</button>
            <button class="btn filter-btn" data-categoria="herramientas">Herramientas</button>
        </div>
    </div>
</div>

<div class="catalogo-grid-compact" id="catalogoGrid">
<?php
if(is_array($productos) || is_object($productos)){
    $contador = 0;
    foreach($productos as $producto){
        
        $nombreLower = strtolower($producto['nombre']);
        
        if(strpos($nombreLower, 'hbo') !== false){
            continue;
        }

        $contador++;
        $categoria = "streaming";
        if(strpos($nombreLower, 'spotify') !== false || strpos($nombreLower, 'music') !== false){
            $categoria = "musica";
        } elseif(strpos($nombreLower, 'iptv') !== false || strpos($nombreLower, 'flujo') !== false || strpos($nombreLower, 'universal') !== false){
            $categoria = "tv";
        } elseif(strpos($nombreLower, 'canva') !== false || strpos($nombreLower, 'chatgpt') !== false || strpos($nombreLower, 'office') !== false || strpos($nombreLower, 'google') !== false){
            $categoria = "herramientas";
        }

        $claseOculta = ($contador > 8) ? 'card-item-oculto' : '';
        $imagenProducto = basename((string)($producto['imagen'] ?? ''));
        if (!is_file(__DIR__ . "/IMG/productos/" . $imagenProducto)) {
            $imagenProducto = 'hbo.png';
        }
?>

<div class="producto-card-compact <?php echo $claseOculta; ?>" data-categoria="<?php echo $categoria; ?>">
    <span class="badge-compact">
        <?php echo ($contador <= 3) ? '🔥 TOP' : 'PREMIUM'; ?>
    </span>
    
    <div class="logo-box-compact">
        <img src="IMG/productos/<?php echo htmlspecialchars($imagenProducto); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
    </div>
    
    <div class="card-title-compact"><?php echo $producto['nombre']; ?></div>
    
    <div class="stock-indicator-compact">
        <span class="stock-dot"></span> Stock disponible
    </div>

    <div class="price-box-compact">
        Desde <strong>S/<?php echo number_format($producto['precio'],2); ?></strong>
    </div>
    
    <button class="btn-comprar-compact btn-abrir-modal" 
            data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>" 
            data-precio="<?php echo $producto['precio']; ?>" 
            data-imagen="IMG/productos/<?php echo htmlspecialchars($imagenProducto); ?>">
        Comprar <i class="fas fa-shopping-cart ms-1"></i>
    </button>
</div>

<?php
    }
}
?>
</div>

<div class="btn-ver-mas-container">
    <button class="btn btn-ver-mas" id="btnToggleVerMas" onclick="toggleVerMasPlataformas()">
        Ver las <span id="cantRestantes">5</span> plataformas restantes <i class="fas fa-chevron-down ms-1" id="iconChevron"></i>
    </button>
</div>

</section>

<section id="combos" class="container py-5">
    <div class="section-title">
        <h5 class="subtitle">💥 OFERTAS EXCLUSIVAS</h5>
        <h2>COMBOS EVYSTREAM</h2>
        <p>Lleva más, por menos. Paquetes combinados al mejor precio.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-3 col-md-6">
            <div class="combo-card popular-card h-100 p-4">
                <div class="popular-badge">MÁS POPULAR</div>
                <div class="text-center">
                    <div class="combo-logos mb-3">
                        <img src="IMG/netflix.png" alt="Netflix" class="img-combo">
                        <span class="plus-sign">+</span>
                        <img src="IMG/disney.png" alt="Disney+" class="img-combo">
                    </div>
                    <h4 class="fw-bold text-white mb-1" style="font-size: 1.2rem;">COMBO CINE</h4>
                    <p class="text-secondary small mb-3">Netflix Premium + Disney+</p>
                </div>
                <div class="text-center mt-auto">
                    <div class="text-secondary text-decoration-line-through small">S/ 25.00</div>
                    <div class="combo-price mb-2">S/ 23.00</div>
                    <span class="badge savings-badge mb-3">AHORRAS S/ 2.00</span>
                    <button type="button" class="btn btn-combo w-100 font-weight-bold btn-abrir-modal-combo" 
                        data-nombre="COMBO CINE"
                        data-descripcion="Netflix Premium + Disney+"
                        data-precio="23.00"
                        data-imagen="IMG/netflix.png"
                        data-features="Calidad Full HD / 4K Ultra HD|Accesos garantizados|Renovación con el mismo usuario|Soporte 24/7 vía WhatsApp">
                        Comprar combo <i class="fas fa-shopping-cart ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="combo-card h-100 p-4">
                <div class="text-center">
                    <div class="combo-logos mb-3">
                        <img src="IMG/netflix.png" alt="Netflix" class="img-combo">
                        <span class="plus-sign">+</span>
                        <img src="IMG/productos/spotify.png" alt="Spotify" class="img-combo">
                    </div>
                    <h4 class="fw-bold text-white mb-1" style="font-size: 1.2rem;">COMBO ENTRETENIMIENTO</h4>
                    <p class="text-secondary small mb-3">Netflix Premium + Spotify Premium</p>
                </div>
                <div class="text-center mt-auto">
                    <div class="text-secondary text-decoration-line-through small">S/ 30.00</div>
                    <div class="combo-price mb-2">S/ 27.00</div>
                    <span class="badge savings-badge mb-3">AHORRAS S/ 3.00</span>
                    <button type="button" class="btn btn-combo w-100 font-weight-bold btn-abrir-modal-combo" 
                        data-nombre="COMBO ENTRETENIMIENTO"
                        data-descripcion="Netflix Premium + Spotify Premium"
                        data-precio="27.00"
                        data-imagen="IMG/productos/spotify.png"
                        data-features="Cuentas Premium estables|Música sin anuncios + Cine en casa|Renovación directa|Soporte 24/7 vía WhatsApp">
                        Comprar combo <i class="fas fa-shopping-cart ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="combo-card h-100 p-4">
                <div class="text-center">
                    <div class="combo-logos mb-3">
                        <img src="IMG/disney.png" alt="Disney+" class="img-combo">
                        <span class="plus-sign">+</span>
                        <img src="IMG/prime.png" alt="Prime Video" class="img-combo">
                    </div>
                    <h4 class="fw-bold text-white mb-1" style="font-size: 1.2rem;">COMBO FAMILIAR</h4>
                    <p class="text-secondary small mb-3">Disney+ + Prime Video</p>
                </div>
                <div class="text-center mt-auto">
                    <div class="text-secondary text-decoration-line-through small">S/ 20.00</div>
                    <div class="combo-price mb-2">S/ 18.00</div>
                    <span class="badge savings-badge mb-3">AHORRAS S/ 2.00</span>
                    <button type="button" class="btn btn-combo w-100 font-weight-bold btn-abrir-modal-combo" 
                        data-nombre="COMBO FAMILIAR"
                        data-descripcion="Disney+ + Prime Video"
                        data-precio="18.00"
                        data-imagen="IMG/disney.png"
                        data-features="Perfiles personalizados|Contenido infantil y películas|Sin interrupciones|Soporte 24/7 vía WhatsApp">
                        Comprar combo <i class="fas fa-shopping-cart ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="combo-card h-100 p-4">
                <div class="text-center">
                    <div class="combo-logos mb-3">
                        <img src="IMG/netflix.png" alt="Netflix" class="img-combo">
                        <span class="plus-sign">+</span>
                        <img src="IMG/disney.png" alt="Disney+" class="img-combo">
                        <span class="plus-sign">+</span>
                        <img src="IMG/prime.png" alt="Prime Video" class="img-combo">
                    </div>
                    <h4 class="fw-bold text-white mb-1" style="font-size: 1.2rem;">COMBO FULL CINE</h4>
                    <p class="text-secondary small mb-3">Netflix + Disney+ + Prime Video</p>
                </div>
                <div class="text-center mt-auto">
                    <div class="text-secondary text-decoration-line-through small">S/ 35.00</div>
                    <div class="combo-price mb-2">S/ 32.00</div>
                    <span class="badge savings-badge mb-3">AHORRAS S/ 3.00</span>
                    <button type="button" class="btn btn-combo w-100 font-weight-bold btn-abrir-modal-combo" 
                        data-nombre="COMBO FULL CINE"
                        data-descripcion="Netflix + Disney+ + Prime Video"
                        data-precio="32.00"
                        data-imagen="IMG/prime.png"
                        data-features="Acceso completo a 3 plataformas|Calidad máxima de reproducción|Renovación mensual sin perder datos|Soporte VIP 24/7">
                        Comprar combo <i class="fas fa-shopping-cart ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12 text-center mb-3">
            <button class="btn btn-desplegable-combo btn-lg" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArmaCombo" aria-expanded="false" aria-controls="collapseArmaCombo">
                🎨 ¿Quieres otra combinación? ¡Arma tu combo personalizado aquí! <i class="fas fa-chevron-down ms-2"></i>
            </button>
        </div>

        <div class="collapse col-12 mt-3" id="collapseArmaCombo">
            <div class="combo-card p-4">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <h4 class="fw-bold text-white mb-2"><i class="fas fa-sliders-h text-danger me-2"></i> Elige tus plataformas favoritas</h4>
                        <p class="text-secondary small mb-3">Selecciona 2 o más plataformas para aplicar tu descuento automático.</p>
                        
                        <div class="row g-2" id="gridArmaCombo">
                            <?php
                            if(is_array($productos) || is_object($productos)){
                                foreach($productos as $prod){
                                    $imagenCombo = basename((string)($prod['imagen'] ?? ''));
                                    if (!is_file(__DIR__ . "/IMG/productos/" . $imagenCombo)) {
                                        $imagenCombo = 'hbo.png';
                                    }
                            ?>
                            <div class="col-md-6">
                                <label class="card-arma-opcion w-100 m-0" for="chk_<?php echo $prod['id_producto']; ?>">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input check-arma-combo me-1" type="checkbox" 
                                               id="chk_<?php echo $prod['id_producto']; ?>" 
                                               data-nombre="<?php echo htmlspecialchars($prod['nombre']); ?>" 
                                               data-precio="<?php echo $prod['precio']; ?>" 
                                               onchange="recalcularComboArmado()">
                                        <img src="IMG/productos/<?php echo htmlspecialchars($imagenCombo); ?>" style="width:28px; height:28px; object-fit:contain;">
                                        <span class="text-white small fw-bold"><?php echo $prod['nombre']; ?></span>
                                    </div>
                                    <span class="badge bg-dark text-secondary border border-secondary">S/ <?php echo number_format($prod['precio'], 2); ?></span>
                                </label>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="summary-card p-4 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="fw-bold text-white mb-3"><i class="fas fa-receipt text-danger me-2"></i> Resumen del Combo</h5>
                                <ul id="listaPlataformasSeleccionadas" class="list-unstyled small mb-3">
                                    <li class="text-secondary italic">No has seleccionado ninguna plataforma.</li>
                                </ul>
                                
                                <hr class="border-secondary opacity-25">
                                
                                <div class="d-flex justify-content-between text-white small mb-2">
                                    <span class="text-secondary">Subtotal:</span>
                                    <span id="txtSubtotalArmado" class="fw-bold">S/ 0.00</span>
                                </div>
                                <div class="d-flex justify-content-between text-success small mb-3">
                                    <span>Descuento aplicado:</span>
                                    <span id="txtDescuentoArmado" class="fw-bold">- S/ 0.00</span>
                                </div>
                                
                                <hr class="border-secondary opacity-25">
                                
                                <div class="d-flex justify-content-between align-items-center text-white">
                                    <span class="fw-bold">Total Final:</span>
                                    <span id="txtTotalArmado" class="h3 mb-0 fw-bold text-danger">S/ 0.00</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a id="btnComprarComboArmado" href="#" target="_blank" class="btn btn-combo w-100 fw-bold py-2 disabled">
                                    <i class="fab fa-whatsapp me-1"></i> Pedir combo por WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="beneficios" class="beneficios">
    <div class="container">
        <div class="section-title">
            <h5 class="subtitle">¿POR QUÉ ELEGIRNOS?</h5>
            <h2>¿Por qué miles de clientes eligen EvyStream?</h2>
            <p>Nos enfocamos en ofrecer cuentas premium con entrega inmediata, atención personalizada y la mejor experiencia para nuestros clientes.</p>
        </div>
        <div class="beneficios-grid">
            <div class="beneficio-card">
                <div class="icono">⚡</div>
                <h4>Entrega inmediata</h4>
                <p>Recibe tus accesos pocos minutos después de confirmar tu pago.</p>
            </div>
            <div class="beneficio-card">
                <div class="icono">🔒</div>
                <h4>100% Seguro</h4>
                <p>Cuentas verificadas con garantía y soporte permanente.</p>
            </div>
            <div class="beneficio-card">
                <div class="icono">💬</div>
                <h4>Soporte 24/7</h4>
                <p>Atención rápida por WhatsApp antes y después de tu compra.</p>
            </div>
            <div class="beneficio-card">
                <div class="icono">💳</div>
                <h4>Pagos seguros</h4>
                <p>Aceptamos Yape, Plin, transferencias y otros métodos seguros.</p>
            </div>
            <div class="beneficio-card">
                <div class="icono">⭐</div>
                <h4>Calidad Premium</h4>
                <p>Solo ofrecemos cuentas funcionales y con excelente rendimiento.</p>
            </div>
            <div class="beneficio-card">
                <div class="icono">🎁</div>
                <h4>Garantía incluida</h4>
                <p>Si ocurre algún inconveniente, te ayudamos a solucionarlo.</p>
            </div>
        </div>
    </div>
</section>

<section class="estadisticas">
<div class="container">
<div class="estadisticas-grid">
<div class="stat-box">
<h2 class="counter" data-target="3500">0</h2>
<p>Clientes felices</p>
</div>
<div class="stat-box">
<h2 class="counter" data-target="1500">0</h2>
<p>Cuentas entregadas</p>
</div>
<div class="stat-box">
<h2 class="counter" data-target="99">0</h2>
<p>% Clientes satisfechos</p>
</div>
<div class="stat-box">
<h2>4.9★</h2>
<p>Calificación promedio</p>
</div>
</div>
</div>
</section>

<section class="reviews py-5" id="opiniones">
<div class="container">
<div class="section-title">
<h5 class="subtitle">⭐ OPINIONES DE CLIENTES</h5>
<h2>Miles de clientes confían en EvyStream</h2>
<p>Estas son algunas opiniones reales de nuestros clientes.</p>
</div>
<div class="reviews-grid">
<div class="review-card">
<div class="stars">★★★★★</div>
<p>"Compré Netflix Premium y en menos de 2 minutos ya tenía acceso. Excelente servicio."</p>
<div class="cliente">
<img src="https://i.pravatar.cc/80?img=11">
<div>
<h5>Carlos Mendoza</h5>
<span>Compra verificada</span>
</div>
</div>
</div>
<div class="review-card">
<div class="stars">★★★★★</div>
<p>"El soporte responde muy rápido. Ya llevo varios meses renovando mi cuenta."</p>
<div class="cliente">
<img src="https://i.pravatar.cc/80?img=32">
<div>
<h5>Andrea López</h5>
<span>Cliente frecuente</span>
</div>
</div>
</div>
<div class="review-card">
<div class="stars">★★★★★</div>
<p>"Todo funciona perfecto. Muy recomendado si buscas cuentas premium."</p>
<div class="cliente">
<img src="https://i.pravatar.cc/80?img=15">
<div>
<h5>Luis Ramírez</h5>
<span>Compra verificada</span>
</div>
</div>
</div>
</div>
</div>
</section>

<div class="text-center mt-5">
<h3 style="font-size:48px;color:#FFD700;">★★★★★</h3>
<h4>4.9 / 5 basado en más de 1,200 clientes satisfechos</h4>
<p style="color:#bdbdbd;">La mayoría de nuestros clientes vuelve a renovar con nosotros cada mes.</p>
</div>

<section id="contacto" class="contacto">
<div class="container">
<div class="contacto-box">
<div class="contacto-info">
<h2>🎬 ¿Listo para disfrutar del mejor entretenimiento?</h2>
<p>Obtén tu cuenta Premium en menos de <strong>2 minutos</strong>. Miles de clientes ya confían en EvyStream para disfrutar de sus plataformas favoritas con garantía y soporte personalizado.</p>
<a href="https://wa.me/51931880582?text=Hola,%20quiero%20información%20sobre%20las%20cuentas%20de%20streaming%20por%20favor." target="_blank" class="btn-contacto">
💬 Hablar por WhatsApp
</a>
</div>
<div class="contacto-detalles">
<div class="detalle-card">
<div class="detalle-icono">📱</div>
<h4>WhatsApp</h4>
<p>+51 931 880 582</p>
<span>Disponible 24/7</span>
</div>
<div class="detalle-card">
<div class="detalle-icono">💳</div>
<h4>Pagos</h4>
<p>Yape • Plin • Transferencia</p>
<span>Pago seguro</span>
</div>
<div class="detalle-card">
    <div class="detalle-icono">🌐</div>
    <h4>Síguenos</h4>
    <div class="redes-sociales">
        <a href="https://facebook.com/TU_USUARIO" target="_blank" class="facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com/TU_USUARIO" target="_blank" class="instagram">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://tiktok.com/@TU_USUARIO" target="_blank" class="tiktok">
            <i class="fab fa-tiktok"></i>
        </a>
    </div>
    <span>@EvyStream</span>
</div>
</div>
</div>
</div>
</section>

<footer class="footer">
<div class="container">
<p>© 2026 <strong>EvyStream</strong> | Todos los derechos reservados.</p>
</div>
</footer>

<div class="whatsapp-widget-container">
    <div class="whatsapp-chat-bubble">
        ¿Tiene alguna pregunta? Contáctenos ahora
    </div>
    <a href="https://wa.me/51931880582?text=Hola,%20quiero%20información%20sobre%20las%20cuentas%20de%20streaming%20por%20favor." target="_blank" class="whatsapp-widget-btn">
        <div class="whatsapp-widget-halo">
            <div class="whatsapp-widget-circle">
                <i class="fa-brands fa-whatsapp"></i>
                <span class="whatsapp-notification-dot"></span>
            </div>
        </div>
    </a>
</div>

<style>
.whatsapp-widget-container {
    position: fixed;
    bottom: 25px;
    right: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 99999;
    font-family: Arial, sans-serif;
}

.whatsapp-chat-bubble {
    background-color: #ffffff;
    color: #1a1a1a;
    padding: 12px 18px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 500;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.15);
    position: relative;
    white-space: nowrap;
}

.whatsapp-chat-bubble::after {
    content: '';
    position: absolute;
    right: -7px;
    top: 50%;
    transform: translateY(-50%);
    border-width: 6px 0 6px 8px;
    border-style: solid;
    border-color: transparent transparent transparent #ffffff;
}

.whatsapp-widget-btn {
    text-decoration: none;
    display: inline-block;
}

.whatsapp-widget-halo {
    width: 75px;
    height: 75px;
    background-color: rgba(37, 211, 102, 0.22);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.whatsapp-widget-circle {
    width: 54px;
    height: 54px;
    background-color: #25D366;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 32px;
    position: relative;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.whatsapp-notification-dot {
    position: absolute;
    top: 2px;
    right: 2px;
    width: 11px;
    height: 11px;
    background-color: #ff3b30;
    border: 2px solid #ffffff;
    border-radius: 50%;
}

.whatsapp-widget-btn:hover .whatsapp-widget-halo {
    transform: scale(1.08);
}
</style>

<div class="modal fade" id="modalSeleccionPlan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-plan-content text-white">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-5 text-center text-md-start border-end border-secondary border-opacity-25 pe-md-4">
                        <div class="p-3 rounded-4 mb-3 text-center" style="background: #1a1c23;">
                            <img id="modalImgPlataforma" src="" class="img-fluid rounded" style="max-height: 120px; object-fit: contain;">
                        </div>
                        <h4 id="modalTituloPlataforma" class="fw-bold mb-1 text-white"></h4>
                        <div class="stock-indicator-compact mb-3 justify-content-start">
                            <span class="stock-dot"></span> Stock disponible
                        </div>
                        <div class="small text-secondary mb-2 fw-semibold">Características:</div>
                        <ul class="list-unstyled small text-secondary">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Calidad Full HD / 4K Ultra HD</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Perfiles personales con PIN</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Sin anuncios y sin interrupciones</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Renovación garantizada</li>
                            <li><i class="fas fa-check text-success me-2"></i> Soporte 24/7 vía WhatsApp</li>
                        </ul>
                    </div>
                    
                    <div class="col-md-7 ps-md-4">
                        <h5 class="fw-bold mb-3 text-white">Selecciona tu plan</h5>
                        
                        <label class="form-label small text-secondary fw-semibold">1. Elige el tipo de acceso</label>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <button class="btn option-btn w-100 p-2 text-start active" id="btnAccesoPantalla" onclick="setTipoAcceso('1 Pantalla (Perfil)', 1)">
                                    <div class="fw-bold text-white small">1 Pantalla</div>
                                    <div class="text-secondary" style="font-size: 11px;">(Perfil individual)</div>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn option-btn w-100 p-2 text-start" id="btnAccesoCuenta" onclick="setTipoAcceso('Cuenta Completa (Uso Personal)', 2.5)">
                                    <div class="fw-bold text-white small">Cuenta Completa</div>
                                    <div class="text-secondary" style="font-size: 11px;">(Uso Personal)</div>
                                </button>
                            </div>
                        </div>

                        <label class="form-label small text-secondary fw-semibold">2. Elige la duración</label>
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <button class="btn duration-btn w-100 p-2 text-center active" id="btnDuracion1" onclick="setDuracion('1 Mes', 1, 0)">
                                    <div class="fw-bold small">1 Mes</div>
                                    <div class="text-danger fw-bold" id="lblPrecio1M" style="font-size: 12px;">S/ 0.00</div>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn duration-btn w-100 p-2 text-center position-relative" id="btnDuracion3" onclick="setDuracion('3 Meses', 3, 0.1111)">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px;">-11.11%</span>
                                    <div class="fw-bold small">3 Meses</div>
                                    <div class="text-danger fw-bold" id="lblPrecio3M" style="font-size: 12px;">S/ 0.00</div>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn duration-btn w-100 p-2 text-center position-relative" id="btnDuracion6" onclick="setDuracion('6 Meses', 6, 0.1556)">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px;">-15.56%</span>
                                    <div class="fw-bold small">6 Meses</div>
                                    <div class="text-danger fw-bold" id="lblPrecio6M" style="font-size: 12px;">S/ 0.00</div>
                                </button>
                            </div>
                        </div>

                        <div class="summary-card p-3 mb-3">
                            <div class="text-secondary small fw-bold mb-2">Resumen de tu pedido</div>
                            <div class="d-flex justify-content-between small text-white mb-1">
                                <span class="text-secondary">Plataforma:</span>
                                <span id="resumenPlataforma" class="fw-bold"></span>
                            </div>
                            <div class="d-flex justify-content-between small text-white mb-1">
                                <span class="text-secondary">Tipo de acceso:</span>
                                <span id="resumenAcceso" class="fw-bold">1 Pantalla (Perfil)</span>
                            </div>
                            <div class="d-flex justify-content-between small text-white mb-1">
                                <span class="text-secondary">Duración:</span>
                                <span id="resumenDuracion" class="fw-bold">1 Mes</span>
                            </div>
                            <hr class="border-secondary border-opacity-25 my-2">
                            <div class="d-flex justify-content-between text-white align-items-center">
                                <span class="fw-bold">Precio:</span>
                                <span id="resumenPrecio" class="h5 fw-bold text-danger mb-0">S/ 0.00</span>
                            </div>
                        </div>

                        <a id="btnConfirmarWhatsApp" href="#" target="_blank" class="btn btn-success w-100 fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #25d366; border: none; border-radius: 10px;">
                            <i class="fab fa-whatsapp fa-lg"></i> Confirmar pedido por WhatsApp
                        </a>
                        <div class="text-center text-secondary mt-2" style="font-size: 11px;">
                            Serás redirigido a WhatsApp para finalizar tu pedido
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPedidoCombo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-plan-content text-white">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-5 text-center text-md-start border-end border-secondary border-opacity-25 pe-md-4">
                        <div class="p-3 rounded-4 mb-3 text-center" style="background: #1a1c23;">
                            <img id="modalImgCombo" src="" class="img-fluid rounded" style="max-height: 120px; object-fit: contain;">
                        </div>
                        <h4 id="modalTituloCombo" class="fw-bold mb-1 text-white"></h4>
                        <div class="stock-indicator-compact mb-3 justify-content-start">
                            <span class="stock-dot"></span> Stock disponible
                        </div>
                        <div class="small text-secondary mb-2 fw-semibold">Características del Combo:</div>
                        <ul id="modalListaComboFeatures" class="list-unstyled small text-secondary">
                        </ul>
                    </div>
                    
                    <div class="col-md-7 ps-md-4">
                        <h5 class="fw-bold mb-3 text-white">Selecciona tu plan</h5>
                        
                        <label class="form-label small text-secondary fw-semibold">1. Elige el tipo de acceso</label>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <button class="btn option-btn w-100 p-2 text-start active" id="btnAccesoComboIndividual" onclick="setTipoAccesoCombo('Perfiles Individuales', 1)">
                                    <div class="fw-bold text-white small">Perfiles</div>
                                    <div class="text-secondary" style="font-size: 11px;">(1 Pantalla c/u)</div>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn option-btn w-100 p-2 text-start" id="btnAccesoComboCompleto" onclick="setTipoAccesoCombo('Cuentas Completas', 1.8)">
                                    <div class="fw-bold text-white small">Completas</div>
                                    <div class="text-secondary" style="font-size: 11px;">(Uso Personal)</div>
                                </button>
                            </div>
                        </div>

                        <label class="form-label small text-secondary fw-semibold">2. Elige la duración</label>
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <button class="btn duration-btn w-100 p-2 text-center active" id="btnComboDuracion1" onclick="setDuracionCombo('1 Mes', 1, 0)">
                                    <div class="fw-bold small">1 Mes</div>
                                    <div class="text-danger fw-bold" id="lblPrecioCombo1M" style="font-size: 12px;">S/ 0.00</div>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn duration-btn w-100 p-2 text-center position-relative" id="btnComboDuracion3" onclick="setDuracionCombo('3 Meses', 3, 0.10)">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px;">-10%</span>
                                    <div class="fw-bold small">3 Meses</div>
                                    <div class="text-danger fw-bold" id="lblPrecioCombo3M" style="font-size: 12px;">S/ 0.00</div>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn duration-btn w-100 p-2 text-center position-relative" id="btnComboDuracion6" onclick="setDuracionCombo('6 Meses', 6, 0.15)">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px;">-15%</span>
                                    <div class="fw-bold small">6 Meses</div>
                                    <div class="text-danger fw-bold" id="lblPrecioCombo6M" style="font-size: 12px;">S/ 0.00</div>
                                </button>
                            </div>
                        </div>

                        <div class="summary-card p-3 mb-3">
                            <div class="text-secondary small fw-bold mb-2">Resumen de tu pedido</div>
                            <div class="d-flex justify-content-between small text-white mb-1">
                                <span class="text-secondary">Combo:</span>
                                <span id="resumenNombreCombo" class="fw-bold"></span>
                            </div>
                            <div class="d-flex justify-content-between small text-white mb-1">
                                <span class="text-secondary">Tipo de acceso:</span>
                                <span id="resumenAccesoCombo" class="fw-bold">Perfiles Individuales</span>
                            </div>
                            <div class="d-flex justify-content-between small text-white mb-1">
                                <span class="text-secondary">Duración:</span>
                                <span id="resumenDuracionCombo" class="fw-bold">1 Mes</span>
                            </div>
                            <hr class="border-secondary border-opacity-25 my-2">
                            <div class="d-flex justify-content-between text-white align-items-center">
                                <span class="fw-bold">Precio:</span>
                                <span id="resumenPrecioCombo" class="h5 fw-bold text-danger mb-0">S/ 0.00</span>
                            </div>
                        </div>

                        <a id="btnConfirmarWhatsAppCombo" href="#" target="_blank" class="btn btn-success w-100 fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #25d366; border: none; border-radius: 10px;">
                            <i class="fab fa-whatsapp fa-lg"></i> Confirmar pedido por WhatsApp
                        </a>
                        <div class="text-center text-secondary mt-2" style="font-size: 11px;">
                            Serás redirigido a WhatsApp para finalizar tu pedido
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const counters = document.querySelectorAll('.counter');
const speed = 80;

const observer = new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
        if(entry.isIntersecting){
            const counter = entry.target;
            const target = +counter.getAttribute('data-target');
            let count = 0;

            const update = ()=>{
                const increment = Math.ceil(target/speed);
                count += increment;

                if(count >= target){
                    counter.innerText = target.toLocaleString();
                }else{
                    counter.innerText = count.toLocaleString();
                    requestAnimationFrame(update);
                }
            };
            update();
            observer.unobserve(counter);
        }
    });
},{threshold:.5});

counters.forEach(counter=>{
    observer.observe(counter);
});

let precioBaseUnitario = 0;
let nombrePlataformaActual = '';
let textoTipoAcceso = '1 Pantalla (Perfil)';
let multiplicadorAcceso = 1;
let textoDuracion = '1 Mes';
let mesesDuracion = 1;
let porcentajeDescuento = 0;

const modalBS = new bootstrap.Modal(document.getElementById('modalSeleccionPlan'));

document.querySelectorAll('.btn-abrir-modal').forEach(btn => {
    btn.addEventListener('click', function() {
        nombrePlataformaActual = this.getAttribute('data-nombre');
        precioBaseUnitario = parseFloat(this.getAttribute('data-precio'));
        let rutaImagen = this.getAttribute('data-imagen');

        document.getElementById('modalTituloPlataforma').innerText = nombrePlataformaActual;
        document.getElementById('modalImgPlataforma').src = rutaImagen;
        document.getElementById('resumenPlataforma').innerText = nombrePlataformaActual;

        setTipoAcceso('1 Pantalla (Perfil)', 1);
        setDuracion('1 Mes', 1, 0);

        modalBS.show();
    });
});

function setTipoAcceso(nombreAcceso, multiplicador) {
    textoTipoAcceso = nombreAcceso;
    multiplicadorAcceso = multiplicador;

    document.getElementById('btnAccesoPantalla').classList.toggle('active', multiplicador === 1);
    document.getElementById('btnAccesoCuenta').classList.toggle('active', multiplicador !== 1);

    document.getElementById('resumenAcceso').innerText = textoTipoAcceso;
    recalcularPlan();
}

function setDuracion(nombreDuracion, meses, descuento) {
    textoDuracion = nombreDuracion;
    mesesDuracion = meses;
    porcentajeDescuento = descuento;

    document.getElementById('btnDuracion1').classList.toggle('active', meses === 1);
    document.getElementById('btnDuracion3').classList.toggle('active', meses === 3);
    document.getElementById('btnDuracion6').classList.toggle('active', meses === 6);

    document.getElementById('resumenDuracion').innerText = textoDuracion;
    recalcularPlan();
}

function recalcularPlan() {
    let precioBaseAcceso = precioBaseUnitario * multiplicadorAcceso;

    let p1M = precioBaseAcceso * 1;
    let p3M = Math.round((precioBaseAcceso * 3) * (1 - 0.1111));
    let p6M = Math.round((precioBaseAcceso * 6) * (1 - 0.1556));

    document.getElementById('lblPrecio1M').innerText = 'S/ ' + p1M.toFixed(2);
    document.getElementById('lblPrecio3M').innerText = 'S/ ' + p3M.toFixed(2);
    document.getElementById('lblPrecio6M').innerText = 'S/ ' + p6M.toFixed(2);

    let precioCalculado = Math.round((precioBaseAcceso * mesesDuracion) * (1 - porcentajeDescuento));
    let precioFinalTxt = 'S/ ' + precioCalculado.toFixed(2);

    document.getElementById('resumenPrecio').innerText = precioFinalTxt;

    let msg = `Hola EvyStream 🖐️\nQuiero realizar una compra:\n\n🎬 *Plataforma:* ${nombrePlataformaActual}\n👤 *Modalidad:* ${textoTipoAcceso}\n⏳ *Duración:* ${textoDuracion}\n💰 *Precio:* ${precioFinalTxt}\n\nQuedo atento para realizar el pago.`;
    
    let urlWA = `https://wa.me/51931880582?text=${encodeURIComponent(msg)}`;
    document.getElementById('btnConfirmarWhatsApp').href = urlWA;
}

let comboPrecioBase = 0;
let comboNombreActual = '';
let comboTipoAcceso = 'Perfiles Individuales';
let comboMultiplicador = 1;
let comboTextoDuracion = '1 Mes';
let comboMeses = 1;
let comboDescuento = 0;

const modalComboBS = new bootstrap.Modal(document.getElementById('modalPedidoCombo'));

document.querySelectorAll('.btn-abrir-modal-combo').forEach(btn => {
    btn.addEventListener('click', function() {
        comboNombreActual = this.getAttribute('data-nombre');
        comboPrecioBase = parseFloat(this.getAttribute('data-precio'));
        let rutaImagen = this.getAttribute('data-imagen');
        let features = this.getAttribute('data-features').split('|');

        document.getElementById('modalTituloCombo').innerText = comboNombreActual;
        document.getElementById('resumenNombreCombo').innerText = comboNombreActual;
        document.getElementById('modalImgCombo').src = rutaImagen;

        let htmlFeatures = '';
        features.forEach(feat => {
            htmlFeatures += `<li class="mb-2"><i class="fas fa-check text-success me-2"></i> ${feat}</li>`;
        });
        document.getElementById('modalListaComboFeatures').innerHTML = htmlFeatures;

        setTipoAccesoCombo('Perfiles Individuales', 1);
        setDuracionCombo('1 Mes', 1, 0);

        modalComboBS.show();
    });
});

function setTipoAccesoCombo(nombreAcceso, multiplicador) {
    comboTipoAcceso = nombreAcceso;
    comboMultiplicador = multiplicador;

    document.getElementById('btnAccesoComboIndividual').classList.toggle('active', multiplicador === 1);
    document.getElementById('btnAccesoComboCompleto').classList.toggle('active', multiplicador !== 1);

    document.getElementById('resumenAccesoCombo').innerText = comboTipoAcceso;
    recalcularCombo();
}

function setDuracionCombo(nombreDuracion, meses, descuento) {
    comboTextoDuracion = nombreDuracion;
    comboMeses = meses;
    comboDescuento = descuento;

    document.getElementById('btnComboDuracion1').classList.toggle('active', meses === 1);
    document.getElementById('btnComboDuracion3').classList.toggle('active', meses === 3);
    document.getElementById('btnComboDuracion6').classList.toggle('active', meses === 6);

    document.getElementById('resumenDuracionCombo').innerText = comboTextoDuracion;
    recalcularCombo();
}

function recalcularCombo() {
    let baseAcceso = comboPrecioBase * comboMultiplicador;

    let p1M = baseAcceso * 1;
    let p3M = (baseAcceso * 3) * (1 - 0.10);
    let p6M = (baseAcceso * 6) * (1 - 0.15);

    document.getElementById('lblPrecioCombo1M').innerText = 'S/ ' + p1M.toFixed(2);
    document.getElementById('lblPrecioCombo3M').innerText = 'S/ ' + p3M.toFixed(2);
    document.getElementById('lblPrecioCombo6M').innerText = 'S/ ' + p6M.toFixed(2);

    let precioCalculado = (baseAcceso * comboMeses) * (1 - comboDescuento);
    let precioFinalTxt = 'S/ ' + precioCalculado.toFixed(2);

    document.getElementById('resumenPrecioCombo').innerText = precioFinalTxt;

    let msg = `Hola EvyStream 🖐️\nQuiero realizar la compra de un combo:\n\n🔥 *Combo:* ${comboNombreActual}\n👤 *Modalidad:* ${comboTipoAcceso}\n⏳ *Duración:* ${comboTextoDuracion}\n💰 *Precio:* ${precioFinalTxt}\n\nQuedo atento para realizar el pago.`;
    
    let urlWA = `https://wa.me/51931880582?text=${encodeURIComponent(msg)}`;
    document.getElementById('btnConfirmarWhatsAppCombo').href = urlWA;
}

function recalcularComboArmado() {
    let checkboxes = document.querySelectorAll('.check-arma-combo');
    let listaUl = document.getElementById('listaPlataformasSeleccionadas');
    let btnWA = document.getElementById('btnComprarComboArmado');
    
    let subtotal = 0;
    let cantidad = 0;
    let nombres = [];

    listaUl.innerHTML = '';

    checkboxes.forEach(chk => {
        let cardParent = chk.closest('.card-arma-opcion');
        if (chk.checked) {
            cardParent.classList.add('selected');
            cantidad++;
            let nombre = chk.getAttribute('data-nombre');
            let precio = parseFloat(chk.getAttribute('data-precio'));
            subtotal += precio;
            nombres.push(nombre);

            let li = document.createElement('li');
            li.className = 'd-flex justify-content-between text-white mb-1';
            li.innerHTML = `<span><i class="fas fa-check text-danger me-2"></i>${nombre}</span><span class="fw-bold">S/ ${precio.toFixed(2)}</span>`;
            listaUl.appendChild(li);
        } else {
            cardParent.classList.remove('selected');
        }
    });

    if (cantidad === 0) {
        listaUl.innerHTML = '<li class="text-secondary italic">No has seleccionado ninguna plataforma.</li>';
        document.getElementById('txtSubtotalArmado').innerText = 'S/ 0.00';
        document.getElementById('txtDescuentoArmado').innerText = '- S/ 0.00';
        document.getElementById('txtTotalArmado').innerText = 'S/ 0.00';
        btnWA.classList.add('disabled');
        btnWA.href = '#';
        return;
    }

    let descuento = 0;
    if (cantidad === 2) {
        descuento = 2;
    } else if (cantidad === 3) {
        descuento = 3;
    } else if (cantidad >= 4) {
        descuento = 5;
    }

    let total = subtotal - descuento;
    if (total < 0) total = 0;

    document.getElementById('txtSubtotalArmado').innerText = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('txtDescuentoArmado').innerText = '- S/ ' + descuento.toFixed(2);
    document.getElementById('txtTotalArmado').innerText = 'S/ ' + total.toFixed(2);

    btnWA.classList.remove('disabled');

    let msg = `Hola EvyStream 🖐️\nQuiero armar mi combo personalizado con:\n\n` +
        nombres.map(n => `• ${n}`).join('\n') +
        `\n\n💰 *Subtotal:* S/ ${subtotal.toFixed(2)}\n🎁 *Descuento:* -S/ ${descuento.toFixed(2)}\n🔥 *Total Final:* S/ ${total.toFixed(2)}\n\nQuedo atento para realizar el pago.`;

    btnWA.href = `https://wa.me/51931880582?text=${encodeURIComponent(msg)}`;
}

let verMasExpandido = false;

function toggleVerMasPlataformas() {
    verMasExpandido = !verMasExpandido;
    const cardsOcultas = document.querySelectorAll('.card-item-oculto');
    const iconChevron = document.getElementById('iconChevron');
    const btnTexto = document.getElementById('btnToggleVerMas');

    if (verMasExpandido) {
        cardsOcultas.forEach(card => card.style.setProperty('display', 'flex', 'important'));
        btnTexto.childNodes[0].nodeValue = 'Mostrar menos ';
        iconChevron.className = 'fas fa-chevron-up ms-1';
    } else {
        cardsOcultas.forEach(card => card.style.setProperty('display', 'none', 'important'));
        btnTexto.childNodes[0].nodeValue = 'Ver las ';
        document.getElementById('cantRestantes').innerText = cardsOcultas.length;
        btnTexto.appendChild(document.createTextNode(' plataformas restantes '));
        btnTexto.appendChild(iconChevron);
        iconChevron.className = 'fas fa-chevron-down ms-1';
    }
}

const inputBuscar = document.getElementById('buscarPlataforma');
const tarjetasProductos = document.querySelectorAll('.producto-card-compact');
const btnVerMasContainer = document.querySelector('.btn-ver-mas-container');
const botonesFiltro = document.querySelectorAll('.filter-btn');

if(inputBuscar){
    inputBuscar.addEventListener('keyup', function() {
        const query = this.value.toLowerCase().trim();
        if(query !== '') {
            if(btnVerMasContainer) btnVerMasContainer.style.display = 'none';
        } else {
            if(btnVerMasContainer) btnVerMasContainer.style.display = 'block';
        }

        tarjetasProductos.forEach(card => {
            const titulo = card.querySelector('.card-title-compact').textContent.toLowerCase();
            if(query === ''){
                if(card.classList.contains('card-item-oculto') && !verMasExpandido){
                    card.style.setProperty('display', 'none', 'important');
                } else {
                    card.style.setProperty('display', 'flex', 'important');
                }
            } else {
                if(titulo.includes(query)){
                    card.style.setProperty('display', 'flex', 'important');
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            }
        });
    });
}

botonesFiltro.forEach(btn => {
    btn.addEventListener('click', function() {
        botonesFiltro.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const cat = this.getAttribute('data-categoria');
        if(inputBuscar) inputBuscar.value = '';

        if(cat !== 'todos') {
            if(btnVerMasContainer) btnVerMasContainer.style.display = 'none';
        } else {
            if(btnVerMasContainer) btnVerMasContainer.style.display = 'block';
        }

        tarjetasProductos.forEach(card => {
            const cardCat = card.getAttribute('data-categoria');
            if(cat === 'todos'){
                if(card.classList.contains('card-item-oculto') && !verMasExpandido){
                    card.style.setProperty('display', 'none', 'important');
                } else {
                    card.style.setProperty('display', 'flex', 'important');
                }
            } else {
                if(cardCat === cat){
                    card.style.setProperty('display', 'flex', 'important');
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            }
        });
    });
});
</script>

</body>
</html>
