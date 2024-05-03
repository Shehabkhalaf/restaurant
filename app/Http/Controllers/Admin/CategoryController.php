<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    use ApiResponse;
    public function add_category(AddCategoryRequest $request): JsonResponse
    {
        try {
            $imageName = $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('categories',$imageName,'restaurant');
            $category = Category::create([
                'title' => $request->title,
                'image' => $imagePath
            ]);
            return $this->JsonResponse(201,'Category created',$category);
        } catch (Exception $e) {
            return $this->JsonResponse(500,'An error occurred while creating the category',$e);
        }
    }
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        Storage::disk('restaurant')->delete($category->image);
        $category->delete();
        return $this->JsonResponse(200,'Category deleted');
    }
    public function update(Request $request)
    {

    }
    public function all_categories(): JsonResponse
    {
        return $this->JsonResponse(200,'All categories here',Category::all());
    }
    public function categories_with_products(): JsonResponse
    {
        $categories = Category::with('products')->get();
        return $this->JsonResponse(200,'All categories here',$categories);
    }
    public function category_with_products($id): JsonResponse
    {
        $category = Category::with('products')->where('id',$id)->first();
        return $category ? $this->JsonResponse(200,'All categories here',$category) :
            $this->JsonResponse(404,'The category not found');;
    }
}
