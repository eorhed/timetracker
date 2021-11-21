<br>Instrucciones para la instalación y prueba de la aplicación _TimeTracker_.

## Windows

1 . Instalar aplicaciones necesarias para ejecutar Timetracker

> Bajar e instalar el XAMPP desde https://www.apachefriends.org/download.html

> Descargar e instalar el Git desde https://git-scm.com/download/win


2 . Clonar/Copiar proyecto timetracker desde Github

> Vamos al repositorio de Github https://github.com/eorhed/timetracker, pulsamos en Code y en el apartado Clone copiamos la dirección https://github.com/eorhed/timetracker.git

> Abrimos la aplicacion Git-Bash instalada junto con Git, es una terminal de comandos.

> Escribimos cd c:
> Ahora vamos al directorio donde se almacenan las webs en XAMPP, haciendo: cd xampp/htdocs

> Una vez en el directorio, ejecutamos el comando git clone https://github.com/eorhed/timetracker.git

> Descargará el directorio de la aplicación web Timetracker.


3.- Activar servicios de servidor web Apache y servidor de bases de datos MySQL/MariaDB

> Menú inicio -> Ejecutar la aplicación XAMPP-control

> Activar pulsando Start los servicios correspondientes al servidor web Apache y el servidor de base de datos MySQL/MariaDB

> **Esperar a que se pongan en verde**, estado que indica que están en funcionamiento


3-Importar archivo bd.sql con la BD de la aplicación Timetracker.

> Abrimos el navegador e introducimos http://localhost/phpmyadmin

> Entramos como usuario root y pulsamos sobre Importar

> Exploramos las carpetas hasta llegar a C:\XAMPP\htdocs\timetracker\bd y seleccionamos el archivo bd.sql


### <br>Ya está listo Timetracker para ser utilizado

Navegador-> http://localhost/timetracker

[Se recomienda desactivar el bloqueador de publicidad para usarlo]

Para entrar como usuario administrador las credenciales son:
-Email: admin@admin.com
-Clave: Admin123_

## <br>Linux

En proceso...

# <br><p align="right">[[Volver al índice|Home]]</p>
