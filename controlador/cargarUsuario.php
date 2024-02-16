<?php
    /* Cargamos los registro de la Base de Datos MySQL "crudPHP" */
    
    // Incluimos el archivo dónde conectamos a la Base de Datos
    // https://www.php.net/manual/es/function.include.php
    include "../modelo/conexion.php";

    // Hacemos una consulta a la tabla crudPHP.Usuario
    // https://www.php.net/manual/es/mysqli.query.php
    $sql=$conexion->query("SELECT * FROM crudPHP.Usuario");

    // Para aplicar correctamente los estilos cebreados de la tabla
    // En filas contiguas color de fondo diferente
    $filaImpar=true;

    // Recorremos los datos obtenidos de la consulta anterior
    // fila a fila gracias a como lo devuelve la función:
    // https://www.php.net/manual/es/mysqli-result.fetch-object.php
    while ($datos = $sql->fetch_object()) {
        // Comprobamos si es fila par o impar para aplicar el estilo correspondiente
        if ($filaImpar){
?>
<tr>
<?php
        } else {
?>
<tr class="pure-table-odd">
<?php
        }
        // Indicamos que si esta fila es Impar la siguiente no lo es
        $filaImpar=!$filaImpar;
        /*
            <?= ?>
        Es el equivalente de hacer

            <?php echo; ?>
        https://www.php.net/manual/es/language.basic-syntax.phptags.php
        */
?>
    <td><?= $datos->usrId ?></td>
    <td><?= $datos->nombre ?></td>
    <td><?= $datos->password ?></td>
    <td><?= $datos->email ?></td>
    <td><?= $datos->fechaNac ?></td>
    <td>
        <!-- Botones "Buttons - Pure CSS" https://purecss.io/buttons/#customizing-buttons -->
        <button class="button-warning pure-button" onclick="editar()">
            <!-- https://www.compart.com/en/unicode/U+270E -->
            &#9998;
        </button>
        &nbsp;&nbsp;
        <button class="button-error pure-button" onclick="eliminar(<?= $datos->usrId ?>)">
            <!-- https://www.amp-what.com/unicode/search/trash -->
            &#128465;
        </button>
    </td>
</tr>
<?php
    }

    // Cerramos la conexión con la Base de Datos
    // https://www.php.net/manual/es/mysqli.close.php
    $conexion->close();
?>