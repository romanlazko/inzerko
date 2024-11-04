<?php

namespace App\Bots\pozor_baraholka_bot\Http\Controllers;

use App\Bots\pozor_baraholka_bot\Models\BaraholkaCategory;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaSubcategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class BaraholkaCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = BaraholkaCategory::when($request->has('search'), function($query) use($request) {
            return $query->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            });
        })
        ->with('subcategories')
        ->orderBy('id')
        ->paginate(50);

        return view('pozor_baraholka_bot::category.index', compact([
            'categories'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pozor_baraholka_bot::category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:baraholka_categories',
            'icon_name' => 'sometimes|max:255',
            'is_active' => 'required|boolean',
        ]);

        BaraholkaCategory::create([
            'name' => $request->name,
            'icon_name' => $request->icon_name,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('pozor_baraholka_bot.category.index')->with([
            'ok' => true,
            'description' => "Category succesfuly created"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BaraholkaCategory $category)
    {
        return view('pozor_baraholka_bot::category.edit', compact([
            'category'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BaraholkaCategory $category)
    {
        $request->validate([
            'name' => 'required|max:255',
            'icon_name' => 'sometimes|max:255',
            'is_active' => 'required|boolean',
        ]);
        $category->update([
            'name' => $request->name,
            'icon_name' => $request->icon_name,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('pozor_baraholka_bot.category.index')->with([
            'ok' => true,
            'description' => "Category succesfuly updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BaraholkaCategory $category)
    {
        $category->delete();

        return redirect()->route('pozor_baraholka_bot.category.index')->with([
            'ok' => true,
            'description' => "Category succesfuly deleted"
        ]);
    }
}
