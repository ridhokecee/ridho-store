<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\FlashSale;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $flashsales = FlashSale::all();
        return view('pages.user.index', compact('products', 'flashsales'));
    }

    public function detail_product($id) 
    {
        $product = Product::findOrFail($id);
        return view('pages.user.detail', compact('product'));
    }

    public function detail_flashSale($id) 
    {
        $flashsale = FlashSale::findOrFail($id);
        return view('pages.user.detailFlash', compact('flashsale'));
    }
    
    public function purchase($productId, $userId) 
    {
        $product = Product::findOrFail($productId);
        $user = User::findOrFail($userId);
    
        // Mengecek apakah user memiliki poin yang cukup untuk membeli produk
        if ($user->point >= $product->price) {
            $totalPoints = $user->point - $product->price;
            
            // Update poin user setelah pembelian
            $user->update([
                'point' => $totalPoints,
            ]);
    
            // Tampilkan alert sukses
            Alert::success('Berhasil!', 'Produk berhasil dibeli!');
            return redirect()->back();
        } else {
            // Tampilkan alert gagal jika poin tidak cukup
            Alert::error('Gagal!', 'Point anda tidak cukup!');
            return redirect()->back();
        }
    }

    public function purchaseFlash($flashsaleId, $userId) 
    {
        $flashsale = FlashSale::findOrFail($flashsaleId);
        $user = User::findOrFail($userId);
    
        // Mengecek apakah user memiliki poin yang cukup untuk membeli produk
        if ($user->point >= $flashsale->price) {
            $totalPoints = $user->point - $flashsale->price;
            
            // Update poin user setelah pembelian
            $user->update([
                'point' => $totalPoints,
            ]);
    
            // Tampilkan alert sukses
            Alert::success('Berhasil!', 'Produk Flash Sale berhasil dibeli!');
            return redirect()->back();
        } else {
            // Tampilkan alert gagal jika poin tidak cukup
            Alert::error('Gagal!', 'Point anda tidak cukup!');
            return redirect()->back();
        }
    }
    
}
