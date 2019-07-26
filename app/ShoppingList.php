<?php

namespace App;

use App\ListItem;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    public function listItems() {
        return $this->hasMany(ListItem::class);
    }

    public static function exists($id) {
        $count = ShoppingList::where('id', $id)->count();
        
        if($count) {
            return true;
        } else {
            return false;
        }
    }
}
