<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importar DB Facade
use App\Models\Ciudad; // Asegúrate de que tu modelo Ciudad exista y esté importado

class CiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ciudades = [
            ['nombre' => 'Lima', 'departamento' => 'Lima'],
            ['nombre' => 'Arequipa', 'departamento' => 'Arequipa'],
            ['nombre' => 'Cusco', 'departamento' => 'Cusco'],
            ['nombre' => 'Trujillo', 'departamento' => 'La Libertad'],
            ['nombre' => 'Chiclayo', 'departamento' => 'Lambayeque'],
            ['nombre' => 'Piura', 'departamento' => 'Piura'],
            ['nombre' => 'Iquitos', 'departamento' => 'Loreto'],
            ['nombre' => 'Huancayo', 'departamento' => 'Junín'],
            ['nombre' => 'Tacna', 'departamento' => 'Tacna'],
            ['nombre' => 'Puno', 'departamento' => 'Puno'],
            ['nombre' => 'Ica', 'departamento' => 'Ica'],
            ['nombre' => 'Cajamarca', 'departamento' => 'Cajamarca'],
            ['nombre' => 'Huaraz', 'departamento' => 'Áncash'],
            ['nombre' => 'Ayacucho', 'departamento' => 'Ayacucho'],
            ['nombre' => 'Tarapoto', 'departamento' => 'San Martín'],
            ['nombre' => 'Moyobamba', 'departamento' => 'San Martín'],
            ['nombre' => 'Puerto Maldonado', 'departamento' => 'Madre de Dios'],
            ['nombre' => 'Tumbes', 'departamento' => 'Tumbes'],
            ['nombre' => 'Pucallpa', 'departamento' => 'Ucayali'],
            ['nombre' => 'Juliaca', 'departamento' => 'Puno'],
        ];

        // Insertar solo si la tabla está vacía o si quieres rellenar cada vez (cuidado con duplicados)
        foreach ($ciudades as $ciudad) {
            Ciudad::firstOrCreate($ciudad); // firstOrCreate evita duplicados si 'nombre' es unique
        }

        // O si prefieres usar el facade DB (más directo, pero no usa el modelo)
        // DB::table('ciudades')->insert($ciudades);
    }
}