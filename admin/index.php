<?php
session_start();
if(isset($_SESSION['user'])){
	if($_SESSION['user']!="root"){
		echo "Access is denied";
		return;
	}
}else{
  echo "bạn chưa đăng nhập";
  return;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>ADMIN</title>
        <meta charset="utf-8">
		<script src="jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
		<script src="jquery.dataTables.min.js"></script>
		<script src="angular-datatables.min.js"></script>
		<script src="bootstrap.min.js"></script>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel="stylesheet" href="datatables.bootstrap.css">
	</head>
	<body ng-app="crudApp" ng-controller="crudController">

		<div class="container" ng-init="fetchData()">
			<br />
				<h3 align="center">Quản lý tài khoản</h3>
			<br />
			<div class="alert alert-success alert-dismissible" ng-show="success" >
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				{{successMessage}}
			</div>
			<div align="right">
				<button type="button" name="add_button" ng-click="addData()" class="btn btn-success">Add</button>
			</div>
			<br />
			<div class="table-responsive" style="overflow-x: unset;">
				<table datatable="ng" dt-options="vm.dtOptions" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Họ tên</th>
							<th>Địa chỉ</th>
							<th>Username</th>
							<th>Password</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="name in namesData">
							<td>{{name.hoten}}</td>
							<td>{{name.diachi}}</td>
							<td>{{name.USERNAME}}</td>
							<td>{{name.PASSWORD}}</td>
							<td><button type="button" ng-click="fetchSingleData(name.id)" class="btn btn-warning btn-xs">Edit</button></td>
							<td><button type="button" ng-click="deleteData(name.id)" class="btn btn-danger btn-xs">Delete</button></td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</body>
</html>

<div class="modal fade" tabindex="-1" role="dialog" id="crudmodal">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
    		<form method="post" ng-submit="submitForm()">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title">{{modalTitle}}</h4>
	      		</div>
	      		<div class="modal-body">
	      			<div class="alert alert-danger alert-dismissible" ng-show="error" >
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						{{errorMessage}}
					</div>
					<div class="form-group">
						<label>Họ Tên</label>
						<input type="text" name="hoten" ng-model="hoten" class="form-control" />
					</div>
					<div class="form-group">
						<label>Địa chỉ</label>
						<input type="text" name="diachi" ng-model="diachi" class="form-control" />
					</div>
					<div class="form-group">
						<label>username</label>
						<input type="text" name="USERNAME" ng-model="USERNAME" class="form-control" />
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="text" name="PASSWORD" ng-model="PASSWORD" class="form-control" />
					</div>
	      		</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="hidden_id" value="{{hidden_id}}" />
	      			<input type="submit" name="submit" id="submit" class="btn btn-info" value="{{submit_button}}" />
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        	</div>
	        </form>
    	</div>
  	</div>
</div>



<script>

var app = angular.module('crudApp', ['datatables']);
app.controller('crudController', function($scope, $http){

	$scope.success = false;

	$scope.error = false;

	$scope.fetchData = function(){
		$http.get('fetch_data.php').success(function(data){
			$scope.namesData = data;
			console.log(data);
		});
	};

	$scope.openModal = function(){
		var modal_popup = angular.element('#crudmodal');
		modal_popup.modal('show');
	};

	$scope.closeModal = function(){
		var modal_popup = angular.element('#crudmodal');
		modal_popup.modal('hide');
	};

	$scope.addData = function(){
		$scope.modalTitle = 'Add Data';
		$scope.submit_button = 'Insert';
		$scope.openModal();
	};

	$scope.submitForm = function(){
		$http({
			method:"POST",
			url:"insert.php",
			data:{'hoten':$scope.hoten,'diachi':$scope.diachi, 'action':$scope.submit_button,'USERNAME':$scope.username,'PASSWORD':$scope.password ,'id':$scope.hidden_id}
		}).success(function(data){
			if(data.error != '')
			{
				$scope.success = false;
				$scope.error = true;
				$scope.errorMessage = data.error;
			}
			else
			{
				$scope.success = true;
				$scope.error = false;
				$scope.successMessage = data.message;
				$scope.form_data = {};
				$scope.closeModal();
				$scope.fetchData();
			}
		});
	};

	$scope.fetchSingleData = function(id){
		$http({
			method:"POST",
			url:"insert.php",
			data:{'id':id, 'action':'fetch_single_data'}
		}).success(function(data){
			$scope.username=data.USERNAME;
			$scope.password=data.PASSWORD;
			$scope.hoten = data.hoten;
			$scope.diachi = data.diachi;
			$scope.hidden_id = id;
			$scope.modalTitle = 'Sửa';
			$scope.submit_button = 'Edit';

			$scope.openModal();
		});
	};

	$scope.deleteData = function(id){
		if(confirm("bạn có chắc muốn xóa user?"))
		{
			$http({
				method:"POST",
				url:"insert.php",
				data:{'id':id, 'action':'Delete'}
			}).success(function(data){
				$scope.success = true;
				$scope.error = false;
				$scope.successMessage = data.message;
				$scope.fetchData();
			});
		}
	};

});

</script>