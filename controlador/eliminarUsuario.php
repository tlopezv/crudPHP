<?php

    // Incluimos el archivo dónde conectamos a la Base de Datos
    // https://www.php.net/manual/es/function.include.php
    include "../modelo/conexion.php";

    // Comprobamos si se ha recibido el "id" del registro a eliminar en la Base de Datos
    // https://www.php.net/manual/es/function.isset.php
    if (isset($_GET["id"])){

        $id=$_GET["id"];
        
        // Eliminamos el registro correspondiente a ese "usrId" de la Base de Datos
        // https://www.php.net/manual/es/mysqli.query.php
        $sql=$conexion->query("DELETE FROM Usuario WHERE usrId=$id");

            // Comprobamos si se realizo la eliminación
            if ($sql === TRUE) {
                // Si se realizó lo mostramos en un mensaje
                echo "Usuario con id '$id' eliminado correctamente";
            } else {
                // Sino informamos del error que indica la Base de Datos
                // https://www.php.net/manual/es/mysqli.error.php
                echo "El usuario con el id '$id' no se pudo borrar. Error: " . $sql . "<br>" . $conn->error."</div>";
            }
            
            // Cerramos la conexión con la Base de Datos
            // https://www.php.net/manual/es/mysqli.close.php
            $conexion->close();

        }
?>