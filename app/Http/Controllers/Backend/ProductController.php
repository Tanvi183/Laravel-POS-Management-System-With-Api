<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::all();
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validation = Validator::make($input,[
            'product_name' => 'required|unique:products,product_name',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'product_code' => 'required|unique:products,product_code',
            'small_unit_id' => 'required',
            'stock_limitation' => 'required',
            'specification' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors'=>$validation->errors()], 403);
        }

        try {
            $input['slug'] = Str::slug($request->product_name);
            $input['created_by'] = Auth::user()->id;
            $data =  Product::create($input);
            return response()->json($data, 201);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errorINfo[2]], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product_name' => "required|unique:products,product_name,$product->id",
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'product_code'=>"required|unique:products,product_code,$product->id",
            'small_unit_id'=>'required',
            'stock_limitation'=> 'required',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],403);
        }

        try {
            $product->slug = Str::slug($request->product_name);
            $product->created_by = Auth::user()->id;
            $product->update($request->all());
            return response()->json('Product Update Successfully', 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errorINfo[2]], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json('Successfully Data Deleted', 200);
        } catch (\Throwable $th) {
            return response()->json(['error'=>$th->errorINfo[2]], 403);
        }
    }
}
