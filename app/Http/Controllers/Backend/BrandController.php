<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data  = Brand::all();
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
        $validation = Validator::make($input, [
            'name' => 'required|unique:brands,name',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        try {
            $input['image']      = $this->ImageUpload($request);
            $input['slug']       = Str::slug($request->name);
            $input['created_by'] = Auth::user()->id;
            Brand::create($input);
            return response()->json('Brand Add Successfully', 201);
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
        $data = Brand::findOrFail($id);
        return response()->json($data, 200);
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
        $input = $request->all();
        $validation = Validator::make($input,[
            'name'=> "required|unique:brands,name,$id",
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()], 403);
        }

        try {
            $brand             = Brand::findorfail($id);
            $brand->slug       = Str::slug($request->name);
            $brand->created_by = Auth::user()->id;
            if($request->hasFile('image')){
                @unlink($brand->image);
                $input['image'] = $this->ImageUpload($request);
            }
            $brand->update($request->all());
            return response()->json('Brand Update Successfully', 201);
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
            $user = Brand::findOrFail($id);
            @unlink($user->image);
            $user->delete();
            return response()->json('Successfully Data Deleted', 200);
        } catch (Exception $e) {
            return response()->json(['error'=>$e->errorINfo[2]], 403);
        }
    }


    // Image Upload
    protected function ImageUpload($request)
    {
        $image = $request->hasFile('image');
        if($image){
            $file = $request->file('image');
            $fileType = $file->getClientOriginalExtension();
            $fileName = date('ymdhis').'.'.$fileType;
            $path = 'images';
            $file->move(public_path($path),$fileName);
            $image_url = $path.'/'.$fileName;

            return $image_url;
        }
    }
}
