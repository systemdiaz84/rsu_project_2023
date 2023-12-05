<?php

namespace Database\Seeders;

use App\Models\admin\Specie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddSpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $especies = include('datos_familia_especies/especies.php');

        foreach($especies as $especie) {
            Specie::create([
                'name' => $especie['name'],
                'scientific_name' => $especie['scientific_name'],
                'description' => $especie['description'],
                'family_id' => $especie['family_id']
            ]);
        }
    }
}
