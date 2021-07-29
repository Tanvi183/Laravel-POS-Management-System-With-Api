<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Supplier::all();
        return response()->json($data, 201);
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
            'company_name' => 'required|max:100|unique:suppliers,company_name',
            'supplier_name' => 'required|max:100',
            'email' => 'email|unique:suppliers,email',
            'phone' => 'required|unique:suppliers,phone',
            'address' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        try {
            $input['created_by'] = Auth::user()->id;
            $unit =  Supplier::create($input);
            return response()->json('Supplier Add Successfully', 200);
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
    public function show(Supplier $supplier)
    {
        return response()->json($supplier, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $input = $request->all();
        $validation = Validator::make($input,[
            'company_name' => "required|max:100|unique:suppliers,company_name,$supplier->id",
            'supplier_name' => 'required|max:100',
            'email' => "required|email|unique:suppliers,email,$supplier->id",
            'phone' => "required|unique:suppliers,phone,$supplier->id",
            'address' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        try {
            $supplier->created_by = Auth::user()->id;
            $supplier->update($request->all());
            return response()->json('Successfully Data Updated', 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errorINfo[2]], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return response()->json('Successfully Data Deleted', 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errorINfo[2]], 403);
        }
    }
}
