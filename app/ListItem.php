<?php

namespace App;

use App\ShoppingList;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    public $timestamps = false;

    public function shoppingList() {
        $this->belongsTo(ShoppingList::class);
    }

    public static function exists($id) {
        $count = ListItem::where('id', $id)->count();
        
        if($count) {
            return true;
        } else {
            return false;
        }

    }
}
