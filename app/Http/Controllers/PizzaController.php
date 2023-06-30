<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pizza;


class PizzaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pizzas = Pizza::all();
        return response()->json($pizzas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $pizza = new Pizza;
        $pizza->name = $request->name;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $pizza->image = $imageName;
        }

        $pizza->save();
        
        return response()->json($pizza);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pizza = Pizza::find($id);
        return response()->json($pizza);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $pizza = Pizza::find($id);
        $pizza->name = $request->name;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $pizza->image = $imageName;
        }
        
        $pizza->save();

        return response()->json($pizza);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pizza = Pizza::find($id);
        $pizza->delete();

        return response()->json(['message' => 'Pizza deleted']);
    }


    /* Metode custom */

    // Adaugare lista de ingrediente
    public function addIngredients(Request $request, $id) {
        $pizza = Pizza::find($id);
        foreach ($request->ingredients as $ingredient) {
            $pizza->ingredients()->attach($ingredient['ingredient_id'], ['order' => $ingredient['order']]);
        }

        return response()->json(['message' => 'Ingredient adăugat']);
    }


    // Stergerea unui ingredient de la o pizza
    public function removeIngredient(Request $request, $id) {
        $pizza = Pizza::find($id);

        $pizza->ingredients()->detach($request->ingredient_id);

        return response()->json(['message' => 'Ingredient scos']);
    }

    // Returnarea tuturor ingredientelor unei pizze
    public function getPizzaIngredients($id) {
        $pizza = Pizza::find($id);
        $ingredients = $pizza->ingredients;
        return response()->json($ingredients);
    }
}
