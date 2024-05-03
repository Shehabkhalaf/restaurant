<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    use ApiResponse;
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'image' => 'required|file|image|mimes:png,jpg|max:2048'
        ]);
        if($validator->fails()){
            return $this->JsonResponse(422,'Check the data',$validator->messages());
        }
        $imageName = $request->file('image')->getClientOriginalName();
        $imagePath = $request->file('image')->storeAs('sliders',$imageName,'restaurant');
        $slider = Slider::create(['image' => $imagePath]);
        return $this->JsonResponse(201,'Slider created',$slider);
    }
    public function get_sliders()
    {
        return $this->JsonResponse(200,'All sliders',Slider::all());
    }
    public function delete($id)
    {
        $slider = Slider::findOrFail($id);
        Storage::disk('restaurant')->delete($slider->image);
        return $this->JsonResponse(200,'Deletion done');
    }
}
