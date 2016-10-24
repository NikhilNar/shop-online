angular.module("my_amazon",["ngRoute","ProductListModule","Services"])
    .config(["$routeProvider",function($routeProvider){
        $routeProvider.
        when('/product/:ASIN',{
            templateUrl:'partials/product-detail.html',
            controller:'ProductListController',
            controllerAs:'productListCtrl',
            resolve: {
                promise:function(SrvProducts,$route){
                    var ASIN={ASIN:$route.current.params.ASIN}
                  return  SrvProducts.getProductDetails(ASIN);
                }
            }
        }).
        when('/search/:category',{
            templateUrl:'partials/product-list.html',
            controller:'ProductListController',
            controllerAs:'productListCtrl',
            resolve: {
                promise:function(SrvProducts){
                  return  SrvProducts.getProductsBasedOnCategory();
                }
            }
        }).
        when('/',{
                templateUrl:'partials/product-list.html',
                controller:'ProductListController',
                controllerAs:'productListCtrl',
                resolve: {
                    promise:function(SrvProducts){
                        return  SrvProducts.getProductsBasedOnCategory();
                    }
                }
            }).
        otherwise({
            redirectTo:'/products'
        });
}]);