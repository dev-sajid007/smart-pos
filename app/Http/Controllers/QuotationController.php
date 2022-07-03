<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use Auth;
use App\Quotation;
use Illuminate\Http\Request;
use App\Warehouse;
use App\Biller;
use App\Customer;
use App\taxRate;
use App\Product;
use App\Discount;
use App\QuotationDetails;
use App\Company;
use App\Status;

class QuotationController extends Controller
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
       $quotations = Quotation::where('fk_company_id', companyId())->paginate(10);
       return view('admin.quotations.index', [
           'quotations'=>$quotations
       ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::where('fk_company_id', Auth::user()->fk_company_id)->get();
        $products = Product::where('fk_company_id', Auth::user()->fk_company_id)->get();
        $statuses = Status::paginate(10);
        return view('admin.quotations.create', [
            'customers'=>$customers,
            'products'=>$products,
            'statuses' => $statuses,
            'customer_categories' => CustomerCategory::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'quotation_date' => 'required|max:160|date',
//            'quotation_reference'=>'required|max:160|unique:quotations',
            'fk_customer_id' => 'required',
//            'fk_status_id' => 'required',
            'product_ids.*' => 'required|not_in:0',
            'unit_prices.*' => 'required',
            'quantities.*' => 'required',
        ],
        [
            'product_ids.*.required' => 'Select Product',
            'product_ids.*.not_in' => 'The selected product is invalid',
            'unit_prices.*.required' => 'The unit price is required',
            'quantities.*.required' => 'The quantity field is required'
        ]);

        $quotation = Quotation::create([
            "fk_customer_id" => $request->fk_customer_id,
            "sub_total" => $request->sub_total,
            "invoice_discount" => $request->invoice_discount,
            "invoice_tax" => $request->invoice_tax,
            "total_payable" => $request->total_payable,
        ]);

        $product_ids = $request->product_ids;
        foreach($product_ids as $key => $value){

            $quotation->quotation_details()->create([
                "fk_product_id" => $value,
                "quantity" => $request->quantities[$key],
                "unit_price" => $request->unit_prices[$key],
                "product_sub_total" => $request->product_sub_total[$key],
            ]);

        }
        return redirect()->route('quotations.show', $quotation)->withSuccess('Quotation Created Successfully!');

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show(Quotation $quotation)
    {
        return view('admin.quotations.invoice', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quotation = $this->get_quotation($id);
        $customers = Customer::where('fk_company_id', companyId())->get();
        $products = Product::where('fk_company_id', companyId())->get();
//        $quotation_details = QuotationDetails::with('product.product_unit')->where('fk_quotation_id', $id)->get();
        
        return view('admin.quotations.edit', compact(['quotation','customers','products',
        'quotation_details','statuses']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quotation $quotation)
    {
        $this->validate($request, [
            'quotation_date' => 'required|max:160|date',
//            'quotation_reference'=>'required|max:160|unique:quotations,quotation_reference,'.$request->id,
            'fk_customer_id' => 'required',
            'product_ids.*' => 'required|not_in:0',
            'unit_prices.*' => 'required',
            'quantities.*' => 'required',
            ],
            [
            'product_ids.*.required' => 'Select Product',
            'product_ids.*.not_in' => 'The selected product is invalid',
            'unit_prices.*.required' => 'The unit price is required',
            'quantities.*.required' => 'The quantity field is required'
        ]);

        $quotation->update([
            'fk_customer_id' => $request->fk_customer_id,
            'sub_total' => $request->sub_total,
            'invoice_discount' => $request->invoice_discount,
            'invoice_tax' => $request->invoice_tax,
            'total_payable' => $request->total_payable,
        ]);

        foreach($request->product_ids as $key => $product_id):

            $quotation->quotation_details()->update([
                'fk_product_id' => $product_id,
                'quantity' => $request->quantities[$key],
                'unit_price' => $request->unit_prices[$key],
                'product_sub_total' => $request->product_sub_total[$key],
            ]);
        endforeach;

        return redirect()->route('quotations.show', $quotation)->withSuccess('Quotation Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $quotation_details = QuotationDetails::where('fk_quotation_id', $id)->get();
        foreach($quotation_details as $details){
            $delete_quotation = QuotationDetails::findOrFail($details->id);
            $delete_quotation->delete();
        }
        
        $quotation = $this->get_quotation($id);
        $quotation->delete();
        return \redirect('quotations')->with(['success'=>'Quotation Deleted Successfully!']);
    }

    public function get_quotation($id){
        return Quotation::findOrFail($id);
    }


    public function test(){
        return view('admin.quotations.test');
    }

    public function invoice($id){
        $quotation = Quotation::with(['quotation_details','quotation_company'])->find($id);
        return view('admin.quotations.invoice', compact([
            'quotation'
        ]));
    }
}
