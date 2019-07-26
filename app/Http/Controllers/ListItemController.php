<?php

namespace App\Http\Controllers;

use App\ListItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\ShoppingList;

class ListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ListItem::all();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if(!ShoppingList::exists($id)) {
            return response()->json([
                'message' => 'The shopping list doesn\'t exist',
            ], 500);            
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:list_items',
            // 'shopping_list_id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'There is an error in the request',
                'errors' => $validator->errors()
            ], 500);
        } else {
            $listItem = new ListItem();
            $listItem->name = $request->name;
            $listItem->shopping_list_id = $id;
            $listItem->save();

            return response()->json([
                'message' => 'List Item saved successfully',
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
        return ListItem::where('id', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!ListItem::exists($request->listitem)) {
            return response()->json([
                'message' => 'The Shopping List Item doesn\'t exist',
            ], 500);  
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:list_items',
            // 'shopping_list_id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'There is an error in the request',
                'errors' => $validator->errors()
            ], 500);
        } else {
            $listItem = ListItem::where('id', $request->listitem)->first();
            $listItem->name = $request->name;
            $listItem->save();

            return response()->json([
                'message' => 'List Item updated successfully',
            ], 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        ListItem::where('id', $request->listitem)->delete();

        return response()->json([
            'message' => 'deleted'
        ], 200);
    }
}
