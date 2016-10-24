angular.module("Services",[])
    .service("ProductListService",function(){
        var productListservice=this;
        productListservice.prop={
            textSearch:''
        };
        productListservice.dropDownValue={category:'All'};
        productListservice.ASIN={ASIN:''};
    });



angular.module("Services")
.factory("SrvProducts",["$q","$http","ProductListService",function($q,$http,ProductListService) {
        return {
            getProductsBasedOnCategory: function () {
                var deferred = $q.defer();
                $http({
                    method: 'POST',
                    url: 'http://localhost/my_amazon/index.php/amazondata/test/',
                    data:ProductListService.dropDownValue,
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (data) {
                    deferred.resolve(data);
                });
                return deferred.promise;
            },

            getProductDetails: function(ASIN){
                var deferred = $q.defer();
                $http({
                    method: 'POST',
                    url: 'http://localhost/my_amazon/index.php/amazondata/test1/',
                    data:ASIN,
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (data) {
                    deferred.resolve(data);
                });
                return deferred.promise;
            }
        }
    }]);