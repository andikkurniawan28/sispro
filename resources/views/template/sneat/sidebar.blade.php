<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                @if(isset($setup->company_logo) && $setup->company_logo)
                    <img src="{{ asset($setup->company_logo) }}" alt="Company Logo" style="height: 25px;">
                @endif
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ ucwords(str_replace('_', ' ', $setup->app_name)) }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    @php
        $permissions = collect($setup->permission)
            ->pluck('feature.route')
            ->toArray();
    @endphp

    <ul class="menu-inner py-1">

        <li class="menu-item @yield('dashboard-active')">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'dashboard')) }}</div>
            </a>
        </li>

        @if (in_array('setup.index', $permissions))
            <li class="menu-item @yield('setup-active')">
                <a href="{{ route('setup.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'setup')) }}</div>
                </a>
            </li>
        @endif

        @if (   in_array('department.index', $permissions) ||
                in_array('role.index', $permissions) ||
                in_array('user.index', $permissions) ||
                in_array('activity_log', $permissions))
            <li
                class="menu-item
                @yield('department-active')
                @yield('role-active')
                @yield('user-active')
                @yield('activity_log-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-door-open"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'access')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('department.index', $permissions))
                        <li class="menu-item @yield('department-active')">
                            <a href="{{ route('department.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'department')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('role.index', $permissions))
                        <li class="menu-item @yield('role-active')">
                            <a href="{{ route('role.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'role')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('user.index', $permissions))
                        <li class="menu-item @yield('user-active')">
                            <a href="{{ route('user.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'user')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('activity_log', $permissions))
                        <li class="menu-item @yield('activity_log-active')">
                            <a href="{{ route('activity_log') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'activity_log')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
                in_array('unit.index', $permissions) ||
                in_array('raw_material_category.index', $permissions) ||
                in_array('raw_material.index', $permissions) ||
                in_array('raw_material_warehouse.index', $permissions) ||
                in_array('product_category.index', $permissions) ||
                in_array('product_status.index', $permissions) ||
                in_array('product.index', $permissions) ||
                in_array('product_warehouse.index', $permissions) ||
                in_array('formula.index', $permissions) ||
                in_array('quality.index', $permissions)
            )
            <li
                class="menu-item
                @yield('unit-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-data"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'master')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('unit.index', $permissions))
                        <li class="menu-item @yield('unit-active')">
                            <a href="{{ route('unit.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'unit')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('raw_material_category.index', $permissions))
                        <li class="menu-item @yield('raw_material_category-active')">
                            <a href="{{ route('raw_material_category.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'raw_material_category')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('raw_material.index', $permissions))
                        <li class="menu-item @yield('raw_material-active')">
                            <a href="{{ route('raw_material.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'raw_material')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('raw_material_warehouse.index', $permissions))
                        <li class="menu-item @yield('raw_material_warehouse-active')">
                            <a href="{{ route('raw_material_warehouse.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'raw_material_warehouse')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('product_category.index', $permissions))
                        <li class="menu-item @yield('product_category-active')">
                            <a href="{{ route('product_category.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'product_category')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('product_status.index', $permissions))
                        <li class="menu-item @yield('product_status-active')">
                            <a href="{{ route('product_status.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'product_status')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('product.index', $permissions))
                        <li class="menu-item @yield('product-active')">
                            <a href="{{ route('product.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'product')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('product_warehouse.index', $permissions))
                        <li class="menu-item @yield('product_warehouse-active')">
                            <a href="{{ route('product_warehouse.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'product_warehouse')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('formula.index', $permissions))
                        <li class="menu-item @yield('formula-active')">
                            <a href="{{ route('formula.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'formula')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('quality.index', $permissions))
                        <li class="menu-item @yield('quality-active')">
                            <a href="{{ route('quality.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'quality')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (in_array('demand.index', $permissions) ||
            in_array('production.index', $permissions) ||
            in_array('production_quality.index', $permissions)
        )
            <li class="menu-item
                @yield('demand-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-transfer"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'transaction')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('demand.index', $permissions))
                        <li class="menu-item @yield('demand-active')">
                            <a href="{{ route('demand.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'demand')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('production.index', $permissions))
                        <li class="menu-item @yield('production-active')">
                            <a href="{{ route('production.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'production')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('production_quality.index', $permissions))
                        <li class="menu-item @yield('production_quality-active')">
                            <a href="{{ route('production_quality.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'production_quality')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        {{-- @if (in_array('company.index', $permissions) ||
            in_array('customer.index', $permissions))
            <li class="menu-item
                @yield('company-active')
                @yield('customer-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-smile"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'client')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('company.index', $permissions))
                        <li class="menu-item @yield('company-active')">
                            <a href="{{ route('company.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'company')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('customer.index', $permissions))
                        <li class="menu-item @yield('customer-active')">
                            <a href="{{ route('customer.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'customer')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (in_array('result_by_station.index', $permissions))
            <li class="menu-item @yield('result_by_station-active')">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-station"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'result_by_station')) }}</div>
                </a>
                <ul class="menu-sub">
                    @foreach ($setup->stations as $station)
                        <li class="menu-item @yield("result_by_station_id_{$station->id}-active")">
                            <a href="{{ route('result_by_station.index', $station->id) }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', $station->name)) }}
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        @if (in_array('result_by_material_category.index', $permissions))
            <li class="menu-item @yield('result_by_material_category-active')">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'result_by_material_category')) }}
                    </div>
                </a>
                <ul class="menu-sub">
                    @foreach ($setup->material_categories as $material_category)
                        <li class="menu-item @yield("result_by_material_category_id_{$material_category->id}-active")">
                            <a href="{{ route('result_by_material_category.index', $material_category->id) }}"
                                class="menu-link">
                                <div data-i18n="Without menu">
                                    {{ ucwords(str_replace('_', ' ', $material_category->name)) }}
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        <li class="menu-item @yield('report-active')">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-printer"></i>
                <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'report')) }}</div>
            </a>
            <ul class="menu-sub">
                @foreach ($setup->report_types as $report_type)
                        <li class="menu-item">
                            <a href="{{ route("report.index", $report_type->id) }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', $report_type->name)) }}
                                </div>
                            </a>
                        </li>
                @endforeach
            </ul>
        </li> --}}

    </ul>

</aside>
<!-- / Menu -->
