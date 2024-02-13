<?php

    // Incluimos el archivo dónde conectamos a la Base de Datos
    // https://www.php.net/manual/es/function.include.php
    include "modelo/conexion.php";

    // Comprobamos si el botón de enviar el formulario se ha presionado
    // https://www.php.net/manual/es/function.isset.php
    if (isset($_POST["enviado"]) AND ($_POST["enviado"] != "false")){
        // Si se ha pulsado el botón de enviar el formulario
        // validaremos todos los datos del formulario.
        if (!empty($_POST["nombreUsuario"]) 
            AND !empty($_POST["password"])
            AND !empty($_POST["emailUsuario"])
            AND !empty($_POST["fechaNacimiento"])) {
            
            // Si ninguno de los campos del formulario está vacío ...
            $nombre=$_POST["nombreUsuario"];
            $password=$_POST["password"];
            $email=$_POST["emailUsuario"];
            $nacim=$_POST["fechaNacimiento"];
            
            // Guardamos los datos en la Base de Datos como un nuevo registro en la tabla 'Usuarios'
            // https://www.php.net/manual/es/mysqli.query.php
            $sql=$conexion->query("INSERT INTO Usuario(nombre, password, email, fechaNac) VALUES ('$nombre','$password','$email','$nacim')");

            // Comprobamos si se realizo la insercción
            if ($sql === TRUE) {
                // Si se realizó lo mostramos en un mensaje
                // Que se insertará en la parte de la página principal dónde se incluye este fichero .php
                echo "<div class='confirmado'>Nuevo usuario, registrado en Base de Datos correctamente!</div>";
            } else {
                // Sino informamos del error que indica la Base de Datos
                // https://www.php.net/manual/es/mysqli.error.php
                echo "<div class='falloBBDD'>Error: " . $sql . "<br>" . $conn->error."</div>";
            }
            
            // Cerramos la conexión con la Base de Datos
            // https://www.php.net/manual/es/mysqli.close.php
            $conexion->close();

        } else {
            // Si alguno de los campos del formulario está vacío ...
            echo "<div class='falloBBDD'>Algún o algunos campo/s están vacíos</div>";
        }
    }
?>