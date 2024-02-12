<?php
// Creamos una variable '$conexion' en la que establecemos la conexión con la
// Base de datos 'crudPHP' con el usuario que creamos para dicha práctica 'crudPHP'
// NOTA: Nos conectamos a la Base de Datos que está en el mismo equipo luego 'localhost'
// https://www.php.net/manual/es/function.mysqli-connect
// Sintaxis mysqli_connect('host','usuario','contraseña','BaseDeDatos') -- El puerto al ser el por defecto, 3306, no lo pongo como último argumento.
//$conexion=new mysqli('localhost','root','administrador','crudPHP');
$conexion = mysqli_connect("127.0.0.1", "crudPHP", "crudPHP.1234", "crudPHP");
// Para que reconozca cualquier caracter extraño, eñes, etc, indicamos la codificación de caracteres
// que utilizaremos en esta conexión.
// https://www.php.net/manual/es/mysqli.set-charset.php
$conexion->set_charset('utf8');

/* NOTA: Si da el error:
    Uncaught Error: Class "mysqli" not found in /home/...

   Se debe a que nos falta instalar MySQLi con el comando:

    # sudo apt install php-mysql

   Y editar el fichero /usr/local/lib/php.ini para descomentar las líneas
    ;extension=mysqli
   Sólo hay que quitar el ; del principio.

*/
?>