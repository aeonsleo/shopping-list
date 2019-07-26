<?php

namespace App\Http\Controllers;

use App\ShoppingList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShoppingListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ShoppingList::with('listitems')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:shopping_lists'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Shopping List could not be saved',
                'errors' => $validator->errors()
            ], 500);
        } else {
            $shoppingList = new ShoppingList();
            $shoppingList->name = $request->name;
            $shoppingList->save();

            return response()->json([
                'message' => 'Shopping List saved successfully',
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ShoppingList::where('id', $id)->with('listitems')->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:shopping_lists'
        ]);
        
        if($validator->fails()) {
            return response()->json([
                'message' => 'Shopping List could not be saved',
                'errors' => $validator->errors()
            ], 500);
        } else {
            $shoppingList = ShoppingList::where('id', $id)->first();
            $shoppingList->name = $request->name;
            $shoppingList->save();

            return response()->json([
                'message' => 'Shopping List updated successfully',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ShoppingList::where('id', $id)->delete();

        return response()->json([
            'message' => 'deleted'
        ], 200);
    }
}
