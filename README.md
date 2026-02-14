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
        cd in-time-control

    Instalar dependencias de PHP
        composer install

    Configurar entorno
        cp .env.example .env
            Editar el archivo .env y configurar las credenciales de la base de datos.
        php artisan key:generate

    Ejecutar migraciones y seeders
        php artisan migrate --seed


    Instalar dependencias de Node
        npm install

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
