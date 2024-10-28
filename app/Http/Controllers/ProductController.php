<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'price' => 'required',
        ]);

        if ($request->has('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $filename = time() . '.' . $extension;
            $path = 'uploads/category/';
            $file->move($path, $filename);

            $data['image'] = $path . $filename;
        }

        Product::create($data);

        return redirect()->route('show.products');
    }

    public function getEditProduct($id, Request $request)
    {
        $product = Product::findOrFail($id);
        return view('edit-product', compact('product'));
    }

    public function postEditProduct(Request $request)
    {
        $data = $request->validate([
            'product_name' => 'required',
            'details' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'price' => 'required'
        ]);

        // $category = Product::where('id', $request->id);
        $category = Product::findOrFail($request->id);

        if ($request->has('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $filename = time() . '.' . $extension;
            $path = 'uploads/category/';
            $file->move($path, $filename);

            $data['image'] = $path . $filename;

            if (File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            } else {
                // If no new image is uploaded, keep the old image path
                unset($data['image']); // Remove the image key if no new image is provided
            }
        }

        //Product::where('id', $request->id)//
        $category->update($data);
        return redirect()->route('show.products')->with('Product successfully Updated');
    }

    public function deleteProduct($id)
    {
        // $category = Product::where('id', $id);

        $category = Product::findOrFail($id);
        if (File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }
        $category->delete();
        return redirect('products')->with('Successfully Deleted');
    }
}
