<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use App\Models\File;
 
class MultiFileUploadAjaxController extends Controller
{
    public function index()
    {
        return view('multi-file-ajax-upload');
    }
 
 
    public function storeMultiFile(Request $request)
    {
         
       $validatedData = $request->validate([
        'files' => 'required',
        'files.*' => 'mimes:jpg,jpeg,png'
        ]);
 
        if($request->TotalFiles > 0)
        {
                
           for ($x = 0; $x < $request->TotalFiles; $x++) 
           {
 
               if ($request->hasFile('files'.$x)) 
                {
                    $file      = $request->file('files'.$x);
 
                    $path = $file->store('public/files');
                    $name = $file->getClientOriginalName();
 
                    $insert[$x]['name'] = $name;
                    $insert[$x]['path'] = $path;
                }
           }
 
            File::insert($insert);
 
            return response()->json(['success'=>'Ajax Multiple fIle has been uploaded']);
 
          
        }
        else
        {
           return response()->json(["message" => "Please try again."]);
        }
 
    }
}