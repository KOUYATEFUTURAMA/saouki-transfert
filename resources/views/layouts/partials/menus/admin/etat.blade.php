<li class="menu-item menu-item-submenu {{request()->is('etat/*') || request()->is('parametre/fiche-customer/*') ? 'menu-item-here menu-item-open menu-item-active' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
    <a href="javascript:;" class="menu-link menu-toggle">
        <span class="svg-icon menu-icon">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:D:\xampp\htdocs\keenthemes\legacy\keen\theme\demo1\dist/../src/media/svg/icons\Home\Key.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        <path d="M4.85714286,1 L11.7364114,1 C12.0910962,1 12.4343066,1.12568431 12.7051108,1.35473959 L17.4686994,5.3839416 C17.8056532,5.66894833 18,6.08787823 18,6.52920201 L18,19.0833333 C18,20.8738751 17.9795521,21 16.1428571,21 L4.85714286,21 C3.02044787,21 3,20.8738751 3,19.0833333 L3,2.91666667 C3,1.12612489 3.02044787,1 4.85714286,1 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                        <path d="M6.85714286,3 L14.7364114,3 C15.0910962,3 15.4343066,3.12568431 15.7051108,3.35473959 L20.4686994,7.3839416 C20.8056532,7.66894833 21,8.08787823 21,8.52920201 L21,21.0833333 C21,22.8738751 20.9795521,23 19.1428571,23 L6.85714286,23 C5.02044787,23 5,22.8738751 5,21.0833333 L5,4.91666667 C5,3.12612489 5.02044787,3 6.85714286,3 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fill-rule="nonzero"/>
                    </g>
                </svg><!--end::Svg Icon-->
            </span>
        </span>
        <span class="menu-text">
            Etat
        </span>
        <i class="menu-arrow"></i>
    </a>
    <div class="menu-submenu">
        <i class="menu-arrow"></i>
        <ul class="menu-subnav">
            <li class="menu-item {{Route::currentRouteName() === 'etat.customers' || request()->is('parametre/fiche-customer/*') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.customers')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des clients</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.banks' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.banks')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des banques</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.partenairs' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.partenairs')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des partenaires</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.superviseurs' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.superviseurs')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des superviseurs</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.comptables' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.comptables')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des comptables</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.caissiers' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.caissiers')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des caissiers</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.agencies' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.agencies')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des agences</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.operations-partenairs' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.operations-partenairs')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Op&eacute;rations des partenaires</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.operations-bank' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.operations-bank')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Op&eacute;rations bancaires</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.send-money' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.send-money')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des envois</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.withdrawal-money' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.withdrawal-money')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des retraits</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.encaiss-decaiss' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.encaiss-decaiss')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des encaiss - d&eacute;caiss.</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.operation' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.operation')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des op&eacute;rations</span>
                </a>
            </li>
            <li class="menu-item {{Route::currentRouteName() === 'etat.caisse-closed' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                <a href="{{route('etat.caisse-closed')}}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Liste des clotures de caisse</span>
                </a>
            </li>
        </ul>
    </div>
</li>
