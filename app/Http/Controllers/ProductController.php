<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->idGenerate = Str::random(36);
    }
    public function listData()
    {
        $resData = [
            'status'  => "success",
            'data'    =>  Product::orderBy('created_at','DESC')->get(),
            'message' => 'successfully load data'
        ];
        return response()->json($resData, 200);
    }
    public function storeData(Request $request)
    {
        if(Auth::user()->role !== "cashier"){
            return response()->json(['status' => "error",'message' => 'You are not a cashier!'], 401);
        }
        $this->validate($request, [
            'name'      => 'required',
            'stock'      => 'required|string',
        ]);
        try {
            $product = new Product;
            $product->id       = $this->idGenerate;
            $product->name     = $request->input('name');
            $product->stock    = $request->input('stock');
            $product->status   = "pending";
            $product->created_by   = Auth::user()->id;
            $product->save();

            Notification::create([
                'id'            => Str::random(36),
                'title'         => "There are new products to review",
                'role'          => "supervisor",
                'created_by'    => "SYSTEM",
                'description'   => "new products that the supervisor should review",
            ]);
            $resData = [
                'status'  => "success",
                'data'    =>  $product,
                'message' => 'successfully create data'
            ];
            return response()->json($resData, 200);

        } catch (\Exception $e) {
            return $e;
            $resData = [
                'status'    => "error",
                'data'      => null,
                'message'   => 'Data Failed!'
            ];
            return response()->json($resData, 409);
        }
    }
    public function updateData(Request $request,$id)
    {
        if (Auth::user()->role !== "cashier") {
            return response()->json(['status' => "error", 'message' => 'You are not a cashier!'], 401);
        }
        try {
            $product = Product::where('id',$id)->first();
            if ($product == NULL) {
                return response()->json(['status' => "error", 'message' => 'Data Empty!'], 401);
            }
            $product->name     = $request->input('name');
            $product->stock    = $request->input('stock');
            $product->status   = "pending";
            $product->updated_by   = Auth::user()->id;
            $product->save();

            $resData = [
                'status'  => "success",
                'data'    =>  $product,
                'message' => 'successfully update data'
            ];
            return response()->json($resData, 200);
        } catch (\Exception $e) {
            return $e;
            $resData = [
                'status'    => "error",
                'data'      => null,
                'message'   => 'Data Failed!'
            ];
            return response()->json($resData, 409);
        }
    }
    public function deleteData(Request $request, $id)
    {
        if (Auth::user()->role !== "cashier") {
            return response()->json(['status' => "error", 'message' => 'You are not a cashier!'], 401);
        }
        try {
            $product = Product::where('id',$id)->first();
            if($product == NULL ){
                return response()->json(['status' => "error", 'message' => 'Data Empty!'], 401);
            }
            $product->delete();

            $resData = [
                'status'  => "success",
                'data'    =>  NULL,
                'message' => 'successfully delete data where id = '.$id,
            ];
            return response()->json($resData, 200);
        } catch (\Exception $e) {
            return $e;
            $resData = [
                'status'    => "error",
                'data'      => null,
                'message'   => 'Data Failed!'
            ];
            return response()->json($resData, 409);
        }
    }
    public function reviewProduct(Request $request, $id)
    {
        $this->validate($request, [
            'status'      => 'required',
        ]);
        if (Auth::user()->role !== "supervisor") {
            return response()->json(['status' => "error", 'message' => 'You are not a supervisor!'], 401);
        }
        try {
            $product = Product::where('id', $id)->first();
            if ($product == NULL) {
                return response()->json(['status' => "error", 'message' => 'Data Empty!'], 401);
            }
            if ($request->input('status') === "rejected") {
                $this->validate($request, [
                    'status'      => 'required',
                    'reason'      => 'required',
                ]);
                $message = "Products accepted by supervisors";
            }else{
                $message = "Products rejected by supervisors";

            }
            Notification::create([
                'id'            => Str::random(36),
                'title'         => $message,
                'role'          => "cashier",
                'created_by'    => "SYSTEM",
                'description'   => "new products that the supervisor should review",
            ]);
            $product->status     = $request->input('status');
            $product->reason     = $request->input('reason');
            $product->cheked_at  = Carbon::now();
            $product->cheked_by  = Auth::user()->id;
            $product->save();

            $resData = [
                'status'  => "success",
                'data'    =>  $product,
                'message' => 'successfully update data'
            ];
            return response()->json($resData, 200);
        } catch (\Exception $e) {
            return $e;
            $resData = [
                'status'    => "error",
                'data'      => null,
                'message'   => 'Data Failed!'
            ];
            return response()->json($resData, 409);
        }
    }

}
