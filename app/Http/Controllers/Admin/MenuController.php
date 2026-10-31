<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Menu::with('category');

        // search nama menu
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $menus = $query->orderBy('id', 'desc')->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.menus.index', compact('menus', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuRequest $request, MenuService $service)
    {
        $validated = $request->validated();

        $service->create($validated);

        return redirect()->route('admin.menus.index')->with('message', 'Menu berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuRequest $request, Menu $menu, MenuService $service)
    {
        $service->update($menu, $request->all());

        return redirect()->route('admin.menus.index')->with('message', 'Menu berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
