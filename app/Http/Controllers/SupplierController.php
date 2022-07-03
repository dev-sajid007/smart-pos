<?php

namespace App\Http\Controllers;

use App\Imports\ImportSupplierCSV;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\SupplierUpload;
use App\Supplier;
use Excel;

class SupplierController extends Controller
{

    public function __construct(){
        parent::__construct();
    }


   
    public function index(Request $request)
    {
       if($request->ajax()){
           return Supplier::with('balance')
               ->companies()->where('name', 'LIKE', "%{$request->name}%")
               ->take(10)
               ->pluck('name', 'id');
       }

        $suppliers = Supplier::companies()->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('supplier_code', 'LIKE', "%{$request->search}%")
                    ->orWhere('phone', 'LIKE', "%{$request->search}%")
                    ->orWhere('address', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%");
            })->userLog()->paginate(30);



       return view('admin.people.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.people.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name'          => 'required|max:160',
            'company_name'  =>'',
            'phone'         => 'nullable|unique:suppliers',
            'email'         => 'nullable|max:160|unique:suppliers',
            'address'       => '',
            'opening_due'   => 'numeric'
        ]);

        DB::transaction(function ()use($request, $attributes) {
            $supplier = Supplier::create($attributes);
            $supplier->update(['current_balance' => $supplier->opening_due]);
        });

        return redirect()
            ->route('suppliers.index')
            ->withSuccess('Supplier Created Successfully!');
    }

  
    
    public function show(Supplier $supplier)
    {

        return view('admin.people.suppliers.show', [
            'supplier' => $supplier
        ]);
    }

   
    
    public function edit(Supplier $supplier)
    {
        return view('admin.people.suppliers.edit', compact('supplier'));
    }

 
    
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|max:160|unique:suppliers,name,' . $supplier->id,
            'phone' => 'nullable|unique:suppliers,phone,' . $supplier->id,
        ]);

        $supplier->update($request->except(['_token']));



        (new PurchaseService())->updateSupplierBalance($supplier->id);

        return \redirect('people/suppliers')->withSuccess('Supplier Updated Successfully!');
    }


    
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect('people/suppliers')->withSuccess('Supplier Deleted Successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error','This Supplier is use another table you can not delete it!');
        }
    }

    public function get_supplier($id)
    {
        return Supplier::findOrFail($id);
    }


    public function uploadSuppliers()
    {
        return view('admin.people.suppliers.uploads');
    }

    public function uploadSuppliersStore(Request $request)
    {
        $this->validate($request, [ 'supplier_csv' => 'required|mimes:csv,txt' ]);

        if ($request->file('supplier_csv')) {
            try {
                $data = Excel::import(new ImportSupplierCSV(), $request->file('supplier_csv'));
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return redirect('/people/supplier-confirm-list')->with('message', 'File Uploaded Successfully')->withSuccess('File Uploaded Successfully');
        } else {
            return redirect()->back();
        }

    }

    public function supplierConfirmList()
    {
        $suppliers = SupplierUpload::orderBy('id')->paginate(50);
        return view('admin.people.suppliers.confirm-list', compact('suppliers'));
    }

    public function supplierConfirmListStore(Request $request)
    {
        $log            = $this->getUserLog();
        $supplierIds    = [];
        $suppliers      = SupplierUpload::take(50)->get();

        foreach ($suppliers as $key => $supplier) {
            $item = array_merge($log, [
                'supplier_code'         => $supplier_code = 'supplier-' . (Supplier::max('id') + 1),
                'name'                  => $supplier->name,
                'email'                 => $supplier->email,
                'phone'                 => $supplier->phone,
                'address'               => $supplier->address,
                'opening_due' => $supplier->opening_due ?? 0,
            ]);

            try {
                $newSupplier = Supplier::create($item);
                $newSupplier->update(['current_balance' => $supplier->opening_due]);

                array_push($supplierIds, $supplier->id);
            } catch (\Exception $ex) {}
        }
        if (count($supplierIds) > 0) {
            SupplierUpload::destroy($supplierIds);
            return redirect()->back()->withMessage('Supplier upload confirm')->withSucceess('Supplier upload confirm');
        } else {
            return redirect()->back()->withErrors('No supplier uploaded, something error');
        }
    }

    private function getUserLog()
    {
        return [
            'fk_company_id' => auth()->user()->fk_company_id,
            'fk_created_by' => auth()->id(),
            'fk_updated_by' => auth()->id(),
        ];
    }



    public function confirmSupplierDelete($id)
    {
        try {
            SupplierUpload::destroy($id);
            return redirect()->back()->withSuccess('Supplier Deleted Successfully!');
        }catch (\Exception $ex){
            return redirect()->back()->withError($ex->getMessage());
        }
    }


    public function confirmSupplierDeleteAll()
    {
        try {
            SupplierUpload::truncate();
            return redirect()->back()->withSuccess('All Supplier Deleted Successfully!');
        }catch (\Exception $ex){
            return redirect()->back()->withError($ex->getMessage());
        }
    }


    public function getSupplier(Request $request)
    {
        $term = $request->term;
        $customers = Supplier::where('name', 'LIKE', "%{$term}%")
            ->orWhere('id', '=', $term)
            ->orWhere('email', 'LIKE', "%{$term}%")
            ->orWhere('phone', 'LIKE', "%{$term}%")
            ->take(10)
            ->get()
            ->map(function ($supplier){
                return [
                    'value'             => $supplier->name,
                    'id'                => $supplier->id,
                    'phone'             => $supplier->phone,
                    'supplier_code'     => $supplier->supplier_code,
                    'advanced_payment'  => $supplier->current_balance <= 0 ? $supplier->current_balance : 0,
                    'previous_due'      => $supplier->current_balance >= 0 ? $supplier->current_balance : 0,
                ];
            })->toArray();

        return response($customers, 200);
    }
}
