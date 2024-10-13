<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashsales = FlashSale::all();
        confirmDelete('Hapus Data!', 'Apakah anda yakin ingin menghapus data ini?');
        return view('pages.admin.flashsale.index', compact('flashsales'));
    }
    
    public function create()
    {
        return view('pages.admin.flashsale.create');
    }

    public function store(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'price' => 'numeric|required',
        'category' => 'required',
        'description' => 'required',
        'image' => 'required|mimes:png,jpeg,jpg',
    ]);

    if ($validator->fails()) {
        Alert::error('Gagal!', 'Pastikan semua terisi dengan benar!');
        return redirect()->back();
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move('images/', $imageName);
    }

    $flashsales = FlashSale::create([
        'name' => $request->name,
        'price' => $request->price,
        'category' => $request->category,
        'description' => $request->description,
        'image' => isset($imageName) ? $imageName : null,
    ]);

    if ($flashsales) {
        Alert::success('Berhasil!', 'Flash Sale berhasil ditambahkan!');
        return redirect()->route('admin.flashsale');
    } else {
        Alert::error('Gagal!', 'Produk gagal ditambahkan!');
        return redirect()->back();
    }
    }

    public function detail($id)
    {
        $flashsales = FlashSale::findOrFail($id);
        return view('pages.admin.flashsale.detail', compact('flashsales'));
    }

    public function edit($id)
    {
        $flashsales = FlashSale::findOrFail($id);
        return view('pages.admin.flashsale.edit', compact('flashsales'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'numeric',
            'category' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:png,jpeg,jpg',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal!', 'Pastikan semua terisi dengan benar!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $flashsales = FlashSale::findOrFail($id);

        // Handle file image if exists
        if ($request->hasFile('image')) {
            $oldPath = public_path('images/' . $flashsales->image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move('images/', $imageName);
        } else {
            $imageName = $flashsales->image; // Keep the old image if no new one is uploaded
        }

        // Update product
        $flashsales->update([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        if ($flashsales) {
            Alert::success('Berhasil!', 'Flash Sale berhasil diperbarui!');
            return redirect()->route('admin.flashsale');
        } else {
            Alert::error('Gagal!', 'Flash Sale gagal diperbarui!');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $flashsales = FlashSale::findOrFail($id);

        $oldPath = public_path('images/' .$flashsales->image);
        if (File::exists($oldPath)) {
            File::delete($oldPath);
        }

        $flashsales->delete();

        if ($flashsales) {
            Alert::success('Berhasil!', 'Flash Sale berhasil dihapus!');
            return redirect()->back();
        } else {
            Alert::error('Gagal!', 'Flash Sale gagal dihapus!');
            return redirect()->back();
        }
    }
}
