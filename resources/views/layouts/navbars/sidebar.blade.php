<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="w-100-p b-r-10" src="../images/simplepost_logo_01.png"/>
                </div>
                <div class="logo-element">
                    <img alt="image" class="w-100-p b-r-10" style="padding:5px;" src="../images/simplepost_logo_01.png"/>
                </div>
            </li>
            {{-- <li class="{{ Route::is('dashboard') ? 'active' : '' }}">--}}
            {{-- <a href="{{ Route('dashboard') }}"><i class="fa fa-dashboard"></i>
            <span--}} {{--                        class="nav-label">Dashboard</span></a>--}} {{--            </li>--}}
            <li class="{{ Route::is('gettingStarted') ? 'active' : '' }}">
                <a href="{{ Route('gettingStarted') }}"><i class="fa fa-hand-o-right"></i> <span class="nav-label">Getting Started</span></a>
                </li>
               
                <li class="{{ Route::is('manualCampaigns') ? 'active' : '' }}">
                    <a href="{{ Route('manualCampaigns') }}"><i class="fa fa-bullseye"></i> <span class="nav-label">Manual Campaigns</span></a>
                </li>

            <li class="{{ Route::is('automated-retargeting.index') ? 'active' : '' }}">
                <a href="{{ Route('automated-retargeting.index') }}"><i class="fa fa-bullhorn"></i> <span class="nav-label">Automated Retargeting</span></a>
            </li>

            <li class="{{ Route::is('campaign-overview.index') ? 'active' : '' }}">
                <a href="{{ Route('campaign-overview.index') }}"><i class="fa fa-bullhorn"></i> <span class="nav-label">Campaign Overview</span></a>
            </li>

            <li class="{{ Route::is('analyticsDashboard') ? 'active' : '' }}">
                <a href="{{ Route('analyticsDashboard') }}"><i class="fa fa-line-chart"></i> <span class="nav-label">Analytics Dashboard</span></a>
            </li>
            <li class="{{ Route::is('account') ? 'active' : '' }}">
                    <a href="{{ Route('account') }}"><i class="fa fa-cog"></i> <span class="nav-label">Account</span></a>
                </li>

           {{-- <?php $shops = request()->user()->shops[0] ?>
            <li class="{{ Route::is('subscriptionPortal') ? 'active' : '' }}">
                <a href="{{ Route('subscriptionPortal', $shops->id) }}"><i class="fa fa-dollar"></i> <span class="nav-label">Subscriptions</span></a>
            </li> --}}

            {{-- <li class="{{ (Route::is('viewCampaigns', 'createCampaign', 'selectPostcard')) ? 'active' : '' }}">--}}
            {{-- <a href="{{ Route('viewCampaigns') }}"><i class="fa fa-bullhorn"></i> <span class="nav-label">Automated Retargeting</span>
            <span--}} {{--                        class="fa arrow"></span></a>--}} {{--                <ul class="nav nav-second-level collapse">--}} {{--                    <li class="{{ Route::is('viewCampaigns') ? 'active' : '' }}"><a href="{{ Route('viewCampaigns') }}">View--}}
            {{-- Campaigns</a></li>--}}
            {{-- </ul>--}}
            {{-- </li>--}}

        </ul>

    </div>
</nav>
