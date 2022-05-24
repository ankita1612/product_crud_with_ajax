<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;    
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

use Datatables;
 
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Product::select('*'))
            ->addColumn('action', 'company-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('products');
    }
      
      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        DB::enableQueryLog();


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
                        $name = time().rand(1,100).'.'.$file->extension();
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $product  = Product::with("product_images")->where($where)->first();
        
      //  pr($product->product_images );
        $product_image="";
        foreach($product->product_images as $key=>$value)
        {
            $product_image.="<img width='50px' height='50px' src='".url('images/'.$value->image)."'> <a href='#' onclick='delete_image(".$value->id.")' data-image_id='".$value->id."'>X</a>";

        }
        
        $data['product']=$product;
        $data['product_image_data']=$product_image;
        //pr($data);
      
        return Response()->json($data);
    }
      
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::where('id',$request->id)->delete();      
        return Response()->json($product);
    }
}