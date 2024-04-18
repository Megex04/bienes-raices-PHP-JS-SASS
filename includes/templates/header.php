<?php

    if(!isset($_SESSION)) {
        session_start();
    }

    $auth = $_SESSION['login'] ?? false;

    // var_dump($auth);

    //validar rutas=>
    $uri = $_SERVER['REQUEST_URI'];
    // var_dump($uri);

    $newUri =parse_url($uri); 
    // var_dump(parse_url($uri));
    // var_dump($newUri['path']);
    // echo "<pre>";
    // var_dump($_SERVER);
    // echo "</pre>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes Raíces</title>
    <link rel="stylesheet" href="/bienesraices/build/css/app.css">
</head>
<body>
    
    <header class="header <?php echo $inicio ? 'inicio': ''; ?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="/bienesraices/index.php">
                    <img src="/bienesraices/build/img/logo.svg" alt="logotipo de bienes raices">
                </a>

                <div class="mobile-menu">
                    <img src="/bienesraices/build/img/barras.svg" alt="icono menu responsive">
                </div>

                <div class="derecha">
                    <img  class="dark-mode-boton" src="/bienesraices/build/img/dark-mode.svg" alt="boton-oscuro">
                    <nav class="navegacion">
                        <a href="nosotros.php">Nosotros</a>
                        <a href="anuncios.php">Anuncios</a>
                        <a href="blog.php">Blog</a>
                        <a href="contacto.php">Contacto</a>
                        <?php if($auth){
                            if($uri == '/bienesraices/admin/index.php' ) { ?>
                                <a href="../cerrar-sesion.php">Cerrar Sesión</a>
                             <?php } elseif( $uri == '/bienesraices/admin/propiedades/crear.php' ||
                                             $newUri['path'] == '/bienesraices/admin/propiedades/actualizar.php' ){ ?>
                                <a href="../../cerrar-sesion.php">Cerrar Sesión</a>
                            <?php } else { ?>
                                <a href="cerrar-sesion.php">Cerrar Sesión</a>
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