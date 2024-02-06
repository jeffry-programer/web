<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {   
        $array = [
            'description' => 'Administrador',
            'created_at' => now()
        ];

        DB::table('profiles')->insert($array);

        $array = [
            'description' => 'Tienda',
            'created_at' => now()
        ];

        DB::table('profiles')->insert($array);

        $array = [
            'description' => 'Cliente',
            'created_at' => now()
        ];

        DB::table('profiles')->insert($array);

        $array = [
            'description' => 'Taller',
            'created_at' => now()
        ];

        DB::table('profiles')->insert($array);

        $array = [
            'description' => 'Grua',
            'created_at' => now()
        ];

        DB::table('profiles')->insert($array);

        $array = [
            'description' => 'Venezuela',
            'created_at' => now()
        ];

        DB::table('countries')->insert($array);

        $array = [
            'countries_id' => 1,
            'name' => 'Táchira',
            'created_at' => now()
        ];

        DB::table('states')->insert($array);

        $array = [
            'states_id' => 1,
            'name' => 'San Cristóbal',
            'created_at' => now()
        ];

        DB::table('municipalities')->insert($array);

        $array = [
            'municipalities_id' => 1,
            'name' => 'San Cristóbal',
            'created_at' => now()
        ];

        DB::table('cities')->insert($array);

        
        /*$array = [
            [
                'profiles_id' => 1,
                'cities_id' => 1,  
                'name' => 'Jeffry',
                'last_name' => 'Avellaneda',
                'email' => 'jeffryavellaneda@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), 
                'image' => '',
                'gender' => 'M',
                'status' => true,
                'remember_token' => Hash::make('password'), 
                'created_at' => now()
            ]
        ];*/

        //DB::table('users')->insert($array);

        $array = [
            [
                'name' => 'categories',
                'label' => 'Categorias',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'sub_categories',
                'label' => 'Subcategorias',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'brands',
                'label' => 'Marcas',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'profiles',
                'label' => 'Perfiles',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'type_stores',
                'label' => 'Tipo de tiendas',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'cylinder_capacities',
                'label' => 'Cilindraje',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'models',
                'label' => 'Modelos',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'boxes',
                'label' => 'Cajas',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'type_products',
                'label' => 'Tipo de productos',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'countries',
                'label' => 'Paises',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'days',
                'label' => 'Dias',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'modules',
                'label' => 'Modulos',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'states',
                'label' => 'Estados',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'municipalities',
                'label' => 'Municipios',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'cities',
                'label' => 'Ciudades',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'products',
                'label' => 'Productos',  
                'type' => '2',
                'created_at' => now()
            ],
            [
                'name' => 'users',
                'label' => 'Usuarios',  
                'type' => '3',
                'created_at' => now()
            ],
            [
                'name' => 'plans',
                'label' => 'Tipo plan',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'stores',
                'label' => 'Tiendas',  
                'type' => '3',
                'created_at' => now()
            ],
            [
                'name' => 'attention_times',
                'label' => 'Horarios',  
                'type' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'social_networks',
                'label' => 'Redes sociales',  
                'type' => '1',
                'created_at' => now()   
            ],
            [
                'name' => 'product_stores',
                'label' => 'Productos tienda',  
                'type' => '2',
                'created_at' => now()   
            ],
            [
                'name' => 'type_publicities',
                'label' => 'Tipo de publicidad',  
                'type' => '1',
                'created_at' => now()   
            ],
            [
                'name' => 'publicities',
                'label' => 'Publicidad',  
                'type' => '1',
                'created_at' => now()   
            ],
            [
                'name' => 'promotions',
                'label' => 'Promociones',  
                'type' => '1',
                'created_at' => now()   
            ],
            [
                'name' => 'branches',
                'label' => 'Sucursales',  
                'type' => '1',
                'created_at' => now()   
            ],
            [
                'name' => 'profile_operations',
                'label' => 'Perfil operaciones',  
                'type' => '1',
                'created_at' => now()   
            ],
            [
                'name' => 'operations',
                'label' => 'Operaciones',  
                'type' => '1',
                'created_at' => now()   
            ],
            [
                'name' => 'plan_contractings',
                'label' => 'Plan contratado',  
                'type' => '1',
                'created_at' => now()   
            ]
        ];
        DB::table('tables')->insert($array);
    }
}
