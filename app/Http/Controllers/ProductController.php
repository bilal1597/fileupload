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


    // public function ImgPost(Request $request, $productId)
    // {
    //     $request->validate([
    //         'images.*' => 'required|image|mimes:png,jpg,jpeg,webp' // har image validate karega
    //     ]);

    // $product = Product::findOrFail($productId);

    // $Imagedata = [];
    // if ($request->has('images')) {
    //     $files = $request->file('images');

    //     foreach ($files as $key => $file) {
    //         $extension = $file->extension();
    //         $filename = $key . '-' . time() . '.' . $extension;

    //         $path = 'uploads/category/multiple/';
    //         $file->move($path, $filename);

    //         $Imagedata[] = [
    //             'product_id' => $product->id,
    //             'image' => $path . $filename,
    //         ];
    //     }
    // }
    // ProductImage::insert($Imagedata);
    // return redirect()->back()->with('status', 'Uploaded Successfully');
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


    // public function postAddProduct(Request $request)
    // {


    //     $data = $request->validate([
    //         'product_name' => 'required',
    //         'details' => 'required',
    //         'image' => 'nullable|mimes:png,jpg,jpeg,webp',
    //         'file' => 'nullable',
    //         'images.*' => 'required|image|mimes:png,jpg,jpeg,webp', // Validate each image
    //         'price' => 'required',
    //     ]);
    //     $product = new Product(); // Creates and saves the product.

    //     $product->product_name = $request->product_name;
    //     $product->details = $request->details;
    //     $product->price = $request->price;
    //     // $product->f = $request->file

    //     // Handle single product image upload
    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $filename = time() . '.' . $file->extension();
    //         $path = 'uploads/category/';
    //         $file->move($path, $filename);
    //         $product->image = $path . $filename;
    //     }

    //     // Handle file upload (if any)
    //     if ($request->hasFile('file')) {
    //         $file = $request->file('file');
    //         $filename = time() . '.' . $file->extension();
    //         $path = 'uploads/category/files/';
    //         $file->move($path, $filename);
    //         $product->file = $path . $filename;
    //     }
    //     $product->save();

    //     if ($request->has('images')) {

    //         foreach ($request->file('images') as $file) {
    //             $filename = time() . '-' . uniqid() . '.' . $file->extension(); // Unique filename
    //             $file->move($path, $filename);

    //             // Prepare the data for insertion
    //             $product->productImages()->create([
    //                 'images' => 'uploads/category/multiple/' .
    //                     $filename
    //             ]);
    //         }
    //     }



    // }



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
        $data = $request->validate([
            'product_name' => 'required',
            'details' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'file' => 'nullable',
            'price' => 'required'
        ]);

        // $category = Product::where('id', $request->id);
        $category = Product::findOrFail($request->id);

        if ($request->has('image')) {
            $file = $request->file('image');
            $extension = $file->extension();

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
        if ($request->has('file')) {
            $file = $request->file('file');
            $extension = $file->extension();

            $filename = time() . '.' . $extension;
            $path = 'uploads/category/files/';
            $file->move($path, $filename);

            $data['file'] = $path . $filename;

            if (File::exists(public_path($category->file))) {
                File::delete(public_path($category->file));
            } else {
                unset($data['file']);
            }
        }

        $imagePath = []; // Initialize the array to store image paths

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate a unique filename
                $multi = time() . '_' . uniqid() . '.' . $image->extension();
                $path = 'uploads/category/multi/'; // Use public_path

                // Move the image to the specified path
                $image->move($path, $multi);

                // Store the relative path in the database
                $category->productImages()->create(['images' => $path . $multi]);

                // Add the full path to the array (optional)
                $imagePath[] = $path . $multi;
                if (File::exists(public_path($category->images))) {
                    File::delete(public_path($category->images));
                } else {
                    unset($data['file']);
                }
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
        if (File::exists(public_path($category->file))) {
            File::delete(public_path($category->file));
        }
        $category->delete();
        return redirect('products')->with('Successfully Deleted');
    }
}
