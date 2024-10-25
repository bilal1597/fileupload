<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProducts()
    {
        $show_products = Product::all();
        return view('products', compact('show_products'));
    }

    public function getAddProduct()
    {

        return view('add-products');
    }

    public function postAddProduct(Request $request)
    {
        $data = $request->validate([
            'product_name' => 'required',
            'details'  => 'required',
            'price' => 'required',
        ]);
        Product::create($data);

        return redirect()->route('show.products');
    }

    public function getEditProduct($id, Request $request)
    {
        $product = Product::find($id);
        return view('edit-product', compact('product'));
    }

    public function postEditProduct(Request $request)
    {
        $data = $request->validate([
            'product_name' => 'required',
            'details' => 'required',
            'price' => 'required'
        ]);
        Product::where('id', $request->id)->update($data);
        return redirect()->route('show.products')->with('Product successfully Updated');
    }

    public function deleteProduct($id)
    {
        Product::where('id', $id)->delete();
        return redirect('products')->with('Successfully Deleted');
    }
}
