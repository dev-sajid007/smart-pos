<?php

namespace App\Providers;

use App\AccountReview;
use App\Company;
use App\ProductStock;
use App\Transaction;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Auth;
use App\Biller;
use App\Supplier;
use App\Customer;
use App\Purchase;
use App\Product;
use App\Quotation;
use App\Sale;
use App\Party;
use App\Category;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Schema::defaultStringLength(191);
        view()->composer('*', function($view){
            if(auth()->check()){
//                $user_id_session = Auth::user()->id;
//                $company_id_session = Auth::user()->fk_company_id;
//                $role_id_session = Auth::user()->fk_role_id;
//                $company_name_query = User::where('fk_company_id', $company_id_session)->with('company')->first();
//                $company_name_session = $company_name_query->company->name;
//                $view->with('user_id_session', $user_id_session);
//                $view->with('company_id_session', $company_id_session);
//                $view->with('role_id_session', $role_id_session);
//                $view->with('company_name_session', $company_name_session);
//
//                $biller_max_id = Biller::max('id')+1;
//                $biller_code_db = 'biller-'.$biller_max_id;
//                $view->with('biller_code_db', $biller_code_db);
//
//                $supplier_max_id = Supplier::max('id')+1;
//                $supplier_code_db = 'supplier-'.$supplier_max_id;
//                $view->with('supplier_code_db', $supplier_code_db);
//
//                $customer_max_id = Customer::max('id')+1;
//                $customer_code_db = 'customer-'.$customer_max_id;
//                $view->with('customer_code_db', $customer_code_db);
//
//                $purchase_maximum_id = Purchase::max('id')+1;
//                $purchase_code_db = 'purchase-'.$purchase_maximum_id;
//                $view->with('purchase_code_db', $purchase_code_db);
//
//                $product_max_id = Product::max('id')+1;
//                $product_code_db = 'product-'.$product_max_id;
//                $view->with('product_code_db', $product_code_db);
//
//                $category_max_id = Category::max('id')+1;
//                $category_code_db = 'category-'.$category_max_id;
//                $view->with('category_code_db', $category_code_db);
//
//                $quotation_max_id = Quotation::max('id')+1;
//                $quotation_reference_db = 'quotation-'.$quotation_max_id;
//                $view->with('quotation_reference_db', $quotation_reference_db);
//
//                $purchase_max_id = Purchase::max('id')+1;
//                $purchase_reference_db = 'purchase-'.$purchase_max_id;
//                $view->with('purchase_reference_db', $purchase_reference_db);
//
//                $sale_max_id = Sale::max('id')+1;
//                $sale_reference_db = 'sale-'.$sale_max_id;
//                $view->with('sale_reference_db', $sale_reference_db);
//
//                $party_max_id = Party::max('id')+1;
//                $party_code_db = 'party-'.$party_max_id;
//                $view->with('party_code_db', $party_code_db);
//
//                $account_linked = Company::findOrFail(Auth::user()->fk_company_id)->account_linked;
//                $view->with('account_linked', $account_linked);
//
//                $product_stocks = ProductStock::with('product_alert_quantity', 'stock_product')
//                    ->where('fk_company_id', companyId())
//                    ->where('available_quantity','<' , '20')
//                    ->get();
////                dd($product_stocks);
//                $view->with('product_stocks', $product_stocks);
            }
        });
       // View::share('company_id_se', '123');

    }
}
