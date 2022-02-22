<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-sanitize/1.8.2/angular-sanitize.js" integrity="sha512-1U5h12TxSFIDbYnBIrGiRcfywurjIV3+DfYiwWOMuz64I6jFTYvWHZRaUSUmql2grcZ+U8ZrZOQ5zPjb1qtvSQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://angular-ui.github.io/ui-router/release/angular-ui-router.min.js"></script>    <title>FAQs</title>
    
</head>
<body>
    
    <div ng-app="FaqApp">
        <div ng-controller="NavController as nc">
            <nav class="navbar navbar-expand-lg navbar-light bg-light" >
                <div class="container-fluid">
                  <a class="navbar-brand" href="#">Navbar</a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0" ng-repeat="item in nc.navData">
                      
                       
                      <li class="nav-item dropdown" ng-if="item.subTitleAlias.length>0">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          @{{item.primaryTitle}}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown" >
                            <div ng-repeat="i in item.subTitleAlias">
                                
                                <li><a class="dropdown-item" ui-sref="faq({ titleAlias: i.titleAlias })" >@{{i.title}}</a></li>
                            </div>
                        </ul>
                      </li>
                     
                    </ul>
                  </div>
                </div>
            </nav>
        </div>
        <div class="container">
            <ui-view></ui-view>
        </div>
        
        
    </div>
    <script>
        angular.module('FaqApp', ['ngSanitize', 'ui.router'])
        .config(function($stateProvider, $urlRouterProvider/*, $locationProvider*/){
        $urlRouterProvider.otherwise("/home");

		$stateProvider
			.state('faq', {
				name: 'faq',
				url: '/faq/{titleAlias}',
				template: '<h1 >@{{fc.individualData.heading}}</h1>\
                           <div ng-bind-html="fc.individualData.body"></div>',
                controller: 'FAQController as fc',
			});
        //$locationProvider.html5mode(true);
          
	    })
        .controller('FAQController', FAQController)
        .controller('NavController', NavController)
        .service('DataService',DataService);
        FAQController.$inject=['$stateParams', 'DataService'];
        function FAQController($stateParams, DataService) {
            this.titleAlias=$stateParams['titleAlias'];
            this.individualData=DataService.individualDataquery(this.titleAlias);
        }
        NavController.$inject=['$stateParams', 'DataService'];
        function NavController($stateParams, DataService) {
            this.navData=DataService.navDataquery();
        }
        function DataService() {
            this.data={
                /*'home':{
                    'heading':"Button Request Documentation",
                    'body':'<p></p>',
                },*/
                'classification-of-right-of-shared-device':{
                    'heading':"Classification of Right Of Shared Device",
                    'body':'<p>Basic: The User may only view the messages and the information related to \
                    the device.</p><p>Intermediate: The user may pin or delete the message but can only \
                    view the information related to the device</p><p>Advanced: The user may pin or delete the message and\
                    amend the settings of the device.</p>',
                },
                'share-device-home':{
                    'heading':"Device Sharing",
                    'body':'<p>Device Sharing allows you to share the device to others.</p>',
                },
            };
            this.navHeading=[
                /*{
                    'primaryTitle':"Home",
                    'primaryTitleAlias':"home",
                    'subTitleAlias': []
                },*/
                {
                    'primaryTitle':"Device Share",
                    'primaryTitleAlias':"",
                    'subTitleAlias': [
                        {'titleAlias':'share-device-home', 'title': 'Home'},
                        {'titleAlias':'classification-of-right-of-shared-device', 'title': 'Right Classification'}
                    ]
                }
            ];
            this.individualDataquery=function(titleAlias){
                return this.data[titleAlias];
            } 
            this.navDataquery=function(){
                return this.navHeading;
            } 
        }
        
       
    </script>
    
    
</body>
</html>

<li class="nav-item" hidden>
    <a class="nav-link active" aria-current="page" href="#">Home</a>
  </li>
