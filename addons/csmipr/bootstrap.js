require.config({
    paths: {
    	'csmipr_xcore': '../addons/csmipr/library/xcore/js/xcore',
        'csmipr_csminputstyle': '../addons/csmipr/library/xcore/js/csminputstyle',
        'jquery.simple-color': '../addons/csmipr/library/xcore/js/jquery.simple-color',
    },
    shim: {
        'csmipr_xcore': {
            deps: ["css!../addons/csmipr/library/xcore/css/xcore.css"]
        },
        'csmipr_csminputstyle': {
            deps: ["css!../addons/csmipr/library/xcore/css/csminputstyle.css"]
        },
    }
});