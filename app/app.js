
    /**
     * Initialize Application
     *
     * @module player
     */
    var app = angular.module('player', [

        'ngRoute',

        'player.mediactrl',
        'player.detailctrl',
        'player.playctrl',
        'player.playlistctrl'

    ]);


    /**
     * Setup routing for the application
     * @submodule {config}
     */
    app.config(['$routeProvider', function( $routeProvider ) {

        $routeProvider
            .when('/', {
                templateUrl: 'app/partials/view.media.html'
            })
            .when('/detail', {
                templateUrl: 'app/partials/view.detail.html'
            })
            .when('/play', {
                templateUrl: 'app/partials/view.play.html'
            })
            .when('/playlist', {
                templateUrl: 'app/partials/view.playlist.html'
            });
    }]);


