<?php

function conectarDB() : mysqli {
    $db = new mysqli('localhost', 'root', '1204311012mysql', 'bienesraices_crud', 3308);

    if(!$db) {
        echo "Error no se pudo conectar a la DB";
        exit;
    }
    return $db;
}
