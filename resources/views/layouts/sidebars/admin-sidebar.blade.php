@extends("layouts.sidebars.layout-sidebar")

@php
    $CanReadCategory = userHasPermission("read_categories") || userHasPermission("all_categories") ;
    $CanCreateCategory = userHasPermission("create_categories") || userHasPermission("all_categories") ;
    $CanDDealingGeoLocal = userHasPermission("all_geolocales") ;
    $CanReadStaticPage = userHasPermission("read_static_pages") || userHasPermission("all_static_pages") ;
    $CanCreateStaticPage = userHasPermission("create_static_pages") || userHasPermission("all_static_pages") ;
    $CanReadCategoryTemplate = userHasPermission("read_email_template_categories") || userHasPermission("all_email_template_categories") ;
    $CanCreateCategoryTemplate = userHasPermission("create_email_template_categories") || userHasPermission("all_email_template_categories") ;
    $CanReadSocialMedia = userHasPermission("read_social_email_templates") || userHasPermission("all_social_email_templates") ;
    $CanCreateSocialMedia = userHasPermission("create_social_email_templates") || userHasPermission("all_social_email_templates") ;
    $CanReadUsers = userHasPermission("read_users") || userHasPermission("all_users") ;
    $CanCreateUsers = userHasPermission("create_users") || userHasPermission("all_users") ;
    $CanReadLanguage = userHasPermission("read_languages") || userHasPermission("all_languages") ;
    $CanCreateLanguage = userHasPermission("create_languages") || userHasPermission("all_languages") ;
    $CanReadEmailTemplate = userHasPermission("read_email_templates") || userHasPermission("all_email_templates") ;
    $CanCreateEmailTemplate = userHasPermission("create_email_templates") || userHasPermission("all_email_templates") ;
    $CanReadReport = true ;
    $CanReadControlSetting = userHasPermission("read_settings") || userHasPermission("all_settings") ;
    $CanReadSMTPSetting = userHasPermission("read_email_configurations") || userHasPermission("all_email_configurations") ;
    $CanCreateSMTPSetting = userHasPermission("create_email_configurations") || userHasPermission("all_email_configurations") ;
    $CanReadRoles = userHasPermission("read_roles") || userHasPermission("all_roles") ;
    $CanCreateRole = userHasPermission("create_roles") || userHasPermission("all_roles");
    $CanSendEmail = true ;
    $CanReadEmailSend = true ;
    $CanReadFilesLang = true ;
@endphp


@section("menu-sidebar")
    <ul class="nk-menu">
        <!-- start Operation -->
        @if($CanReadCategory || $CanCreateCategory || $CanCreateStaticPage ||
            $CanReadUsers || $CanCreateUsers || $CanReadLanguage || $CanReadStaticPage ||
            $CanCreateLanguage || $CanReadEmailTemplate || $CanCreateEmailTemplate)
            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">operation</h6>
            </li>
            @if($CanReadCategory || $CanCreateCategory)
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                        <span class="nk-menu-icon"><em class="icon ni ni-layers"></em></span>
                        <span class="nk-menu-text">Categories</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @if($CanReadCategory)
                            <li class="nk-menu-item">
                                <a href="{{ route("category.index") }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Category List</span>
                                </a>
                            </li>
                        @endif
                        @if($CanCreateCategory)
                            <li class="nk-menu-item">
                                <a href="{{ route("category.create") }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Category Create</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if($CanReadStaticPage || $CanCreateStaticPage)
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                        <span class="nk-menu-icon"><em class="icon ni ni-grid-alt-fill"></em></span>
                        <span class="nk-menu-text">Static Page</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @if($CanReadStaticPage)
                            <li class="nk-menu-item">
                                <a href="{{ route("static-page.index") }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Static Page List</span>
                                </a>
                            </li>
                        @endif
                        @if($CanCreateStaticPage)
                            <li class="nk-menu-item">
                                <a href="{{ route("static-page.create") }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Static Page Create</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if($CanReadUsers || $CanCreateUsers)
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                        <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                        <span class="nk-menu-text">Users</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @if($CanReadUsers)
                            <li class="nk-menu-item">
                                <a href="{{ route("user.index") }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Users List</span>
                                </a>
                            </li>
                        @endif
                        @if($CanCreateUsers)
                            <li class="nk-menu-item">
                                <a href="{{ route("user.create") }}" class="nk-menu-link">
                                    <span class="nk-menu-text">User Create</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if($CanReadLanguage || $CanCreateLanguage)
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                        <span class="nk-menu-icon"><em class="icon ni ni-flag-fill"></em></span>
                        <span class="nk-menu-text">Languages</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @if($CanReadLanguage)
                            <li class="nk-menu-item">
                                <a href="{{ route("language.index") }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Languages List</span>
                                </a>
                            </li>
                        @endif
                        @if($CanCreateLanguage)
                            <li class="nk-menu-item">
                                <a href="{{ route("language.create") }}" class="nk-menu-link">
                                    <span class="nk-menu-text">Language Create</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if($CanReadEmailTemplate || $CanCreateEmailTemplate)
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                        <span class="nk-menu-icon"><em class="icon ni ni-mail-fill"></em></span>
                        <span class="nk-menu-text">Email</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @if($CanReadCategoryTemplate || $CanCreateCategoryTemplate)
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-text">Category</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if($CanReadCategoryTemplate)
                                        <li class="nk-menu-item">
                                            <a href="{{ route("email-template-category.index") }}"
                                               class="nk-menu-link">
                                                <span class="nk-menu-text">Category List</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if($CanCreateCategoryTemplate)
                                        <li class="nk-menu-item">
                                            <a href="{{ route("email-template-category.create") }}"
                                               class="nk-menu-link">
                                                <span class="nk-menu-text">Category Create</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if($CanReadEmailTemplate || $CanCreateEmailTemplate)
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-text">Templates</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if($CanReadEmailTemplate)
                                        <li class="nk-menu-item">
                                            <a href="{{ route("email_template.view_live") }}" class="nk-menu-link">
                                                <span class="nk-menu-text">Template List</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if($CanCreateEmailTemplate)
                                        <li class="nk-menu-item">
                                            <a href="{{ route("email-template.create") }}" class="nk-menu-link">
                                                <span class="nk-menu-text">Template Create</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if($CanSendEmail || $CanReadEmailSend)
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-text">Inbox Emails</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if($CanSendEmail)
                                        <li class="nk-menu-item">
                                            <a href="{{ route("send.mails.show.page") }}" class="nk-menu-link">
                                                <span class="nk-menu-text">Send Email</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if($CanReadEmailSend)
                                        <li class="nk-menu-item">
                                            <a href="{{ route("email-sent-storage.index") }}" class="nk-menu-link">
                                                <span class="nk-menu-text">List Sent</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
        <!-- end Operation -->

        <!-- start Pages -->
        <li class="nk-menu-heading">
            <h6 class="overline-title text-primary-alt">pages</h6>
        </li>
        <li class="nk-menu-item">
            <a href="#" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-trend-up"></em></span>
                <span class="nk-menu-text">Statistic</span>
            </a>
        </li>
        <li class="nk-menu-item">
            <a href="{{ route("media.managershow.create") }}" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-grid-add-fill-c"></em></span>
                <span class="nk-menu-text">Media Manager</span>
            </a>
        </li>
        <li class="nk-menu-item has-sub">
            <a href="#" class="nk-menu-link nk-menu-toggle">
                <span class="nk-menu-icon"><em class="icon ni ni-file-text-fill"></em></span>
                <span class="nk-menu-text">Clauses</span>
            </a>
            <ul class="nk-menu-sub">
                <li class="nk-menu-item">
                    <a href="{{ route("policy") }}" class="nk-menu-link">
                        <span class="nk-menu-text">Privacy Policy</span>
                    </a>
                </li>
                <li class="nk-menu-item">
                    <a href="{{ route("term") }}" class="nk-menu-link">
                        <span class="nk-menu-text">Terms &amp; Conditions</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- end Pages -->

        <!-- start Report -->
        @if($CanReadReport)
            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">report</h6>
            </li>
            <li class="nk-menu-item">
                <a href="{{ route("report") }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-reports"></em></span>
                    <span class="nk-menu-text">Report Temp</span>
                </a>
            </li>
        @endif
        <!-- end Report -->

        <!-- start configurations -->
        <li class="nk-menu-heading">
            <h6 class="overline-title text-primary-alt">configurations</h6>
        </li>
        <li class="nk-menu-item has-sub">
            <a href="#" class="nk-menu-link nk-menu-toggle">
                <span class="nk-menu-icon"><em class="icon ni ni-opt-dot-fill"></em></span>
                <span class="nk-menu-text">System Update</span>
            </a>
            <ul class="nk-menu-sub">
                <li class="nk-menu-item">
                    <a href="{{ (Route::currentRouteName() == "setting.control.show") ? route("setting.control.show") : route("setting.show") }}"
                       class="nk-menu-link">
                        <span class="nk-menu-text">Global Setting</span>
                    </a>
                </li>
                @if($CanReadFilesLang)
                    <li class="nk-menu-item">
                        <a href="{{ route("lang.file.editorshow") }}" class="nk-menu-link">
                            <span class="nk-menu-text">Files Language</span>
                        </a>
                    </li>
                @endif
                @if($CanReadSMTPSetting || $CanCreateSMTPSetting)
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-text">SMTP</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @if($CanReadSMTPSetting)
                                <li class="nk-menu-item">
                                    <a href="{{ route("email-configuration.index") }}" class="nk-menu-link">
                                        <span class="nk-menu-text">SMTP List</span>
                                    </a>
                                </li>
                            @endif
                            @if($CanCreateSMTPSetting)
                                <li class="nk-menu-item">
                                    <a href="{{ route("email-configuration.create") }}" class="nk-menu-link">
                                        <span class="nk-menu-text">SMTP Create</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(true)
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-text">Social Media</span>
                        </a>
                        <ul class="nk-menu-sub">
                            @if($CanReadSocialMedia)
                                <li class="nk-menu-item">
                                    <a href="{{ route("social-media.index") }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Social Media List</span>
                                    </a>
                                </li>
                            @endif
                            @if($CanCreateSocialMedia)
                                <li class="nk-menu-item">
                                    <a href="{{ route("social-media.create") }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Social Media Create</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </li>
        @if($CanReadRoles || $CanCreateRole)
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon"><em class="icon ni ni-star-round"></em></span>
                    <span class="nk-menu-text">Roles</span>
                </a>
                <ul class="nk-menu-sub">
                    @if($CanReadRoles)
                        <li class="nk-menu-item">
                            <a href="{{ route("role.index") }}" class="nk-menu-link">
                                <span class="nk-menu-text">Role List</span>
                            </a>
                        </li>
                    @endif
                    @if($CanCreateRole)
                        <li class="nk-menu-item">
                            <a href="{{ route("role.create") }}" class="nk-menu-link">
                                <span class="nk-menu-text">Role Create</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if($CanDDealingGeoLocal)
            <li class="nk-menu-item">
                <a href="{{ route("geo-locale.main") }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-globe"></em></span>
                    <span class="nk-menu-text">Geo Local</span>
                </a>
            </li>
        @endif
        <!-- end configurations -->

        <!-- start my account -->
        <li class="nk-menu-heading">
            <h6 class="overline-title text-primary-alt">my account</h6>
        </li>
        <li class="nk-menu-item">
            @php
                $NotificationRoute = "" ;
                switch (Route::currentRouteName()) {
                    case "notification.audit.show" :
                        $NotificationRoute = "notification.audit.show" ;
                        break;
                    case "notification.print.table.show" :
                        $NotificationRoute = "notification.print.table.show" ;
                        break;
                    default :
                        $NotificationRoute = "notification.with-out-audit" ;
                        break;
                }
            @endphp
            <a href="{{ route($NotificationRoute) }}"
               class="nk-menu-link">
                <span class="nk-menu-icon">
                    <em class="icon ni ni-bell-fill"></em>
                </span>
                <span class="nk-menu-text">Notification</span>
            </a>
        </li>
        <li class="nk-menu-item">
            <a href="{{ route("account-setting") }}" class="nk-menu-link">
                <span class="nk-menu-icon"><em class="icon ni ni-setting-fill"></em></span>
                <span class="nk-menu-text">Setting</span>
            </a>
        </li>
        <!-- end my account -->
    </ul>
@endsection
