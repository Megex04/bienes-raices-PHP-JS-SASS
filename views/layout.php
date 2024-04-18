<?php

    if(!isset($_SESSION)) {
        session_start();
    }

    $auth = $_SESSION['login'] ?? false;

    $uri = $_SERVER['REQUEST_URI'];

    $newUri =parse_url($uri);

    if(!isset($inicio)) {
        $inicio = false;
    }
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes Raíces</title>
    <link rel="stylesheet" href="../build/css/app.css">
</head>
<body>
    
    <header class="header <?php echo $inicio ? 'inicio': ''; ?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="../index.php">
                    <img src="../build/img/logo.svg" alt="logotipo de bienes raices">
                </a>

                <div class="mobile-menu">
                    <img src="../build/img/barras.svg" alt="icono menu responsive">
                </div>

                <div class="derecha">
                    <img  class="dark-mode-boton" src="../build/img/dark-mode.svg" alt="boton-oscuro">
                    <nav class="navegacion">
                        <a href="/nosotros">Nosotros</a>
                        <a href="/propiedades">Anuncios</a>
                        <a href="/blog">Blog</a>
                        <a href="/contacto">Contacto</a>
                        <?php if($auth){
                            if($uri == '../public/admin/index.php' ) { ?>
                                <a href="/logout">Cerrar Sesión</a>
                             <?php } elseif( $uri == '../public/admin/propiedades/crear.php' ||
                                             $newUri['path'] == '../public/admin/propiedades/actualizar.php' ){ ?>
                                <a href="/logout">Cerrar Sesión</a>
                            <?php } else { ?>
                                <a href="/logout">Cerrar Sesión</a>
                            <?php }
                        } ?>
                    </nav>
                </div>
                
            </div> <!--.barra-->

            <?php if($inicio) { ?>
                <h1>Venta de Casas y Departamentos Exclusivos de Lujo</h1>
            <?php } ?>
        </div>
    </header>

    <?php echo $contenido; ?>


    <footer class="footer seccion">
        <div class="contenedor contenedor-footer">
            <nav class="navegacion">
                <a href="/nosotros">Nosotros</a>
                <a href="/propiedades">Anuncios</a>
                <a href="/blog">Blog</a>
                <a href="/contacto">Contacto</a>
            </nav>
        </div>
        
        <p class="copyright">Todos los Derechos Reservados <?php echo date('Y'); ?> &copy;</p>
    </footer>

    <script src="../build/js/bundle.min.js"></script>
</body>
</html>