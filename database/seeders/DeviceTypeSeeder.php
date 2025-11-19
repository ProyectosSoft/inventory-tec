<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceType;

class DeviceTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Computador de Escritorio
        DeviceType::updateOrCreate(
            ['key' => 'Computador de Escritorio'],
            [
                'name'   => 'Computador de Escritorio',
                'code'   => 'PC',
                'schema' => [
                    'groups' => [


                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brandcarac',    'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'modelcarac',    'label' => 'Modelo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'seriecarac',    'label' => 'Serial',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'wificarac',     'label' => 'WiFi',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'ethernetcarac', 'label' => 'Ethernet',    'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],


                        [
                            'label'  => 'RAM (general)',
                            'fields' => [
                                ['key' => 'ram.general', 'label' => 'RAM (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        // <<< RAM REPETIBLE >>>
                        [
                            'label'      => 'Memoria RAM (por slot)',
                            'columns'    => 4,
                            // repeatable define meta de colección
                            'repeatable' => [
                                // prefijo lógico para la colección
                                'key'   => 'ram.slots',
                                // etiquetas y límites opcionales
                                'itemLabel' => 'Slot RAM',
                                'min'   => 1,
                                'max'   => 8
                            ],
                            // estos campos se repiten por cada fila/slot
                            'fields'  => [
                                ['key' => 'type',    'label' => 'Tipo',        'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'brand',   'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'size_gb', 'label' => 'Tamaño (GB)', 'type' => 'number', 'rules' => 'nullable|integer|min:1|max:1024'],
                                ['key' => 'freq',    'label' => 'Frecuencia',  'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],

                        [
                            'label'  => 'Procesador',
                            'fields' => [
                                ['key' => 'cpu.general', 'label' => 'General',   'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.brand',   'label' => 'Marca',     'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.model',   'label' => 'Modelo',    'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'cpu.speed',   'label' => 'Velocidad', 'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.cores',   'label' => 'Núcleos',   'type' => 'number', 'rules' => 'nullable|integer|min:1|max:128'],
                                ['key' => 'cpu.threads', 'label' => 'Hilos',     'type' => 'number', 'rules' => 'nullable|integer|min:1|max:256'],
                                ['key' => 'cpu.type',    'label' => 'Tipo',      'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],

                        [
                            'label'  => 'GRÁFICOS (general)',
                            'fields' => [
                                ['key' => 'graf.general', 'label' => 'GRÁFICOS (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        [
                            'label'      => 'Gráficos (GPU)',
                            'columns'    => 4,
                            'repeatable' => [
                                'key'       => 'gpu.units',
                                'itemLabel' => 'GPU',
                                'min'       => 1,
                                'max'       => 4
                            ],
                            'fields' => [
                                ['key' => 'brand',    'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'model',    'label' => 'Modelo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'gddr',     'label' => 'GDDR',        'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'memory_gb', 'label' => 'Tamaño (GB)', 'type' => 'number', 'rules' => 'nullable|integer|min:1|max:100000'],
                            ],
                        ],
                        [
                            'label'  => 'Almcenamiento (general)',
                            'fields' => [
                                ['key' => 'almgral.general', 'label' => 'Almcenamiento (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        [
                            'label'      => 'Almacenamiento (Discos duros)',
                            'columns'    => 4,
                            'repeatable' => [
                                'key'       => 'storage.disks',
                                'itemLabel' => 'Disco',
                                'min'       => 1,
                                'max'       => 6
                            ],
                            'fields' => [
                                ['key' => 'fullname',  'label' => 'Nombre Completo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'technology', 'label' => 'Tecnologia',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'brand',     'label' => 'Marca',                'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'model',     'label' => 'Modelo',               'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'capacity',  'label' => 'Capacidad (GB)',       'type' => 'number', 'rules' => 'nullable|integer|min:1|max:100000'],
                                ['key' => 'serial',    'label' => 'Serial',               'type' => 'text',   'rules' => 'nullable|string|max:100'],
                            ],
                        ],
                        [
                            'label'  => 'Fuente de Poder',
                            'fields' => [
                                ['key' => 'psu.general', 'label' => 'General', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.brand',   'label' => 'Marca',   'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.model',   'label' => 'Modelo',  'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.watts',   'label' => 'Watts',   'type' => 'number', 'rules' => 'nullable|integer|min:1|max:2000'],
                            ],
                        ],
                        [
                            'label'  => 'Información del Sistema',
                            'fields' => [
                                ['key' => 'os.name',        'label' => 'Sistema Operativo',   'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'os.version',     'label' => 'Compilación / Versión', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.admin',     'label' => 'Usuario Admin',       'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.secondary', 'label' => 'Usuario 2',           'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.others',    'label' => 'Otros Usuarios',      'type' => 'text', 'rules' => 'nullable|string|max:255'],
                                ['key' => 'device.name',    'label' => 'Nombre de Equipo',    'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'anydesk.code',   'label' => 'Código AnyDesk',      'type' => 'text', 'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                        [
                            'label'  => 'Pantalla',
                            'fields' => [
                                ['key' => 'brandscr',        'label' => 'Marca',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'modelscr',        'label' => 'Modelo',       'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'serialscr',       'label' => 'Serie',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'size_inchesscr',  'label' => 'Pulgadas',     'type' => 'number', 'rules' => 'nullable|integer|min:1|max:200'],
                                ['key' => 'connectivityscr', 'label' => 'Conectividad', 'type' => 'text',   'rules' => 'nullable|string|max:100'],
                            ],
                        ],



                    ],
                ],
            ]
        );

        // Computador Portátil
        DeviceType::updateOrCreate(
            ['key' => 'Computador Portátil'],
            [
                'name'   => 'Computador Portátil',
                'code'   => 'PC',
                'schema' => [
                    'groups' => [


                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brandcarac',    'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'modelcarac',    'label' => 'Modelo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'seriecarac',    'label' => 'Serial',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'wificarac',     'label' => 'WiFi',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'ethernetcarac', 'label' => 'Ethernet',    'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],


                        [
                            'label'  => 'RAM (general)',
                            'fields' => [
                                ['key' => 'ram.general', 'label' => 'RAM (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        // <<< RAM REPETIBLE >>>
                        [
                            'label'      => 'Memoria RAM (por slot)',
                            'columns'    => 4,
                            // repeatable define meta de colección
                            'repeatable' => [
                                // prefijo lógico para la colección
                                'key'   => 'ram.slots',
                                // etiquetas y límites opcionales
                                'itemLabel' => 'Slot RAM',
                                'min'   => 1,
                                'max'   => 8
                            ],
                            // estos campos se repiten por cada fila/slot
                            'fields'  => [
                                ['key' => 'type',    'label' => 'Tipo',        'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'brand',   'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'size_gb', 'label' => 'Tamaño (GB)', 'type' => 'number', 'rules' => 'nullable|integer|min:1|max:1024'],
                                ['key' => 'freq',    'label' => 'Frecuencia',  'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],

                        [
                            'label'  => 'Procesador',
                            'fields' => [
                                ['key' => 'cpu.general', 'label' => 'General',   'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.brand',   'label' => 'Marca',     'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.model',   'label' => 'Modelo',    'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'cpu.speed',   'label' => 'Velocidad', 'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.cores',   'label' => 'Núcleos',   'type' => 'number', 'rules' => 'nullable|integer|min:1|max:128'],
                                ['key' => 'cpu.threads', 'label' => 'Hilos',     'type' => 'number', 'rules' => 'nullable|integer|min:1|max:256'],
                                ['key' => 'cpu.type',    'label' => 'Tipo',      'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],

                        [
                            'label'  => 'GRÁFICOS (general)',
                            'fields' => [
                                ['key' => 'graf.general', 'label' => 'GRÁFICOS (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        [
                            'label'      => 'Gráficos (GPU)',
                            'columns'    => 4,
                            'repeatable' => [
                                'key'       => 'gpu.units',
                                'itemLabel' => 'GPU',
                                'min'       => 1,
                                'max'       => 4
                            ],
                            'fields' => [
                                ['key' => 'brand',    'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'model',    'label' => 'Modelo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'gddr',     'label' => 'GDDR',        'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'memory_gb', 'label' => 'Tamaño (GB)', 'type' => 'number', 'rules' => 'nullable|integer|min:1|max:100000'],
                            ],
                        ],
                        [
                            'label'  => 'Almcenamiento (general)',
                            'fields' => [
                                ['key' => 'almgral.general', 'label' => 'Almcenamiento (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        [
                            'label'      => 'Almacenamiento (Discos duros)',
                            'columns'    => 4,
                            'repeatable' => [
                                'key'       => 'storage.disks',
                                'itemLabel' => 'Disco',
                                'min'       => 1,
                                'max'       => 6
                            ],
                            'fields' => [
                                ['key' => 'fullname',  'label' => 'Nombre Completo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'technology', 'label' => 'Tecnologia',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'brand',     'label' => 'Marca',                'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'model',     'label' => 'Modelo',               'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'capacity',  'label' => 'Capacidad (GB)',       'type' => 'number', 'rules' => 'nullable|integer|min:1|max:100000'],
                                ['key' => 'serial',    'label' => 'Serial',               'type' => 'text',   'rules' => 'nullable|string|max:100'],
                            ],
                        ],
                        [
                            'label'  => 'Fuente de Poder',
                            'fields' => [
                                ['key' => 'psu.general', 'label' => 'General', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.brand',   'label' => 'Marca',   'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.model',   'label' => 'Modelo',  'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.watts',   'label' => 'Watts',   'type' => 'number', 'rules' => 'nullable|integer|min:1|max:2000'],
                            ],
                        ],
                        [
                            'label'  => 'Información del Sistema',
                            'fields' => [
                                ['key' => 'os.name',        'label' => 'Sistema Operativo',   'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'os.version',     'label' => 'Compilación / Versión', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.admin',     'label' => 'Usuario Admin',       'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.secondary', 'label' => 'Usuario 2',           'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.others',    'label' => 'Otros Usuarios',      'type' => 'text', 'rules' => 'nullable|string|max:255'],
                                ['key' => 'device.name',    'label' => 'Nombre de Equipo',    'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'anydesk.code',   'label' => 'Código AnyDesk',      'type' => 'text', 'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                        [
                            'label'      => 'Pantalla',
                            'fields' => [
                                ['key' => 'brandscr',        'label' => 'Marca',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'modelscr',        'label' => 'Modelo',       'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'serialscr',       'label' => 'Serie',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'size_inchesscr',  'label' => 'Pulgadas',     'type' => 'number', 'rules' => 'nullable|integer|min:1|max:200'],
                                ['key' => 'connectivityscr', 'label' => 'Conectividad', 'type' => 'text',   'rules' => 'nullable|string|max:100'],
                            ],
                        ],



                    ],
                ],
            ]
        );

        // Computador Todo en Uno
        DeviceType::updateOrCreate(
            ['key' => 'Todo en Uno'],
            [
                'name'   => 'Computador Todo en Uno',
                'code'   => 'PC',
                'schema' => [
                    'groups' => [


                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brandcarac',    'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'modelcarac',    'label' => 'Modelo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'seriecarac',    'label' => 'Serial',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'wificarac',     'label' => 'WiFi',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'ethernetcarac', 'label' => 'Ethernet',    'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],


                        [
                            'label'  => 'RAM (general)',
                            'fields' => [
                                ['key' => 'ram.general', 'label' => 'RAM (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        // <<< RAM REPETIBLE >>>
                        [
                            'label'      => 'Memoria RAM (por slot)',
                            'columns'    => 4,
                            // repeatable define meta de colección
                            'repeatable' => [
                                // prefijo lógico para la colección
                                'key'   => 'ram.slots',
                                // etiquetas y límites opcionales
                                'itemLabel' => 'Slot RAM',
                                'min'   => 1,
                                'max'   => 8
                            ],
                            // estos campos se repiten por cada fila/slot
                            'fields'  => [
                                ['key' => 'type',    'label' => 'Tipo',        'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'brand',   'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'size_gb', 'label' => 'Tamaño (GB)', 'type' => 'number', 'rules' => 'nullable|integer|min:1|max:1024'],
                                ['key' => 'freq',    'label' => 'Frecuencia',  'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],

                        [
                            'label'  => 'Procesador',
                            'fields' => [
                                ['key' => 'cpu.general', 'label' => 'General',   'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.brand',   'label' => 'Marca',     'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.model',   'label' => 'Modelo',    'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'cpu.speed',   'label' => 'Velocidad', 'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'cpu.cores',   'label' => 'Núcleos',   'type' => 'number', 'rules' => 'nullable|integer|min:1|max:128'],
                                ['key' => 'cpu.threads', 'label' => 'Hilos',     'type' => 'number', 'rules' => 'nullable|integer|min:1|max:256'],
                                ['key' => 'cpu.type',    'label' => 'Tipo',      'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],

                        [
                            'label'  => 'GRÁFICOS (general)',
                            'fields' => [
                                ['key' => 'graf.general', 'label' => 'GRÁFICOS (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        [
                            'label'      => 'Gráficos (GPU)',
                            'columns'    => 4,
                            'repeatable' => [
                                'key'       => 'gpu.units',
                                'itemLabel' => 'GPU',
                                'min'       => 1,
                                'max'       => 4
                            ],
                            'fields' => [
                                ['key' => 'brand',    'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'model',    'label' => 'Modelo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'gddr',     'label' => 'GDDR',        'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'memory_gb', 'label' => 'Tamaño (GB)', 'type' => 'number', 'rules' => 'nullable|integer|min:1|max:100000'],
                            ],
                        ],
                        [
                            'label'  => 'Almcenamiento (general)',
                            'fields' => [
                                ['key' => 'almgral.general', 'label' => 'Almcenamiento (general)', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                            ],
                        ],

                        [
                            'label'      => 'Almacenamiento (Discos duros)',
                            'columns'    => 4,
                            'repeatable' => [
                                'key'       => 'storage.disks',
                                'itemLabel' => 'Disco',
                                'min'       => 1,
                                'max'       => 6
                            ],
                            'fields' => [
                                ['key' => 'fullname',  'label' => 'Nombre Completo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'technology', 'label' => 'Tecnologia',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'brand',     'label' => 'Marca',                'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'model',     'label' => 'Modelo',               'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'capacity',  'label' => 'Capacidad (GB)',       'type' => 'number', 'rules' => 'nullable|integer|min:1|max:100000'],
                                ['key' => 'serial',    'label' => 'Serial',               'type' => 'text',   'rules' => 'nullable|string|max:100'],
                            ],
                        ],
                        [
                            'label'  => 'Fuente de Poder',
                            'fields' => [
                                ['key' => 'psu.general', 'label' => 'General', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.brand',   'label' => 'Marca',   'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.model',   'label' => 'Modelo',  'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'psu.watts',   'label' => 'Watts',   'type' => 'number', 'rules' => 'nullable|integer|min:1|max:2000'],
                            ],
                        ],
                        [
                            'label'  => 'Información del Sistema',
                            'fields' => [
                                ['key' => 'os.name',        'label' => 'Sistema Operativo',   'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'os.version',     'label' => 'Compilación / Versión', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.admin',     'label' => 'Usuario Admin',       'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.secondary', 'label' => 'Usuario 2',           'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'user.others',    'label' => 'Otros Usuarios',      'type' => 'text', 'rules' => 'nullable|string|max:255'],
                                ['key' => 'device.name',    'label' => 'Nombre de Equipo',    'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'anydesk.code',   'label' => 'Código AnyDesk',      'type' => 'text', 'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                        [
                            'label'  => 'Pantalla',
                            'fields' => [
                                ['key' => 'brandscr',        'label' => 'Marca',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'modelscr',        'label' => 'Modelo',       'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'serialscr',       'label' => 'Serie',        'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'size_inchesscr',  'label' => 'Pulgadas',     'type' => 'number', 'rules' => 'nullable|integer|min:1|max:200'],
                                ['key' => 'connectivityscr', 'label' => 'Conectividad', 'type' => 'text',   'rules' => 'nullable|string|max:100'],
                            ],
                        ],



                    ],
                ],
            ]
        );


        // Monitor (ejemplo simple)
        DeviceType::updateOrCreate(
            ['key' => 'monitor'],
            [
                'name'   => 'Monitor',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Pantalla',
                            'fields' => [
                                ['key' => 'screen.brand',   'label' => 'Marca',      'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'screen.model',   'label' => 'Modelo',     'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'screen.serial',  'label' => 'Serie',      'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'screen.size_in', 'label' => 'Pulgadas',   'type' => 'text', 'rules' => 'nullable|string|max:20'],
                                ['key' => 'screen.conn',    'label' => 'Conectividad', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'screen.obs',    'label' => 'Otras Características', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Celular
        DeviceType::updateOrCreate(
            ['key' => 'phone'],
            [
                'name'   => 'Celular',
                'code'   => 'CL',
                'schema' => [
                    'groups' => [

                        // 🟠 Observaciones
                        [
                            'label'  => 'Observaciones',
                            'fields' => [
                                ['key' => 'notes.general', 'label' => 'General', 'type' => 'textarea', 'rules' => 'nullable|string|max:500'],
                            ],
                        ],
                        // 🟣 Características principales
                        [
                            'label'  => 'Características Principales',
                            'fields' => [
                                ['key' => 'brand',    'label' => 'Marca',   'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'model',    'label' => 'Modelo',  'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'serial',   'label' => 'Serie',   'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'imei.1',   'label' => 'IMEI 1',  'type' => 'text', 'rules' => 'required_if:type,phone|string|max:20'],
                                ['key' => 'imei.2',   'label' => 'IMEI 2',  'type' => 'text', 'rules' => 'nullable|string|max:20'],
                            ],
                        ],

                        // 🧠 Procesador
                        [
                            'label'  => 'Procesador',
                            'fields' => [
                                ['key' => 'cpu.general', 'label' => 'General',   'type' => 'text',   'rules' => 'nullable|string|max:150'],
                                ['key' => 'cpu.brand',   'label' => 'Marca',     'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'cpu.model',   'label' => 'Modelo',    'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'cpu.speed',   'label' => 'Velocidad', 'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'cpu.cores',   'label' => 'Núcleos',   'type' => 'number', 'rules' => 'nullable|integer|min:1|max:32'],
                                ['key' => 'cpu.threads', 'label' => 'Hilos',     'type' => 'number', 'rules' => 'nullable|integer|min:1|max:64'],
                                ['key' => 'cpu.type',    'label' => 'Tipo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                            ],
                        ],

                        // 🔧 Información del sistema
                        [
                            'label'  => 'Información del Sistema',
                            'fields' => [
                                ['key' => 'os.name',     'label' => 'Sistema Operativo', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'os.version',  'label' => 'Compilación / Versión', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'device.name', 'label' => 'Nombre de Equipo', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'device.pin',  'label' => 'PIN', 'type' => 'text', 'rules' => 'nullable|string|max:20'],
                            ],
                        ],
                    ],
                ],
            ]
        );


        //Simcard
        DeviceType::updateOrCreate(
            ['key' => 'simcard'],
            [
                'name'   => 'Simcard',
                'code'   => 'SC',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'number',      'label' => 'Número',        'type' => 'text',   'rules' => 'nullable|string|max:20'],
                                ['key' => 'carrier',     'label' => 'Compañía',      'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'plan',        'label' => 'Plan',          'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'phone_number', 'label' => 'Número Teléfono', 'type' => 'text',   'rules' => 'nullable|string|max:20'],
                                ['key' => 'icc_id',      'label' => 'ICC ID',       'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                    ],
                ],
            ]

        );

        //Tablet
        DeviceType::updateOrCreate(
            ['key' => 'tablet'],
            [
                'name'   => 'Tablet',
                'code'   => 'TB',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'imei',       'label' => 'IMEI',            'type' => 'text',   'rules' => 'nullable|string|max:20'],
                                ['key' => 'os',         'label' => 'Sistema Operativo', 'type' => 'text',   'rules' => 'nullable|string|max:50'],
                                ['key' => 'screen.size', 'label' => 'Tamaño Pantalla',  'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Ratón (ejemplo simple)
        DeviceType::updateOrCreate(
            ['key' => 'mouse'],
            [
                'name'   => 'Ratón',
                'code'   => 'MS',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brand',      'label' => 'Marca',        'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'model',      'label' => 'Modelo',       'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'serial',     'label' => 'Número de Serie', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'keyserial',     'label' => 'Nro de Serie Teclado', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'connection', 'label' => 'Conectividad', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                    ],
                ],
            ]
        );
        // Teclado (ejemplo simple)
        DeviceType::updateOrCreate(
            ['key' => 'keyboard'],
            [
                'name'   => 'Teclado',
                'code'   => 'TD',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brand',      'label' => 'Marca',        'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'model',      'label' => 'Modelo',       'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'serial',     'label' => 'Número de Serie', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'mouseserial',     'label' => 'Nro de Serie Mouse', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'connection', 'label' => 'Conectividad', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        //Camara web (ejemplo simple)
        DeviceType::updateOrCreate(
            ['key' => 'webcam'],
            [
                'name'   => 'Cámara Web',
                'code'   => 'CW',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brand',      'label' => 'Marca',        'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'model',      'label' => 'Modelo',       'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'serial',     'label' => 'Número de Serie', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'resolution', 'label' => 'Resolución',   'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'connection', 'label' => 'Conectividad', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'sound',      'label' => 'Audio', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'pc',         'label' => 'Computador', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        //Regulador de voltaje (ejemplo simple)
        DeviceType::updateOrCreate(
            ['key' => 'ups'],
            [
                'name'   => 'Regulador de Voltaje',
                'code'   => 'RG',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brand',      'label' => 'Marca',       'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'model',      'label' => 'Modelo',      'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'serial',     'label' => 'Serie',       'type' => 'text',   'rules' => 'nullable|string|max:100'],
                                ['key' => 'outlets',    'label' => 'Tomas',       'type' => 'number', 'rules' => 'nullable|integer|min:1|max:20'],
                                ['key' => 'power',      'label' => 'Potencia',    'type' => 'text',   'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                    ],
                ],
            ]
        );



        // Headset (ejemplo simple)
        DeviceType::updateOrCreate(
            ['key' => 'headset'],
            [
                'name'   => 'Headset',
                'code'   => 'DD',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brand',      'label' => 'Marca',        'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'model',      'label' => 'Modelo',       'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'serial',     'label' => 'Número de Serie', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'connection', 'label' => 'Conectividad', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                            ],
                        ],
                    ],
                ],
            ]
        );


        //Convertidor (ejemplo simple)
        DeviceType::updateOrCreate(
            ['key' => 'adapter'],
            [
                'name'   => 'Convertidor',
                'code'   => 'CV',
                'schema' => [
                    'groups' => [
                        [
                            'label'  => 'Características',
                            'fields' => [
                                ['key' => 'brand',      'label' => 'Marca',        'type' => 'text', 'rules' => 'nullable|string|max:50'],
                                ['key' => 'model',      'label' => 'Modelo',       'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'serial',     'label' => 'Número de Serie', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                                ['key' => 'type',       'label' => 'Tipo de Convertidor', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
