<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::with('section')->get();
        $sections = Section::all();
        return view('products.products',compact('products','sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        //
       $data = $request->validated();
       Product::create($data);
       flash()->addSuccess('تم اضافة المنتج بنجاح');
       return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request,$id)
    {
        $product = Product::query()->where('id',$request->id)->first();
        $data=$request->validated();
        $product->update($data);
        flash()->addSuccess('تم تعديل المنتج بنجاح');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        //
        $id= $request->id;
        //dd($id);
        $product = Product::findOrFail($id);
        $product->delete();
        flash()->addSuccess('تم حذف المنتج بنجاح');
        return redirect()->back();
    }
}
