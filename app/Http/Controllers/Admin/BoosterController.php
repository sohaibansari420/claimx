<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Booster;
use App\Models\PurchasedPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BoosterController extends Controller
{
    public function tap()
    {
        $page_title = 'Booster Tap';
        $empty_message = 'No Tap found';
        $taps = Booster::where('title','Tap')->paginate(getPaginate());
        return view('admin.booster.Tap.index', compact('page_title', 'taps', 'empty_message'));
    }
    public function tapStore(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'description'       => 'required',
            'features'          => 'required',
            'price'             => 'required|numeric|min:0',
            'image'             => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $booster = new Booster();

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/plan/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'image/svg' || $file_type = 'mage/svg+xml'){
                //Move Uploaded File
                $file->move($location,$filename);
            }
            else{
                $size = "200x200";
                $image = Image::make($file);
                $size = explode('x', strtolower($size));
                $image->resize($size[0], $size[1]);
                $image->save($location . '/' . $filename);                
            }

            $booster->image = $filename;
        }

        $booster->name             = $request->name;
        $booster->title            = 'Tap';
        $booster->price            = $request->price;
        $booster->description      = $request->description;
        $booster->features         = is_array($request->post('features')) ? serialize($request->post('features')) : $request->post('features');
        $booster->status           = $request->status?1:0;
        $booster->save();

        $notify[] = ['success', 'New Booster Tap created successfully'];
        return back()->withNotify($notify);
    }
    public function tapUpdate(Request $request)
    {
        $this->validate($request, [
            'id'                => 'required',
            'name'              => 'required',
            'description'       => 'required',
            'features'          => 'required',
            'price'             => 'required|numeric|min:0',
            'image'             => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $features = explode(",", $request->features);

        $booster = Booster::find($request->id);

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/plan/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'image/svg' || $file_type = 'mage/svg+xml'){
                //Move Uploaded File
                $file->move($location,$filename);
            }
            else{
                $size = "200x200";
                $image = Image::make($file);
                $size = explode('x', strtolower($size));
                $image->resize($size[0], $size[1]);
                $image->save($location . '/' . $filename);                
            }

            $booster->image = $filename;
        }

        $booster->name             = $request->name;
        $booster->price            = $request->price;
        $booster->description      = $request->description;
        $booster->features         = is_array($features) ? serialize($features) : $features;
        $booster->title            = $request->title;
        $booster->status           = $request->status?1:0;
        $booster->save();

        $notify[] = ['success', 'Booster Tap Updated Successfully.'];
        return back()->withNotify($notify);
    }

    public function speed()
    {
        $page_title = 'Booster Speed';
        $empty_message = 'No Speed found';
        $speeds = Booster::where('title','Speed')->paginate(getPaginate());
        return view('admin.booster.Speed.index', compact('page_title', 'speeds', 'empty_message'));
    }
    public function speedStore(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'description'       => 'required',
            'features'          => 'required',
            'price'             => 'required|numeric|min:0',
            'image'             => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $booster = new Booster();

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/plan/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'image/svg' || $file_type = 'mage/svg+xml'){
                //Move Uploaded File
                $file->move($location,$filename);
            }
            else{
                $size = "200x200";
                $image = Image::make($file);
                $size = explode('x', strtolower($size));
                $image->resize($size[0], $size[1]);
                $image->save($location . '/' . $filename);                
            }

            $booster->image = $filename;
        }

        $booster->name             = $request->name;
        $booster->title            = 'Speed';
        $booster->price            = $request->price;
        $booster->description      = $request->description;
        $booster->features         = is_array($request->post('features')) ? serialize($request->post('features')) : $request->post('features');
        $booster->status           = $request->status?1:0;
        $booster->save();

        $notify[] = ['success', 'New Booster Speed created successfully'];
        return back()->withNotify($notify);
    }
    public function speedUpdate(Request $request)
    {
        $this->validate($request, [
            'id'                => 'required',
            'name'              => 'required',
            'description'       => 'required',
            'features'          => 'required',
            'price'             => 'required|numeric|min:0',
            'image'             => 'mimes:png,jpg,jpeg,svg,gif'
        ]);

        $features = explode(",", $request->features);

        $booster = Booster::find($request->id);

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // Real File Name
            $real_name = $file->getClientOriginalName();
        
            // File Extension
            $file_ext = $file->getClientOriginalExtension();
        
            //File Mime Type
            $file_type = $file->getMimeType();

            // New Name
            $filename = uniqid() . time() . '.' . $file_ext;

            // File Location to Save
            $location = 'assets/images/plan/';

            $link = $location . $filename;
            if (file_exists($link)) {
                @unlink($link);
            }

            if($file_type == 'image/svg' || $file_type = 'mage/svg+xml'){
                //Move Uploaded File
                $file->move($location,$filename);
            }
            else{
                $size = "200x200";
                $image = Image::make($file);
                $size = explode('x', strtolower($size));
                $image->resize($size[0], $size[1]);
                $image->save($location . '/' . $filename);                
            }

            $booster->image = $filename;
        }

        $booster->name             = $request->name;
        $booster->price            = $request->price;
        $booster->description      = $request->description;
        $booster->features         = is_array($features) ? serialize($features) : $features;
        $booster->title            = $request->title;
        $booster->status           = $request->status?1:0;
        $booster->save();

        $notify[] = ['success', 'Booster Speed Updated Successfully.'];
        return back()->withNotify($notify);
    }
}
