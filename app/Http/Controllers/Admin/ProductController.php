<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponse;
    public function add_product(AddProductRequest $request): JsonResponse
    {
        $imageName = $request->file('image')->getClientOriginalName();
        $imagePath = $request->file('image')->storeAs('products',$imageName,'restaurant');
        if($imagePath){
            $product = Product::create([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'initial_price' => $request->initial_price,
                'final_price' => $request->final_price,
                'description' => $request->description,
                'image' => $imagePath
            ]);
            return $product ? $this->JsonResponse(201,'Added successfully',$product)
                : $this->JsonResponse(500,'Error has been occurred');
        }else{
            return $this->JsonResponse(500,'Error has been occurred');
        }
    }
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $imagePath = $product->image;
        $deleted = Storage::disk('restaurant')->delete($imagePath);
        $productDeleted = $product->delete();
        return $productDeleted && $deleted ? $this->JsonResponse(200,'Deleted successfully')
            : $this->JsonResponse(500,'Error has been occurred');
    }
    public function get_product($id)
    {
        $product = Product::with('category')->where('id',$id)->first();
        return $product  ? $this->JsonResponse(200,'The product is here',$product) :
            $this->JsonResponse(404,'The product not found');
    }
    public function get_products()
    {
        $products = Product::with('category')->get();
        return $this->JsonResponse(200,'Your products here',$products);
    }
}
