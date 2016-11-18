<!DOCTYPE html>
<html lang="en-US">


<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.16/angular-resource.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>


<body>

    <div ng-app="myApp" ng-controller="myCtrl">

        <div class="row">
            <div class="col-md-4">
                <h1>Items</h1>
                <table class="table table-bordered">
                    <tr ng-repeat="i in items">
                        <td>{{i.id}}</td>
                        <td>{{i.value}}</td>
                        <td>
                            <button class="btn btn-info" ng-click="editItem(i)">Edit</button>
                            <button class="btn btn-danger" ng-click="removeItem(i)">Delete</button>
                        </td>
                    </tr>
                </table>

                <form class="form-group">
                    <input class="form-control" type="text" ng-model="newItem"></input>
                    <button class="btn btn-success" ng-click="addItem(newItem)">Add</button>
                </form>
            </div>
        </div>


    </div>

<script>

    angular.module('myApp',['ngResource']);

    var app = angular.module('myApp', ['ngResource']);

    app.factory('provider', function ($resource){
        return {
            items : $resource('items.php?id=:id', {id: '@id'},
                {
                    create : {method:'PUT', params:{add:true}, url:'items.php'}
                })
        };
    });

    app.controller('myCtrl', ['$scope', 'provider', function($scope, provider) {

        $scope.addItem = function(text)
        {
            provider.items.create({value:text}, function(it){
                    $scope.items.push(it);
                });
        }

        $scope.removeItem = function(item)
        {
            provider.items.remove({id:item.id},
                function(){
                    $scope.items.splice($scope.items.indexOf(item), 1);
                });
        }

        $scope.editItem = function(item)
        {
            bootbox.prompt("Enter new value for '"+item.value+"'", function(result) {
                if (result === null) {
                } else {
                    provider.items.save({id:item.id, value:result}, function(){
                        item.value = result;
                    });
                }
            });
        }

        $scope.items = provider.items.query();
    }]);
</script>

</body>
</html>