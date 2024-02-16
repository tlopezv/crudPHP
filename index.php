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
            .confirmado, .falloBBDD, #estadoOperacion {
                border: 1px solid gray;
                border-radius: 0.5rem;
                margin: 0.5rem;
                padding: 0.5rem;
                font-weight: bold;
                color: #DDD;
                background-color: #2B1;
            }
            .falloBBDD {
                background-color: #B21;
            }
            #estadoOperacion {
                background-color: #6FF;
            }
            .ocultar {
                display: none;
            }
            .mostrar {
                display: block;
            }
            #btnActualizar {
                display: none;
            }
        </style>
        <script>
            /**
             * Se encarga de limpiar el formulario, incluyendo
             * el cuadro de información de la última acción que se realizó con este.
             * Y muestra el botón "Registrar" (ocultando el de "Actualizar" si fuera necesario)
             */
            function limpiar(){
                // Vuelve a poner a "false" el campo oculto del formulario
                // Para informar al PHP que tratará dicho formulario que no se ha enviado
                document.getElementById('enviado').value='false';
                let mensajeConfirmacion = document.getElementsByClassName('confirmado')[0];
                let mensajeError = document.getElementsByClassName('falloBBDD')[0];
                // De estar el DIV que muestra un mensaje anterior de confirmación de 
                // inserción en la BBDD, lo quitamos
                if (mensajeConfirmacion) {
                    mensajeConfirmacion.remove();
                }
                // De estar el DIV que muestra un mensaje anterior de fallo en la 
                // inserción en la BBDD, lo quitamos
                if (mensajeError) {
                    mensajeError.remove();
                }

                // Ocultamos el "btnActualizar"
                document.getElementById("btnActualizar").style.display="none";
                // Mostramos el "btnRegistrar"
                document.getElementById("btnRegistrar").style.display="inline-block";
            }

            /**
             * Haciendo una petición AJAX al script PHP que elimina un registro de la BBDD
             * Borra el registro de la BBDD y actualiza la vista en la tabla
             */
            function eliminar(id) {
                // Guardamos una referencia al botón de borrar que se pulsó
                let aux = event.target || event.srcElement;
                // Y apuntamos a la fila <tr> en la cuál está:
                let tr = aux.parentElement.parentElement;

                // Hacemos una petición AJAX al PHP "controlador/eliminarUsuario.php"
                // https://www.w3schools.com/php/php_ajax_php.asp
                // https://tutorialesprogramacionya.com/ajaxya/temarios/descripcion.php?punto=5&cod=10&inicio=0
                
                // Creamos un objeto "XMLHttpRequest"
                // Este objeto nos permite enviar y recibir información a través del protocolo HTTP
                // Sin necesidad de cargar un documento HTML
                var xmlhttp = new XMLHttpRequest();
                // Cuando el objeto cambie de estado ejecutará una función
                xmlhttp.onreadystatechange = function() {
                    // La propiedad "readyState" del objeto "XMLHttpRequest"
                    // almacena el estado actual (4 -> Estado Completado, la petición en este caso)
                    // La propiedad "status" del objeto "XMLHttpRequest"
                    // almacena el código de respuesta HTTP https://developer.mozilla.org/es/docs/Web/HTTP/Status
                    if (this.readyState == 4 && this.status == 200) {
                        // Indicamos que se ha borrado correctamente
                        // Recuperando el mensaje que devuelve el fichero php que hemos llamado
                        let mostrarEstado = document.getElementById("estadoOperacion");
                        mostrarEstado.innerHTML= this.responseText;
                        // Quitamos la clase que oculta el DIV del mensaje
                        // https://www.w3schools.com/howto/howto_js_remove_class.asp
                        mostrarEstado.classList.remove("ocultar");
                        // Añadimos una clase que hará visible el DIV del mensaje
                        // https://www.w3schools.com/howto/howto_js_add_class.asp
                        mostrarEstado.classList.add("mostrar");
                        // Y a los 5 segundos lo ocultará
                        // https://www.w3schools.com/jsref/met_win_settimeout.asp
                        setTimeout(function(){
                            mostrarEstado.classList.remove("mostrar");
                            mostrarEstado.classList.add("ocultar");
                            // Si el mensaje de eliminación no informa de ningún Error
                            // https://www.w3schools.com/jsref/jsref_includes.asp
                            if (!mostrarEstado.innerHTML.includes("Error")) {
                                // entonces quitamos de la tabla al fila correspondiente:
                                // https://www.w3schools.com/jsref/met_element_remove.asp#gsc.tab=0
                                //tr.remove();
                                cargarContenidoTabla();
                            }
                        },3000);
                    } 
                };
                // Abre la petición HTTP al Servidor (en este caso a "controlador/eliminarUsuario.php")
                // Pasándole el id que recibe como parámetro esta función
                xmlhttp.open('GET','controlador/eliminarUsuario.php?id='+id);
                // Envia la petición HTTP al Servidor
                xmlhttp.send();
            }

            /**
             * Carga los datos del Usuario de la fila de la tabla en la que se pulsó el botón "Editar"
             * Al formulario. Ocultando el botón "Registrar" y mostrando el de "Actualizar"
             */
            function editar(){
                // Guardamos una referencia al botón de editar que se pulsó
                let aux = event.target || event.srcElement;
                // Y apuntamos a la fila <tr> en la cuál está:
                let tr = aux.parentElement.parentElement;
                // Marcando esta fila con la clase ".editando"
                tr.classList.add("editando");

                // Obtenemos todos los elementos <td> de la fila
                var td = tr.getElementsByTagName("td");
                // Y los asignamos a su lugar correspondiente en el formulario
                document.getElementById("enviado").value=td[0].innerHTML;
                document.getElementById("nombre-usuario").value=td[1].innerHTML;
                document.getElementById("aligned-password").value=td[2].innerHTML;
                document.getElementById("email-usuario").value=td[3].innerHTML;
                document.getElementById("fecha-nacimiento").value=td[4].innerHTML;

                // Ocultaremos el botón Registrar y mostraremos el botón Actualizar en su lugar en el Formulario.
                document.getElementById("btnRegistrar").style.display="none";
                document.getElementById("btnActualizar").style.display="inline-block";     
            }

            /**
             * Haciendo una petición AJAX al script PHP que actualiza un registro de la BBDD
             * Actualiza el registro de la BBDD y actualiza la vista en la tabla
             */
            function actualizar() {
                // Recuperamos la fila en la que se presionó el botón de "Editar"
                let tr = document.getElementsByClassName("editando")[0];

                // Hacemos una petición AJAX al PHP "controlador/actualizarUsuario.php"
                // https://www.w3schools.com/php/php_ajax_php.asp
                // https://tutorialesprogramacionya.com/ajaxya/temarios/descripcion.php?punto=5&cod=10&inicio=0
                
                // Creamos un objeto "XMLHttpRequest"
                // Este objeto nos permite enviar y recibir información a través del protocolo HTTP
                // Sin necesidad de cargar un documento HTML
                var xmlhttp = new XMLHttpRequest();
                // Cuando el objeto cambie de estado ejecutará una función
                xmlhttp.onreadystatechange = function() {
                    // La propiedad "readyState" del objeto "XMLHttpRequest"
                    // almacena el estado actual (4 -> Estado Completado, la petición en este caso)
                    // La propiedad "status" del objeto "XMLHttpRequest"
                    // almacena el código de respuesta HTTP https://developer.mozilla.org/es/docs/Web/HTTP/Status
                    if (this.readyState == 4 && this.status == 200) {
                        // Indicamos que se ha borrado correctamente
                        // Recuperando el mensaje que devuelve el fichero php que hemos llamado
                        let mostrarEstado = document.getElementById("estadoOperacion");
                        mostrarEstado.innerHTML= this.responseText;
                        // Quitamos la clase que oculta el DIV del mensaje
                        // https://www.w3schools.com/howto/howto_js_remove_class.asp
                        mostrarEstado.classList.remove("ocultar");
                        // Añadimos una clase que hará visible el DIV del mensaje
                        // https://www.w3schools.com/howto/howto_js_add_class.asp
                        mostrarEstado.classList.add("mostrar");
                        // Y a los 3 segundos lo ocultará
                        // https://www.w3schools.com/jsref/met_win_settimeout.asp
                        setTimeout(function(){
                            mostrarEstado.classList.remove("mostrar");
                            mostrarEstado.classList.add("ocultar");
                            // Si el mensaje de actualización no informa de ningún Error
                            // https://www.w3schools.com/jsref/jsref_includes.asp
                            if (!mostrarEstado.innerHTML.includes("Error")) {
                                // volvemos a cargar la tabla
                                cargarContenidoTabla();
                                // Se pulsa el botón limpiar para que limpie el formulario.
                                document.getElementById("btnLimpiar").click();
                            }
                        },3000);
                    } 
                };
                // Para mandar los campos del formulario en un AJAX los cogemos del formulario y los pasamos a variables
                let id=document.getElementById("enviado").value;
                let nombre=document.getElementById("nombre-usuario").value;
                let password=document.getElementById("aligned-password").value;
                let email=document.getElementById("email-usuario").value;
                let nacimiento=document.getElementById("fecha-nacimiento").value;

                // Abre la petición HTTP al Servidor (en este caso a "controlador/actualizarUsuario.php")
                // Pasándole el id que recibe como parámetro esta función
                xmlhttp.open('GET','controlador/actualizarUsuario.php?id='+id+'&nombre='+nombre+'&pass='+password+'&email='+email+'&nacimi='+nacimiento);
                // Envia la petición HTTP al Servidor
                xmlhttp.send();
            }

            /**
             * Haciendo una petición AJAX al script PHP que carga el <tbody> de la tabla
             * realiza una consulta a la tabla "Usuario" de la BBDD y actualiza la vista en la tabla
             */
            function cargarContenidoTabla(){
                var xmlhttp = new XMLHttpRequest();
                // Cuando el objeto cambie de estado ejecutará una función
                xmlhttp.onreadystatechange = function() {
                    // La propiedad "readyState" del objeto "XMLHttpRequest"
                    // almacena el estado actual (4 -> Estado Completado, la petición en este caso)
                    // La propiedad "status" del objeto "XMLHttpRequest"
                    // almacena el código de respuesta HTTP https://developer.mozilla.org/es/docs/Web/HTTP/Status
                    if (this.readyState == 4 && this.status == 200) {
                        // Recuperando el mensaje que devuelve el fichero php que hemos llamado
                        document.getElementsByTagName("tbody")[0].innerHTML= this.responseText;
                    } 
                };
                // Abre la petición HTTP al Servidor (en este caso a "controlador/cargarUsuario.php")
                // Pasándole el id que recibe como parámetro esta función
                xmlhttp.open('GET','controlador/cargarUsuario.php');
                // Envia la petición HTTP al Servidor
                xmlhttp.send();
            }
        </script>
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
                <form class="pure-form pure-form-aligned" method="POST">
                    <fieldset>
                        <legend>Registro de usuarios: </legend>
                        <input type="hidden" id="enviado" name="enviado" value="false"/>
                        <div class="pure-control-group">
                            <label for="nombre-usuario">Nombre de Usuario</label>
                            <input type="text" id="nombre-usuario" name="nombreUsuario" placeholder="Nombre de usuario" />
                        </div>
                        <div class="pure-control-group">
                            <label for="aligned-password">Password</label>
                            <input type="password" id="aligned-password" name="password" placeholder="Password" />
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
                            <!-- Se le han añadido el atributo "id" a todos los botones -->
                            <button type="submit" class="pure-button pure-button-primary" onclick="document.getElementById('enviado').value='true'" id="btnRegistrar">Registrar</button>
                            <!-- Se añade un botón más (oculto con la clase .ocultar) que llamará a la función JavaScript "actualizar()" -->
                            <button type="button" class="pure-button pure-button-primary" onclick="actualizar()" id="btnActualizar">Actualizar</button>
                            <button type="reset" class="pure-button pure-button-primary" onclick="limpiar()" id="btnLimpiar">Limpiar</button>
                        </div>
                    </fieldset>
                </form>
                <!-- Aquí incluiremos el controlador, que recibirá el formulario -->
                <!-- Para en caso de error en la validación muestre aquí el mensaje de error -->
                <?php
                    include "controlador/registroUsuario.php";
                ?>
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
                        <script>
                            cargarContenidoTabla();
                        </script>
                    </tbody>
                </table>
                <div id="estadoOperacion" class="ocultar">
                </div>
            </div>
        </div>
    </body>
</html>