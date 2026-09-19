<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">

        <li class="nav-item">

            <a class="nav-link" data-widget="pushmenu" href="#" role="button">

                <i class="fas fa-bars"></i>

            </a>

        </li>

    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown user-menu">

            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">

                <?php

                $fotoNav = $_SESSION["foto"] ?? "";

                $rutaFotoNav = "../ARCHIVOS/" . $fotoNav;

                if(!empty($fotoNav) && file_exists($rutaFotoNav)){

                ?>

                <img 

                    src="<?php echo $rutaFotoNav; ?>" 

                    class="user-image img-circle elevation-1" 

                    alt="User Image"

                    style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">

                <?php

                }else{

                ?>

                <i class="far fa-user-circle"></i>

                <?php

                }

                ?>

                <span class="d-none d-md-inline">

                    <?php echo $_SESSION["nombre"] ?? "Usuario"; ?>

                </span>

            </a>

        </li>

    </ul>

</nav>