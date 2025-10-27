<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmpresaDeTransporte; // Importar el modelo

class EmpresaDeTransporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = [
            [
                'nombre' => 'Cruz del Sur',
                'ruc' => '20100078912',
                'logo_url' => 'images/empresas/cruz-del-sur.png', // Ejemplo de URL de logo
                'email_contacto' => 'contacto@cruzdelsur.com.pe',
                'telefono_contacto' => '01-610-5000',
            ],
            [
                'nombre' => 'Oltursa',
                'ruc' => '20100078913',
                'logo_url' => 'images/empresas/oltursa.png',
                'email_contacto' => 'info@oltursa.com.pe',
                'telefono_contacto' => '01-708-5000',
            ],
            [
                'nombre' => 'Civa',
                'ruc' => '20100078914',
                'logo_url' => 'images/empresas/civa.png',
                'email_contacto' => 'atencion@civa.com.pe',
                'telefono_contacto' => '01-418-1111',
            ],
        ];

        foreach ($empresas as $empresa) {
            EmpresaDeTransporte::firstOrCreate(['nombre' => $empresa['nombre']], $empresa);
        }
    }
}