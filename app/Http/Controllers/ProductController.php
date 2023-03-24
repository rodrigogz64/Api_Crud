<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class ProductController extends Controller
{
    //show all the data
    public function index(){
        $products = Product::all();
        return response()->json(['products' => $products], 200);
    }

    //show the data by id
    public function show($id){
        $products = Product::find($id);
        if($products)
            return response()->json(['products' => $products], 200);
        else
            return response()->json(['products' => 'No Product Found'], 404);
    }
    
    //store the values
    public function store(Request $request){
        $request->validate([
            'name' => 'required|max: 255',
            'description' => 'required|max: 255',
            'price' => 'required|max: 255',
            'quantity' => 'required|max: 255'
        ]);

        $product = new Product();

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->save();
        return response()->json(['message' => 'Product Added Succefully'], 200);
    }

    //update data
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|max: 255',
            'description' => 'required|max: 255',
            'price' => 'required|max: 255',
            'quantity' => 'required|max: 255'
        ]);

        $product = Product::find($id);
        if($product){
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->update();
            return response()->json(['message' => 'Product Updated Succefully'], 200);//202 OK
        }
        else{
            return response()->json(['message' => 'The Product Not Found'], 404);//404 not found
        }
    }

    //delete row data
    public function delete($id){
        $products = Product::find($id);
        if($products){
            $products->delete();
            return response()->json(['products' => 'Product Deleted Succefully'], 200);
        }
        else {
            return response()->json(['products' => 'The Product Not Found'], 404);
        }
    }
}
