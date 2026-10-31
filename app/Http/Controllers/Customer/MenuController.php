<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TableSession;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request): View|Closure|string
    {
        $tableNumber = $request->order_draft->table->table_number;

        $categories = Category::with(['menus' => function ($query) {
            $query->where('stock', '>', 0)
                ->orderBy('name')
                ->orderBy('category_id');
        }])->get();

        return view('customer.menu', compact('categories', 'tableNumber'));
    }
}
