<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $categories = ['الكل', 'بروتين', 'قوة', 'تعافي', 'فيتامينات', 'صحة', 'معدات'];
        return view('store.index', compact('products', 'categories'));
    }
}
