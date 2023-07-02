<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Log;


class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::all();
        
        return response()->json($ingredients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            'cost_price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $ingredient = new Ingredient;
        $ingredient->name = $request->name;
        $ingredient->cost_price = $request->cost_price;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $ingredient->image = $imageName;
        }

        $ingredient->save();

        return response()->json($ingredient);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ingredient = Ingredient::find($id);
        return response()->json($ingredient);
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'cost_price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $ingredient = Ingredient::find($id);
        $ingredient->name = $request->name;
        $ingredient->cost_price = $request->cost_price;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $ingredient->image = $imageName;
        }

        $ingredient->save();

        return response()->json($ingredient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateIn(Request $request, string $id)
    {
        
        $request->validate([
            'name' => 'required|max:255',
            'cost_price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $ingredient = Ingredient::find($id);
        $ingredient->name = $request->name;
        $ingredient->cost_price = $request->cost_price;
        Log::info($request->image);
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            
            $request->image->move(public_path('images'), $imageName);
            $ingredient->image = $imageName;
        }
        
        $ingredient->save();

        return response()->json($ingredient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->delete();
    }
}
