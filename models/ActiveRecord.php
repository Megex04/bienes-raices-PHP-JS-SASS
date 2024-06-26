<?php

namespace Model;

class ActiveRecord {
    // base de datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';
    // errores
    protected static $errores = [];

    public $id;
    public $imagen;

    // definir la conexion a la BD
    public static function setDB($database) {
        self::$db = $database;
    }

    public function guardar() {

        // if( ($this->id) != "") { // modificado PARA QUE FUNCIONE
        if( !is_null($this->id)) {
            // actualizar
            $this->actualizar();
        } 
        else {
            //creando un nuevo registro
           return $this->crear();
        }
        
    }
    public function actualizar() {
        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];

        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = " UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1";


        $resultado = self::$db->query($query);

        if ($resultado) {
            // redireccionar al usuario
            header('Location: /admin?resultado=2');
        }
    }

    public function crear() {

        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        // insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        $resultado = self::$db->query($query);

        if ($resultado) {
            header('Location: /admin?resultado=1');
        }
    }
    // eliminar un registro
    public function eliminar() {
        // eliminar la propiedad de la base de datos
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        if($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    // identificar y unir atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna){
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }
    // subida de archivos
    public function setImagen($imagen) {
        // eliminar la imagen previa
        if( !is_null($this->id) ) { // modificado PARA QUE FUNCIONE
            // comprobar si existe el archivo
            $this->borrarImagen();
        }

        // asignar al atributo de imagen el nombre de la imagen
        if($imagen) {
            $this->imagen = $imagen;
        }
    }
    // elimina el archivo
    public function borrarImagen() {
        // comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    // validacion
    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    // lista todads los registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    // lista todads los registros
    public static function getLimit($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    // busca una propiedad por su id (PARA MODIFICAR)
    public static function finById($id) {
        $consultaUpdate = "SELECT * FROM " . static::$tabla . " WHERE id = $id";

        $resultado = self::consultarSQL($consultaUpdate);
        return array_shift( $resultado );
    }

    public static function consultarSQL($query) {
        // consultar la base de datos
        $resultado = self::$db->query($query);

        //iterar os resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
        
        // liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value) {
            if(property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // sincroniza el objeto en memoria con los cambios del usuario(PARA MODIFICAR)
    public function sincronizar($args = []) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}