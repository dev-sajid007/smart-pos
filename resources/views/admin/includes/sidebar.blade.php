<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
{{--
    <div class="app-sidebar__user">
        <div>
            <div class="float-left">
                <i class="fa fa-user fa-3x"></i>
            </div>
            <div class="float-right pl-3">
                <p class="app-sidebar__user-name">{{ ucfirst(Auth::user()->name) }}</p>
                <p class="app-sidebar__user-designation">{{ Auth::user()->user_role->role['name'] }}</p>
            </div>
        </div>
    </div>
--}}

    <ul class="app-menu">
        <li>
            <a class="app-menu__item {{ request()->is('home') ? 'active':'' }}" href="{{ url('/home') }}">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Dashboard</span>
            </a>
        </li>
        <!-- Sale Module -->
        @if(hasPermission('sales.index') && $tests == '')
            <li class="treeview {{ request()->segment(1) == 'sales' || request()->is('product-return*') || request()->is('sale-return*') || request()->is('currier*') ? 'is-expanded':'' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-shopping-cart"></i>
                    <span class="app-menu__label">Sales</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">
                    @if(hasPermission('sales.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('sales/create') ? 'active' : '' }}" href="{{ route('sales.create') }}">
                                <i class="icon fa fa-plus"></i> New Sale
                            </a>
                        </li>

                        @if ($settings->where('title', 'Hole Sale Price')->where('options', 'yes')->count() > 0)
                            <li>
                                <a class="treeview-item {{ request()->is('sales/create') ? 'active' : '' }}" href="{{ route('sales.create') }}?type=hole-sale">
                                    <i class="icon fa fa-plus"></i> Hole/Retail Sale
                                </a>
                            </li>
                        @endif
                    @endif

                    @if(hasPermission('sales.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('sales') || request()->is('sales/invoice*')  ? 'active':'' }}" href="{{ route('sales.index') }}">
                                <i class="icon fa fa-list"></i> Sales List
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('collections.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('due-collections/create*') ? 'active':'' }}" href="{{ url('/due-collections/create?type=customer') }}">
                                <i class="icon fa fa-money"></i> Collection
                            </a>
                        </li>
                    @endif

                    <li>
                       <a class="treeview-item" href="{{ route('package-sales.create') }}">
                            <i class="icon fa fa-plus"> Package Sale</i>
                        </a>
                    </li>
                        {{-- @if(hasPermission('sale.invoices.return'))--}}
                        {{--     <li>--}}
                        {{--         <a class="treeview-item {{ request()->is('sale-return*') ? 'active':'' }}" href="{{ url('/sale-returns') }}">--}}
                        {{--             <i class="icon fa fa-recycle"></i> Sale Returns (Invoice)--}}
                        {{--         </a>--}}
                        {{--     </li>--}}
                        {{-- @endif--}}

                    @if(hasPermission('sale.products.return'))
                        <li>
                            <a class="treeview-item {{ request()->is('sale-return*') ? 'active':'' }}" href="{{ route('sale-returns.create') }}">
                                <i class="icon fa fa-recycle"></i> Sales Return
                            </a>
                        </li>
                    @endif

                    @if ($settings->where('title', 'Courier Service For Sale')->where('options', 'yes')->count() > 0 && hasPermission('sales.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('currier*') ? 'active':'' }}" href="{{ route('curriers.create') }}">
                                <i class="icon fa fa-plus"></i> Create Courier
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @else
        <li>
            <h4 class="text-center text-warning bg-danger"
                style="font-size: 15px;padding: 3px;margin: 0 auto;height: 25px;">
                Menu&nbsp;Disabled&nbsp;
            </h4>
        </li>
        @endif

        <!-- Purchase Module -->
        @if(hasPermission('purchases.index') && $tests == '')
            <li class="treeview {{ request()->segment(1)=='purchases' || request()->is('due-collections/create?type=supplier') ? 'is-expanded' : '' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-cart-plus"></i>
                    <span class="app-menu__label">Purchases</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">
                    @if(hasPermission('purchases.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('purchases/create') ? 'active' : '' }}" href="{{ route('purchases.create') }}">
                                <i class="icon fa fa-plus"></i> Supplier Wise Purchases
                            </a>
                        </li>

                        <li>
                            <a class="treeview-item {{ request()->is('individual-purchase') ? 'active' : '' }}" href="{{ route('individual.purchase') }}">
                                <i class="icon fa fa-plus"></i> Purchases Individual
                            </a>
                        </li>
                        {{-- <li>
                            <a class="treeview-item {{ request()->is('bulk-purchase-create') ? 'active' : '' }}" href="{{ route('bulk.purchase.create') }}">
                                <i class="icon fa fa-plus"></i> Bulk Purchases
                            </a>
                        </li> --}}
                    @endif
                    @if(hasPermission('purchases.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('purchases') || request()->is('purchases/invoice*') ? 'active' : '' }}" href="{{ route('purchases.index') }}">
                                <i class="icon fa fa-list"></i> Purchases List
                            </a>
                        </li>
                    @endif
                    @if(hasPermission('payments.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('due-collections/create?type=supplier') ? 'active' : '' }}" href="{{ url("/due-collections/create?type=supplier") }}">
                                <i class="icon fa fa-money"></i> Payment
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('purchases.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('purchase-returns/create?') ? 'active' : '' }}" href="{{ route('purchase-returns.create') }}">
                                <i class="icon fa fa-plus"></i> Purchase Return
                            </a>
                        </li>

                        <li>
                            <a class="treeview-item {{ request()->is('purchase-return-receives/create?') ? 'active' : '' }}" href="{{ route('purchase-return-receives.create') }}">
                                <i class="icon fa fa-plus"></i> Purchase Return Receive
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if($tests == '')
            <!-- account / General Account -->
            <li class="treeview {{ request()->segment(1) == 'account' ? 'is-expanded':'' }}">
                <a href="#" data-toggle="treeview" class="app-menu__item">
                    <i class="app-menu__icon fa fa-money"></i>
                    <span class="app-menu__label">General Accounts</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu pl-2">

                    <li style="width: 100%; background: #abc8b18a !important; color:black !important;" class="text-center">Setup</li>

                    @if(hasPermission('accounts.index'))
                        <li>
                            <a class="treeview-item {{ request()->segment(3) == 'accounts' ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                                <i class="icon fa fa-list"></i> Accounts
                            </a>
                        </li>
                    @endif

                        {{-- @if(hasPermission('accounts.index'))--}}
                        <li>
                            <a class="treeview-item {{ request()->segment(3) == 'gl-accounts' ? 'active' : '' }}" href="{{ route('gl-accounts.index') }}">
                                <i class="icon fa fa-list"></i> Gl Accounts
                            </a>
                        </li>
                        {{-- @endif--}}

                    @if(hasPermission('accounts-charts.index'))
                        <li>
                            <a class="treeview-item {{ request()->segment(3) == 'accounts-charts' ? 'active' : '' }}" href="{{ route('accounts-charts.index') }}">
                                <i class="icon fa fa-list"></i> Chart of Accounts
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('payments-method.index'))
                        <li>
                            <a class="treeview-item {{ request()->segment(3) == 'payments-method' ? 'active' : '' }}" href="{{ route('payments-method.index') }}">
                                <i class="icon fa fa-list"></i> Payment Methods
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('accounts.index'))
                        <li>
                            <a class="treeview-item {{ request()->segment(3) == 'assets' ? 'active' : '' }}" href="{{ route('assets.index') }}">
                                <i class="icon fa fa-list"></i> Company Asset
                            </a>
                        </li>
                        <li>
                            <a class="treeview-item {{ request()->segment(3) == 'liabilities' ? 'active' : '' }}" href="{{ route('liabilities.index') }}">
                                <i class="icon fa fa-list"></i> Company Liability
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('parties.index'))
                        <li>
                            <a href="{{ route('parties.index') }}" class="treeview-item {{ request()->segment(3) == 'parties' ? 'active':'' }}">
                                <i class="icon fa fa-list"></i> Parties
                            </a>
                        </li>
                    @endif

                    <li style="width: 100%; background: #abc8b18a !important; color:black !important;" class="text-center">Voucher</li>

                    @if(hasPermission('debit.vouchers.index'))
                        <li>
                            <a class="treeview-item {{ request()->segment(3) == 'payments' && request('type')=='debit' ? 'active' : '' }}" href="{{ route('payments.create', ['type'=>'debit'])}}">
                                <i class="icon fa fa-plus"></i>Debit Voucher (Expense)
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('credit.vouchers.index'))
                        <li>
                            <a class="treeview-item {{ request()->segment(3) == 'payments' && request('type')=='credit' ? 'active' : '' }}" href="{{ route('payments.create', ['type'=>'credit'])}}">
                                <i class="icon fa fa-plus"></i>Credit Voucher (Income)
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('accounts.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('asset-purchases') ? 'active':'' }}"
                            href="{{ route('asset-purchases.index') }}"><i class="icon fa fa-plus"></i> Add Asset</a>
                        </li>
                        <li>
                            <a class="treeview-item {{ request()->is('liabilities-purchases') ? 'active':'' }}"
                            href="{{ route('liability-purchases.index') }}"><i class="icon fa fa-plus"></i> Add Liability</a>
                        </li>
                    @endif

                    @if(hasPermission('fund-transfers.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('fund-transfers') ? 'active':'' }}"
                            href="{{route('fund-transfers.index')}}"><i class="icon fa fa-plus"></i> Fund Transfer</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Quotation Module hidden -->
            {{--        @if(hasPermission('quotations.index.index'))--}}
            {{--            <li class="treeview {{ request()->segment(1)=='quotations' ? 'is-expanded' : '' }}">--}}
            {{--                <a class="app-menu__item" href="#" data-toggle="treeview">--}}
            {{--                    <i class="app-menu__icon fa fa-list-ol"></i>--}}
            {{--                    <span class="app-menu__label">Quotations</span>--}}
            {{--                    <i class="treeview-indicator fa fa-angle-right"></i>--}}
            {{--                </a>--}}

            {{--                <ul class="treeview-menu">--}}
            {{--                    @if(hasPermission('quotations.index.index'))--}}
            {{--                        <li>--}}
            {{--                            <a class="treeview-item {{ request()->is('quotations') ? 'active':'' }}" href="{{route('quotations.index')}}">--}}
            {{--                                <i class="icon fa fa-plus"></i> Manage Quotations--}}
            {{--                            </a>--}}
            {{--                        </li>--}}
            {{--                    @endif--}}

            {{--                    @if(hasPermission('quotations.create'))--}}
            {{--                        <li>--}}
            {{--                            <a class="treeview-item {{ request()->is('quotations/create') ? 'active':'' }}" href="{{route('quotations.create')}}">--}}
            {{--                                <i class="icon fa fa-list"></i> Add Quotations--}}
            {{--                            </a>--}}
            {{--                        </li>--}}
            {{--                    @endif--}}
            {{--                </ul>--}}
            {{--            </li>--}}
            {{--        @endif--}}


        <!-- Product Module -->
        @if(hasPermission('products.index') && $tests == '')
            <li class="treeview {{ request()->segment(1) == 'product' ? 'is-expanded':'' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-product-hunt"></i>
                    <span class="app-menu__label">Product</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">

                    @if(hasPermission('products.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('product/unit*') ? 'active':'' }}" href="{{ route('units.index') }}">
                                <i class="icon fa fa-list"></i> Manage Units
                            </a>
                        </li>


                {{-- @if(hasPermission('product-groups.index'))--}}
                {{--     <li>--}}
                {{--         <a class="treeview-item {{ request()->is('product/product-group*') ? 'active' : '' }}" href="{{ route('product-groups.index') }}">--}}
                {{--             <i class="icon fa fa-list"></i> Manage Groups--}}
                {{--         </a>--}}
                {{--     </li>--}}
                {{-- @endif--}}

                        <li>
                            <a class="treeview-item {{ request()->is('product/brand*') ? 'active' : '' }}" href="{{ route('brands.index') }}">
                                <i class="icon fa fa-list"></i> Manage Brands
                            </a>
                        </li>

                        <li>
                            <a class="treeview-item {{ request()->is('product/categor*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                                <i class="icon fa fa-list"></i> Manage Categories
                            </a>
                        </li>


                        @if ($settings->where('title', 'Product Subcategory')->where('options', 'yes')->count() > 0)
                            <li>
                                <a class="treeview-item {{ request()->is('product/subcategor*') ? 'active' : '' }}" href="{{ route('subcategories.index') }}">
                                    <i class="icon fa fa-list"></i> Manage Sub Categories
                                </a>
                            </li>
                        @endif


                        @if ($settings->where('title', 'Product Generic Name')->where('options', 'yes')->count() > 0)
                            <li>
                                <a class="treeview-item {{ request()->is('product/generic*') ? 'active':'' }}" href="{{ route('generics.index') }}">
                                    <i class="icon fa fa-list"></i> Manage Generics
                                </a>
                            </li>
                        @endif

                        @if ($settings->where('title', 'Product Rak In Product')->where('options', 'yes')->count() > 0)
                            <li>
                                <a class="treeview-item {{ request()->is('product/product-rak*') ? 'active' : '' }}" href="{{ route('product-raks.index') }}">
                                    <i class="icon fa fa-list"></i> Manage Product Raks
                                </a>
                            </li>
                        @endif

                        @if ($settings->where('title', 'Warehouse Wise Product Stock')->where('options', 'yes')->count() > 0)
                            <li>
                                <a class="treeview-item {{ request()->is('product/warehouse*') ? 'active' : '' }}" href="{{ route('warehouses.index') }}">
                                    <i class="icon fa fa-list"></i> Manage Warehouse
                                </a>
                            </li>
                        @endif
                    @endif

                    @if(hasPermission('products.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('products') ? 'active':'' }}" href="{{ route('products.index') }}">
                                <i class="icon fa fa-list"></i> Product List
                            </a>
                        </li>
                        <li>
                            <a class="treeview-item {{ request()->is('serial-products') ? 'active':'' }}" href="{{ route('product.serial') }}">
                                <i class="icon fa fa-list"></i> Serial Product List
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('products.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('products/create') ? 'active':'' }}" href="{{ route('products.create') }}">
                                <i class="icon fa fa-plus"></i> Add Product
                            </a>
                        </li>

                        <li>
                            <a class="treeview-item {{ request()->is('product/upload-products') ? 'active' : '' }}" href="{{ route('upload-products.index') }}">
                                <i class="icon fa fa-upload"></i> Upload Product by CSV
                            </a>
                        </li>

                        <li>
                            <a class="treeview-item {{ request()->is('product/barcode*') ? 'active':'' }}" href="{{ route('barcodes.index') }}">
                                <i class="icon fa fa-list"></i> Manage Barcode
                            </a>
                        </li>
                        <li>
                            <a class="treeview-item {{ request()->is('product/product-prices-edi*') ? 'active':'' }}" href="{{ route('product.price.edit') }}">
                                <i class="icon fa fa-list"></i> Update Price
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('products.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('product-package*') ? 'active':'' }}" href="{{ route('product-packages.index') }}">
                                <i class="icon fa fa-list"></i> Product Package
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('product-damages.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('products') ? 'active':'' }}" href="{{ route('product-damages.index') }}">
                                <i class="icon fa fa-list"></i> Product Damages
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('products.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('customer-product-pricing') ? 'active' : '' }}" href="{{ route('customer-product-pricing.create') }}">
                                <i class="icon fa fa-list"></i> Customer Product Pricing
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if($tests == '')
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-truck"></i>
                <span class="app-menu__label">Marketers</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a class="py-2 treeview-item {{ request()->is('marketers/create') ? 'active':'' }}" href="{{ route('marketers.create') }}">
                        <i class="icon fa fa-list">
                            Add New
                        </i>
                    </a>
                </li>
                <li>
                    <a class="py-2 treeview-item {{ request()->is('marketers') ? 'active':'' }}" href="{{ route('marketers.index') }}">
                        <i class="icon fa fa-list">
                            All Lists
                        </i>
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ request()->segment(1) == 'stock-transfer' ? 'is-expanded':'' }}">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-truck"></i>
                <span class="app-menu__label">Stock Transfer</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>

            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item {{ request()->is('stock-transfer/warehouse-to-warehouses') ? 'active':'' }}" href="{{ route('warehouse-to-warehouses.index') }}">
                        <i class="icon fa fa-list"></i> Warehouse to Warehouse
                    </a>
                </li>
                @if ($hasMultipleCompany)
                    <li>
                        <a class="treeview-item {{ request()->is('stock-transfer/company-to-companies') ? 'active':'' }}" href="{{ route('company-to-companies.index') }}">
                            <i class="icon fa fa-list"></i> Company to Company
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        @endif
        <!-- Supplier Module -->
        @if(hasPermission('suppliers.index') && $tests == '')
            <li class="treeview {{ Request::segment(2) == 'suppliers' ? 'is-expanded':'' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-truck"></i>
                    <span class="app-menu__label">Suppliers</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">
                    @if(hasPermission('suppliers.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('people/suppliers') ? 'active':'' }}" href="{{ route('suppliers.index') }}">
                                <i class="icon fa fa-list"></i> Manage Supplier
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('suppliers.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('people/suppliers/create') ? 'active':'' }}" href="{{ route('suppliers.create') }}">
                                <i class="icon fa fa-plus"></i> Add Supplier
                            </a>
                        </li>


                        <li>
                            <a class="treeview-item {{ request()->is('people/upload-suppliers') ? 'active' : '' }}" href="{{ route('upload-suppliers.create') }}">
                                <i class="icon fa fa-upload"></i> Upload Supplier by CSV
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- Customer Module -->
        @if(hasPermission(['suppliers.index', 'customer-category.index']) && $tests == '')
            <li class="treeview {{ (request()->segment(2) == 'customers') || (request()->segment(1)=='customer-category') ? 'is-expanded':'' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-user-md"></i>
                    <span class="app-menu__label">Customers</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">
                    @if(hasPermission('customers.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('people/customers') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                                <i class="icon fa fa-list"></i> Manage Customer
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('customers.create'))
                        <li>
                            <a class="treeview-item {{ request()->is('people/customers/create') ? 'active' : '' }}" href="{{ route('customers.create') }}">
                                <i class="icon fa fa-plus"></i> Add Customer
                            </a>
                        </li>

                        <li>
                            <a class="treeview-item {{ request()->is('people/upload-customers') ? 'active' : '' }}" href="{{ route('upload-customers.create') }}">
                                <i class="icon fa fa-upload"></i> Upload Customer by CSV
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('customer-category.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('customer-category') ? 'active' : '' }}" href="{{ route('customer-category.index') }}">
                                <i class="icon fa fa-cogs"></i> Customer Category
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if($tests == '')
            <!-- Report Module -->
            <li class="treeview {{ request()->segment(1)=='reports' || request()->segment(1)=='stock'  || request()->segment(1)=='daily-report'  ? 'is-expanded':'' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-book"></i>
                    <span class="app-menu__label">Reports</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">

                    <li style="width: 100%; background: #abc8b18a !important; color:black !important;" class="text-center">Inventory Report</li>

                    @if(hasPermission('supplier.wise.stock'))
                        <li title="Supplier Wise Stock Report">
                            <a href="{{ route('supplier.wise.stock.reports') }}" class="treeview-item {{ request()->is('reports/supplier-wise-stock-reports') ? 'active' : '' }}">
                                <i class="app-menu__icon fa fa-database"></i> Supplier Wise Stock
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('product.wise.stock'))
                        <li class="">
                            <a href="{{ route('product.wise.stock') }}" class="treeview-item {{ request()->is('reports/product-stocks') ? 'active' : '' }}">
                                <i class="app-menu__icon fa fa-database"></i> Product Inventory Report
                            </a>
                        </li>
                    @endif
                    <li class="">
                        <a href="{{ route('top.sell.product') }}" class="treeview-item {{ request()->is('reports/topsellproduct') ? 'active' : '' }}">
                            <i class="app-menu__icon fa fa-database"></i> Top Sell product Report
                        </a>
                    </li>
                    @if ($settings->where('title', 'Product Expire Date')->where('options', 'yes')->count() > 0)
                        <li class="">
                            <a href="{{ route('expire.products') }}" class="treeview-item {{ request()->is('reports/expire-products') ? 'active' : '' }}">
                                <i class="app-menu__icon fa fa-database"></i> Expire Products
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('products.alert'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/product_alert') ? 'active':'' }}" href="{{ url('reports/product_alert') }}">
                                <i class="app-menu__icon fa fa-bell"></i> Product Alert
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('wastages.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/wastages') ? 'active':'' }}" href="{{ url('reports/wastages') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Wastage / Damage Report
                            </a>
                        </li>
                    @endif

                    {{-- @if(hasPermission('damages.report'))--}}
                    {{--     <li>--}}
                    {{--         <a class="treeview-item {{ request()->is('reports/damage-report') ? 'active':'' }}" href="{{ url('reports/damage-report') }}">--}}
                    {{--             <i class="app-menu__icon fa fa-shopping-cart"></i> Damage Report--}}
                    {{--         </a>--}}
                    {{--     </li>--}}
                    {{-- @endif--}}

                    @if(hasPermission('sales.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/sales') ? 'active' : '' }}" href="{{ url('reports/sales') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Sales Report
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('supplier.wise.sales.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/supplier-wise-sales-report') ? 'active' : '' }}" href="{{ url('reports/supplier-wise-sales-report') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Supplier Wise Sales
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="treeview-item {{ request()->is('reports/marketers-report') ? 'active' : '' }}"
                           href="{{ url('reports/marketers-report') }}">
                            <i class="app-menu__icon fa fa-shopping-cart"></i> Marketers Report
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item {{ request()->is('reports/marketers-ledger') ? 'active' : '' }}"
                           href="{{ url('reports/marketers-ledger') }}">
                            <i class="app-menu__icon fa fa-shopping-cart"></i> Marketers Ledger
                        </a>
                    </li>
                    @if(hasPermission('supplier.wise.discount'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/sale_discounts') ? 'active' : '' }}" href="{{ url('reports/sale_discounts') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Supplier Wise Discount
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('purchases.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/purchase') ? 'active' : '' }}" href="{{ url('reports/purchase') }}">
                                <i class="app-menu__icon fa fa-cart-plus"></i> Purchase Report
                            </a>
                        </li>
                    @endif



                    <li style="width: 100%; background: #abc8b18a !important; color:black !important;" class="text-center">Financial Report</li>

                    @if(hasPermission('cash.flow.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/cash-flow-reports') ? 'active' : '' }}"  href="{{ route('cash.flow.reports') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Cash Flow Report
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('daily.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/daily-report') ? 'active' : '' }}"  href="{{ url('reports/daily-report') }}">
                                <i class="app-menu__icon fa fa-calendar-check-o"></i> Daily Report
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('income.expense.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/debit_credit') ? 'active':'' }}" href="{{ url('reports/debit_credit') }}">
                                <i class="app-menu__icon fa fa-money"></i> Income Expense Report
                            </a>
                        </li>
                    @endif

                    {{--  @if(hasPermission('income.expense.report'))--}}
                        <li>
                            <a class="treeview-item {{ request()->is('reports/ga-parties-report') ? 'active':'' }}" title="General Account Party Report" href="{{ url('reports/ga-parties-report') }}">
                                <i class="app-menu__icon fa fa-money"></i> G A Party Reports
                            </a>
                        </li>
                    {{--         @endif--}}

                    @if(hasPermission('customers.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/customer-report') ? 'active' : '' }}" href="{{ route('customers.report') }}">
                                <i class="app-menu__icon fa fa-user-md"></i> Customer Report
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('suppliers.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/supplier-reports') ? 'active' : '' }}" href="{{ route('suppliers.report') }}">
                                <i class="app-menu__icon fa fa-truck"></i> Supplier Report
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('sale.returns.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/sale-returns') ? 'active' : '' }}" href="{{ url('reports/sale-returns') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Sales Return Report
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('tax.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/tax-report') ? 'active' : '' }}" href="{{ route('tax.report') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Tax Report
                            </a>
                        </li>
                    @endif

                    {{--                @if(hasPermission('tax.report'))--}}
                        <li>
                            <a class="treeview-item {{ request()->is('reports/product-wise-profit-report') ? 'active' : '' }}" href="{{ route('product.wise.profit.report') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Product Wise Profit
                            </a>
                        </li>
                    {{--                @endif--}}

                    @if(hasPermission('payable.due.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/payable_due_report') ? 'active' : '' }}" href="{{ url('/reports/payable-due-reports') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Payable Due Report
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('receivable.due.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/receivable-due-reports') ? 'active' : '' }}" href="{{ route('receivable.due.report') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Receivable Due Report
                            </a>
                        </li>
                    @endif

                    @if(hasPermission('profit.loss.report'))
                        <li>
                            <a class="treeview-item {{ request()->is('reports/profit_loss') ? 'active' : '' }}" href="{{ url('reports/profit_loss') }}">
                                <i class="app-menu__icon fa fa-shopping-cart"></i> Profit Loss Report
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <!-- Setting Module -->
            <li class="treeview {{ in_array(request()->segment(1), ['companies', 'settings']) ? 'is-expanded':'' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-cog"></i>
                    <span class="app-menu__label">Settings</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">

                    @if(hasPermission('companies.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('/backup') ? 'active':'' }}"
                               href="{{ url('/backup') }}" aria-label="Backup">
                                <i class="fa fa-upload fa-lg" aria-hidden="true">&nbsp;</i>
                                Backup
                            </a>
                        </li>
                        <li>
                            <a class="treeview-item {{ request()->is('companies') ? 'active':'' }}" href="{{ route('companies.edit', companyId()) }}">
                                <i class="icon fa fa-list"></i> Manage Companies
                            </a>
                        </li>
                    @endif

                {{-- @if(hasPermission('companies.index'))--}}
                        <li>
                            <a class="treeview-item {{ request()->is('software-settings') ? 'active':'' }}" href="{{ route('software-settings') }}">
                                <i class="icon fa fa-list"></i> Software Setting
                            </a>
                        </li>
                        @if (auth()->user()->email == 'admin@gmail.com')
                            <li>
                                <a class="treeview-item {{ request()->is('sms-api*') ? 'active':'' }}" href="{{ route('sms-apis.index') }}">
                                    <i class="icon fa fa-list"></i> Sms Apis
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="treeview-item {{ request()->is('refresh-application') ? 'active' : '' }}" href="{{ route('refresh.application') }}">
                                <i class="icon fa fa-refresh"></i> Junk Clean
                            </a>
                        </li>

                    {{--  @endif--}}
                </ul>
            </li>
            <!-- Send Sms Module -->
            <li class="treeview {{ request()->segment(1)=='sms*' ? 'is-expanded':'' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-envelope"></i>
                    <span class="app-menu__label">Send SMS</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">
                    @if(hasPermission('groups.index'))
                        <li hidden>
                            <a class="treeview-item {{ request()->is('sms/sms-setting*') ? 'active' : '' }}" href="{{ route('sms-settings.index') }}">
                                <i class="icon fa fa-gear"></i> SMS Setting
                            </a>
                        </li>
                    @endif
                    @if(hasPermission('groups.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('sms/send') ? 'active' : '' }}" href="{{ url('sms/send') }}">
                                <i class="icon fa fa-plus"></i> Send SMS
                            </a>
                        </li>
                    @endif
                    @if(hasPermission('groups.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('smsGroup') ? 'active' : '' }}" href="{{ route('smsGroup.index') }}">
                                <i class="icon fa fa-plus"></i> Group Lists
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <!-- User Module -->
            <li class="treeview {{ request()->segment(1)=='user' ? 'is-expanded' : '' }}">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-users"></i>
                    <span class="app-menu__label">Users</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>

                <ul class="treeview-menu">
                    <!-- User Section -->
                    @if (hasPermission('users.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('people/users') ? 'active':'' }}" href="{{ route('users.index') }}">
                                <i class="icon fa fa-list"></i> Manage Users
                            </a>
                        </li>
                    @endif

                    <!-- Role Section -->
                    @if (hasPermission('roles.index'))
                        <li>
                            <a class="treeview-item {{ request()->is('user/role*') ? 'active':'' }}" href="{{ route('roles.index') }}">
                                <i class="icon fa fa-list"></i> Manage Roles
                            </a>
                        </li>
                    @endif

                    <!-- Module Section -->
                    <li hidden>
                        <a class="treeview-item {{ request()->is('users/modules') ? 'active':'' }}" href="{{ route('modules.index') }}">
                            <i class="icon fa fa-list"></i>Manage Module
                        </a>
                    </li>

                    <!-- Permission Section -->
                    <li hidden>
                        <a class="treeview-item {{ request()->is('users/permissions') ? 'active':'' }}" href="{{ route('permissions.index') }}">
                            <i class="icon fa fa-list"></i> Manage Permission
                        </a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
</aside>
