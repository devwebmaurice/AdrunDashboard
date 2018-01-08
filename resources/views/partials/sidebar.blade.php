@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

            <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="fa fa-wrench"></i>
                    <span class="title">@lang('global.app_dashboard')</span>
                </a>
            </li>
            
            @can('users_manage')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="title">@lang('global.user-management.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ $request->segment(2) == 'permissions' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('global.permissions.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(2) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('global.roles.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(2) == 'users' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user"></i>
                            <span class="title">
                                @lang('global.users.title')
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Adrun Settings -->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    <span class="title">@lang('global.adrun-settings.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ $request->segment(3) == 'annonceur' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.annonceur.index') }}">
                            <i class="fa fa-address-book" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.annonceur.title')
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'editeur' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.editeur.index') }}">
                            <i class="fa fa-address-book-o" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.editeur.title')
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'format' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.format.index') }}">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.format.title')
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'ciblage' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.ciblage.index') }}">
                            <i class="fa fa-bullseye" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.ciblage.title')
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'pack' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.pack.index') }}">
                            <i class="fa fa-files-o" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.pack.title')
                            </span>
                        </a>
                    </li>
                    
                    
                </ul>
            </li>
            <!-- Adrun Settings -->
            @endcan
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>
                    <span class="title">@lang('global.availability.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                   

                    <li class="{{ $request->segment(3) == 'availability-overview' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-overview.index') }}">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.availability-overview.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(3) == 'availability-details' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-details.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.availability-details.title')
                            </span>
                        </a>
                    </li>
                 
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>
                    <span class="title">@lang('global.campaign.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                   

                    <li class="{{ $request->segment(3) == 'availability-overview' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-overview.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.pending-quote.title')
                                <span class="badge">500</span>
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'availability-details' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-details.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.expired-quote.title')
                                <span class="badge">48</span>
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'availability-details' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-details.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.verifie-campaign.title')
                                <span class="badge">53</span>
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'availability-details' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-details.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.ok-campaign.title')
                                <span class="badge">15</span>
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'availability-details' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-details.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.online-campaign.title')
                                <span class="badge">32</span>
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'availability-details' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-details.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.delivered-campaign.title')
                                <span class="badge">25</span>
                            </span>
                        </a>
                    </li>
                    
                    <li class="{{ $request->segment(3) == 'availability-details' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.availability-details.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.pending-reporting.title')
                                <span class="badge">80</span>
                            </span>
                        </a>
                    </li>
                 
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    <span class="title">@lang('global.balance-sheet.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ $request->segment(3) == 'permissions' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.balance-sheet-overviews.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(3) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.balance-sheet-details.title')
                            </span>
                        </a>
                    </li>
                 
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    <span class="title">@lang('global.billing.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ $request->segment(3) == 'permissions' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.billing-overviews.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(3) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.billing-details.title')
                            </span>
                        </a>
                    </li>
                 
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-o" aria-hidden="true"></i>
                    <span class="title">@lang('global.quotation.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ $request->segment(3) == 'permissions' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.quotation-overviews.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(3) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="title">
                                @lang('global.quotation-details.title')
                            </span>
                        </a>
                    </li>
                 
                </ul>
            </li>
            

            <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">Change password</span>
                </a>
            </li>

            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('global.app_logout')</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('global.logout')</button>
{!! Form::close() !!}
