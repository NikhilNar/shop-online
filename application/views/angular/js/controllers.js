/**
 * Created by Nikhil on 22-11-2015.
 */

angular.module("ProductListModule",["Services"])
    .controller("ProductListController",["ProductListService","promise",function(ProductListService,promise){
    this.model=ProductListService.prop;
    this.products=promise.data;
    this.dropDownValue=ProductListService.dropDownValue;
}]);

angular.module("ProductListModule")
    .controller("HeaderInfoController",["ProductListService","SrvProducts","$location",function(ProductListService,SrvProducts,$location){
        this.model=ProductListService.prop;
        this.dropDownValue=ProductListService.dropDownValue;
        this.submitForm=function(){
            $location.path("/search/"+this.dropDownValue.category);
        }
    }]);

angular.module("ProductListModule")
.controller("LoaderController",["$scope",function($scope){
        var viewloader=this;
        viewloader.isViewLoading=false;
        $scope.$on('$routeChangeStart',function(){
           viewloader.isViewLoading=true;
        });
        $scope.$on('$routeChangeSuccess',function(){
            viewloader.isViewLoading=false;
        });
        $scope.$on('$routeChangeError',function(){
            viewloader.isViewLoading=false;
        });
    }]);