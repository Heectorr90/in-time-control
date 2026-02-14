# in-time-control

Sistema de Gestión de Inventario

Aplicación desarrollada en Laravel + Livewire para la gestión de categorías jerárquicas (Padre → Hijo → Subcategoría) y Equipos, con validaciones de integridad referencial y filtros dinámicos.

Requisitos

    Asegúrate de tener instalado:

        PHP >= 8.2

        Composer >= 2.8

        Node.js >= 22.16

        NPM >= 11.4

        MySQL / MariaDB

        Git

Instalación

    Clonar el repositorio

        git clone https://github.com/Heectorr90/in-time-control.git
        cd tu-repo

    Instalar dependencias de PHP
        composer install

    Instalar dependencias de Node
        npm install

Configurar variables de entorno

    Copiar el archivo .env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=inventario_in_time_control
        DB_USERNAME=usuario "cambiar por tus datos"
        DB_PASSWORD=contraseña "cambiar por tus datos"

Migraciones y Seeders
php artisan migrate --seed

Ejecutar el proyecto

    Levantar servidor
        php artisan serve
    Compilar assets
        npm run dev

Acceder

    http://127.0.0.1:8000

Funcionalidades principales

    CRUD de Categorías Padre, Hijo y Subcategorías

    CRUD de Equipos

    Filtros jerárquicos dinámicos

    Validación para evitar eliminación con dependencias

    Mensajes flash de éxito y error

    Interfaz limpia con TailwindCSS

Consideraciones Técnicas

    Se bloquea la eliminación si existen registros relacionados para preservar la integridad referencial.

    Uso de relaciones Eloquent correctamente definidas.

    Filtros encadenados en tiempo real mediante Livewire.

Autor

    Héctor Jovanny Ramírez Malváez
