<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Stmt\Return_;

class ProductController extends Controller
{
    public function showProducts()
    {
        $product = Product::with('productImages')->get();

        return view('products', compact('product'));
    }


    // public function ImgView($productId)
    // {
    //     $show_images = Product::findOrFail($productId);
    //     $product_images = ProductImage::where('product_id', $productId)->get();

    //     return view('image', compact('show_images', 'product_images'));
    // }



    public function ImgDelete($imageId)
    {
        $multi = ProductImage::findOrFail($imageId);

        if (File::exists(public_path($multi->image))) {
            File::delete(public_path($multi->image));
        }
        $multi->delete();
        return redirect()->back()->with('status', 'Successfully Removed');
    }


    ///////////////PRODUCTS//////////////

    public function getAddProduct()
    {
        return view('add-products');
    }





    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'details' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'file' => 'nullable',
            'images.*' => 'required|image|mimes:png,jpg,jpeg,webp', // Validate each image
            'price' => 'required',
        ]);

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->details = $request->details;
        $product->price = $request->price;



        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->extension();
            $path = 'uploads/category/';
            $file->move($path, $filename);
            $product->image = $path . $filename;
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->extension();
            $path = 'uploads/category/files/';
            $file->move($path, $filename);
            $product->file = $path . $filename;
        }
        $product->save();


        $imagePath = []; // Initialize the array to store image paths

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate a unique filename
                $multi = time() . '_' . uniqid() . '.' . $image->extension();
                $path = 'uploads/category/multi/'; // Use public_path

                // Move the image to the specified path
                $image->move($path, $multi);

                // Store the relative path in the database
                $product->productImages()->create(['images' => $path . $multi]);

                // Add the full path to the array (optional)
                $imagePath[] = $path . $multi;
            }
        }

        return redirect()->route('show.products')->with('success', 'Product updated successfully!');
    }



    public function getEditProduct($id, Request $request)
    {
        $product = Product::findOrFail($id);
        return view('edit-product', compact('product'));
    }


    public function postEditProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'details' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'file' => 'nullable',
            'price' => 'required',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg,webp',
        ]);

        $product = Product::findOrFail($request->id);
        $product->product_name = $request->product_name;
        $product->details = $request->details;
        $product->price = $request->price;

        // Handle single image upload
        if ($request->has('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $file = $request->file('image');
            $filename = time() . '.' . $file->extension();
            $path = 'uploads/category/';
            $file->move(public_path($path), $filename);
            $product->image = $path . $filename;
        }

        // Handle single file upload
        if ($request->has('file')) {
            if ($product->file && file_exists(public_path($product->file))) {
                unlink(public_path($product->file));
            }
            $file = $request->file('file');
            $filename = time() . '.' . $file->extension();
            $path = 'uploads/category/';
            $file->move(public_path($path), $filename);
            $product->file = $path . $filename;
        }

        $product->save();

        // Handle multiple images
        if ($request->hasFile('images')) {
            // Delete existing product images
            foreach ($product->productImages as $image) {
                if (file_exists(public_path($image->images))) {
                    unlink(public_path($image->images));
                }
                $image->delete();
            }
            $images = [];
            // Save new images
            foreach ($request->file('images') as $image) {
                $multiFilename = time() . '_' . uniqid() . '.' . $image->extension();
                $path = 'uploads/category/multi/';
                $image->move(public_path($path), $multiFilename);

                // Store the new image path in the database
                $product->productImages()->create(['images' => $path . $multiFilename]);
                $images[] = $path . $multiFilename;
            }
        }

        return redirect()->route('show.products')->with('success', 'Product successfully updated');
    }


    public function deleteProduct($id)
    {
        // $category = Product::where('id', $id);

        $category = Product::findOrFail($id);
        if (File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }
        if (File::exists(public_path($category->file))) {
            File::delete(public_path($category->file));
        }
        $category->delete();
        return redirect('products')->with('Successfully Deleted');
    }
}
