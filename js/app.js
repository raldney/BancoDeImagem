var app = angular.module('Application', ['ngRoute']);

app.config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when('/', {templateUrl: 'paginas/usuarios/listarUsuarios.html', controller: 'listarUsuariosController'}).
      when('/Usuarios', {templateUrl: 'paginas/usuarios/listarUsuarios.html', controller: 'listarUsuariosController'}).
      when('/NovoUsuario', {templateUrl: 'paginas/usuarios/novoUsuario.html', controller: 'adicionarUsuarioController'}).
      when('/EditarUsuario/:id', {templateUrl: 'paginas/usuarios/editarUsuario.html', controller: 'editarUsuarioController'}).
      //Eventos
      when('/Eventos', {templateUrl: 'paginas/eventos/listarEventos.html', controller: 'listarEventosController'}).
      when('/NovoEvento', {templateUrl: 'paginas/eventos/novoEvento.html', controller: 'adicionarEventoController'}).
      when('/EditarEvento/:id', {templateUrl: 'paginas/eventos/editarEvento.html', controller: 'editarEventoController'}).
      otherwise({redirectTo: '/'});
}]);

/*USUARIOS*/
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

/*EVENTOS*/

app.controller ('listarEventosController',[
  '$scope','$http',
  function ($scope, $http) {
      $http.get('api/Eventos').success(function(data) {
        $scope.eventos = data;  
      });
  }    
]),

app.controller ('adicionarEventoController',[
  '$scope','$http','$location',
  function ($scope, $http, $location) {
  
      $scope.master = {};
      $scope.activePath = null;

      $scope.novoUsuario = function(evento, AddNewForm) {
          $http.post('api/NovoEvento', evento).success(function(){
              $scope.reset();
              $scope.activePath = $location.path('/');
          });

          $scope.reset = function() {
              $scope.evento = angular.copy($scope.master);
          };

          $scope.reset();
      };

  }
]),

app.controller('editarEventoController',[

  '$scope','$http','$location','$routeParams',
  function ($scope, $http, $location, $routeParams) {

      var id = $routeParams.id;
      $scope.activePath = null;

      $http.get('api/Eventos/'+id).success(function(data) {
        $scope.EventoDetalhes = data;
      });

      $scope.EditarEvento = function(evento) {
          $http.put('api/Eventos/'+id, evento).success(function(data) {
          $scope.EventoDetalhes = data;
          $scope.activePath = $location.path('/');
        });

      };

      $scope.DeletarEvento = function(evento) {
          var deleteCustomer = confirm('Você tem certeza que deseja excluir esse evento?');
          if (deleteCustomer) {
              $http.delete('api/Eventos/'+evento.id);
              $scope.activePath = $location.path('/');
          }        
      };

  }
]);