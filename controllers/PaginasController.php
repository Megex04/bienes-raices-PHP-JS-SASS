<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index( Router $router ) {
        
        $propiedades = Propiedad::getLimit(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }
    public static function nosotros( Router $router ) {
        
        $router->render('paginas/nosotros', []);
    }
    public static function propiedades( Router $router ) {
        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }
    public static function propiedad( Router $router ) {
        
        $id = validarORedireccionar('/propiedades');

        // buscar la propiedad por su id
        $propiedad = Propiedad::finById($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }
    public static function blog( Router $router ) {
        
        $router->render('paginas/blog');
    }
    public static function entrada( Router $router ) {
        
        $router->render('paginas/entrada');
    }
    public static function contacto( Router $router ) {
        
        $mensaje = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ENVIO DE CORREOS CON LA LIBRERIA phpmailer

            $respuestas = $_POST['contacto'];

            // crear una instancia de PHPMailer
            $mail = new PHPMailer();

            //configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'cd687014cb5dec';
            $mail->Password = '2840a2dc633eb9';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // configurar el contenido del mail
            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', 'bienesraices.com');
            $mail->Subject = 'Tienes un nuevo mensaje';

            // habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            // definir el contenido del correo
            $contenido = '<html>';
            $contenido .= '<p>Tienes un nuevo mensaje</p>';
            $contenido .= '<p>Nombre:  ' . $respuestas['nombre'] .'</p>';

            if($respuestas['contacto'] === 'telefono') {
                
                $contenido .= '<p>Eligió ser contactado por Telefonia:</p>';
                $contenido .= '<p>Teléfono:  ' . $respuestas['telefono'] .'</p>';
                $contenido .= '<p>Fecha Contacto:  ' . $respuestas['fecha'] .'</p>';
                $contenido .= '<p>Hora:  ' . $respuestas['hora'] .'</p>';
            } else {
                $contenido .= '<p>Eligió ser contactado por Electronic mail:</p>';
                $contenido .= '<p>Email:  ' . $respuestas['email'] .'</p>';
            }
            
            $contenido .= '<p>Mensaje:  ' . $respuestas['mensaje'] .'</p>';
            $contenido .= '<p>Vende o compra:  ' . $respuestas['tipo'] .'</p>';
            $contenido .= '<p>Precio o presupuesto:  S/.' . $respuestas['precio'] .'</p>';
            $contenido .= '<p>Prefiere ser contactado por:  ' . $respuestas['contacto'] .'</p>';
            $contenido .= '</html>';

            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es un texto alternativo sin HTML';

            // enviar email
            if($mail->send()) {
                $mensaje = "Mensaje ENVIADO CORRECTAMENTE";
            } else {
                $mensaje ="El mensaje NO SE ENVIO :'(";
            }
        }

        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}

