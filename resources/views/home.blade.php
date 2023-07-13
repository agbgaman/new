<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta data -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="{{ $information['author'] }}">
    <meta name="keywords" content="{{ $information['keywords'] }}">
    <meta name="description" content="{{ $information['description'] }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ $information['title'] }}</title>

    <!--CSS Files -->
    <link href="{{URL::asset('plugins/slick/slick.css')}}" rel="stylesheet">
    <link href="{{URL::asset('plugins/slick/slick-theme.css')}}" rel="stylesheet">
    <script type='text/javascript'>
        window.smartlook || (function (d) {
            var o = smartlook = function () {
                o.api.push(arguments)
            }, h = d.getElementsByTagName('head')[0];
            var c = d.createElement('script');
            o.api = new Array();
            c.async = true;
            c.type = 'text/javascript';
            c.charset = 'utf-8';
            c.src = 'https://web-sdk.smartlook.com/recorder.js';
            h.appendChild(c);
        })(document);
        smartlook('init', '48ad407efd143a50d6ce1a3c7361cf8a98058a22', {region: 'eu'});
    </script>

    <script
        src="{{asset('home/assets-dist/js/swiper-bundle.min.js')}}"></script>
    <link href="{{asset('home/assets-dist/css/swiper-bundle.min.css')}}"
          rel="stylesheet">

    <link rel='stylesheet' id='structured-content-frontend-css'
          href={{asset('home/assets-dist/dist/blocks.style.build.css?ver=1.5.3')}}
          type='text/css' media='all'/>
    <link rel='stylesheet' id='wp-block-library-css'
          href={{asset('home/assets-dist/dist/style.min.css')}}
          type='text/css' media='all'/>
    <style id='global-styles-inline-css' type='text/css'>
        body {
            --wp--preset--color--black: #000000;
            --wp--preset--color--cyan-bluish-gray: #abb8c3;
            --wp--preset--color--white: #ffffff;
            --wp--preset--color--pale-pink: #f78da7;
            --wp--preset--color--vivid-red: #cf2e2e;
            --wp--preset--color--luminous-vivid-orange: #ff6900;
            --wp--preset--color--luminous-vivid-amber: #fcb900;
            --wp--preset--color--light-green-cyan: #7bdcb5;
            --wp--preset--color--vivid-green-cyan: #00d084;
            --wp--preset--color--pale-cyan-blue: #8ed1fc;
            --wp--preset--color--vivid-cyan-blue: #0693e3;
            --wp--preset--color--vivid-purple: #9b51e0;
            --wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg, rgba(6, 147, 227, 1) 0%, rgb(155, 81, 224) 100%);
            --wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg, rgb(122, 220, 180) 0%, rgb(0, 208, 130) 100%);
            --wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg, rgba(252, 185, 0, 1) 0%, rgba(255, 105, 0, 1) 100%);
            --wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg, rgba(255, 105, 0, 1) 0%, rgb(207, 46, 46) 100%);
            --wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg, rgb(238, 238, 238) 0%, rgb(169, 184, 195) 100%);
            --wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg, rgb(74, 234, 220) 0%, rgb(151, 120, 209) 20%, rgb(207, 42, 186) 40%, rgb(238, 44, 130) 60%, rgb(251, 105, 98) 80%, rgb(254, 248, 76) 100%);
            --wp--preset--gradient--blush-light-purple: linear-gradient(135deg, rgb(255, 206, 236) 0%, rgb(152, 150, 240) 100%);
            --wp--preset--gradient--blush-bordeaux: linear-gradient(135deg, rgb(254, 205, 165) 0%, rgb(254, 45, 45) 50%, rgb(107, 0, 62) 100%);
            --wp--preset--gradient--luminous-dusk: linear-gradient(135deg, rgb(255, 203, 112) 0%, rgb(199, 81, 192) 50%, rgb(65, 88, 208) 100%);
            --wp--preset--gradient--pale-ocean: linear-gradient(135deg, rgb(255, 245, 203) 0%, rgb(182, 227, 212) 50%, rgb(51, 167, 181) 100%);
            --wp--preset--gradient--electric-grass: linear-gradient(135deg, rgb(202, 248, 128) 0%, rgb(113, 206, 126) 100%);
            --wp--preset--gradient--midnight: linear-gradient(135deg, rgb(2, 3, 129) 0%, rgb(40, 116, 252) 100%);
            --wp--preset--duotone--dark-grayscale: url('#wp-duotone-dark-grayscale');
            --wp--preset--duotone--grayscale: url('#wp-duotone-grayscale');
            --wp--preset--duotone--purple-yellow: url('#wp-duotone-purple-yellow');
            --wp--preset--duotone--blue-red: url('#wp-duotone-blue-red');
            --wp--preset--duotone--midnight: url('#wp-duotone-midnight');
            --wp--preset--duotone--magenta-yellow: url('#wp-duotone-magenta-yellow');
            --wp--preset--duotone--purple-green: url('#wp-duotone-purple-green');
            --wp--preset--duotone--blue-orange: url('#wp-duotone-blue-orange');
            --wp--preset--font-size--small: 13px;
            --wp--preset--font-size--medium: 20px;
            --wp--preset--font-size--large: 36px;
            --wp--preset--font-size--x-large: 42px;
            --wp--preset--spacing--20: 0.44rem;
            --wp--preset--spacing--30: 0.67rem;
            --wp--preset--spacing--40: 1rem;
            --wp--preset--spacing--50: 1.5rem;
            --wp--preset--spacing--60: 2.25rem;
            --wp--preset--spacing--70: 3.38rem;
            --wp--preset--spacing--80: 5.06rem;
            --wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, 0.2);
            --wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, 0.4);
            --wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, 0.2);
            --wp--preset--shadow--outlined: 6px 6px 0px -3px rgba(255, 255, 255, 1), 6px 6px rgba(0, 0, 0, 1);
            --wp--preset--shadow--crisp: 6px 6px 0px rgba(0, 0, 0, 1);
        }

        :where(.is-layout-flex) {
            gap: 0.5em;
        }

        body .is-layout-flow > .alignleft {
            float: left;
            margin-inline-start: 0;
            margin-inline-end: 2em;
        }

        body .is-layout-flow > .alignright {
            float: right;
            margin-inline-start: 2em;
            margin-inline-end: 0;
        }

        body .is-layout-flow > .aligncenter {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        body .is-layout-constrained > .alignleft {
            float: left;
            margin-inline-start: 0;
            margin-inline-end: 2em;
        }

        body .is-layout-constrained > .alignright {
            float: right;
            margin-inline-start: 2em;
            margin-inline-end: 0;
        }

        body .is-layout-constrained > .aligncenter {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        body .is-layout-constrained > :where(:not(.alignleft):not(.alignright):not(.alignfull)) {
            max-width: var(--wp--style--global--content-size);
            margin-left: auto !important;
            margin-right: auto !important;
        }

        body .is-layout-constrained > .alignwide {
            max-width: var(--wp--style--global--wide-size);
        }

        body .is-layout-flex {
            display: flex;
        }

        body .is-layout-flex {
            flex-wrap: wrap;
            align-items: center;
        }

        body .is-layout-flex > * {
            margin: 0;
        }

        :where(.wp-block-columns.is-layout-flex) {
            gap: 2em;
        }

        .has-black-color {
            color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-color {
            color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-color {
            color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-color {
            color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-color {
            color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-color {
            color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-color {
            color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-color {
            color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-color {
            color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-color {
            color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-color {
            color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-color {
            color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-black-background-color {
            background-color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-background-color {
            background-color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-background-color {
            background-color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-background-color {
            background-color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-background-color {
            background-color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-background-color {
            background-color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-background-color {
            background-color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-background-color {
            background-color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-background-color {
            background-color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-background-color {
            background-color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-background-color {
            background-color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-background-color {
            background-color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-black-border-color {
            border-color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-border-color {
            border-color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-border-color {
            border-color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-border-color {
            border-color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-border-color {
            border-color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-border-color {
            border-color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-border-color {
            border-color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-border-color {
            border-color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-border-color {
            border-color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-border-color {
            border-color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-border-color {
            border-color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-border-color {
            border-color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-vivid-cyan-blue-to-vivid-purple-gradient-background {
            background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;
        }

        .has-light-green-cyan-to-vivid-green-cyan-gradient-background {
            background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;
        }

        .has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background {
            background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-orange-to-vivid-red-gradient-background {
            background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;
        }

        .has-very-light-gray-to-cyan-bluish-gray-gradient-background {
            background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;
        }

        .has-cool-to-warm-spectrum-gradient-background {
            background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;
        }

        .has-blush-light-purple-gradient-background {
            background: var(--wp--preset--gradient--blush-light-purple) !important;
        }

        .has-blush-bordeaux-gradient-background {
            background: var(--wp--preset--gradient--blush-bordeaux) !important;
        }

        .has-luminous-dusk-gradient-background {
            background: var(--wp--preset--gradient--luminous-dusk) !important;
        }

        .has-pale-ocean-gradient-background {
            background: var(--wp--preset--gradient--pale-ocean) !important;
        }

        .has-electric-grass-gradient-background {
            background: var(--wp--preset--gradient--electric-grass) !important;
        }

        .has-midnight-gradient-background {
            background: var(--wp--preset--gradient--midnight) !important;
        }

        .has-small-font-size {
            font-size: var(--wp--preset--font-size--small) !important;
        }

        .has-medium-font-size {
            font-size: var(--wp--preset--font-size--medium) !important;
        }

        .has-large-font-size {
            font-size: var(--wp--preset--font-size--large) !important;
        }

        .has-x-large-font-size {
            font-size: var(--wp--preset--font-size--x-large) !important;
        }

        .wp-block-navigation a:where(:not(.wp-element-button)) {
            color: inherit;
        }

        :where(.wp-block-columns.is-layout-flex) {
            gap: 2em;
        }

        .wp-block-pullquote {
            font-size: 1.5em;
            line-height: 1.6;
        }
    </style>
    <link rel='stylesheet' id='wpml-blocks-css'
          href={{asset('home/assets-dist/css/styles.css')}}
          type='text/css' media='all'/>
    <style id='wpml-legacy-dropdown-click-0-inline-css' type='text/css'>
        .wpml-ls-statics-shortcode_actions {
            background-color: #eeeeee;
        }

        .wpml-ls-statics-shortcode_actions, .wpml-ls-statics-shortcode_actions .wpml-ls-sub-menu, .wpml-ls-statics-shortcode_actions a {
            border-color: #cdcdcd;
        }

        .wpml-ls-statics-shortcode_actions a {
            color: #444444;
            background-color: #ffffff;
        }

        .wpml-ls-statics-shortcode_actions a:hover, .wpml-ls-statics-shortcode_actions a:focus {
            color: #000000;
            background-color: #eeeeee;
        }

        .wpml-ls-statics-shortcode_actions .wpml-ls-current-language > a {
            color: #444444;
            background-color: #ffffff;
        }

        .wpml-ls-statics-shortcode_actions .wpml-ls-current-language:hover > a, .wpml-ls-statics-shortcode_actions .wpml-ls-current-language > a:focus {
            color: #000000;
            background-color: #eeeeee;
        }

        .wpml-ls-legacy-dropdown-click {
            width: 80px;
        }

        .wpml-ls-statics-shortcode_actions {
            background-color: transparent;
        }

        .wpml-ls-legacy-dropdown-click a.wpml-ls-item-toggle:after {
            right: 4px;
        }
    </style>
    <link rel='stylesheet' id='cw_style-css'
          href={{asset('home/assets-dist/dist/style-d87eeb7855.min.css')}}
          type='text/css' media='all'/>
    <link rel='stylesheet' id='moove_gdpr_frontend-css'
          href={{asset('home/assets-dist/dist/gdpr-main-nf.css?ver=4.10.6')}}
          type='text/css' media='all'/>
    <style id='moove_gdpr_frontend-inline-css' type='text/css'>
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main h3.tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main span.tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content .moove-gdpr-branding-cnt a,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton,
        #moove_gdpr_cookie_modal .cookie-switch .cookie-slider:after,
        #moove_gdpr_cookie_modal .cookie-switch .slider:after,
        #moove_gdpr_cookie_modal .switch .cookie-slider:after,
        #moove_gdpr_cookie_modal .switch .slider:after,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content p,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content p a,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h1,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h2,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h3,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h4,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h5,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h6,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-modal-title .tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-tab-main h3.tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-tab-main span.tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-branding-cnt a {
            font-weight: inherit
        }

        #moove_gdpr_cookie_modal, #moove_gdpr_cookie_info_bar, .gdpr_cookie_settings_shortcode_content {
            font-family: inherit
        }

        #moove_gdpr_save_popup_settings_button {
            background-color: #373737;
            color: #fff
        }

        #moove_gdpr_save_popup_settings_button:hover {
            background-color: #000
        }

        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton, #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton {
            background-color: #f8b02f
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton, .gdpr_cookie_settings_shortcode_content .gdpr-shr-button.button-green {
            background-color: #f8b02f;
            border-color: #f8b02f
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton:hover, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton:hover, .gdpr_cookie_settings_shortcode_content .gdpr-shr-button.button-green:hover {
            background-color: #fff;
            color: #f8b02f
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close i, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close span.gdpr-icon {
            background-color: #f8b02f;
            border: 1px solid #f8b02f
        }

        #moove_gdpr_cookie_info_bar span.change-settings-button.focus-g, #moove_gdpr_cookie_info_bar span.change-settings-button:focus {
            -webkit-box-shadow: 0 0 1px 3px #f8b02f;
            -moz-box-shadow: 0 0 1px 3px #f8b02f;
            box-shadow: 0 0 1px 3px #f8b02f
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close i:hover, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close span.gdpr-icon:hover, #moove_gdpr_cookie_info_bar span[data-href] > u.change-settings-button {
            color: #f8b02f
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li.menu-item-selected a span.gdpr-icon, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li.menu-item-selected button span.gdpr-icon {
            color: inherit
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a span.gdpr-icon, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button span.gdpr-icon {
            color: inherit
        }

        #moove_gdpr_cookie_modal .gdpr-acc-link {
            line-height: 0;
            font-size: 0;
            color: transparent;
            position: absolute
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close:hover i, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button i, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a i, #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content a:hover, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton:hover, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton:hover, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a:hover, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button:hover, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content span.change-settings-button:hover, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button:hover, #moove_gdpr_cookie_info_bar span[data-href] > u.change-settings-button, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton.focus-g, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton.focus-g, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.focus-g, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.focus-g, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton:focus, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton:focus, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a:focus, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button:focus, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content span.change-settings-button.focus-g, span.change-settings-button:focus, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button.focus-g, #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button:focus {
            color: #f8b02f
        }

        #moove_gdpr_cookie_modal.gdpr_lightbox-hide {
            display: none
        }
    </style>
    <script type='text/javascript' src={{asset('home/assets-dist/js/jquery.min.js')}}
            id='jquery-core-js'></script>
    <script type='text/javascript'
            src={{asset('home/assets-dist/js/jquery-migrate.min.js?ver=3.4.0')}}
            id='jquery-migrate-js'></script>
    <script type='text/javascript' id='wpml-cookie-js-extra'>
        /* <![CDATA[ */
        var wpml_cookies = {"wp-wpml_current_language": {"value": "en", "expires": 1, "path": "\/"}};
        var wpml_cookies = {"wp-wpml_current_language": {"value": "en", "expires": 1, "path": "\/"}};
        /* ]]> */
    </script>
    <script type='text/javascript' id='wpml-xdomain-data-js-extra'>
        /* <![CDATA[ */
        var wpml_xdomain_data = {
            "css_selector": "wpml-ls-item",
            "ajax_url": "https:\/\/www.clickworker.com\/wp-admin\/admin-ajax.php",
            "current_lang": "en",
            "_nonce": "921af3b9a2"
        };
        var wpml_xdomain_data = {
            "css_selector": "wpml-ls-item",
            "ajax_url": "https:\/\/www.clickworker.com\/wp-admin\/admin-ajax.php",
            "current_lang": "en",
            "_nonce": "921af3b9a2"
        };
        /* ]]> */
    </script>
    <style>
        div.wpforms-container-full .wpforms-form .h-captcha,
        #wpforo #wpforo-wrap div .h-captcha,
        .h-captcha {
            position: relative;
            display: block;
            margin-bottom: 2rem;
            padding: 0;
            clear: both;
        }

        #af-wrapper div.editor-row.editor-row-hcaptcha {
            display: flex;
            flex-direction: row-reverse;
        }

        #af-wrapper div.editor-row.editor-row-hcaptcha .h-captcha {
            margin-bottom: 0;
        }

        form.wpsc-create-ticket .h-captcha {
            margin: 0 15px 15px 15px;
        }

        .gform_previous_button + .h-captcha {
            margin-top: 2rem;
        }

        #wpforo #wpforo-wrap.wpft-topic div .h-captcha,
        #wpforo #wpforo-wrap.wpft-forum div .h-captcha {
            margin: 0 -20px;
        }

        .wpdm-button-area + .h-captcha {
            margin-bottom: 1rem;
        }

        .w3eden .btn-primary {
            background-color: var(--color-primary) !important;
            color: #fff !important;
        }

        div.wpforms-container-full .wpforms-form .h-captcha[data-size="normal"],
        .h-captcha[data-size="normal"] {
            width: 303px;
            height: 78px;
        }

        div.wpforms-container-full .wpforms-form .h-captcha[data-size="compact"],
        .h-captcha[data-size="compact"] {
            width: 164px;
            height: 144px;
        }

        div.wpforms-container-full .wpforms-form .h-captcha[data-size="invisible"],
        .h-captcha[data-size="invisible"] {
            display: none;
        }

        .h-captcha::before {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            /*background: url(https://www.clickworker.com/wp-content/plugins/hcaptcha-for-forms-and-more/assets/images/hcaptcha-div-logo.svg) no-repeat;*/
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .h-captcha[data-size="normal"]::before {
            width: 300px;
            height: 74px;
            background-position: 94% 27%;
        }

        .h-captcha[data-size="compact"]::before {
            width: 156px;
            height: 136px;
            background-position: 50% 77%;
        }

        .h-captcha[data-theme="light"]::before {
            background-color: #fafafa;
            border: 1px solid #e0e0e0;
        }

        .h-captcha[data-theme="dark"]::before {
            background-color: #333;
            border: 1px solid #f5f5f5;
        }

        .h-captcha[data-size="invisible"]::before {
            display: none;
        }

        div.wpforms-container-full .wpforms-form .h-captcha iframe,
        .h-captcha iframe {
            position: relative;
        }

        span[data-name="hcap-cf7"] .h-captcha {
            margin-bottom: 0;
        }

        span[data-name="hcap-cf7"] ~ input[type="submit"] {
            margin-top: 2rem;
        }

        .elementor-field-type-hcaptcha .elementor-field {
            background: transparent !important;
        }

        .elementor-field-type-hcaptcha .h-captcha {
            margin-bottom: unset;
        }

        div[style*="z-index: 2147483647"] div[style*="border-width: 11px"][style*="position: absolute"][style*="pointer-events: none"] {
            border-style: none;
        }
    </style>
    <link rel="icon" href="{{URL::asset('home/favi.webp')}}" type="image/x-icon"/>

    <link rel="icon" href="{{URL::asset('home/favi.webp')}}" type="image/x-icon"/>

    <meta name="theme-color" content="#ffffff">


    <style type="text/css">
        .fancybox-custom .fancybox-outer {
            box-shadow: 0 0 50px #222;
        }
    </style>

</head>

<body
    class="page-template page-template-clickworker-app-landingpage page-template-clickworker-app-landingpage-php page page-id-37913 v7">
<div id="header" class="fixed-top">
    <div id="sup-nav" class="cw-style">
        <div class="container position-relative">
            <div class="row no-gutters justify-content-end">
                <div class="col-auto visible-lg">
                    <div class="menu-customer-contact-menu-container">
                        <ul id="menu-customer-contact-menu" class="nav navbar-nav">
                            <li id="menu-item-61769"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-61769  main-menu-item dropdown menu-item-61769 nav-item">
                                <a href="{{route('login')}}"
                                   class="nav-link"><span>Login</span></a>
                            </li>
                            <li id="menu-item-61772"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-61772  main-menu-item dropdown menu-item-61772 nav-item">
                                <a href="{{route('register')}}"
                                   class=" nav-link"><span>Register</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav id="nav-bar" class="navbar cliworkerNav navbar-expand-lg navbar-light mega-menu cw-style justify-content-end ">
        <div class="container position-relative">
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ URL::asset('img/brand/logo.png') }}" alt=""></a>
            <div class="text-right d-lg-none mobile-nav-toggler">
                <button class="navbar-toggler float-right" type="button" data-toggle="collapse"
                        data-target="#main-navbar-collaps" aria-controls="main-navbar-collapse" aria-expanded="false"
                        aria-label="Toggle navigation"><i class="fa fa-bars" aria-hidden="true"></i></button>
            </div>
            <div class="collapse navbar-collapse w-100 flex-grow-0" id="main-navbar-collaps">
                <div class="navbar-nav w-sm-100">
                    <div class="nav-item wide row no-gutters w-100">
                        <div class="col-lg-auto mr-lg-auto visible-lg order-lg-first">
                            <div class="menu-clickworker-menu-container">
                                <ul id="menu-clickworker-menu"
                                    class="nav navbar-nav no-float menu-clickworker-menu-container">
                                    @if (config('frontend.pricing_section') == 'on')
                                        <li class="nav-item">
                                            <a class="nav-link scroll" href="#prices-wrapper">{{ __('Prices') }}</a>
                                        </li>
                                    @endif
                                    @if (config('frontend.blogs_section') == 'on')
                                        <li class="nav-item">
                                            <a class="nav-link scroll" href="{{route('privacy')}}">{{ __('Privacy') }}</a>
                                        </li>
                                    @endif
                                    @if (config('frontend.faq_section') == 'on')
                                        <li class="nav-item">
                                            <a class="nav-link scroll" href="{{ route('terms') }}">{{ __('Term and Condition') }}</a>
                                        </li>
                                    @endif
                                    @if (config('frontend.contact_section') == 'on')
                                        <li class="nav-item">
                                            <a class="nav-link scroll"
                                               href="#contact-wrapper">{{ __('Contact Us') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </nav>
    <div class="clear"></div>

</div>

<div class="page">


    <style>
        .page-template-clickworker-app-landingpage .platform-preview-section .nav-pills .nav-link {
            margin: 0 5px;
            border-radius: 30px;
            border: 0;
            padding: 15px 40px;
            background: #f1f1f1;
        }

        @media screen and (min-width: 576px) {
            .iso-container {
                max-width: 540px;
            }
        }

        @media screen and (max-width: 767px) {
            .iso-container {
                padding-right: 0.9375rem;
                padding-left: 0.9375rem;
                margin-right: auto;
                margin-left: auto;
            }

            .iso-container .iso-overlay-inner {
                width: calc((100vw - 100vw) / 2 + 100%);
                width: 50vw;
                left: 0;
                right: 0;
                top: 0;
                margin: auto;
                position: relative;
                height: auto;
            }

            .iso-section-padding {
                padding-top: 50px;
                padding-bottom: 50px;
            }

        }

        @media screen and (max-width: 766px) {
            .page-template-clickworker-app-landingpage .element-1-3 {
                width: 300px;
            }

            .page-template-clickworker-app-landingpage .logo_image {
                max-width: 150px;
            }

            .page-template-clickworker-app-landingpage .element-1-1,
            .page-template-clickworker-app-landingpage .element-1-2 {
                right: 0;
                left: 0;
                margin: 0 auto;
            }

            .page .btn.app-cta-btn {
                min-width: 100%;
                display: block !important;
            }

            .iso-container .iso-overlay-inner {
                width: calc((100vw - 100vw) / 2 + 100%);
                width: 100vw;
            }

            .platform-preview-section li.nav-item {
                width: 100%;
                margin-bottom: 10px;

            }

            .platform-preview-section .nav-pills .nav-link {
                width: 100%;
                display: block;
                padding: 15px 30px;
            }

            .app-cta-btn {
                padding-top: 15px !important;
                padding-right: 50px !important;
                padding-bottom: 15px !important;
                padding-left: 50px !important;
                display: inline !important;
            }

            .cw-services-section .swiper-button-next, .cw-services-section .swiper-button-prev {
                background: #ebebeb;
                border-radius: 50%;
                width: 30px !important;
                height: 30px !important;
            }

            .cw-services-section .swiper-button-next:after, .cw-services-section .swiper-button-prev:after {
                font-size: 14px;
            }

            .container {
                padding-right: 1.5rem;
                padding-left: 1.5rem;
                margin-right: auto;
                margin-left: auto;
            }

            .swipper-1-container .swiper-xs-view .swiper--navigation {
                width: 100px !important;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ededed;

            }

            .logo_image {
                width: auto;
                background: white;
                text-align: left;
                padding: 20px;
                border-radius: 15px;
                bottom: 0;
                box-shadow: rgb(0 0 0 / 5%) 12px 18px 30px -3px, rgb(0 0 0 / 5%) 0px 4px 6px -2px;
                max-width: 150px;
                margin-bottom: 10px;
            }
        }

        @media screen and (min-width: 768px) {
            .iso-container {
                max-width: 720px !important;
            }

            .iso-container .iso-overlay-inner {
                width: calc((100vw - 100vw) / 2 + 100%);
                width: 50vw;
            }

        }

        @media screen and (min-width: 62rem) {
            .iso-container {
                max-width: 970px !important;
            }

            .iso-container .iso-overlay-inner {
                width: calc((100vw - 970px) / 2 + 100%);
                width: 50vw;
            }
        }

        @media screen and (min-width: 85.5rem) {
            .iso-container {
                max-width: 1170px !important;
            }

            .iso-container .iso-overlay-inner {
                width: calc((100vw - 1170px) / 2 + 100%);
                width: 50vw;
            }
        }

        @media only screen and (max-width: 600px) {
            .page-template-clickworker-app-landingpage .cw-services-section .swiper-wrapper-outer {
                width: auto !important;
            }

            .swiper-xl-view {
                display: none !important;
            }

            .swiper-xs-view {
                display: block !important;
            }

            .deco-1 {
                display: none;
            }

            .section.section-space {
                padding: 50px 0;
            }

            .cw-iso-section-left {
                padding-top: 50px;
                padding-bottom: inherit;
                padding-right: inherit;
                padding-bottom: 50px;
            }

            .cw-difference-left {
                padding-right: 0.9375rem !important;
            }

        }

        .page-template-clickworker-app-landingpage .cw-services-section {
            /*background: url(https://cdn.clickworker.com/wp-content/uploads/2023/04/services-section-cw-1-1.jpg) 50% 50% no-repeat !important;*/
            background-size: cover !important;
            background-position: center;
        }

        .page-template-clickworker-app-landingpage .cw-cta-section {
            /*background: url(https://cdn.clickworker.com/wp-content/uploads/2023/04/cta-section-cw-5-1.jpg) 50% 50% no-repeat !important;*/
            background-size: cover !important;
        }
    </style>


    <section class="section-space cw-hero-section">
        <div class="overlay__shadow"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 my-auto">

                    <h1 class="cw-header-h1 text-dark mb-4"><span class="span_main">Dash</span>. Artificial Intelligence
                        Training Data</h1>
                    <p class="lead text-dark mb-5">
                        GTS Dash is an online hub for project collaborators seeking to
                        participate in various projects in the text, audio, video, and
                        image categories. Collaborators can sign up and select from a
                        variety of projects, each offering different payments as
                        benefits.
                    </p>
                    <a href="{{route('register')}}" class="app-cta-btn app-cta-btn-solid btn btn-primary btn-md mr-3">Signup</a>
                    <a href="{{route('login')}}" class="app-cta-btn app-cta-btn-border btn btn-outline-primary btn-md">Login</a>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">

                    <img id="special-image-margin" src="{{ URL::asset('img/files/main-banner.svg') }}">

                </div>
            </div>
        </div>
    </section>

    <section class="section section-space white">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 my-auto p-lg-5">
                    <span class="cw-title-span"
                          style="color: #D76C8A;">A great community is made even better by you</span>
                    <h2 class="mb-4 mt-2">You are important to us</h2>
                    <p class="mb-5  lead">In our community, everyone is an important part and makes their individual
                        contribution.
                        For this
                        reason, we are able to offer diverse and good jobs.</p>
                    <ul class="benefitStyled cw--pink">
                        <li>Be part of a great community</li>
                        <li>Work with us and shape the future</li>
                        <li>We can&#8217;t do it without you. We need you!</li>
                    </ul>
                </div>
                <div class="col-12 col-md-6 col-lg-6 text-center my-auto">
                    <div class="element-1 element-1-1 animated fadeInUp delay-2s">
                        <h4>
                            Great community
                        </h4>
                        <p>
                            Become a part of dash&#8217;s great community today and experience the collaborative,
                            supportive, and fulfilling work environment our members enjoy. <a
                                href="{{route('register')}}" target="_blank" rel="noopener">Sign-up
                                here!</a>
                        </p>
                    </div>
                    <img decoding="async"
                         src="{{asset('home/imgpsh_fullsize_anim.jpeg')}}"
                         alt="DashApp - A great community" class="img-fluid position-relative"/>

                </div>
            </div>
        </div>
    </section>
    <section class="section section-space grad-blue-bottom-to-top">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-6 col-lg-6 my-auto p-lg-5 order-lg-last">
                    <span class="cw-title-span animated fadeInUp" style="color: #837DBB;">Technologically always state-of-the-art</span>
                    <h2 class="mb-4 mt-2 animated fadeInUp">High-end technology</h2>
                    <p class="mb-5  lead animated fadeInUp">Our modern server housing ensures that we can guarantee you
                        stable performance and
                        availability of
                        our platform &#8211; no matter when you&#8217;re working or where you are.</p>
                    <ul class="benefitStyled cw--purple animated fadeInUp">
                        <li>State-of-the-art technology</li>
                        <li>High availability guaranteed</li>
                        <li>High safety standards</li>
                    </ul>
                </div>
                <div class="col-12 col-md-6 col-lg-6 text-center my-auto">
                    <div class="element-1-2  animated fadeInUp delay-2s">
                        <h4>
                            Modern approach
                        </h4>
                        <p>
                            Our modern server housing means that you can work anytime, anywhere, with stable platform
                            performance. <a href="{{route('register')}}" target="_blank"
                                            rel="noopener">Join now</a> and start working with us today.
                        </p>
                    </div>
                    <img decoding="async"
                         src="{{asset('/home/favicon.png')}}"
                         alt="DashApp - High-end technology - iOS Android App"
                         class="img-fluid position-relative"/>

                </div>
            </div>
        </div>
    </section>
{{--    <section class="section bg-blue section-space">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-12 w-100">--}}
{{--                    <!-- TrustBox widget - Horizontal -->--}}
{{--                    <div class="trustpilot-widget w-100" data-locale="en-US" data-template-id="5406e65db0d04a09e042d5fc"--}}
{{--                         data-businessunit-id="54b6bf150000ff00057cbc9b" data-style-height="28px"--}}
{{--                         data-style-width="100%"--}}
{{--                         data-theme="light">--}}
{{--                        <a href="https://www.trustpilot.com/review/clickworker.com" target="_blank" rel="noopener">Trustpilot</a>--}}
{{--                    </div>--}}
{{--                    <!-- End TrustBox widget -->--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
    <section class="section section-space grad-blue-top-to-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 my-auto p-lg-5">
                    <span class="cw-title-span">The largest job offer in the Game</span>
                    <h2 class="mb-4 mt-2">Micro-tasking made for you</h2>
                    <p class="mb-5  lead">We don&#8217;t just provide easy click jobs but offer interesting and fun
                        tasks as well.
                        Join
                        us in shaping
                        the future by participating in AI training, surveys, voice recordings, and photo contests.</p>
                    <ul class="benefitStyled">
                        <li>Wide variety of jobs to choose from</li>
                        <li>Great range of jobs available</li>
                        <li>Customized just for you</li>
                    </ul>
                </div>
                <div class="col-12 col-md-6 col-lg-6 text-center position-relative my-auto">
                    <img decoding="async"
                         src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/section-phone-1.png"
                         alt="DashApp - icro-tasking made for you" class="img-fluid position-relative"/>
                    <div class="deco-2 d-none"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-space white pt-0">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-6 col-lg-6 my-auto p-lg-5 order-md-last">
                    <span class="cw-title-span" style="color: #8DCA7A;">Weekly payments</span>
                    <h2 class="mb-4 mt-2">Earn money with micro tasking</h2>
                    <p class="mb-5  lead">Once your work is finished and approved by the client, you can receive regular
                        and
                        secure payments
                        with ease.</p>
                    <ul class="benefitStyled cw--green">
                        <li>Secure payment providers like Paypal &#038; Payoneer</li>
                        <li>Weekly payments</li>
                        <li>Decide for yourself when you get paid.</li>
                    </ul>
                </div>
                <div class="col-12 col-md-6 col-lg-6 my-auto pt-5 p-lg-5 text-center section__green">
                    <div class="element-1-3">
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/profile-app-iphone.svg"
                             alt="Dash App - Payoneer"
                             class="img-fluid position-relative logo_image_1 logo_image"/>
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/payoneer-logo.svg"
                             alt="Dash App - Sepa" class="img-fluid position-relative logo_image_2 logo_image"/>
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/sepa-logo.svg"
                             alt="DashApp - Paypal"
                             class="img-fluid position-relative logo_image_3 logo_image"/>
                    </div>
                    <img decoding="async"
                         src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/paypal-logo.svg"
                         alt="DashApp - Profile" class="img-fluid position-relative"/>
                    <div class="deco-2 d-none"></div>
                </div>

            </div>
        </div>
    </section>
    <section class="section test-section bg-blue cw-iso-section">
        <div class="container-fluid">
            <div class="row">
                <div class="iso-container iso-section-padding">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <span class="cw-title-span" style="color: #FCA61B;">ISO Certified</span>
                            <h2 class="mb-4 mt-2">Keeping your data safe</h2>
                            <p class="mb-5  lead">At clickworker, we take data privacy very seriously. We&#8217;ve
                                developed our platform
                                to ensure that your privacy is always protected to the best of our ability.</p>
                            <ul class="benefitStyled orange">
                                <li>Data security</li>
                                <li>Data privacy</li>
                                <li>Transparent handling of your data</li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-12 d-none d-md-block">
                            <div class="iso-overlay">
                                <div class="iso-overlay-inner ">
                                    <img decoding="async" class="security-logo-img"
                                         src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets/img/raw/privacy-page/security-logo.svg"/>
                                    <div class="iso-inner-overlay-shadow">

                                    </div>
                                    <img decoding="async" draggable="false"
                                         style="object-position: center center; display: block;"
                                         class="iso-inner-image-bg overlap__image object-fit-cover "
                                         src="https://cdn.clickworker.com/wp-content/uploads/2023/04/cw-hero-section-background.jpg"
                                    />

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="d-md-none iso-graphic">
        <div class="container-fluid px-0 h-100">
            <div class="row h-100">
                <div class="col-12 h-100">
                    <img decoding="async" class="security-logo-img" alt="DashSecurity"
                         src=""/>
                    <div class="iso-inner-overlay-shadow"></div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space section white workplace-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 col-lg-8">
                    <span class="cw-title-span">The place where it all happens</span>
                    <h2 class="mb-4 mt-2">Workplace</h2>
                    <p class="mb-5 lead">No matter where you are or how much time you have, you&#8217;re guaranteed to
                        find the
                        right
                        job for you.
                    </p>
                </div>
            </div>
            <div class="row workplace-cards mb-4">
                <div class="col-12 col-lg-4 mb-4">
                    <div class="">
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-2.svg"
                             class="workplace-icons" alt="DashApp - Find the right Job"/>
                        <div class="card-body">
                            <h4>Find the right Job</h4>
                            <p>No matter where you are or how much time you have, you&#8217;re guaranteed to find the
                                right job
                                for you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-4">
                    <div class="">
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-1.svg"
                             class="workplace-icons" alt="DashApp - Never miss a job"/>
                        <div class="card-body">
                            <h4>Never miss a job</h4>
                            <p>Activate push notifications in the Dashapp and we&#8217;ll immediately let you
                                know when a
                                new job is available. That way, you&#8217;ll never miss an opportunity!</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-4">
                    <div class="">
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-4.svg"
                             class="workplace-icons" alt="DashApp - Account balance"/>
                        <div class="card-body">
                            <h4>Your account balance always in sight</h4>
                            <p>You can easily track what you&#8217;ve earned and how much of it is available for
                                payment.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row workplace-cards">
                <div class="col-12 col-lg-4 mb-4">
                    <div class="">
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-3.svg"
                             class="workplace-icons" alt="DashApp - offline work"/>
                        <div class="card-body">
                            <h4>Working without internet connection</h4>
                            <p>Each accepted job is reserved for you for a fixed time. So you can work offline and
                                submit
                                the job when you are online again.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-4">
                    <div class="">
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-5.svg"
                             class="workplace-icons" alt="DashApp - Environment"/>
                        <div class="card-body">
                            <h4>Safe environment</h4>
                            <p>Ensuring a secure environment for your data, so you can focus on your tasks without
                                worrying
                                about potential security breaches.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-4">
                    <div class="">
                        <img decoding="async"
                             src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-6.svg"
                             class="workplace-icons" alt="DashApp - Support"/>
                        <div class="card-body">
                            <h4>Helpdesk Support</h4>
                            <p>Do you have questions or need help? We are happy to help you with all your queries about
                                the
                                Workplace.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-4 d-none">
                    <div class="">
                        <img decoding="async"
                             src=""
                             class="workplace-icons" alt="DashApp - Variety of jobs"/>
                        <div class="card-body">

                            <h4>Variety of jobs</h4>
                            <p>We provide a diverse selection of tasks and projects, so you can find the perfect
                                opportunity
                                to demonstrate your skills and earn money while doing it.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space section platform-preview-section grad-blue-top-to-bottom">
        <div class="container">
            <div class="row mb-1">
                <div class="col-12 col-lg-7 text-center mx-auto">
                    <span class="cw-title-span">Work on any device</span>
                    <h2 class="mb-4 mt-2">Access our platform from all your devices</h2>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <ul class="nav nav-pills mb-5 d-flex justify-content-center" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                    data-target="#pills-home"
                                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Work on
                                your
                                smartphone
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                    data-target="#pills-profile"
                                    type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Work on
                                your
                                desktop or notebook
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                             aria-labelledby="pills-home-tab">
                            <div class="container swipper-2-container">
                                <div class="row">
                                    <div class="col-10 col-sm-6 col-md-8 col-lg-10 mx-auto">
                                        <div class="pb-5 swiper mySwiper2">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/iphone-slider-1.png"
                                                        alt="DashApp - ios" class="img-fluid"/></div>
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/iphone-slider-2.png"
                                                        alt="DashApp - ios" class="img-fluid"/></div>
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/iphone-slider-3.png"
                                                        alt="DashApp - ios" class="img-fluid"/></div>
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/iphone-slider-4.png"
                                                        alt="DashApp - ios" class="img-fluid"/></div>
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/iphone-slider-5.png"
                                                        alt="DashApp - ios" class="img-fluid"/></div>
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/iphone-slider-6.png"
                                                        alt="DashApp - ios" class="img-fluid"/></div>
                                            </div>

                                            <div class="swiper-pagination"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="swiper--navigation">
                                            <div class="swiper-button-next sw-next-1"></div>
                                            <div class="swiper-button-prev sw-prev-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                             aria-labelledby="pills-profile-tab">
                            <div class="container swipper-3-container">
                                <div class="row">
                                    <div class="col-10 col-sm-6 col-md-8 col-lg-12 mx-auto">
                                        <div class="pb-5 swiper mySwiper3">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/mackbook-slider-4.png"
                                                        alt="DashApp - Desktop" class="img-fluid"/></div>
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/mackbook-slider-3.png"
                                                        alt="DashApp - Desktop" class="img-fluid"/></div>
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/mackbook-slider-2.png"
                                                        alt="DashApp - Desktop" class="img-fluid"/></div>
                                                <div class="swiper-slide"><img
                                                        src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/mackbook-slider-1.png"
                                                        alt="DashApp - Desktop" class="img-fluid"/></div>
                                            </div>

                                            <div class="swiper-pagination"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="swiper--navigation">
                                            <div class="swiper-button-next sw-next-3"></div>
                                            <div class="swiper-button-prev sw-prev-3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space section white pt-0">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto text-center">
                    <h2>Download now for iOS and Android</h2>
                    <p class="lead mb-5">The Dash app displays appropriate jobs for you, so you can quickly get
                        an overview of the
                        available work and get busy earning money.</p>
                    <a href="{{route('register')}}" target="_blank"
                       rel="noopener">
                        <img decoding="async"
                             src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/appstore.png"
                             alt="DashApp" class="img-fluid"/>
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.clickworker.clickworkerapp&#038;pli=1"
                       target="_blank" rel="noopener">
                        <img decoding="async"
                             src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/googleplay.png"
                             alt="DashApp" class="img-fluid"/>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space section cw-services-section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <h2 class="mb-4 mt-2">Discover a platform that always has the right job for you.</h2>
                    <p class="mb-5 lead">
                        Exciting projects and diverse tasks are waiting for you!
                    </p>
                </div>
                <div class="col-lg-2 swiper-xl-view">
                    <div class="swiper-button-next sw-next-2"></div>
                    <div class="swiper-button-prev sw-prev-2"></div>
                </div>
            </div>
        </div>
        <div class="container swipper-1-container">
            <div class="row">
                <div class="col-lg-12 p-lg-0">
                    <div class="swiper-wrapper-outer">
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="card h-100 text-center">
                                        <div class="card-icon-wrapper">
                                            <img decoding="async"
                                                 src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/photo-capturing-icon.svg"
                                                 class="workplace-icons mx-auto" alt="Surveys"/>
                                        </div>

                                        <div class="card-body">
                                            <h4>Surveys</h4>
                                            <p>Share your opinion by participating in various types of surveys.</p>
                                            <a href="" class="services-link">More
                                                about Surveys</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card h-100 text-center">
                                        <div class="card-icon-wrapper">
                                            <img decoding="async"
                                                 src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/photo-capturing-icon.svg"
                                                 class="workplace-icons" alt="Text Creation"/>
                                        </div>

                                        <div class="card-body">
                                            <h4>Text creation</h4>
                                            <p>Create informative texts, product descriptions or articles about a given
                                                topic.
                                                More about Text Creation
                                            </p>
                                            <a href=""
                                               class="services-link">More about Text Creation</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card h-100 text-center">
                                        <div class="card-icon-wrapper">
                                            <img decoding="async"
                                                 src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/audo-recording-icon.svg"
                                                 class="workplace-icons" alt="Research"/>
                                        </div>

                                        <div class="card-body">
                                            <h4>Research</h4>
                                            <p>Search for data and addresses of companies, restaurants and other
                                                localities
                                                in the web.</p>
                                            <a href=""
                                               class="services-link">More about Research</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card h-100 text-center">
                                        <div class="card-icon-wrapper">
                                            <img decoding="async"
                                                 src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/mistery-visit-icon.svg"
                                                 class="workplace-icons" alt="Mystery Visit"/>
                                        </div>

                                        <div class="card-body">
                                            <h4>Mystery Visit</h4>
                                            <p>Who wouldn’t enjoy getting paid for a weekly shopping trip? Visit nearby
                                                stores, take pictures of products and upload them at clickworker. That’s
                                                it!
                                            </p>
                                            <a href=""
                                               class="services-link">More about Mistery Visit</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card h-100 text-center">
                                        <div class="card-icon-wrapper">
                                            <img decoding="async"
                                                 src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/surveys-icon.svg"
                                                 class="workplace-icons" alt="App Testing"/>
                                        </div>

                                        <div class="card-body">
                                            <h4>App Testing</h4>
                                            <p>As an app jobber you can help optimize the design, functionality and
                                                usability of different applications.</p>
                                            <a href="" class="services-link">More
                                                about App Testing</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card h-100 text-center">
                                        <div class="card-icon-wrapper">
                                            <img decoding="async"
                                                 src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/text-creation-icon.svg"
                                                 class="workplace-icons" alt="Photo capturing"/>
                                        </div>

                                        <div class="card-body">
                                            <h4>Photo capturing</h4>
                                            <p>Take any kind of picture with your smartphone. Upload, done!</p>
                                            <a href=""
                                               class="services-link">More about Photo Capturing</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card h-100 text-center">
                                        <div class="card-icon-wrapper">
                                            <img decoding="async"
                                                 src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/audo-recording-icon.svg"
                                                 class="workplace-icons" alt="Audio recording"/>
                                        </div>

                                        <div class="card-body">
                                            <h4>Audio recording</h4>
                                            <p>At home or on the go, record short audio clips with your smartphone.</p>
                                            <a href="#"
                                               class="services-link">More about Audio Recordings</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card h-100 text-center">
                                        <div class="card-icon-wrapper">
                                            <img decoding="async"
                                                 src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/new/ic/icon-video-recordings.svg"
                                                 class="workplace-icons" style="max-height: 55px;"
                                                 alt="Video recordings"/>
                                        </div>

                                        <div class="card-body">
                                            <h4>Video recordings</h4>
                                            <p>Show us your skills by making short videos. Grab your cell phone and get
                                                filming!</p>
                                            <a href="#"
                                               class="services-link">More about Video Recordings</a>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-2 swiper-xs-view">
                    <div class="swiper--navigation">
                        <div class="swiper-button-prev sw-prev-2"></div>
                        <div class="swiper-button-next sw-next-2"></div>
                    </div>

                </div>
            </div>
        </div>

    </section>
    <section class="section-space section white cw-difference-section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-5 my-auto cw-difference-left">
                    <span class="cw-title-span">This is Dash</span>
                    <h2 class="mb-4 mt-2">What makes us different?</h2>
                    <p class="mb-5 lead">Dashoffers a unique combination of diverse, high-quality tasks, a
                        supportive community, and reliable payments. <a
                            href="{{route('register')}}" target="_blank" rel="noopener">Join
                            us now!</a></p>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-4">
                            <div class="">
                                <img decoding="async"
                                     src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/t-1-8.svg"
                                     class="workplace-icons" alt="+1 million microtasks"/>
                                <div class="card-body">
                                    <h4>+1 million microtasks</h4>
                                    <p>Unlock endless job opportunities with microtasking, and earn money anytime,
                                        anywhere
                                        in
                                        the world.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 mb-4">
                            <div class="">
                                <img decoding="async"
                                     src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-7.svg"
                                     class="workplace-icons" alt="+50K HitApps"/>
                                <div class="card-body">
                                    <h4>+50K HitApps</h4>
                                    <p>We are one of the UHRS providers with the highest HitApp availability. Benefit
                                        from
                                        our partner platform.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="">
                                <img decoding="async"
                                     src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-6.svg"
                                     class="workplace-icons" alt="+230K solved Support tickets"/>
                                <div class="card-body">
                                    <h4>+230K solved Support tickets</h4>
                                    <p>Need help? Our dedicated helpdesk is 24/7 available to quickly resolve any issues
                                        or
                                        requests you may have, whether you prefer to connect with our community or a
                                        support
                                        agent. We&#8217;re committed to taking care of.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="">
                                <img decoding="async"
                                     src="https://www.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tc-1-5.svg"
                                     class="workplace-icons" alt="ISO certified"/>
                                <div class="card-body">
                                    <h4>ISO certified since 2022</h4>
                                    <p>We have been providing a high-quality and safe working environment for over 15
                                        years
                                        and comply with international safety standards.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space section cw-testimonial-section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 col-lg-8 text-center mx-auto">
                    <span class="cw-title-span">join Global Community</span>
                    <h2 class="mb-4 mt-2">Do tasks, get paid. It’s that simple</h2>
                    <span class="cw-title-span">Join a community of 240,000+ taskers.</span>
                </div>
            </div>
            <div class="row mb-5 d-none">
                <div class="col-12 col-lg-4">
                    <img decoding="async"
                         src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tp-1.png"
                         class="img-fluid" alt="reviews"/>
                </div>
                <div class="col-12 col-lg-4">
                    <img decoding="async"
                         src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tp-1.png"
                         class="img-fluid" alt="reviews"/>
                </div>
                <div class="col-12 col-lg-4">
                    <img decoding="async"
                         src="https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/img/clickworker-landingpage/tp-1.png"
                         class="img-fluid" alt="reviews"/>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="card h-100 text-center">
                        <img decoding="async"
                             src="https://www.computersciencedegreehub.com/wp-content/uploads/2020/06/Is-Computer-Coding-Useful-for-the-Average-Person-1024x683.jpg"
                             class="card-img-top" alt="Text Creation" height="60%"/>

                        <div class="card-body">
                            <h4>Learn Easily</h4>
                            <p>Learn how to do tasks with our quick
                                online courses or free hands-on training
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card h-100 text-center">
                        <img decoding="async"
                             src="https://st4.depositphotos.com/4678277/25084/i/600/depositphotos_250843226-stock-photo-close-up-photo-beautiful-she.jpg"
                             class="card-img-top" alt="Text Creation"  height="60%"/>

                        <div class="card-body">
                            <h4>Complete Task</h4>
                            <p>Create informative texts, product descriptions or articles about a given
                                topic.
                                More about Text Creation
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card h-100 text-center">
                        <img decoding="async"
                             src="https://media.istockphoto.com/id/615823282/photo/happy-businessman-successfuly-completed-his-task-and-triumphing.jpg?s=612x612&w=0&k=20&c=pZwVhn5jD685RTUz4vMU91xjodHRII0q4y63qasq5MI="
                             class="card-img-top" alt="Text Creation"  height="60%"/>

                        <div class="card-body">
                            <h4>Get Paid Weekly</h4>
                            <p>Get paid fast via PayPal or AirTM based on
                                your quality & number of tasks completed
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="cw-cta-section section-space">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7 mx-auto text-center">
                    <h2 class="mb-4 mt-2">GTS Dash</h2>
                    <p class="mb-5 lead">Experience the benefits of Dashfirsthand by signing up today and
                        trying it out for yourself!</p>
                    <a href="{{route('register')}}"
                       class="app-cta-btn app-cta-btn-solid btn btn-primary btn-md mr-3">Signup</a>
                    <a href="{{route('login')}}"
                       class="app-cta-btn app-cta-btn-border btn btn-outline-primary btn-md">Login</a>
                </div>
            </div>
        </div>
    </section>

    <!-- TrustBox script -->
    <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
    <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
    <!-- End TrustBox script -->
    <!-- Swiper JS -->


    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 20,
            navigation: {
                nextEl: ".sw-next-2",
                prevEl: ".sw-prev-2",
            },
            loop: true,
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                1380: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                1380: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
            },
        });
        var swiper2 = new Swiper(".mySwiper2", {
            centeredSlides: false,
            spaceBetween: 40,
            slidesPerView: 3,
            pagination: {
                el: ".swiper-pagination",
            },
            navigation: {
                nextEl: ".sw-next-1",
                prevEl: ".sw-prev-1",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1380: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
            },

        });
        var swiper3 = new Swiper(".mySwiper3", {
            centeredSlides: true,
            spaceBetween: 40,
            slidesPerView: 1,
            pagination: {
                el: ".swiper-pagination",
            },
            navigation: {
                nextEl: ".sw-next-3",
                prevEl: ".sw-prev-3",
            },
        });
    </script>

    <section id="footer" class="footer-bg d-print-none test">
        <div class="container">
            <div id="column-footer" class="row">
                <div class="col-md-4 col-sm-12" id="footer-logo">

                    <img src="{{ URL::asset('img/brand/logo.png') }}" alt="Brand Logo">

                    <p class="mb-0">Globose Technology Solutions Pvt Ltd (GTS) is an AI data collection Company that
                        provides different Datasets like image datasets, video datasets, text datasets, speech datasets,
                        etc. to train your machine learning model. Contact Us</p>

                    <div class="dropdown header-locale ml-0" id="frontend-local">
                        <a class="nav-link icon" data-bs-toggle="dropdown" style="color: white">
                            <span class="fs-17 fa fa-globe pr-2"></span><span class="fs-12"
                                                                              style="vertical-align:middle">{{ Config::get('locale')[App::getLocale()]['code'] }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
                            <div class="local-menu">
                                @foreach (Config::get('locale') as $lang => $language)
                                    @if ($lang != App::getLocale())
                                        <a href="{{ route('locale', $lang) }}" class="dropdown-item d-flex"
                                           style="color: white !important;">
                                            <div class="text-info"><i
                                                    class="flag flag-{{ $language['flag'] }} mr-3"></i></div>
                                            <div>
                                                <span class="font-weight-normal fs-12">{{ $language['display'] }}</span>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>


                <div class="col-md-8 col-sm-12" id="footer-links">

                    <div class="row w-100">

                        <!-- INFORMATION LINKS -->
                        {{--									<div class="col-md-3 col-sm-12">--}}

                        {{--										<h5>{{ __('Information') }}</h5>--}}

                        {{--										<ul class="list-unstyled">--}}
                        {{--											<li><a href="https://aws.amazon.com" target="_blank">AWS Cloud</a></li>--}}
                        {{--										</ul>--}}

                        {{--									</div> <!-- END INFORMATION LINKS -->--}}


                        <!-- SOLUTIONS LINKS -->
                        <div class="col-md-4 col-sm-12">

                            <h5>{{ __('Site Pages') }}</h5>

                            <ul class="list-unstyled">
                                <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                                <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                            </ul>

                        </div> <!-- END SOLUTIONS LINKS -->


                        <!-- COMPANY LINKS -->
                        <div class="col-md-4 col-sm-12">

                            <h5>{{ __('Company') }}</h5>

                            <ul class="list-unstyled">
                                <li><a href="{{ route('terms') }}">{{ __('Terms and Conditions') }}</a></li>
                                <li><a href="{{ route('privacyPolicies') }}">{{ __('Privacy Policy') }}</a></li>
                            </ul>

                        </div> <!-- COMPANY LINKS -->


                        <!-- CONNECTION & NEWS LINKS -->
                        <div class="col-md-4 col-sm-12 footer-connect pr-0">

                            <h5>{{ __('Social Media') }}</h5>

                            <h6>{{ __('Follow up on social media to find out the latest updates') }}.</h6>

                            <ul id="footer-icons" class="list-inline">
                                @if (config('frontend.social_linkedin'))
                                    <a href="{{ config('frontend.social_linkedin') }}" target="_blank">
                                        <li class="list-inline-item"><i class="footer-icon fa-brands fa-linkedin"></i>
                                        </li>
                                    </a>
                                @endif
                                @if (config('frontend.social_twitter'))
                                    <a href="{{ config('frontend.social_twitter') }}" target="_blank">
                                        <li class="list-inline-item"><i class="footer-icon fa-brands fa-twitter"></i>
                                        </li>
                                    </a>
                                @endif
                                @if (config('frontend.social_instagram'))
                                    <a href="{{ config('frontend.social_instagram') }}" target="_blank">
                                        <li class="list-inline-item"><i class="footer-icon fa-brands fa-instagram"></i>
                                        </li>
                                    </a>
                                @endif
                                @if (config('frontend.social_facebook'))
                                    <a href="{{ config('frontend.social_facebook') }}" target="_blank">
                                        <li class="list-inline-item"><i class="footer-icon fa-brands fa-facebook"></i>
                                        </li>
                                    </a>
                                @endif

                            </ul>

                            <h5 class="mt-6 mb-4">{{ __('Get Started For Free') }}</h5>

                            <a href="{{ route('register') }}"
                               class="btn btn-primary pl-5 pr-5">{{ __('Sign Up Now') }}</a>

                        </div> <!-- END CONNECTION & NEWS LINKS -->

                    </div>


                </div> <!-- END FOOTER LINKS -->

                <div class="col-12">

                                <span
                                    class="copyright">Copyright © 2022 GTS. All rights reserved. | <a
                                        href="{{ route('terms') }}">Terms</a> | <a
                                        href="{{route('privacy')}}" target="_blank">Term & Condition</a></span>
                    <span class="copyright mt-0 pt-0"><a href="https://storyset.com" style="font-size: 12px;"
                                                         target="_blank" rel="noopener noreferrer"></a></span>

                </div>

            </div>
        </div>
    </section>

    <!--copyscapeskip-->
    <aside id="moove_gdpr_cookie_info_bar"
           class="moove-gdpr-info-bar-hidden moove-gdpr-align-center moove-gdpr-dark-scheme gdpr_infobar_postion_top"
           aria-label="GDPR Cookie Banner" style="display: none;">
        <div class="moove-gdpr-info-bar-container">
            <div class="moove-gdpr-info-bar-content">

                <div class="moove-gdpr-cookie-notice">
                    <p>We are using cookies to give you the best experience on our website. Find further information in
{{--                        our <a href="https://workplace.clickworker.com/en/agreements/10124" target="_blank"--}}
{{--                               rel="noopener">data protection policy</a>.</p>--}}
                </div>
                <!--  .moove-gdpr-cookie-notice -->
                <div class="moove-gdpr-button-holder">
                    <button class="mgbutton moove-gdpr-infobar-allow-all gdpr-fbo-0" aria-label="Accept All"
                            role="button">Accept All
                    </button>
                    <button class="mgbutton moove-gdpr-infobar-reject-btn gdpr-fbo-1 " aria-label="Reject All">Reject
                        All
                    </button>
{{--                    <button class="mgbutton moove-gdpr-infobar-settings-btn change-settings-button gdpr-fbo-2"--}}
{{--                            data-href="#moove_gdpr_cookie_modal" aria-label="Cookies settings">Cookies settings--}}
{{--                    </button>--}}
                </div>
                <!--  .button-container -->      </div>
            <!-- moove-gdpr-info-bar-content -->
        </div>
        <!-- moove-gdpr-info-bar-container -->
    </aside>
    <!-- #moove_gdpr_cookie_info_bar -->
    <!--/copyscapeskip-->
    <script type='text/javascript'
            src='https://www.clickworker.com/wp-content/plugins/structured-content/dist/app.build.js?ver=1.5.3'
            id='structured-content-frontend-js'></script>
    <script type='text/javascript'
            src='https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/js/popper.min.js'
            id='cw_popperJs-js'></script>
    <script type='text/javascript'
            src='https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/js/vendors-53cac2f8af.min.js'
            id='cw_vendorsJs-js'></script>
    <script type='text/javascript'
            src='https://cdn.clickworker.com/wp-content/themes/clickworkerV8/assets-dist/js/custom-15261bec9e.min.js'
            id='cw_customJs-js'></script>
    <script type='text/javascript' id='moove_gdpr_frontend-js-extra'>
        /* <![CDATA[ */
        var moove_frontend_gdpr_scripts = {
            "ajaxurl": "https:\/\/www.clickworker.com\/wp-admin\/admin-ajax.php",
            "post_id": "37913",
            "plugin_dir": "https:\/\/cdn.clickworker.com\/wp-content\/plugins\/gdpr-cookie-compliance",
            "show_icons": "all",
            "is_page": "1",
            "strict_init": "2",
            "enabled_default": {"third_party": 0, "advanced": 0},
            "geo_location": "false",
            "force_reload": "false",
            "is_single": "",
            "hide_save_btn": "false",
            "current_user": "0",
            "cookie_expiration": "365",
            "script_delay": "500",
            "close_btn_action": "1",
            "close_btn_rdr": "",
            "gdpr_scor": "true",
            "wp_lang": "_en"
        };
        /* ]]> */
    </script>
    <script type='text/javascript'
            src='https://www.clickworker.com/wp-content/plugins/gdpr-cookie-compliance/dist/scripts/main.js?ver=4.10.6'
            id='moove_gdpr_frontend-js'></script>
    <script type='text/javascript' id='moove_gdpr_frontend-js-after'>
        var gdpr_consent__strict = "true"
        var gdpr_consent__thirdparty = "true"
        var gdpr_consent__advanced = "true"
        var gdpr_consent__cookies = "strict|thirdparty|advanced"
    </script>


    <!--copyscapeskip-->
    <!-- V1 -->
    <div id="moove_gdpr_cookie_modal" class="gdpr_lightbox-hide" role="complementary" aria-label="GDPR Settings Screen">
        <div class="moove-gdpr-modal-content moove-clearfix logo-position-left moove_gdpr_modal_theme_v1">

            <button class="moove-gdpr-modal-close" aria-label="Close GDPR Cookie Settings">
                <span class="gdpr-sr-only">Close GDPR Cookie Settings</span>
                <span class="gdpr-icon moovegdpr-arrow-close"></span>
            </button>
            <div class="moove-gdpr-modal-left-content">

                <div class="moove-gdpr-company-logo-holder">
                    <img
                        src="https://d2v95urbopcvz7.cloudfront.net/wp-content/themes/clickworkerV7/assets-dist/img/logo/logo.png"
                        alt="clickworker.com" class="img-responsive"/>
                </div>
                <!--  .moove-gdpr-company-logo-holder -->
                <ul id="moove-gdpr-menu">

                    <li class="menu-item-on menu-item-privacy_overview menu-item-selected">
                        <button data-href="#privacy_overview" class="moove-gdpr-tab-nav"
                                aria-label="Cookie Declaration">
                            <span class="gdpr-nav-tab-title">Cookie Declaration</span>
                        </button>
                    </li>

                    <li class="menu-item-strict-necessary-cookies menu-item-off">
                        <button data-href="#strict-necessary-cookies" class="moove-gdpr-tab-nav"
                                aria-label="Strictly Necessary Cookies">
                            <span class="gdpr-nav-tab-title">Strictly Necessary Cookies</span>
                        </button>
                    </li>


                    <li class="menu-item-off menu-item-third_party_cookies">
                        <button data-href="#third_party_cookies" class="moove-gdpr-tab-nav"
                                aria-label="Additional Cookies">
                            <span class="gdpr-nav-tab-title">Additional Cookies</span>
                        </button>
                    </li>


                </ul>

                <div class="moove-gdpr-branding-cnt">
                </div>
                <!--  .moove-gdpr-branding -->      </div>
            <!--  .moove-gdpr-modal-left-content -->
            <div class="moove-gdpr-modal-right-content">
                <div class="moove-gdpr-modal-title">

                </div>
                <!-- .moove-gdpr-modal-ritle -->
                <div class="main-modal-content">

                    <div class="moove-gdpr-tab-content">

                        <div id="privacy_overview" class="moove-gdpr-tab-main">
                            <span class="tab-title">Cookie Declaration</span>
                            <div class="moove-gdpr-tab-main-content">
                                <p>This website uses cookies to provide you with the best user experience possible.<br/>
                                    Cookies are small text files that are cached when you visit a website to make the
                                    user experience more efficient.<br/>
                                    We are allowed to store cookies on your device if they are absolutely necessary for
                                    the operation of the site. For all other cookies we need your consent.</p>
                                <p>You can at any time change or withdraw your consent from the Cookie Declaration on
                                    our website. Find the link to your settings in our footer.</p>
                                <p>Find out more in our <a href="https://workplace.clickworker.com/en/agreements/33"
                                                           target="_blank">privacy policy</a> about our use of cookies
                                    and how we process personal data.</p>
                            </div>
                            <!--  .moove-gdpr-tab-main-content -->

                        </div>
                        <!-- #privacy_overview -->
                        <div id="strict-necessary-cookies" class="moove-gdpr-tab-main" style="display:none">
                            <span class="tab-title">Strictly Necessary Cookies</span>
                            <div class="moove-gdpr-tab-main-content">
                                <p>Necessary cookies help make a website usable by enabling basic functions like page
                                    navigation and access to secure areas of the website. The website cannot properly
                                    without these cookies.</p>
                                <div class="moove-gdpr-status-bar gdpr-checkbox-disabled checkbox-selected">
                                    <div class="gdpr-cc-form-wrap">
                                        <div class="gdpr-cc-form-fieldset">
                                            <label class="cookie-switch" for="moove_gdpr_strict_cookies">
                                                <span class="gdpr-sr-only">Enable or Disable Cookies</span>
                                                <input type="checkbox" aria-label="Strictly Necessary Cookies" disabled
                                                       checked="checked" value="check" name="moove_gdpr_strict_cookies"
                                                       id="moove_gdpr_strict_cookies">
                                                <span class="cookie-slider cookie-round" data-text-enable="Enabled"
                                                      data-text-disabled="Disabled"></span>
                                            </label>
                                        </div>
                                        <!-- .gdpr-cc-form-fieldset -->
                                    </div>
                                    <!-- .gdpr-cc-form-wrap -->
                                </div>
                                <!-- .moove-gdpr-status-bar -->
                                <div class="moove-gdpr-strict-warning-message" style="margin-top: 10px;">
                                    <p>If you disable this cookie, we will not be able to save your preferences. This
                                        means that every time you visit this website you will need to enable or disable
                                        cookies again.</p>
                                </div>
                                <!--  .moove-gdpr-tab-main-content -->

                            </div>
                            <!--  .moove-gdpr-tab-main-content -->
                        </div>
                        <!-- #strict-necesarry-cookies -->

                        <div id="third_party_cookies" class="moove-gdpr-tab-main" style="display:none">
                            <span class="tab-title">Additional Cookies</span>
                            <div class="moove-gdpr-tab-main-content">
                                <p>Any cookies that may not be particularly necessary for the website to function and is
                                    used specifically to collect user personal data via analytics, ads, other embedded
                                    contents are termed as additional cookies.</p>
                                <div class="moove-gdpr-status-bar">
                                    <div class="gdpr-cc-form-wrap">
                                        <div class="gdpr-cc-form-fieldset">
                                            <label class="cookie-switch" for="moove_gdpr_performance_cookies">
                                                <span class="gdpr-sr-only">Enable or Disable Cookies</span>
                                                <input type="checkbox" aria-label="Additional Cookies" value="check"
                                                       name="moove_gdpr_performance_cookies"
                                                       id="moove_gdpr_performance_cookies">
                                                <span class="cookie-slider cookie-round" data-text-enable="Enabled"
                                                      data-text-disabled="Disabled"></span>
                                            </label>
                                        </div>
                                        <!-- .gdpr-cc-form-fieldset -->
                                    </div>
                                    <!-- .gdpr-cc-form-wrap -->
                                </div>
                                <!-- .moove-gdpr-status-bar -->
                                <div class="moove-gdpr-strict-secondary-warning-message"
                                     style="margin-top: 10px; display: none;">
                                    <p>Please enable Strictly Necessary Cookies first so that we can save your
                                        preferences!</p>
                                </div>
                                <!--  .moove-gdpr-tab-main-content -->

                            </div>
                            <!--  .moove-gdpr-tab-main-content -->
                        </div>
                        <!-- #third_party_cookies -->


                    </div>
                    <!--  .moove-gdpr-tab-content -->
                </div>
                <!--  .main-modal-content -->
                <div class="moove-gdpr-modal-footer-content">
                    <div class="moove-gdpr-button-holder">
                        <button class="mgbutton moove-gdpr-modal-allow-all button-visible" role="button"
                                aria-label="Enable All">Enable All
                        </button>
                        <button class="mgbutton moove-gdpr-modal-save-settings button-visible" role="button"
                                aria-label="Save changes">Save changes
                        </button>
                    </div>
                    <!--  .moove-gdpr-button-holder -->        </div>
                <!--  .moove-gdpr-modal-footer-content -->
            </div>
            <!--  .moove-gdpr-modal-right-content -->

            <div class="moove-clearfix"></div>

        </div>
        <!--  .moove-gdpr-modal-content -->
    </div>
    <!-- #moove_gdpr_cookie_modal -->
    <!--/copyscapeskip-->

</div>
</body>
</html>

