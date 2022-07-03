<!-- Navbar-->
<header class="app-header">
    <a class="app-header__logo" href="{{ url('/home') }}">
        {{ config('app.project_title') }}
    </a>
    <!-- Sidebar toggle button-->
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <h3 class="app-nav-item pt-2" style="color:#fff">{{ auth()->user()->company->name }}</h3>

    @if (hasPermission('sales.create'))
        <h3 class="app-nav-item pt-2" style="color:#fff; margin-left: 20px !important;">
            <a class="px-1" href="{{ url('sales/create') }}" title="Create Sale" style="margin-left: 20px; font-size: 13px; text-decoration: none; color: #fff;  border: 1px solid #fff !important;">
                <i class="fa fa-shopping-cart" style="font-size: 12px"></i>
            </a>
        </h3>
    @endif
    @if (hasPermission('collections.create'))
        <h3 class="app-nav-item pt-2" style="color:#fff">
            <a class="px-1" href="{{ url('due-collections/create?type=customer') }}" title="Collection" style="margin-left: 5px; font-size: 13px; text-decoration: none; color: #fff;  border: 1px solid #fff !important;">
                <i class="fa fa-money" style="font-size: 12px"></i>
            </a>
        </h3>
    @endif
    @if(hasPermission('sale.invoices.return'))
        <h3 class="app-nav-item pt-2" style="color:#fff">
            <a class="px-1" href="{{ url('/sale-returns') }}" title="Sale Invoice Return" style="margin-left: 5px; font-size: 13px; text-decoration: none; color: #fff;  border: 1px solid #fff !important;">
                <i class="fa fa-cart-plus" style="font-size: 12px"></i>
            </a>
        </h3>
    @endif

    <h3 class="app-nav-item pt-2" style="color:#fff; width: 10px"></h3>

    @if(hasPermission('purchases.create'))
        <h3 class="app-nav-item pt-2" style="color:#fff">
            <a class="px-1" href="{{ url('purchases/create') }}" title="Create Purchase" style="margin-left: 5px; font-size: 13px; text-decoration: none; color: #fff;  border: 1px solid #fff !important;">
                <i class="fa fa-cart-plus" style="font-size: 12px"></i>
            </a>
        </h3>
    @endif
    @if(hasPermission('payments.create'))
        <h3 class="app-nav-item pt-2" style="color:#fff">
            <a class="px-1" href="{{ url('due-collections/create?type=supplier') }}" title="Payment" style="margin-left: 5px; font-size: 13px; text-decoration: none; color: #fff;  border: 1px solid #fff !important;">
                <i class="fa fa-money" style="font-size: 12px"></i>
            </a>
        </h3>
    @endif

    <h3 class="app-nav-item pt-2" style="color:#fff; width: 10px"></h3>

    @if(hasPermission('credit.vouchers.index'))
        <h3 class="app-nav-item pt-2" style="color:#fff">
            <a class="px-1" href="{{ url('account/voucher/payments/create?type=credit') }}" title="Credit Voucher (Income)" style="margin-left: 5px; font-size: 13px; text-decoration: none; color: #fff;  border: 1px solid #fff !important;">
                <i class="fa fa-plus" style="font-size: 12px"></i>
            </a>
        </h3>
    @endif
    @if(hasPermission('debit.vouchers.index'))
        <h3 class="app-nav-item pt-2" style="color:#fff">
            <a class="px-1" href="{{ url('account/voucher/payments/create?type=debit') }}" title="Debit Voucher (Expense)" style="margin-left: 5px; font-size: 13px; text-decoration: none; color: #fff;  border: 1px solid #fff !important;">
                <i class="fa fa-minus" style="font-size: 12px"></i>
            </a>
        </h3>
    @endif

{{--    <a href="{{ url('/home') }}" class="ml-5" style="text-decoration: none;"><p class="app-nav-item pt-2"--}}
{{--                                                                                style="color:#fff;margin-top: 6px;"><i--}}
{{--                    class="fa fa-backward"></i> Back to Dashboard</p></a>--}}


    <!-- Nav bar Right Menu-->
    <ul class="app-nav">
        <!--Notification Menu-->
        {{-- <li>
            <a class="app-nav__item" href="{{ url('/backup') }}" aria-label="Backup">
                <i class="fa fa-upload fa-lg" aria-hidden="true">&nbsp;</i>
                                <sup class="badge badge-danger not-badge"></sup>
            </a>
        </li> --}}
        <li class="dropdown">
            <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications">
                <i class="fa fa-bell-o fa-lg"></i>
                <sup class="badge badge-danger not-badge"></sup>
            </a>
            <ul class="app-notification dropdown-menu dropdown-menu-right">

                <li class="app-notification__title titleCount"></li>
                <div class="app-notification__content temp">
                </div>
                <li class="app-notification__footer">
                    <a href="{{ url('reports/product_alert') }}">See All and Details</a>
                </li>
            </ul>
        </li>


        <!-- User Menu-->
        <li class="dropdown">
            <a class="app-nav__item" style="text-decoration:none" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
                <span class="pr-2">{{ ucfirst(Auth::user()->name) }}</span>
                <i class="fa fa-user fa-lg"></i>
            </a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li>
                    <a class="dropdown-item" title="Edit"  href="{{ route('users.edit', Auth::user()->id) }}">
                        <i class="fa fa-edit fa-lg bg-success"></i> My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out fa-lg "></i> Logout
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
        </li>
    </ul>
</header>
