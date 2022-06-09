<li class="menu-item menu-item-submenu {{request()->is('parametre/*') ? 'menu-item-here menu-item-open menu-item-active' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
    <a href="javascript:;" class="menu-link menu-toggle">
        <span class="svg-icon menu-icon">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:D:\xampp\htdocs\keenthemes\legacy\keen\theme\demo1\dist/../src/media/svg/icons\Home\Key.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <rect fill="#000000" opacity="0.3" x="2" y="5" width="20" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="2" y="17" width="20" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="2" y="9" width="5" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="16" y="13" width="6" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="9" y="9" width="13" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="2" y="13" width="12" height="2" rx="1"/>
    </g>
                </svg><!--end::Svg Icon-->
            </span>
        </span>
        <span class="menu-text">
            Param&egrave;tre
        </span>
        <i class="menu-arrow"></i>
    </a>
    <div class="menu-submenu">
        <i class="menu-arrow"></i>
        <ul class="menu-subnav">
            <li class="menu-item {{Route::currentRouteName() === 'parametre.countries.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.countries.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Pays</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'parametre.cities.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.cities.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Ville</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'parametre.municipalities.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.municipalities.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Commune</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'parametre.agencies.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.agencies.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Agence</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'parametre.caisses.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.caisses.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Caisse</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'parametre.taux-transferts.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.taux-transferts.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Taux de transfert</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'parametre.customers.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.customers.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Client</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'parametre.partenairs.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.partenairs.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Partenaire</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'parametre.banks.index' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('parametre.banks.index')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Banque</span>
                </a>
            </li>
        </ul>
    </div>
</li>
