<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategory = SubCategory::all();
        return response()->json($subcategory, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validation = Validator::make($data,[
            'name' => 'required|max:90|unique:sub_categories,name',
            'category_id' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        try {
            $data['slug'] = Str::slug($request->name);
            $data['category_id'] = $request->category_id;
            $data['created_by'] = Auth::user()->id;
            $user = SubCategory::create($data);
            return response()->json('Category Add Successfully', 200);
        } catch (Exception $e) {
            return response()->json($e, 403);
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
        $subcategory = SubCategory::findOrFail($id);
        return response()->json($subcategory, 200);
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
            'name' => "required|max:90|unique:sub_categories,name,$id",
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=> $validation->errors()], 403);
        }

        try {
            $data = SubCategory::findorfail($id);
            $data->slug = Str::slug($request->name);
            $data->category_id = $request->category_id;
            $data->created_by = Auth::user()->id;
            $data->update($request->all());
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
    public function destroy($id)
    {
        try {
            $user = SubCategory::findOrFail($id);
            $user->delete();
            return response()->json('Successfully Data Deleted', 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errorINfo[2]], 403);
        }
    }
}
