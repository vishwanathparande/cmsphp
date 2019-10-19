<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $products = Product::with('Category')->orderBy('title', 'asc')->paginate(5);

        return view('products.index', compact('products'))
                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $categories = Category::pluck('name', 'id');
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'title' => [
                'required', 'string', 'max:255',
                Rule::unique('products')->where(function ($query)use($request) {
                            return $query->where('category_id', $request->category_id);
                        }),
            ],
            'category_id' => 'required|numeric|exists:categories,id',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:1',
            'status' => 'required|in:Active,Inactive',
                ], [
            'title.unique' => 'The title has already been taken for selected category.'
        ]);

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $path = 'uploads/products/' . $filename;
            $file->move('uploads/products/', $filename);
            chmod($path, 0777);
        }

        $data = $request->all();
        $data['image'] = $path;
        Product::create($data);

        return redirect()->route('products.index')
                        ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product) {
        $categories = Category::pluck('name', 'id');
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product) {
        $request->validate([
            'title' => [
                'required', 'string', 'max:255',
                Rule::unique('products')->where(function ($query)use($product) {
                            return $query->where('category_id', $product->category_id)
                                            ->where('id', '!=', $product->id);
                        }),
            ],
            'category_id' => 'required|numeric|exists:categories,id',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:1',
            'status' => 'required|in:Active,Inactive',
                ], [
            'title.unique' => 'The title has already been taken for selected category.'
        ]);

        $path = '';
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $path = 'uploads/products/' . $filename;
            $file->move('uploads/products/', $filename);
            chmod($path, 0777);
        }

        $data = $request->all();
        if ($path) {
            $data['image'] = $path;
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return redirect()->route('products.index')
                        ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product) {
        $product->delete();

        return redirect()->route('products.index')
                        ->with('success', 'Product deleted successfully');
    }

}
