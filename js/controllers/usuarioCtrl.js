'use strict';
var app = angular.module('BancoDeImagens', ['ngRoute']);

app.controller ('listarUsuariosController',[
  '$scope','$http',
  function ($scope, $http) {
      $http.get('api/Usuarios').success(function(data) {
        $scope.usuarios = data;  
      });
  }    
]),

app.controller ('adicionarUsuarioController',[
  '$scope','$http','$location',
  function ($scope, $http, $location) {
  
      $scope.master = {};
      $scope.activePath = null;

      $scope.novoUsuario = function(usuario, AddNewForm) {
          $http.post('api/NovoUsuario', usuario).success(function(){
              $scope.reset();
              $scope.activePath = $location.path('/');
          });

          $scope.reset = function() {
              $scope.usuario = angular.copy($scope.master);
          };

          $scope.reset();
      };

  }
]),

app.controller('editarUsuarioController',[

  '$scope','$http','$location','$routeParams',
  function ($scope, $http, $location, $routeParams) {

      var id = $routeParams.id;
      $scope.activePath = null;

      $http.get('api/Usuarios/'+id).success(function(data) {
        $scope.UsuarioDetalhes = data;
      });

      $scope.EditarUsuario = function(usuario) {
          $http.put('api/Usuarios/'+id, usuario).success(function(data) {
          $scope.UsuarioDetalhes = data;
          $scope.activePath = $location.path('/');
        });

      };

      $scope.DeletarUsuario = function(usuario) {
          var deleteCustomer = confirm('Você tem certeza que deseja excluir esse usuário?');
          if (deleteCustomer) {
              $http.delete('api/Usuarios/'+usuario.id);
              $scope.activePath = $location.path('/');
          }        
      };

  }
]);