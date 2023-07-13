<!-- SIDE MENU BAR -->
<aside class="app-sidebar">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{url('/')}}">
            <img src="{{URL::asset('img/brand/logo.png')}}" class="header-brand-img desktop-lgo" alt="Admintro logo">
            <img src="{{URL::asset('img/brand/favicon.png')}}" class="header-brand-img mobile-logo" alt="Admintro logo">
        </a>
    </div>
    <ul class="side-menu app-sidebar3">
        @role('accounts')

        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.dashboard') }}">
                <span class="side-menu__icon fa-solid fa-chart-tree-map"></span>
                <span class="side-menu__label">{{ __('Admin Dashboard') }}</span>
            </a>
        </li>


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa-solid fa-user-shield"></span>
                <span class="side-menu__label">{{ __('User Management') }}</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.user.dashboard') }}" class="slide-item">{{ __('User Dashboard') }}</a></li>
                <li><a href="{{ route('admin.user.list') }}" class="slide-item">{{ __('User List') }}</a></li>
                <li><a href="{{ route('admin.user.activity') }}" class="slide-item">{{ __('Activity Monitoring') }}</a>
                <li><a href="{{ route('admin.user.permission.request') }}"
                       class="slide-item">{{ __('User Permission') }}
                        @if (App\Models\ProjectApplication::WhereNull('read_at')->count())
                            <span
                                class="badge badge-warning ml-2">{{ App\Models\ProjectApplication::WhereNull('read_at')->count() }}</span>
                        @endif
                    </a></li>
                </li>
            </ul>
        </li>


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa-solid fa-sack-dollar"></span>
                <span class="side-menu__label">{{ __('Finance Management') }}</span>
                @if (auth()->user()->unreadNotifications->where('type', 'App\Notifications\PayoutRequestNotification')->count())
                    <span
                        class="badge badge-warning">{{ auth()->user()->unreadNotifications->where('type', 'App\Notifications\PayoutRequestNotification')->count() }}</span>
                @else
                    <i class="angle fa fa-angle-right"></i>
                @endif
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.finance.dashboard') }}"
                       class="slide-item">{{ __('Finance Dashboard') }}</a></li>
                <li><a href="{{ route('admin.finance.transactions') }}" class="slide-item">{{ __('Transactions') }}</a>
                </li>
                {{--                <li><a href="{{ route('admin.finance.plans') }}" class="slide-item">{{ __('Subscription Plans') }}</a>--}}
                {{--                </li>--}}
                {{--                <li><a href="{{ route('admin.finance.prepaid') }}" class="slide-item">{{ __('Prepaid Plans') }}</a></li>--}}
                {{--                <li><a href="{{ route('admin.finance.subscriptions') }}" class="slide-item">{{ __('Subscribers') }}</a>--}}
                {{--                </li>--}}
                <li><a href="{{ route('admin.referral.settings') }}" class="slide-item">{{ __('Referral System') }}</a>
                </li>
                <li><a href="{{ route('admin.referral.payouts') }}" class="slide-item">{{ __('Referral Payouts') }}
                        @if ((auth()->user()->unreadNotifications->where('type', 'App\Notifications\PayoutRequestNotification')->count()))
                            <span
                                class="badge badge-warning ml-5">{{ auth()->user()->unreadNotifications->where('type', 'App\Notifications\PayoutRequestNotification')->count() }}</span>
                        @endif
                    </a>
                </li>
                <li><a href="{{ route('admin.settings.invoice') }}" class="slide-item">{{ __('Invoice Settings') }}</a>
                </li>
                <li><a href="{{ route('admin.finance.settings') }}" class="slide-item">{{ __('Payment Settings') }}</a>
                </li>
                <li><a href="{{ route('admin.price') }}" class="slide-item">{{ __('Price') }}</a></li>
                <li><a href="{{ route('admin.user.invoices') }}" class="slide-item">{{ __('Invoices') }}</a></li>
            </ul>
        </li>
        @endrole

        @role('admin')
        <li class="side-item side-item-category mt-4">{{ __('Admin Panel') }}</li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.dashboard') }}">
                <span class="side-menu__icon fa-solid fa-chart-tree-map"></span>
                <span class="side-menu__label">{{ __('Admin Dashboard') }}</span>
            </a>
        </li>
        {{--                <li class="slide">--}}
        {{--                    <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">--}}
        {{--                        <span class="side-menu__icon fa-solid fa-boxes-packing"></span>--}}
        {{--                        <span class="side-menu__label">{{ __('Studio Management') }}</span><i--}}
        {{--                            class="angle fa fa-angle-right"></i>--}}
        {{--                    </a>--}}
        {{--                    <ul class="slide-menu">--}}
        {{--                        <li><a href="{{ route('admin.studio.dashboard') }}" class="slide-item">{{ __('Studio Dashboard') }}</a>--}}
        {{--                        </li>--}}
        {{--                        <li><a href="{{ route('admin.voiceover.results') }}"--}}
        {{--                               class="slide-item">{{ __('Voiceover Results') }}</a></li>--}}
        {{--                        <li><a href="{{ route('admin.transcribe.results') }}"--}}
        {{--                               class="slide-item">{{ __('Transcribe Results') }}</a></li>--}}
        {{--                        <li><a href="{{ route('admin.voiceover.voices') }}"--}}
        {{--                               class="slide-item">{{ __('Voices Customization') }}</a></li>--}}
        {{--                        <li><a href="{{ route('admin.transcribe.languages') }}"--}}
        {{--                               class="slide-item">{{ __('Languages Customization') }}</a></li>--}}
        {{--                        <li><a href="{{ route('admin.sound.studio') }}" class="slide-item">{{ __('Sound Studio Settings') }}</a>--}}
        {{--                        </li>--}}
        {{--                        <li><a href="{{ route('admin.voiceover.settings') }}"--}}
        {{--                               class="slide-item">{{ __('Voiceover Studio Settings') }}</a></li>--}}
        {{--                        <li><a href="{{ route('admin.transcribe.settings') }}"--}}
        {{--                               class="slide-item">{{ __('Transcribe Studio Settings') }}</a></li>--}}
        {{--                    </ul>--}}
        {{--                </li>--}}
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa-solid fa-user-shield"></span>
                <span class="side-menu__label">{{ __('User Management') }}</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.user.dashboard') }}" class="slide-item">{{ __('User Dashboard') }}</a></li>
                <li><a href="{{ route('admin.user.list') }}" class="slide-item">{{ __('User List') }}</a></li>
                <li><a href="{{ route('admin.user.activity') }}" class="slide-item">{{ __('Activity Monitoring') }}</a>
                <li><a href="{{ route('admin.user.permission.request') }}"
                       class="slide-item">{{ __('User Permission') }}
                        @if (App\Models\ProjectApplication::WhereNull('read_at')->count())
                            <span
                                class="badge badge-warning ml-2">{{ App\Models\ProjectApplication::WhereNull('read_at')->count() }}</span>
                        @endif
                    </a></li>
                </li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa-solid fa-user-shield"></span>
                <span class="side-menu__label">{{ __('Mailing System') }}</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.mailing.system.index') }}"
                       class="slide-item">{{ __('User List') }}</a></li>
                <li><a href="{{ route('admin.mailing.system.campaign.index') }}"
                       class="slide-item">{{ __('Campaign') }}</a></li>
                <li><a href="{{ route('admin.mailing.system.unsubscribe.list') }}"
                       class="slide-item">{{ __('UnSubscribe User') }}</a></li>
                <li><a href="{{ route('admin.mailing.system.report') }}"
                       class="slide-item">{{ __('Campaign Report') }}</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa-solid fa-sack-dollar"></span>
                <span class="side-menu__label">{{ __('Finance Management') }}</span>
                @if (auth()->user()->unreadNotifications->where('type', 'App\Notifications\PayoutRequestNotification')->count())
                    <span
                        class="badge badge-warning">{{ auth()->user()->unreadNotifications->where('type', 'App\Notifications\PayoutRequestNotification')->count() }}</span>
                @else
                    <i class="angle fa fa-angle-right"></i>
                @endif
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.finance.dashboard') }}"
                       class="slide-item">{{ __('Finance Dashboard') }}</a></li>
                <li><a href="{{ route('admin.finance.transactions') }}" class="slide-item">{{ __('Transactions') }}</a>
                </li>
                {{--                <li><a href="{{ route('admin.finance.plans') }}" class="slide-item">{{ __('Subscription Plans') }}</a>--}}
                {{--                </li>--}}
                {{--                <li><a href="{{ route('admin.finance.prepaid') }}" class="slide-item">{{ __('Prepaid Plans') }}</a></li>--}}
                {{--                <li><a href="{{ route('admin.finance.subscriptions') }}" class="slide-item">{{ __('Subscribers') }}</a>--}}
                {{--                </li>--}}
                <li><a href="{{ route('admin.referral.settings') }}" class="slide-item">{{ __('Referral System') }}</a>
                </li>
                <li><a href="{{ route('admin.referral.payouts') }}" class="slide-item">{{ __('Referral Payouts') }}
                        @if ((auth()->user()->unreadNotifications->where('type', 'App\Notifications\PayoutRequestNotification')->count()))
                            <span
                                class="badge badge-warning ml-5">{{ auth()->user()->unreadNotifications->where('type', 'App\Notifications\PayoutRequestNotification')->count() }}</span>
                        @endif
                    </a>
                </li>
                <li><a href="{{ route('admin.settings.invoice') }}" class="slide-item">{{ __('Invoice Settings') }}</a>
                </li>
                <li><a href="{{ route('admin.finance.settings') }}" class="slide-item">{{ __('Payment Settings') }}</a>
                </li>
                <li><a href="{{ route('admin.price') }}" class="slide-item">{{ __('Price') }}</a></li>
                <li><a href="{{ route('admin.user.invoices') }}" class="slide-item">{{ __('Invoices') }}</a></li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.support') }}">
                <span class="side-menu__icon fa-solid fa-message-question"></span>
                <span class="side-menu__label">{{ __('Support Requests') }}</span>
                @if (App\Models\Support::where('status', 'Open')->count())
                    <span class="badge badge-warning">{{ App\Models\Support::where('status', 'Open')->count() }}</span>
                @endif
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa-solid fa-message-exclamation"></span>
                <span class="side-menu__label">{{ __('Notifications') }}</span>
                @if (auth()->user()->unreadNotifications->where('type', '<>', 'App\Notifications\GeneralNotification')->count())
                    <span class="badge badge-warning" id="total-notifications-a"></span>
                @else
                    <i class="angle fa fa-angle-right"></i>
                @endif
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.notifications') }}" class="slide-item">{{ __('Mass Notifications') }}</a>
                </li>
                <li><a href="{{ route('admin.notifications.system') }}"
                       class="slide-item">{{ __('System Notifications') }}
                        @if ((auth()->user()->unreadNotifications->where('type', '<>', 'App\Notifications\GeneralNotification')->count()))
                            <span class="badge badge-warning ml-5" id="total-notifications-b"></span>
                        @endif
                    </a>
                </li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa fa-globe"></span>
                <span class="side-menu__label">{{ __('Frontend Management') }}</span><i
                    class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.settings.frontend') }}"
                       class="slide-item">{{ __('Frontend Settings') }}</a></li>
                <li><a href="{{ route('admin.settings.appearance') }}" class="slide-item">{{ __('SEO & Logos') }}</a>
                </li>
                <li><a href="{{ route('admin.settings.blog') }}" class="slide-item">{{ __('Blogs Manager') }}</a></li>
                <li><a href="{{ route('admin.settings.faq') }}" class="slide-item">{{ __('FAQs Manager') }}</a></li>
                <li><a href="{{ route('admin.settings.usecase') }}" class="slide-item">{{ __('Use Cases Manager') }}</a>
                </li>
                <li><a href="{{ route('admin.settings.review') }}" class="slide-item">{{ __('Reviews Manager') }}</a>
                </li>
                <li><a href="{{ route('admin.settings.terms') }}" class="slide-item">{{ __('Pages Manager') }}</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa fa-sliders"></span>
                <span class="side-menu__label">{{ __('General Settings') }}</span><i
                    class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.settings.global') }}" class="slide-item">{{ __('Global Settings') }}</a>
                </li>
                <li><a href="{{ route('admin.settings.oauth') }}" class="slide-item">{{ __('Auth Settings') }}</a></li>
                <li><a href="{{ route('admin.settings.registration') }}"
                       class="slide-item">{{ __('Registration Settings') }}</a></li>
                <li><a href="{{ route('admin.settings.smtp') }}" class="slide-item">{{ __('SMTP Settings') }}</a></li>
                <li><a href="{{ route('admin.settings.backup') }}" class="slide-item">{{ __('Database Backup') }}</a>
                </li>
                <li><a href="{{ route('admin.settings.activation') }}" class="slide-item">{{ __('Activation') }}</a>
                </li>
                <li><a href="{{ route('admin.settings.upgrade') }}" class="slide-item">{{ __('Upgrade Software') }}</a>
                </li>
            </ul>
        </li>
        @endrole
        @role('admin')
        <li class="side-item side-item-category">{{ __('My Account') }}</li>
        @endrole
        @role('user|subscriber')
        <li class="side-item side-item-category mt-4">{{ __('My Account') }}</li>
        @endrole
        @role('admin|user|subscriber')
        <li class="slide" id="dashboard-menu-item">
            <a class="side-menu__item" href="{{ route('user.dashboard') }}">
                <span class="side-menu__icon lead-3 fa-solid fa-chart-tree-map"></span>
                <span class="side-menu__label">{{ __('My Dashboard') }}</span>
            </a>
        </li>

        <li class="slide" id="accounts">
            <a class="side-menu__item" href="{{ route('user.referral') }}">
                <span class="side-menu__icon lead-3 fa-solid fa-badge-dollar"></span>
                <span class="side-menu__label">{{ __('Account') }}</span></a>
        </li>
        @endrole
        @role('user')
        <li class="slide" id="teams">
            <a class="side-menu__item" href="{{ route('user.referral.referrals') }}">
                <span class="side-menu__icon lead-3 fas fa-users"></span>
                <span class="side-menu__label">{{ __('Team') }}</span>
            </a>
        </li>
        @endrole
        @role('quality_assurance')
        @if (config('settings.user_support') == 'enabled')
            <li class="slide" id="support-requests">
                <a class="side-menu__item" href="{{ route('user.support') }}">
                    <span class="side-menu__icon fa-solid fa-messages-question"></span>
                    <span class="side-menu__label">{{ __('Support Requests') }}</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                    <span class="side-menu__icon fa-solid fa-message-exclamation"></span>
                    <span class="side-menu__label">{{ __('Notifications') }}</span>
                    @if (auth()->user()->unreadNotifications->where('type', '<>', 'App\Notifications\GeneralNotification')->count())
                        <span class="badge badge-warning" id="total-notifications-a"></span>
                    @else
                        <i class="angle fa fa-angle-right"></i>
                    @endif
                </a>
                <ul class="slide-menu">
                    <li><a href="{{ route('admin.notifications') }}"
                           class="slide-item">{{ __('Mass Notifications') }}</a>
                    </li>
                    <li><a href="{{ route('user.notifications') }}" class="slide-item">
                            <span class="side-menu__label">{{ __('Notifications') }}</span>

                            @if (auth()->user()->unreadNotifications->where('type', 'App\Notifications\GeneralNotification')->count())
                                <span
                                    class="badge badge-warning">{{ auth()->user()->unreadNotifications->where('type', 'App\Notifications\GeneralNotification')->count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        @endrole
        @role('user|subscriber')
        @if (config('settings.user_support') == 'enabled')
            <li class="slide" id="support-requests">
                <a class="side-menu__item" href="{{ route('user.support') }}">
                    <span class="side-menu__icon fa-solid fa-messages-question"></span>
                    <span class="side-menu__label">{{ __('Support Requests') }}</span>
                </a>
            </li>
        @endif

        @if (config('settings.user_notification') == 'enabled')
            <li class="slide" id="notifications">
                <a class="side-menu__item" href="{{ route('user.notifications') }}">
                    <span class="side-menu__icon fa-solid fa-message-exclamation"></span>
                    <span class="side-menu__label">{{ __('Notifications') }}</span>
                    @if (auth()->user()->unreadNotifications->where('type', 'App\Notifications\GeneralNotification')->count())
                        <span
                            class="badge badge-warning">{{ auth()->user()->unreadNotifications->where('type', 'App\Notifications\GeneralNotification')->count() }}</span>
                    @endif
                </a>
            </li>
        @endif
        @endrole
        @role('admin')
        <li class="slide">
            <a class="side-menu__item" href="{{ route('user.support') }}">
                <span class="side-menu__icon fa-solid fa-messages-question"></span>
                <span class="side-menu__label">{{ __('Support Requests') }}</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('user.notifications') }}">
                <span class="side-menu__icon fa-solid fa-message-exclamation"></span>
                <span class="side-menu__label">{{ __('Notifications') }}</span>
                @if (auth()->user()->unreadNotifications->where('type', 'App\Notifications\GeneralNotification')->count())
                    <span
                        class="badge badge-warning">{{ auth()->user()->unreadNotifications->where('type', 'App\Notifications\GeneralNotification')->count() }}</span>
                @endif
            </a>
        </li>
        @endrole
        @role('user|admin')
        <li class="side-item side-item-category">{{ __('Projects Section') }}</li>
        @endrole

        @role('user')
        <li class="slide" id="projects">
            <a class="side-menu__item" href="{{ route('user.project.index') }}">
                <span class="side-menu__icon lead-3 fa-solid fa-boxes-packing"></span>
                <span class="side-menu__label">{{ __('Projects') }}</span></a>
        </li>
        @endrole

        @php
            $permissions = auth()->user()->project_permission ? json_decode(auth()->user()->project_permission, true) : [];

            $projects = \App\Models\Project::where('status', 'active')->latest()->get();
        @endphp

        @if ($permissions && config('stt.enable.aws_live') == 'on')
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                    <span class="side-menu__icon lead-3 fa fa-clipboard"></span>
                    <span class="side-menu__label">{{ __('My Projects') }}</span><i
                        class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    @foreach ($projects as $project)
                        @if (array_key_exists($project->id, $permissions ) && $permissions[$project->id] === true)
                            <li>
                                <a href="{{ route('user.project.apply.project',['id' => $project->id]) }}"
                                   class="slide-item">{{ ucfirst($project->name) }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endif
        {{--        @if($permissions)--}}
        {{--            @if ( config('stt.enable.aws_live')  == 'on')--}}
        {{--                <li class="slide">--}}
        {{--                    <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">--}}
        {{--                        <span class="side-menu__icon lead-3 fa-solid fa-microphone-lines"></span>--}}
        {{--                        <span class="side-menu__label">{{ __('Assign Projects') }}</span><i--}}
        {{--                            class="angle fa fa-angle-right"></i></a>--}}
        {{--                    <ul class="slide-menu">--}}
        {{--                        @if (array_key_exists('image', $permissions) && $permissions['image'] === true)--}}
        {{--                            <li>--}}
        {{--                                <a href="{{ route('user.transcribe.assign-folder') }}"--}}
        {{--                                   class="slide-item">{{ __('Image') }}</a></li>--}}
        {{--                        @endif--}}
        {{--                        @if (array_key_exists('text', $permissions)  && $permissions['text'] === true)--}}
        {{--                            <li>--}}
        {{--                                <a href="{{route('user.transcribe.assign-text')}}"--}}
        {{--                                   class="slide-item">{{ __('Text to Speech') }}</a>--}}
        {{--                            </li>--}}
        {{--                        @endif--}}
        {{--                        --}}{{--                        @if (array_key_exists('text', $permissions))--}}
        {{--                        <li>--}}
        {{--                            <a href="{{route('user.transcribe.assign-text-to-text')}}"--}}
        {{--                               class="slide-item">{{ __('Text to Text') }}</a>--}}
        {{--                        </li>--}}
        {{--                        --}}{{--                        @endif--}}
        {{--                        @if (array_key_exists('coco', $permissions)  && $permissions['coco'] === true)--}}
        {{--                            <li>--}}
        {{--                                <a href="{{ route('user.images.folder') }}"--}}
        {{--                                   class="slide-item">{{ __('COCO') }}</a>--}}
        {{--                            </li>--}}
        {{--                        @endif--}}
        {{--                            @if (array_key_exists('SMS and Event', $permissions) && $permissions['SMS and Event'] === true)--}}
        {{--                            <li>--}}
        {{--                                <a href="{{ route('user.images.folder.sms') }}"--}}
        {{--                                   class="slide-item">{{ __('SMS and Event') }}</a>--}}
        {{--                            </li>--}}
        {{--                        @endif--}}

        {{--                    </ul>--}}
        {{--                </li>--}}
        {{--            @endif--}}
        {{--        @endif--}}
        @role('user')
        {{--        <li class="slide">--}}
        {{--            <a class="side-menu__item" href="{{ route('user.transcribe.results') }}">--}}
        {{--                <span class="side-menu__icon lead-3 fa-solid fa-folder-music"></span>--}}
        {{--                <span class="side-menu__label">{{ __('Task Submitted') }}</span></a>--}}
        {{--        </li>--}}
        @endrole
        @role('admin')
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.project-instruction') }}">
                <span class="side-menu__icon fas fa-sticky-note"></span>
                <span class="side-menu__label">{{ __('Project') }}</span>
            </a>
        </li>
        @endrole

        @role('admin')
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon fa fa-sliders"></span>
                <span class="side-menu__label">{{ __('Add Projects') }}</span><i
                    class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('admin.images.folder') }}" class="slide-item">{{ __('Folder') }}</a></li>
                <li><a href="{{ route('admin.image.index') }}" class="slide-item">{{ __('Image') }}</a></li>
                <li><a href="{{ route('admin.csv.index') }}" class="slide-item">{{ __('CSV Text') }}</a></li>
                <li><a href="{{ route('admin.text.index') }}" class="slide-item">{{ __('Text') }}</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon lead-3 fa-solid fa-boxes-packing"></span>
                <span class="side-menu__label">{{ __('Projects Report Data') }}</span><i
                    class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                @foreach($projects as $project )
                    <li><a href="{{ route('admin.assigned.projects',$project->id) }}"
                           class="slide-item">{{ ucfirst($project->name) }}</a></li>
                @endforeach
            </ul>
        </li>
        {{--        <li class="slide">--}}
        {{--            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">--}}
        {{--                <span class="side-menu__icon lead-3 fa-solid fa-boxes-packing"></span>--}}
        {{--                <span class="side-menu__label">{{ __('Projects Report Data') }}</span><i--}}
        {{--                    class="angle fa fa-angle-right"></i></a>--}}
        {{--            <ul class="slide-menu">--}}
        {{--                <li><a href="{{ route('admin.liveTranscription.index') }}" class="slide-item">{{ __('Image') }}</a></li>--}}
        {{--                <li><a href="{{ route('admin.text.list.user') }}" class="slide-item">{{ __('Text') }}</a></li>--}}
        {{--                <li><a href="{{ route('admin.coco.user') }}" class="slide-item">{{ __('COCO') }}</a></li>--}}
        {{--                <li><a href="{{ route('admin.text.user') }}" class="slide-item">{{ __('Text to Text') }}</a></li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}
        {{--        <li class="slide">--}}
        {{--            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">--}}
        {{--                <span class="side-menu__icon lead-3 fa-solid fa-boxes-packing"></span>--}}
        {{--                <span class="side-menu__label">{{ __('Report') }}</span><i--}}
        {{--                    class="angle fa fa-angle-right"></i></a>--}}
        {{--            <ul class="slide-menu">--}}
        {{--                <li><a href="{{route('admin.report.project')}}" class="slide-item">{{ __('Project') }}</a></li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}
        @endrole
        @role('quality_assurance')
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('#')}}">
                <span class="side-menu__icon lead-3 fa-solid fa-boxes-packing"></span>
                <span class="side-menu__label">{{ __('Quality Assurance Data') }}</span><i
                    class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('qa.coco-folder') }}" class="slide-item">{{ __('COCO') }}</a>
                </li>
            </ul>
            <ul class="slide-menu">
                <li><a href="{{ route('qa.text-folder') }}" class="slide-item">{{ __('Text') }}</a>
                </li>
            </ul>
            <ul class="slide-menu">
                <li><a href="{{ route('qa.text-to-text-folder') }}" class="slide-item">{{ __('Text to Text') }}</a>
                </li>
            </ul>
        </li>
        @endrole


    </ul>

</aside>
<!-- END SIDE MENU BAR -->
