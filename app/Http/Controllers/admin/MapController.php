<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Tree;
use Illuminate\Http\Request;

class MapController extends Controller
{
    function index()
    {
        $trees = Tree::select('latitude', 'longitude', 'name')->get();

        return view('admin.maps.index', compact('trees'));
    }
}
