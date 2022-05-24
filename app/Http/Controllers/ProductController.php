<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;    
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use File;

use Datatables;
 
class ProductController extends Controller
{
    /**
        This function shows listing of products
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Product::select('*'))
            ->addColumn('action', 'product-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('products');
    }
      
      
    /**
        This function store products
    */
    public function store(Request $request)
    {           
        if($request->hasFile('files')){
            $rules = [
                'product_name' => 'required',
                'product_price' => 'required',        
                'files' => 'required',
                'files.*' => 'image|mimes:jpg,jpeg,png'
            ];
        }
        else
        {
            $rules = [
                'product_name' => 'required',
                'product_price' => 'required',        
           ]; 
        }

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) 
        {            
            $data['status']="error";
            $data['message']="error in image";
            //return \Redirect::back()->withErrors($validator);
            return Response()->json($validator); 
        } 
        else 
        {            
            $productId = $request->id; 
            $mode_str=($productId?"Updated":"Added");
            $product   =   Product::updateOrCreate(
                        [
                         'id' => $productId
                        ],
                        [
                        'product_name' => $request->product_name, 
                        'product_price' => $request->product_price,
                        'product_desc' => $request->product_desc
                        ]);                  
 
            if($request->TotalFiles > 0)
            {                    
               for ($x = 0; $x < $request->TotalFiles; $x++) 
               {     
                   if ($request->hasFile('files'.$x)) 
                    {

                        $file      = $request->file('files'.$x);     
                        $name = date("ymdhis").rand(1,100).rand(1,100).'.'.$file->extension();                        
                        //$name = time().rand(1,100).'.'.$file->extension();
                        $file->move(public_path('images'), $name);  
                        
                        $insert[$x]['name'] = $name;
                        $insert[$x]['path'] = public_path('images');
                    }
                }    
                           
                $product_image_array = [];

                for($i =0; $i < count($insert); $i++) {
                    $product_image_array[] =  new ProductImage([
                        'image' =>  $insert[0]['name']
                    ]);
                }
                $product->product_images()->saveMany($product_image_array);
               // print_R(DB::getQueryLog());                
                
            }   
            $data['status']="success";
            $data['message']="Product {$mode_str} Successfully";                                  
            return Response()->json($data); 
        }

     } 
      
    /**
        This function edit products
    */

    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $product  = Product::with("product_images")->where($where)->first();        
        $product_image="";
        foreach($product->product_images as $key=>$value)
        {
            $product_image.="<div class='col-sm-3' id='image_".$value->id."'><img width='50px' height='50px' src='".url('images/'.$value->image)."'><br><a href='javascript:void(0)' onclick='delete_image(".$value->id.")' data-image_id='".$value->id."'> Delete</i></a></div>";
        }        
       
        $data['product']=$product;
        $data['product_image_data']=$product_image;
        //pr($data);
      
        return Response()->json($data);
    }
      
      
    /**
        This function delete products
    */

    public function destroy(Request $request)
    {
        $product = Product::where('id',$request->id)->delete();      
        return Response()->json($product);
    }

    /**
        This function delete images
    */
    public function image_delete($id)
    {       
        DB::enableQueryLog();

        $product_image = ProductImage::where('id',$id)->first();              

        if($product_image)
        {
            $image_path="images/".$product_image->image;
            $file_exist=(File::exists($image_path)?true:false);
            if($file_exist)
                unlink($image_path);
            
        }
        $product = ProductImage::where('id',$id)->delete();          
        
        $data['message']="Image Deleted Successfully";
        $data['sucess']=true;
        return Response()->json($data);      
    }
}