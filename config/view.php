<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths de las vistas
    |--------------------------------------------------------------------------
    |
    | Aquí puedes definir los directorios donde Laravel buscará las vistas.
    | Por defecto usa la carpeta "resources/views", pero puedes cambiarlo.
    |
    */

    'paths' => [
        base_path('app/views'), // 👈 cambia esto según donde están tus vistas
    ],

    /*
    |--------------------------------------------------------------------------
    | Complied Path
    |--------------------------------------------------------------------------
    |
    | Esta opción define dónde se almacenan las vistas compiladas de Blade.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

];
