<?php

namespace App\Http\Controllers\Product;

use App\Barcode;
use App\Http\Controllers\Controller;
use App\Product;
use App\SoftwareSetting;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $barcodes = Barcode::whereHas('product')->when($request->filled('search'), function ($q) use($request) {
            $q->where('barcode_number', 'LIKE', '%' . $request->search . '%')
                ->orWhereHas('product', function ($qr) use ($request) {
                    $qr->companies()->where('product_name', 'LIKE', '%' . $request->search . '%');
                });
        })->orderByDesc('id')->paginate(10);
       return view('admin.product.barcodes.index', compact('barcodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::companies()->select('product_name as name', 'id')->withCount(['barcode as barcode_number' => function($q) {
            $q->select('barcode_number');
        }])->get();
        return view('admin.product.barcodes.create', compact('products'));
    }

    public function searchProduct()
    {
        $product = Product::companies()->with('product_stock')->select('product_name','id','product_price','tax')->get();
            return response()->json($product);
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
            'product_names.*'    => 'required',
            'product_ids.*'      => 'required',
            'barcode_numbers.*'  => 'required|unique:barcodes,product_id',

        ]);

        $count_id = array_count_values($request->product_ids);

        foreach ($count_id as $count) {
            if ($count > 1) {
                return redirect()->back()->with(['error_message' => 'You can not select same product more than once!']);
            }
        }

        if ($request->product_ids) {
            foreach ($request->product_ids as $key => $product_id) {
                $barcode = Barcode::updateOrCreate(['product_id' => $product_id], ['barcode_number' => $request->barcode_numbers[$key]])->first();
            }
        }
        return redirect()->route('barcodes.index')->withMessage('Barcode Generate Successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Barcode $barcode)
    {
        $barcodeWithCompanyName = SoftwareSetting::where('title', 'barcode With Company Name')->where('options', 'yes')->count();
        if ($barcodeWithCompanyName) {
            $company = optional(optional($barcode->product)->company)->name ?? 'Company';
            return view('admin.product.barcodes.print-with-company-name', compact('barcode', 'company'));
        }
        return view('admin.product.barcodes.print', compact('barcode'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
