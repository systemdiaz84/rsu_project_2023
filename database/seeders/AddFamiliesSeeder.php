<?php

namespace Database\Seeders;

use App\Models\admin\Family;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddFamiliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $familias = include('datos_familia_especies/familias.php');

        foreach($familias as $familia) {
            Family::create([
                'name' => $familia['name'],
                'scientific_name' => $familia['scientific_name'],
                'description' => $familia['description'],
            ]);
        }
    }
}
