<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use App\CustomerUpload;
use App\Imports\ImportCustomerCSV;
use App\Services\SalesService;
use App\Traits\FileSaver;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class CustomerController extends Controller
{
    use FileSaver;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $customers = Customer::customers()
           ->when($request->filled('search'), function ($q) use ($request) {
               $q->where('name', 'LIKE', "%{$request->search}%")
               ->orWhere('customer_code', 'LIKE', "%{$request->search}%")
               ->orWhere('phone', 'LIKE', "%{$request->search}%")
               ->orWhere('address', 'LIKE', "%{$request->search}%")
               ->orWhere('email', 'LIKE', "%{$request->search}%");
           })
           ->orderBy('name');;
       if ($request->ajax()) {
           return $customers->where('name', 'LIKE', "%{$request->name}%")->take(15)->pluck('name', 'id');
       }

       return view('admin.people.customers.index', ['customers' => $customers->userLog()->paginate(30)]);
    }
    public function create()
    {
        $customer_categories = CustomerCategory::all();
       return view('admin.people.customers.create', compact('customer_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate customer data
        $request->validate([
            'name'  => 'required|max:160|unique:customers,name',
            'phone' => 'nullable|unique:customers',
            'email' => 'max:160|unique:customers|email|nullable',
            'customer_previous_due' => 'numeric'
        ]);
        try {
            DB::transaction(function () use($request) {
                // new customer create
                $customer = Customer::create($request->except('image'));

                // upload customer image
                $this->uploadFileWithResize($request->image, $customer, 'image', 'uploads/customers');

                // set customer as default
                $this->setDefaultCustomer($request, $customer->id);

                $customer->update(['current_balance' => $customer->customer_previous_due]);
            });
            return redirect()->route('customers.index')->withSuccess('Customer Created Successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = $this->get_customer($id);
        return view('admin.people.customers.show', ['customer'=>$customer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $customer_categories = CustomerCategory::all();

        return view('admin.people.customers.edit', [
            'customer' => $customer,
            'customer_categories' => $customer_categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $this->validate($request, [
            'name'  => 'required|max:160|unique:customers,name,'.$customer->id,
            'phone' => 'nullable|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|max:160|unique:customers,email,' . $customer->id,
        ]);

        $data = $request->only('name', 'phone', 'email', 'address', 'customer_category_id', 'customer_previous_due', 'due_limit', 'company_name');

        try {
            // update default customer
            $this->setDefaultCustomer($request, $customer->id);
            $data['default'] = $request->has('default') ? 1 : 0;

            // update customer info
            $customer->update($data);

            // upload customer image
            $this->uploadFileWithResize($request->image, $customer, 'image', 'uploads/customers');

            // update customer balance
            (new SalesService)->updateCustomerBalance($customer->id);

            return redirect()->route('customers.index')->withSuccess('Customer Updated Successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    public function setDefaultCustomer(Request $request, $id)
    {
        return $request->has('default')
            ?  Customer::where('id', '<>', $id)->update(['default' => false])
            : false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {
            if ($customer->id == 1){
                return redirect()->back()->with('error_message','This Customer use in another table you can not to delete it');
            }
            $customer->delete();
        } catch (\Exception $ex) {
            return redirect()->back()->with('error_message','This Customer use in another table you can not to delete it');
        }

        return \redirect('people/customers')->with(['message'=>'Customer Deleted Successfully!']);
    }

    public function get_customer($id){
        return Customer::findOrFail($id);
    }



    // upload customers
    public function uploadCustomers()
    {
        return view('admin.people.customers.uploads');
    }

    public function uploadCustomersStore(Request $request)
    {

        if ($request->file('customer_csv')) {
            try {
                $data = Excel::import(new ImportCustomerCSV(), $request->file('customer_csv'));
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return redirect()->route('customer-confirm-list.index')->with('message', 'File Uploaded Successfully')->withSuccess('File Uploaded Successfully');
        } else {
            return redirect()->back();
        }
    }

    public function customerConfirmList()
    {
        $customers = CustomerUpload::orderBy('id')->paginate(50);
        return view('admin.people.customers.confirm-list', compact('customers'));
    }

    public function customerConfirmListStore(Request $request)
    {
        $log            = $this->getUserLog();
        $customerIds    = [];
        $customers      = CustomerUpload::take(50)->get();

        foreach ($customers as $key => $customer) {
            $item = array_merge($log, [
                'customer_code'         => $customer_code = 'customer-' . (Customer::max('id') + 1),
                'name'                  => $customer->name,
                'email'                 => $customer->email,
                'phone'                 => $customer->phone,
                'address'               => $customer->address,
                'due_limit'             => $customer->due_limit ?? 999999999,
                'customer_category_id'  => $this->getCustomerCategoryId($customer->customer_category),
                'customer_previous_due' => $customer->opening_due ?? 0,
            ]);

            try {
                $newCustomer = Customer::create($item);
                $newCustomer->update(['current_balance' => $newCustomer->customer_previous_due]);

                array_push($customerIds, $customer->id);
            } catch (\Exception $ex) {}
        }
        if (count($customerIds) > 0) {
            CustomerUpload::destroy($customerIds);
            return redirect()->back()->withSucceess('Customer upload confirm');
        } else {
            return redirect()->back()->withErrors('No customer uploaded, something error');
        }
    }

    private function getCustomerCategoryId($category_name)
    {
        $category = CustomerCategory::where('name', $category_name)->orWhere('id', $category_name)->first();
        return $category_id = optional($category)->id ?? CustomerCategory::create([
                'company_id' => auth()->user()->fk_company_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'name' => $category_name
            ])->id;
    }

    private function getUserLog()
    {
        return [
            'fk_company_id' => auth()->user()->fk_company_id,
            'fk_created_by' => auth()->id(),
            'fk_updated_by' => auth()->id(),
        ];
    }

    public function confirmCustomerDelete($id)
    {
        try {
            CustomerUpload::destroy($id);
            return redirect()->back()->withSuccess('Customer Deleted Successfully!');
        }catch (\Exception $ex){
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    public function confirmCustomerDeleteAll()
    {
        try {
            CustomerUpload::truncate();
            return redirect()->back()->withSuccess('All Customer Deleted Successfully!');
        }catch (\Exception $ex){
            return redirect()->back()->withError($ex->getMessage());
        }
    }
}
