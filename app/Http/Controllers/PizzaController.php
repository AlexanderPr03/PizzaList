<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pizza;
use Illuminate\Support\Facades\Log;



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
            'selling_price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ingredients' => 'required|array',
            'ingredients.*' => 'exists:ingredients,id',
        ]);

        $pizza = new Pizza;
        $pizza->name = $request->name;
        $pizza->selling_price = $request->selling_price;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $pizza->image = $imageName;
            
        }

        $pizza->save();

        foreach ($request->ingredients as $index => $ingredientId) {
            $pizza->ingredients()->attach($ingredientId, ['order' => $index + 1]);
        }
        
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
            'selling_price' => 'required',
            'ingredients' => 'required|array',
            'ingredients.*' => 'exists:ingredients,id',
        ]);

        $pizza = Pizza::find($id);
        $pizza->name = $request->name;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $pizza->image = $imageName;
        }

        
        
        $pizza->save();

        $ingredientIdsWithOrder = [];
        foreach ($request->ingredients as $ingredient) {
            $ingredientIdsWithOrder[$ingredient['id']] = ['order' => $ingredient['order']];
        }
        $pizza->ingredients()->sync($ingredientIdsWithOrder);

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

        $currentIngredients = $pizza->ingredients->pluck('id')->toArray();


        if (!in_array($request['ingredient_id'], $currentIngredients)) {
            $pizza->ingredients()->attach($request['ingredient_id'], ['order' => $request['order']]);
            return response()->json(['message' => 'Ingredient adăugat']);
        }

        return response()->json(['message' => 'Ingredientul este deja adăugat la pizza']);
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
        $ingredients = $ingredients->sortBy(function($ingredient, $key) {
            return $ingredient->pivot->order;
        });
        
        return response()->json($ingredients->values()->all());
    }


    public function reorderIngredient(Request $request, $id) {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'new_order' => 'required|integer|min:1',
        ]);

        $pizza = Pizza::find($id);
        
        $currentIngredients = $pizza->ingredients()->orderByPivot('order')->pluck('ingredient_id')->toArray();
        
        $currentOrder = array_search($request['ingredient_id'], $currentIngredients);

        if ($currentOrder === false) {
            return response()->json(['message' => 'Ingredientul nu este în lista curentă de ingrediente pentru pizza']);
        }
        unset($currentIngredients[$currentOrder]);

        $newOrder = $request['new_order'] - 1;
        array_splice($currentIngredients, $newOrder, 0, $request['ingredient_id']);

        foreach ($currentIngredients as $order => $ingredientId) {
            $pizza->ingredients()->updateExistingPivot($ingredientId, ['order' => $order + 1]);
        }

        return response()->json(['message' => 'Ordinea ingredientelor a fost actualizată']);
    }
}
