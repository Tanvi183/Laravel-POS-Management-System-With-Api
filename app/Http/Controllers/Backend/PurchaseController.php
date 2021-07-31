<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Purchase;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\PurchaseItem;
use App\Models\InventoryChallan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Purchase::all();
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
        /*
        {
            "purchase_date" : "2021-05-26",
            "challan_no" : "r2424",
            "supplier_id" : 1,
            "note" : "First Purchase",
            "total_amount" : 6000,
            "items":[
                {
                    "product_id":1,
                    "small_unit_price":500,
                    "small_unit_qty":4,
                    "small_unit_sales_price":550,

                    "big_unit_price":1000 (nullable),
                    "big_unit_qty":3  (nullable),
                    "big_unit_sales_price":1500  (nullable)
                },
                {
                    "product_id":2,
                    "small_unit_price":100,
                    "small_unit_qty":10,
                    "small_unit_sales_price":120
                }
            ]
        }
        */

        DB::beginTransaction();
        try {
            $input = $request->all();
            $validator = Validator::make($input,[
                'purchase_date' => 'required|date',
                'challan_no' => 'required|unique:purchases,challan_no',
                'total_amount' => 'required|numeric',
                'supplier_id' => 'required',
                'items.*.product_id'=>'required',
                'items.*.small_unit_qty'=>'required',
                'items.*.small_unit_price'=>'required',
                'items.*.small_unit_sales_price'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors'=>$validator->errors()], 403);
            }

            /* Start Purchase  */
            $purchaseInput = [
                'purchase_date' => date('Y-m-d',strtotime($request->purchase_date)),
                'challan_no'    => $request->challan_no,
                'supplier_id'   => $request->supplier_id,
                'note'          => $request->note ?? '',
                'total_amount'  => $request->total_amount,
                'created_by'    => Auth::user()->id
            ];
            $purchase = Purchase::create($purchaseInput);
            /* End Purchase  */

            /*Start Purchase Item */
            foreach ($request->items as $singleItem) {
                $item = (object) $singleItem;

                $purchaseItemInput = [
                    'purchase_id'      => $purchase->id,
                    'product_id'       => $item->product_id,
                    'big_unit_price'   => $item->big_unit_price??null,
                    'small_unit_price' => $item->small_unit_price,
                    'big_unit_qty'     => $item->big_unit_qty??null,
                    'small_unit_qty'   => $item->small_unit_qty
                ];
                $purchaseItem = PurchaseItem::create($purchaseItemInput);
                /* End Purchase Item */

                /* Start Inventory/Stock */
                $existProduct = Inventory::where('product_id', $item->product_id)->first();
                $available_big_unit_qty   = $item->big_unit_qty ?? 0;
                $available_small_unit_qty = $item->small_unit_qty;

                if ($existProduct != '') {
                    $available_big_unit_qty   += $existProduct->available_big_unit_qty;
                    $available_small_unit_qty += $existProduct->available_small_unit_qty;
                }

                $inventoryInput = [
                    'product_id'               => $item->product_id,
                    'available_big_unit_qty'   => $available_big_unit_qty,
                    'available_small_unit_qty' => $available_small_unit_qty,
                    'big_unit_sales_price'     => $item->big_unit_sales_price ?? null,
                    'small_unit_sales_price'   => $item->small_unit_sales_price,
                ];
                if ($existProduct != '') {
                    $existProduct->update($inventoryInput);
                    $inventory = $existProduct;
                }else {
                    $inventory = Inventory::create($inventoryInput);
                }
                /* End Inventory/Stock */

                /*Start Inventory Challan */
                $inventoryChallanInput = [
                    'purchase_id'            => $purchase->id,
                    'inventory_id'           => $inventory->id,
                    'product_id'             => $item->product_id,
                    'big_unit_sales_price'   => $item->big_unit_sales_price ?? null,
                    'small_unit_sales_price' => $item->small_unit_sales_price,
                    'big_unit_cost_price'    => $item->big_unit_price ?? null,
                    'small_unit_cost_price'  => $item->small_unit_price,
                    'big_unit_qty'           => $item->big_unit_qty ?? null,
                    'small_unit_qty'         => $item->small_unit_qty,
                    'available_big_unit_qty' => $item->big_unit_qty?? null,
                    'available_small_unit_qty'=> $item->small_unit_qty,
                ];
                InventoryChallan::create($inventoryChallanInput);
                /* End InventoryChallan */
            }

        DB::commit();
        return response()->json("Successfully Inserted",201);
        } catch (Exception $e) {
        DB::rollBack();
        return response()->json(['error'=>$e->errorInfo[2]], 403 );
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        return response()->json($purchase, 201);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
