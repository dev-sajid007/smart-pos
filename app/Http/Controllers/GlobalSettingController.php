<?php

namespace App\Http\Controllers;

use App\globalSetting;
use App\taxRate;
use App\Discount;
use Illuminate\Http\Request;
use DB;

class GlobalSettingController extends Controller
{

    public function __construct(){
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'currency_code'=>'required|max:160',
            'fk_product_tax_id'=>'required',
            'fk_invoice_tax_id'=>'required',
            'fk_default_discount_id'=>'required',
            'purchase_prefix'=>'required|max:160',
            'sales_prefix'=>'required|max:160',
            'quotation_prefix'=>'required|max:160'
        ]);

        $setting=new globalSetting;

        $setting->currency_code=$request->currency_code;
        $setting->fk_product_tax_id=$request->fk_product_tax_id;
        $setting->fk_invoice_tax_id=$request->fk_invoice_tax_id;
        $setting->fk_default_discount_id=$request->fk_default_discount_id;
        $setting->purchase_prefix=$request->purchase_prefix;
        $setting->sales_prefix=$request->sales_prefix;
        $setting->quotation_prefix=$request->quotation_prefix;

        $setting->fk_created_by=$request->user_id;
        $setting->fk_updated_by=$request->user_id;
        $setting->fk_company_id=$request->company_id;
        $setting->save();

        if($setting){
            return redirect()->route('systems.edit',$request->company_id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\globalSetting  $globalSetting
     * @return \Illuminate\Http\Response
     */
    public function show(globalSetting $globalSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\globalSetting  $globalSetting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=globalSetting::where('fk_company_id',$id)->first();

        if($data){
            $setting = DB::table('global_settings')
            ->where('global_settings.fk_company_id',$id)
            ->join('tax_rates as tx_product', 'global_settings.fk_product_tax_id', '=', 'tx_product.id')
            ->join('tax_rates as tx_invoice', 'global_settings.fk_invoice_tax_id', '=', 'tx_invoice.id')
            ->join('discounts', 'global_settings.fk_default_discount_id', '=', 'discounts.id')
            ->select('global_settings.*', 'discounts.title as discount_title','discounts.id as discount_id','tx_invoice.tax_rate_title as invoice_tax_title','tx_invoice.id as tax_invoice_id','tx_product.tax_rate_title as product_tax_title','tx_product.id as tax_product_id')
            ->first();
            //dd($setting);
            $tax_rate=taxRate::paginate(10);
            $discount=Discount::paginate(10);
            return view('admin.global_config.system_settings_edit')->with('setting',$setting)
                                                                    ->with('tax_rate',$tax_rate)
                                                                    ->with('discount',$discount);
        }else{
            $tax_rate=taxRate::paginate(10);
            $discount=Discount::paginate(10);
            return view('admin.global_config.system_settings')
                                                ->with('tax_rate',$tax_rate)
                                                ->with('discount',$discount);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\globalSetting  $globalSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'currency_code'=>'required|max:160',
            'fk_product_tax_id'=>'required',
            'fk_invoice_tax_id'=>'required',
            'fk_default_discount_id'=>'required',
            'purchase_prefix'=>'required|max:160',
            'sales_prefix'=>'required|max:160',
            'quotation_prefix'=>'required|max:160'
        ]);


        $stts=globalSetting::where('fk_company_id',$id)
                        ->update(array('currency_code' => $request->currency_code,
                                        'fk_product_tax_id'=>$request->fk_product_tax_id,
                                        'fk_invoice_tax_id'=>$request->fk_invoice_tax_id,
                                        'fk_default_discount_id'=>$request->fk_default_discount_id,
                                        'purchase_prefix'=>$request->purchase_prefix,
                                        'sales_prefix'=>$request->sales_prefix,
                                        'quotation_prefix'=>$request->quotation_prefix,
                                        'fk_updated_by'=>$request->user_id));
        if($stts){
            return redirect()->route('systems.edit',$request->company_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\globalSetting  $globalSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(globalSetting $globalSetting)
    {
        //
    }
}
