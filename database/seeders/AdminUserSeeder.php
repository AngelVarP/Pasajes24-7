<?php

namespace Database\Seeders;

use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Para hashear la contraseña

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea un usuario administrador si no existe ya
        User::firstOrCreate(
            ['email' => 'admin@pasajes24-7.com'], // Busca por este email para evitar duplicados
            [
                'name' => 'Angel Vargas',
                'password' => Hash::make('password'), // Contraseña simple para el admin
                'is_admin' => true, // ¡Este es el campo clave!
                'email_verified_at' => now(), // Marcar como verificado para evitar problemas
            ]
        );

        // Puedes añadir más admins o usuarios normales aquí si lo necesitas
        // User::factory()->count(10)->create(); // Para crear 10 usuarios normales de prueba
    }
}