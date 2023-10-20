<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\admin\Province;

class AddProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ubigeo_peru_provinces = include('datos_ubigeo/ubigeo_peru_2016_provincias.php');

        foreach($ubigeo_peru_provinces as $province) {
            Province::create([
                'id' => $province['id'],
                'name' => $province['name'],
                'departament_id' => $province['department_id'],
            ]);
        }
    }
}
