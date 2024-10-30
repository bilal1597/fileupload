<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Stmt\Return_;

class ProductController extends Controller
{
    public function showProducts()
    {
        $show_products = Product::all();
        return view('products', compact('show_products'));
    }
    public function ImgView($productId)
    {
        $show_images = Product::findOrFail($productId);
        $product_images = ProductImage::where('product_id', $productId)->get();

        return view('image', compact('show_images', 'product_images'));
    }

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

    public function postAddProduct(Request $request)
    {
        $data = $request->validate([
            'product_name' => 'required',
            'details'  => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'file' => 'nullable',
            'images.*' => 'required|image|mimes:png,jpg,jpeg,webp', // har image validate karega
            'price' => 'required',
        ]);

        if ($request->has('image')) {
            $file = $request->file('image');
            $extension = $file->extension();

            $filename = time() . '.' . $extension;
            $path = 'uploads/category/';
            $file->move($path, $filename);

            $data['image'] = $path . $filename;
        }
        if ($request->has('file')) {
            $file = $request->file('file');
            $extension = $file->extension();

            $filename = time() . '.' . $extension;
            $path = 'uploads/category/files/';
            $file->move($path, $filename);

            $data['file'] = $path . $filename;
        }
        // $product = Product::findOrFail($producId);

        $imagespath = [];
        if ($request->has('images')) {
            $files = $request->file('images');

            foreach ($files as $key => $file) {
                $extension = $file->extension();
                $filename = $key . '-' . time() . '.' . $extension;

                $path = 'uploads/category/multiple/';
                $file->move($path, $filename);

                // $data['image'] =  $path . $filename;

                $imagespath[] = [
                    'images' => $path . $filename,
                ];
                $data['images'] = $imagespath;
                // $data['images'] = json_encode($imagespath);
            }
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
