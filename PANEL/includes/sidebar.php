<aside class="main-sidebar sidebar-dark-danger elevation-4">

<a href="dashboard.php" class="brand-link">

<img src="../IMG/logo.png"
class="brand-image img-circle elevation-3"
style="opacity:.9">

<span class="brand-text font-weight-light">

EvyStream

</span>

</a>

<div class="sidebar">

<nav class="mt-3">

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

<li class="nav-item">

<a href="dashboard.php"

class="nav-link <?php echo ($menu=="dashboard") ? "active" : ""; ?>">

<i class="nav-icon fas fa-home"></i>

<p>Dashboard</p>

</a>

</li>

<li class="nav-item">

<a href="plataformas.php"
class="nav-link <?php echo ($menu=="plataformas") ? "active" : ""; ?>">

<i class="nav-icon fas fa-tv"></i>

<p>Plataformas</p>

</a>

</li>

<li class="nav-item">

    <a href="ventas.php"
       class="nav-link <?php if($menu=="ventas") echo "active"; ?>">

        <i class="nav-icon fas fa-shopping-cart"></i>

        <p>Ventas</p>

    </a>

</li>

<li class="nav-item">

<a href="clientes.php" class="nav-link 

<?php echo ($menu === 'clientes') ? 'active' : ''; ?>">

    <i class="nav-icon fas fa-users"></i>
    
    <p>Clientes</p>
</a>

</li>

<li class="nav-item">

<a href="configuracion.php" class="nav-link <?php echo (isset($menu) && $menu == 'configuracion') ? 'active' : ''; ?>">

<i class="nav-icon fas fa-cog"></i>

<p>Configuración</p>

</a>

</li>

<li class="nav-item">

<a href="../LOGIN/cerrar_sesion.php"
class="nav-link text-danger">

<i class="nav-icon fas fa-sign-out-alt"></i>

<p>Cerrar sesión</p>

</a>

</li>

</ul>

</nav>

</div>

</aside>

<div class="content-wrapper">