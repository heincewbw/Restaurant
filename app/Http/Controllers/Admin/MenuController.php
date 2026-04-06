<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Cek login simple (tanpa DB)
            $simpleUser = session('simple_user');
            if ($simpleUser && $simpleUser['role'] === 'admin') {
                return $next($request);
            }

            // Cek login DB (lama)
            $userId = session('user_id');
            if ($userId) {
                $user = \App\Models\User::find($userId);
                if ($user && $user->is_admin) {
                    return $next($request);
                }
            }
            abort(403, 'Akses ditolak. Hanya admin dapat mengakses halaman ini.');
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'group' => 'required|string|in:Makanan,Minuman,Sup',
            'barcode' => 'required|string|max:100|unique:menus,barcode',
            'image' => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        Menu::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'group' => 'required|string|in:Makanan,Minuman,Sup',
            'barcode' => 'required|string|max:100|unique:menus,barcode,' . $menu->id,
            'image' => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
