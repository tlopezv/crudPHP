<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crud en PHP y MySQL</title>
        <!-- Utilizaremos el Framework CSS "Pure CSS" -->
        <!-- https://purecss.io/start/#add-pure-to-your-page -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
        <!-- https://purecss.io/grids/#pure-responsive-grids -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/grids-responsive-min.css" />
        <style>
            h2, form, table {
                margin: auto 2rem ;
                padding: 2rem;
            }

            h2 {
                text-align: center;
            }

            .button-error,
            .button-warning{
                color: white;
                border-radius: 4px;
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            }

            .button-error {
                background: rgb(180, 80, 80); /*rgb(202, 60, 60);*/
                /* this is a maroon */
            }

            .button-warning {
                background: rgb(223, 187, 20); /*rgb(223, 117, 20);*/
                /* this is an orange */
            }
            td > button {
                font-weight: bolder;
            }
        </style>
        <!-- Para probar el documnto en el "Servidor web interno" de PHP https://www.php.net/manual/es/features.commandline.webserver.php 
            Ejecutamos en el directorio dónde se encuentran los fichero .php a probar:
                $ php -S localhost:8000
        -->
    </head>
    <body>
        <!-- Encabezado "Grid - Pure CSS" https://purecss.io/grids/#pure-responsive-grids -->
        <div class="pure-g">
            <div class="pure-u-1-5"></div>
            <div class="pure-u-3-5">
                <h2>CRUD (Create, Read, Update, Delete) en PHP y MySQL</h2>
            </div>
            <div class="pure-u-1-5"></div>
        </div>
        <!-- Sección principal "Grid - Pure CSS" https://purecss.io/grids/#pure-responsive-grids -->
        <div class="pure-g">
            <div class="pure-u-2-6">
                <!-- Formulario de registro "Form - Pure CSS" https://purecss.io/forms/#aligned-form -->
                <form class="pure-form pure-form-aligned">
                    <fieldset>
                        <div class="pure-control-group">
                            <label for="nombre-usuario">Nombre de Usuario</label>
                            <input type="text" id="nombre-usuario" name="nombreUsuario" placeholder="Nombre de usuario" />
                        </div>
                        <div class="pure-control-group">
                            <label for="aligned-password">Password</label>
                            <input type="password" id="aligned-password" placeholder="Password" />
                        </div>
                        <div class="pure-control-group">
                            <label for="email-usuario">Email</label>
                            <input type="email" id="email-usuario" name="emailUsuario" placeholder="Email" />
                        </div>
                        <div class="pure-control-group">
                            <label for="fecha-nacimiento">Fecha de nacimiento</label>
                            <input type="date" id="fecha-nacimiento" name="fechaNacimiento" placeholder="Fecha de nacimiento" />
                        </div>
                        <div class="pure-controls">
                            <button type="submit" class="pure-button pure-button-primary">Registrar</button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="pure-u-4-6">
                <!-- Tabla "Table - Pure CSS" https://purecss.io/tables/#striped-table -->
                <table class="pure-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Password</th>
                            <th>Email</th>
                            <th>Fecha de nacimiento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            /* Cargamos los registro de la Base de Datos MySQL "crudPHP" */
                            
                            // Incluimos el archivo dónde conectamos a la Base de Datos
                            // https://www.php.net/manual/es/function.include.php
                            include "modelo/conexion.php";

                            // Hacemos una consulta a la tabla crudPHP.Usuario
                            // https://www.php.net/manual/es/mysqli.query.php
                            $sql=$conexion->query("SELECT * FROM crudPHP.Usuario");

                            // Recorremos los datos obtenidos de la consulta anterior
                            // fila a fila gracias a como lo devuelve la función:
                            // https://www.php.net/manual/es/mysqli-result.fetch-object.php
                            while ($datos = $sql->fetch_object()) {
                                // Comprobamos si es fila par o impar para aplicar el estilo correspondiente
                                if ($datos->usrId%2==0){
                        ?>
                        <tr>
                        <?php
                                } else {
                        ?>
                        <tr class="pure-table-odd">
                        <?php
                                }
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
                                <button class="button-warning pure-button">
                                    <!-- https://www.compart.com/en/unicode/U+270E -->
                                    &#9998;
                                </button>
                                &nbsp;&nbsp;
                                <button class="button-error pure-button">
                                    <!-- https://www.amp-what.com/unicode/search/trash -->
                                    &#128465;
                                </button>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                        <!-- <tr>
                            <td>2</td>
                            <td>Prueba</td>
                            <td>TestTest</td>
                            <td>test@test.com</td>
                            <td>20-12-1990</td>
                            <td>
                                <!-- Botones "Buttons - Pure CSS" https://purecss.io/buttons/#customizing-buttons -->
                                <!-- <button class="button-warning pure-button">&#9998;</button>
                                &nbsp;&nbsp;
                                <button class="button-error pure-button">&#128465;</button>
                            </td>
                        </tr>
                        <tr class="pure-table-odd">
                            <td>3</td>
                            <td>Oldest</td>
                            <td>FirstUser</td>
                            <td>main@user.com</td>
                            <td>12-02-2000</td>
                            <td>
                                <!-- Botones "Buttons - Pure CSS" https://purecss.io/buttons/#customizing-buttons -->
                                <!-- <button class="button-warning pure-button">&#9998;</button>
                                &nbsp;&nbsp;
                                <button class="button-error pure-button">&#128465;</button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Curioso</td>
                            <td>Visitante</td>
                            <td>sniffer@snoffing.com</td>
                            <td>03-07-1998</td>
                            <td>
                                <!-- Botones "Buttons - Pure CSS" https://purecss.io/buttons/#customizing-buttons -->
                                <!-- <button class="button-warning pure-button">&#9998;</button>
                                &nbsp;&nbsp;
                                <button class="button-error pure-button">&#128465;</button>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>