<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {

        $families = Tree::select(
            'families.name as name',
            DB::raw('count(*) as y'),
            'families.name as drilldown'
        )
            ->join('species', 'species.id', '=', 'trees.specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->groupBy('families.name')->get();

        $families_list = Tree::select('families.name', 'families.id')
            ->join('species', 'species.id', '=', 'trees.specie_id')
            ->join('families', 'families.id', '=', 'species.family_id')
            ->distinct()
            ->get();

        $species_families = [];

        for ($i = 0; $i < count($families_list); $i++) {
            $data = [];
            $species_families[$i]['name'] = $families_list[$i]->name;
            $species_families[$i]['id'] = $families_list[$i]->name;
            $id = $families_list[$i]->id;

            $species_counter = Tree::select('species.name', DB::raw('count(*) as  cant'))
                ->join('species', 'species.id', '=', 'trees.specie_id')
                ->where('species.family_id', '=', $id)
                ->groupBy('species.name')
                ->get();

            foreach ($species_counter as $specie) {
                array_push($data, array($specie->name, $specie->cant));
            }

            $species_families[$i]['data'] = $data;
        }

        $species = Tree::select(
            'species.name as name',
            DB::raw('count(*) as y')
        )
            ->join('species', 'species.id', '=', 'trees.specie_id')
            ->groupBy('species.name')->get();

        $zones = Tree::select(
            'zones.name as name',
            DB::raw('count(*) as y')
        )
            ->join('zones', 'zones.id', '=', 'trees.zone_id')
            ->groupBy('zones.name')->get();

        return View('admin.index', compact('families', 'species_families', 'species', 'zones'));
    }
}
