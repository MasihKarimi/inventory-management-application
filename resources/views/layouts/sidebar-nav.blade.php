<div class="page-sidebar navbar-collapse collapse">
    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
        data-slide-speed="200" style="padding-top: 20px">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li>
        <!-- END SIDEBAR TOGGLER BUTTON -->
        <li class="nav-item @if(Request::fullUrl() == route('home')) active @endif">
            <a href="{{ route('home') }}" class="nav-link">
                <i class="icon-globe"></i>
                <span class="title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item  @if(Request::segment(2) == 'customers') active open @endif">
            <a href="{{ route('customers-view') }}" class="nav-link">
                <i class="icon-users"></i>
                <span class="title">Manage Customers</span>
            </a>
        </li>
        <li class="nav-item  @if(Request::segment(2) == 'products') active @endif">
            <a href="{{ route('products-view') }}" class="nav-link">
                <i class="icon-handbag"></i>
                <span class="title">Manage Products</span>
            </a>
        </li>
        <li class="nav-item  @if(Request::segment(2) == 'purchases') active @endif">
            <a href="{{ route('purchases-view') }}" class="nav-link">
                <i class="icon-notebook"></i>
                <span class="title">Manage Purchases</span>
            </a>
        </li>
        <li class="nav-item @if(Request::segment(2) == 'invoices') active @endif">
            <a href="{{ route('invoices-view') }}" class="nav-link">
                <i class="icon-basket-loaded"></i>
                <span class="title">Manage Invoices/Sales</span>
            </a>
        </li>
        <li class="nav-item @if(Request::segment(2) == 'quotations') active @endif">
            <a href="{{ route('quotations-view') }}" class="nav-link">
                <i class="icon-list"></i>
                <span class="title">Manage Quotations</span>
            </a>
        </li>
        <li class="nav-item @if(Request::segment(2) == 'transactions') active @endif">
            <a href="{{ route('transactions-view') }}" class="nav-link">
                <i class="icon-calculator"></i>
                <span class="title">Manage Transactions</span>
            </a>
        </li>
        <li class="nav-item @if(Request::segment(2) == 'expenses') active @endif">
            <a href="{{ route('expenses-view') }}" class="nav-link">
                <i class="icon-wallet"></i>
                <span class="title">Manage Expenses</span>
            </a>
        </li>
        @if(Auth::user()->hasRole('Admin'))
            <li class="nav-item @if(Request::segment(2) == 'users') active @endif">
                <a href="{{ route('users-view') }}" class="nav-link">
                    <i class="icon-user"></i>
                    <span class="title">Manage Users</span>
                </a>
            </li>
        @endif
        <li class="nav-item @if(Request::segment(2) == 'reports') active open @endif">
            <a href="javascript:" class="nav-link nav-toggle">
                <i class="icon-book-open"></i>
                <span class="title">Reports</span>
                @if(Request::segment(2) == 'reports')
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                @else
                    <span class="arrow"></span>
                @endif
            </a>
            <ul class="sub-menu">
                <li class="nav-item @if(Request::segment(3) == 'expenses') active open @endif">
                    <a href="{{ route('reports-expenses-view') }}" class="nav-link ">
                        <span class="title">Expenses</span>
                    </a>
                </li>
                <li class="nav-item @if(Request::segment(3) == 'transactions') active open @endif">
                    <a href="{{ route('reports-transactions-view') }}" class="nav-link ">
                        <span class="title">Transactions</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
