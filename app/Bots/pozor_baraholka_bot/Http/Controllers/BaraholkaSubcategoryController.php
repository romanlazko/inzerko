<?php

namespace App\Bots\pozor_baraholka_bot\Http\Controllers;

use App\Bots\pozor_baraholka_bot\Models\BaraholkaCategory;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaSubcategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaraholkaSubcategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = BaraholkaCategory::all();

        return view('pozor_baraholka_bot::subcategory.create', compact(
            'categories'
        ));
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
            'name' => 'required|max:255',
            'icon_name' => 'sometimes|max:255',
            'is_active' => 'required|boolean',
            'category' => 'required|integer'
        ]);

        BaraholkaSubcategory::create([
            'name' => $request->name,
            'baraholka_category_id' => $request->category,
            'icon_name' => $request->icon_name,
            'is_active' => $request->is_active,
        ]); 

        return redirect()->route('pozor_baraholka_bot.category.index')->with([
            'ok' => true,
            'description' => "Subcategory succesfuly created"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BaraholkaSubcategory $subcategory)
    {
        $categories = BaraholkaCategory::all();

        return view('pozor_baraholka_bot::subcategory.edit', compact([
            'subcategory',
            'categories'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BaraholkaSubcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|max:255',
            'icon_name' => 'required|max:255',
            'category' => 'required|integer',
            'is_active' => 'required|boolean'
        ]);

        $subcategory->update([
            'name' => $request->name,
            'icon_name' => $request->icon_name,
            'is_active' => $request->is_active,
            'baraholka_category_id' => $request->category,
        ]);

        return redirect()->route('pozor_baraholka_bot.category.index')->with([
            'ok' => true,
            'description' => "Subcategory succesfuly updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BaraholkaSubcategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('pozor_baraholka_bot.category.index')->with([
            'ok' => true,
            'description' => "Subcategory succesfuly deleted"
        ]);
    }
}
