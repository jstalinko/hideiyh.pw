<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Documentation;

class DocumentationController extends Controller
{

    public function index()
    {
        return Inertia::location('/docs/getting-started');
    }
    public function detail(Request $request)
    {

        $props['docs'] = Documentation::where('is_published',true)->orderBy('order','asc')->get();
        $props['doc'] = Documentation::where('slug', $request->slug)->firstOrFail();
        $data['props'] = $props;
        return Inertia::render('Docs/detail', $data);
    }
}
