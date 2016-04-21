<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
?>
<!doctype html>
<html ng-app="myApp">
<head>
    <meta charset="utf-8">
    <!-- TODO: dynamic title -->
    <title>HRis API Documentation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <!-- styles CDN -->
    <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/angular_material/1.0.5/angular-material.min.css">

    <!-- inject:css -->
    <link rel="stylesheet" href="./vendor/material-swagger-ui/github-markdown.min.css">
    <link rel="stylesheet" href="./vendor/material-swagger-ui/swagger-ui-material.min.css">
    <!-- endinject -->

    <style>
        .ng-cloak {
            display: none;
        }
    </style>
</head>
<body ng-controller="DetailController as vm" layout="row" class="ng-cloak sw-ui-md"
      ng-include="'views/app.layout.html'">

<!-- scripts CDN -->
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-animate.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-aria.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-messages.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angular_material/1.0.5/angular-material.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-sanitize.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/showdown/1.3.0/showdown.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/js-yaml/3.5.3/js-yaml.min.js"></script>

<!-- inject:js -->
<script src="./vendor/material-swagger-ui/swagger-ui-material.full.min.js"></script>
<!-- endinject -->

<script type="text/javascript">
    angular.module('myApp', [
            'sw.ui.md',
            // 'sw.plugin.auth',
            'sw.plugin.markdown',
            // 'sw.plugin.xmlFormater',
            'sw.plugin.operations',
            'sw.plugin.sort',
            'sw.plugin.parser',
            'sw.plugin.base',
            'sw.plugin.split',
            'sw.plugin.transform',
            'sw.plugin.yaml'
            // 'sw.plugin.externalReferences'
        ])
        .config(function ($mdThemingProvider, $logProvider, $windowProvider) {
            var $window = $windowProvider.$get();
            var search = {};
            var query = $window.location.search.substring(1);
            var vars = query.split('&');

            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split('=');
                search[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }

            $mdThemingProvider.definePalette('accent', $mdThemingProvider.extendPalette(search.accent || 'grey', {
                // tweaking md-button.md-accent.md-focused background-color
                '700': 'dadada'
            }));

            //noinspection JSUnresolvedFunction
            $mdThemingProvider
                .theme('default')
                .primaryPalette(search.primary || 'teal')
                .accentPalette('accent')
                .warnPalette(search.warn || 'amber')
                .foregroundPalette[3] = 'rgba(0, 0, 0, 0.4)';

            if ($window.location.hostname !== 'localhost') {
                $logProvider.debugEnabled(false);
            }
        })
        .run(function ($location, $mdToast, $log, $window, data) {
            //noinspection JSCheckFunctionSignatures
            var swaggerUrl = './docs'

            data.setUrl(swaggerUrl);
            // data.validatorUrl = 'http://online.swagger.io/validator';

            // error management
            function myErrorHandler(error) {
                var e = error || {};
                var m = 'Something is wrong';
                $mdToast.show($mdToast.simple().textContent(e.statusText || e.message || m));
                $log.error(error || m);
            }
        });
</script>
</body>
</html>
