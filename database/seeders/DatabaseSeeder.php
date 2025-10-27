<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create(); // Descomenta si quieres usuarios de prueba

        $this->call([
            CiudadSeeder::class, // ¡Primero las ciudades!
            EmpresaDeTransporteSeeder::class, // Luego las empresas
            RutaSeeder::class, // Luego las rutas (dependen de ciudades)
            ViajeSeeder::class, // Finalmente los viajes (dependen de rutas y empresas)
            // AsientoSeeder::class, // No necesitamos un seeder de Asiento separado, se crean en ViajeSeeder
        ]);
    }
}