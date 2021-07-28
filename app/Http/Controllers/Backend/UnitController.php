<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Unit::all();
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
            'name' => 'required|unique:units,name',
            'type' => 'required|numeric|between:1,2',
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        try {
            $input['created_by'] = Auth::user()->id;
            $unit =  Unit::create($input);
            return response()->json('Unit Add Successfully', 200);
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
    public function show($id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json($unit, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validation = Validator::make($data,[
            "name' => 'required|unique:units,name,$id",
            'type' => 'numeric|between:1,2',
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        try {
            $unit = Unit::findOrFail($id);
            $unit->created_by = Auth::user()->id;
            $unit->update($request->all());
            return response()->json('Unit Update Successfully', 200);
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
    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();
            return response()->json('Successfully Data Deleted', 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errorINfo[2]], 403);
        }
    }
}
