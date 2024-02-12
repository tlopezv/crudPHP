/**
    -- Nos situamos en el directorio dónde tengamos este fichero

    -- Comprobamos el estado del servidor MySQL
    # systemctl status mysql

    -- NOTA: Si está parado lo arrancamos:
    # systemctl start mysql

    -- Y nos conectamos con el usuario "root" en MySQL:
    # sudo mysql -u root -padministrador

    -- Ejecutamos este script:
    mysql> source ./bbdd.sql
**/
-- *** lenguaje de Definición de Datos (DDL) *** --
-- Creamos la Base de Datos 'crudPHP' ( _ci (case insensitive) )
-- https://www.mysqltutorial.org/mysql-basics/mysql-create-database/

CREATE DATABASE IF NOT EXISTS crudPHP CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Creamos el usuario para esta APP de CRUD (Create, Read, Update, Delete) con PHP y MySQL
-- Usuario: crudPHP
-- Contraseña: 1234
-- NOTA: Tras el @ le ponemos '%' para que pueda conectar no sólo desde Localhost
-- https://www.mysqltutorial.org/mysql-administration/mysql-create-user/
-- NOTA: Mirar la política de asignación de Password, con la consulta:
--  mysql> SHOW VARIABLES LIKE 'validate_password%';

CREATE USER IF NOT EXISTS 'crudPHP'@'%' IDENTIFIED WITH mysql_native_password BY 'crudPHP.1234';

-- Le damos todos los privilegios (permitimos cualquier acción) al usuario 'crudPHP' sobre la Base de Datos 'crudPHP'
-- https://www.mysqltutorial.org/mysql-administration/mysql-grant/

GRANT ALL PRIVILEGES ON crudPHP.* TO 'crudPHP'@'%';

-- Nos aseguramos de volver a cargar de nuevo todos los privilegios, para que se tengan en cuenta los recién creados.

FLUSH PRIVILEGES;

-- Le indicamos que utilice la Base de Datos 'crudPHP'
-- https://www.mysqltutorial.org/mysql-basics/selecting-a-mysql-database-using-use-statement/

USE crudPHP;

-- Creamos la tabla 'Usuario'
-- https://www.mysqltutorial.org/mysql-basics/mysql-create-table/

CREATE TABLE IF NOT EXISTS Usuario (
    usrId INT AUTO_INCREMENT PRIMARY KEY COMMENT 'La clave de usuario la genera internamente el propio Gestor de Base de Datos.',
    nombre VARCHAR(30) NOT NULL COMMENT 'Nombre del usuario.',
    password VARCHAR(30) NOT NULL COMMENT 'Contraseña de usuario.',
    email VARCHAR(80) NOT NULL UNIQUE COMMENT 'Correo electrónico del usuario; Obligatorio.', -- No puede haber 2 usuarios o más con el mismo email.
    fechaNac DATE NOT NULL COMMENT 'Fecha de nacimiento del usuario; Obligatorio.'
);

-- He insertamos datos en la tabla 'Usuario':
-- https://www.mysqltutorial.org/mysql-basics/mysql-insert/
INSERT INTO Usuario(nombre, password, email, fechaNac) VALUES ('Administrador','AdminRoot','admin@admin.com','1990-12-20');
INSERT INTO Usuario(nombre, password, email, fechaNac) VALUES ('Prueba','TestTest','test@test.com','1990-12-20');
INSERT INTO Usuario(nombre, password, email, fechaNac) VALUES ('Oldest','FirstUser','main@user.com','2000-02-12');
INSERT INTO Usuario(nombre, password, email, fechaNac) VALUES ('Curioso','Visitante','sniffer@snoffing.com','1998-07-03');

-- Confirmamos todas las inserciones de datos en la Base de Datos 'crudPHP'

COMMIT;