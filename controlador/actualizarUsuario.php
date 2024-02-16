<?php

    // Incluimos el archivo dónde conectamos a la Base de Datos
    // https://www.php.net/manual/es/function.include.php
    include "../modelo/conexion.php";

    $mensajeSalida="";

    // Validaremos todos los datos que nos han enviado (ver funcion JavaScript "actualizar()")
    // https://www.php.net/manual/es/function.isset.php
    if (isset($_GET["id"]) 
        AND isset($_GET["nombre"]) 
        AND isset($_GET["pass"])
        AND isset($_GET["email"])
        AND isset($_GET["nacimi"])) {
            
        // Si ninguno de los campos del formulario está vacío ...
        $id=$_GET["id"];
        $nombre=$_GET["nombre"];
        $password=$_GET["pass"];
        $email=$_GET["email"];
        $nacim=$_GET["nacimi"];
        
        // Actualizamos los datos en la Base de Datos en el registro correspondiente de la tabla 'Usuario'
        // https://www.php.net/manual/es/mysqli.query.php
        $sql=$conexion->query("UPDATE Usuario set nombre='$nombre', password='$password', email='$email', fechaNac='$nacim' WHERE usrId='$id'");

        $mensajeSalida.=$sql;
        // Comprobamos si se realizo la insercción
        if ($sql === TRUE) {
            // Si se realizó lo mostramos en un mensaje
            // Que se insertará en la parte de la página principal dónde se incluye este fichero .php
            $mensajeSalida.= "Usuario, actualizado en Base de Datos correctamente!";
        } else {
            // Sino informamos del error que indica la Base de Datos
            // https://www.php.net/manual/es/mysqli.error.php
            $mensajeSalida.= "El usuario con el id '$id' no se pudo actualizar. Error: " . $sql . "<br>" . $conn->error."</div>";
        }
        
        // Cerramos la conexión con la Base de Datos
        // https://www.php.net/manual/es/mysqli.close.php
        $conexion->close();

    } else {
        // Si alguno de los campos del formulario está vacío ...
        $mensajeSalida.= "<div class='falloBBDD'>Algún o algunos campo/s están vacíos<ul><li>".$_GET["id"]."</li><li>".$_GET["nombre"]."</li><li>".$_GET["pass"]."</li><li>".$_GET["email"]."</li><li>".$_GET["nacimi"]."</li></ul></div>";
    }

    echo $mensajeSalida;
?>