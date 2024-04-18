<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {

    public static function index(Router $router) {
        
        $propiedades = Propiedad::all();

        $vendedores = Vendedor::all();

        // muestra mensaje condicional
        $resultado = $_GET['resultado'] ?? null;

        $router->render("propiedades/admin", [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }

    public static function crear(Router $router) {

        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();

        //arreglo con mensajes de errores
        $errores = Propiedad::getErrores();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            // crea una nueva instancia
            $propiedad = new Propiedad($_POST['propiedad']);

            // generar un nombre unico
            $nombreImagen = md5(uniqid( rand(), true )) . ".jpg";

            // realiza un resize a la img con interventionImage
            // establece la imagen
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
                $propiedad->setImagen($nombreImagen);
            }

            $errores = $propiedad->validar();
            
            
            // revisar que el arreglo de errores este vacio
            if (empty($errores)) {

                // crear la carpeta para subir imagenes
                if(!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // guarda la imagen en el servidor(save=> metodo de InterventionImage)
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                // guarda en la BD
                $propiedad->guardar();

                
            }
        }

        $router->render("propiedades/crear", [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        
        $id = validarORedireccionar('/admin');

        $propiedad = Propiedad::finById($id);

        $vendedores = Vendedor::all();

        $errores = Propiedad::getErrores();

        // metodo POST para actualizar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // asignar los atributos
            $args = $_POST['propiedad'];
        
            $propiedad->sincronizar($args);
        
            // validacion
            $errores = $propiedad->validar();
        
            // subida de archivos
            // generar un nombre unico
            $nombreImagen = md5(uniqid( rand(), true )) . ".jpg";
        
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
                $propiedad->setImagen($nombreImagen);
            }
        
            // revisar que el arreglo de errores este vacio
            if (empty($errores)) {
                // almacenar la imagen
                if($_FILES['propiedad']['tmp_name']['imagen']) {
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
        
                $propiedad->guardar();
            }
        }

        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);

    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {


            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
    
            if($id) {
    
                $tipo = $_POST['tipo'];
                if(validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::finById($id);
                    $propiedad->eliminar();
                }
                
            }
            
        }
    }
}

