/**
 * Created by Ibrahim on 8/28/2014.
 */
angular.module('esn').controller('mainCtrl', function ($scope, $http, localStorageService, $Auth, $location) {

    if (localStorageService.isSupported) {
        var user = localStorageService.get('esnSessionUser');
        console.log(user);
        if (user != null) {
            $Auth.setUserFromSession(user);
            console.log(user);
        }
    } else {
        console.log('Local Storage Not Supported in this Browser');
    }

})
    .filter('custom', function () {
        return function (data) {
            for (var i = 1; i < data.length; i++) {
                for (var k = i; k > 0 && data[k]['created'] < data[k - 1]['created']; k--) {
                    var temp = data[k];
                    data[k] = data[k - 1];
                    data[k - 1] = temp;
                }

            }
            return data;

        };


    }).controller('NotificationsController', function ($scope, $http) {
        $scope.notifications = [];
        $scope.loadingNotifications = false;
        $http.get('http://imadevelopers.com/esn/users/getNotifications.json', {withCredentials: true})
            .success(function (data) {
                console.log(data);
                if (data.result.success) {
                    $scope.notifications = data.result.notifications;
                } else {
                    console.log(data);
                }
            }).error(function (error) {
                console.log(error);
            });
        $scope.markRead = function ($notification, $index) {

            return $http.post('http://imadevelopers.com/esn/users/markRead.json', $notification, {withCredentials: true})
                .success(function (data) {
                    if (data.result.success) {
                        $scope.notifications.splice($index, 1);
                    }
                }).error(function (error) {
                    console.log(error);
                });
        };
        $scope.markAll = function () {
            if ($scope.notifications.length > 0) {
                var status = $scope.markRead($scope.notifications[0], 0);
                status.then(function () {
                    $scope.markAll();
                });
            } else {

            }

        }
    });