var app = angular.module('BancoDeImagens', ['ngRoute', 'ngAnimate', 'ngResource',  'ngCookies']);

app.run(function($rootScope, $location, $cookieStore) {
        $rootScope.$on('$routeChangeStart', function(event, next, current) {
            if ($cookieStore.get('logado') == false || $cookieStore.get('logado') == null) {
                $location.path('/');
            } else {
                var usuario = $cookieStore.get('usuario');
                if (next.templateUrl == 'paginas/outras/login.html' && usuario.tipo == 'fotografo') {
                    $location.path('/Eventos');
                }else if(next.templateUrl == 'paginas/outras/login.html' && usuario.tipo == 'admin'){
                    $location.path('/Usuarios');
                }else if(next.templateUrl == 'paginas/outras/login.html' && usuario.tipo == 'evento'){
                    $location.path('/ListarFotos/'+usuario.evento);
                }
            }
        })
    })
    .config(['$routeProvider', function($routeProvider) {
        $routeProvider
        .when('/', {
        templateUrl: 'paginas/outras/login.html',
        controller: 'LoginController'
        })
            //Usuarios
        .when('/Usuarios', {
            templateUrl: 'paginas/usuarios/listarUsuarios.html',
            controller: 'listarUsuariosController'
        }).
        when('/NovoUsuario', {
            templateUrl: 'paginas/usuarios/novoUsuario.html',
            controller: 'adicionarUsuarioController'
        }).
        when('/EditarUsuario/:id', {
                templateUrl: 'paginas/usuarios/editarUsuario.html',
                controller: 'editarUsuarioController'
            }).
            //Eventos
        when('/Eventos', {
            templateUrl: 'paginas/eventos/listarEventos.html',
            controller: 'listarEventosController'
        }).
        when('/NovoEvento', {
            templateUrl: 'paginas/eventos/novoEvento.html',
            controller: 'adicionarEventoController'
        }).
        when('/EditarEvento/:id', {
            templateUrl: 'paginas/eventos/editarEvento.html',
            controller: 'editarEventoController'
        }).
        when('/ListarFotos/:id', {
            templateUrl: 'paginas/eventos/listarFotos.html',
            controller: 'listarFotosController'
        }).
        otherwise({
            redirectTo: '/'
        });
    }]);

app.controller('LoginController', function ($scope, $log, $cookieStore, $location,$http) {
    function usuarioSessao(usr) {
      $scope.usuarioLogado.nome = usr.nome;
      $scope.usuarioLogado.tipo = usr.tipo;
      $scope.usuarioLogado.logado = true;
      
      $log.info($scope.usuarioLogado);

      $cookieStore.put('logado', true);
      $cookieStore.put('usuario', usr);
      window.location.reload()

    };

    $scope.iniciarSessao = function() {
        $http.post('api/Login',$scope.usuarioLogado).success(function(data) {
            console.log(data);
                if(data != null && data != "false"){
                    usuarioSessao(data);
                }
                else{
                    alert("Login ou Senha invalido!");
                }
            })
        };

    $scope.sair = function() {
      $scope.usuarioLogado = {nombre: "", puesto: '', estaConectado: ''};

      $cookieStore.remove('logado');
      $cookieStore.remove('usuario');

      window.location.reload()
    };


    if($cookieStore.get('logado')){
        $scope.logado = $cookieStore.get('logado');
        $scope.usuarioLogado = $cookieStore.get('usuario');
        if($scope.usuarioLogado.tipo == 'fotografo'){
            $scope.usuarioLogado.usuarios = false;
            $scope.usuarioLogado.eventos = true;
            $scope.usuarioLogado.fotos = false;
        }
        if($scope.usuarioLogado.tipo == 'evento'){
            $scope.usuarioLogado.usuarios = false;
            $scope.usuarioLogado.eventos = false;
            $scope.usuarioLogado.fotos = true;
        }
        if($scope.usuarioLogado.tipo == 'admin'){
            $scope.usuarioLogado.usuarios = true;
            $scope.usuarioLogado.eventos = true;
            $scope.usuarioLogado.fotos = false;
        }


    }

});

app.controller('listarUsuariosController', [
        '$scope', '$http',
        function($scope, $http) {
            $http.get('api/Usuarios').success(function(data) {
                $scope.usuarios = data;
            });
        }
    ]),

    app.controller('adicionarUsuarioController', [
        '$scope', '$http', '$location',
        function($scope, $http, $location) {

            $scope.master = {};
            $scope.activePath = null;

            $scope.novoUsuario = function(usuario, AddNewForm) {
                $http.post('api/NovoUsuario', usuario).success(function() {
                    $scope.reset();
                    $scope.activePath = $location.path('/Usuarios');
                });

                $scope.reset = function() {
                    $scope.usuario = angular.copy($scope.master);
                };

                $scope.reset();
            };

        }
    ]),

    app.controller('editarUsuarioController', [

        '$scope', '$http', '$location', '$routeParams',
        function($scope, $http, $location, $routeParams) {

            var id = $routeParams.id;
            $scope.activePath = null;

            $http.get('api/Usuarios/' + id).success(function(data) {
                $scope.UsuarioDetalhes = data;
            });

            $scope.EditarUsuario = function(usuario) {
                $http.put('api/Usuarios/' + id, usuario).success(function(data) {
                    $scope.UsuarioDetalhes = data;
                    $scope.activePath = $location.path('/Usuarios');
                });

            };

            $scope.DeletarUsuario = function(usuario) {
                var deleteCustomer = confirm('Você tem certeza que deseja excluir esse usuário?');
                if (deleteCustomer) {
                    $http.delete('api/Usuarios/' + usuario.id);
                    $scope.activePath = $location.path('/Usuarios');
                }
            };

        }
    ]);

    app.controller('listarEventosController', [
        '$scope', '$http',
        function($scope, $http) {
            $http.get('api/Eventos').success(function(data) {
                $scope.eventos = data;
            });
        }
    ]),

    app.controller('adicionarEventoController', [
        '$scope', '$http', '$location',
        function($scope, $http, $location) {

            $scope.master = {};
            $scope.activePath = null;

            $scope.novoEvento = function(evento, AddNewForm) {
                $http.post('api/NovoEvento', evento).success(function() {
                    $scope.reset();
                    $scope.activePath = $location.path('/Eventos');
                });

                $scope.reset = function() {
                    $scope.evento = angular.copy($scope.master);
                };

                $scope.reset();
            };

        }
    ]),

    app.controller('editarEventoController', [
        '$scope', '$http', '$location', '$routeParams',
        function($scope, $http, $location, $routeParams) {
            var id = $routeParams.id;
            $scope.activePath = null;
            $http.get('api/Eventos/' + id).success(function(data) {
                $scope.EventoDetalhes = data;
            });

            $scope.EditarEvento = function(evento) {
                $http.put('api/Eventos/' + id, evento).success(function(data) {
                    $scope.EventoDetalhes = data;
                    $scope.activePath = $location.path('/Eventos');
                });

            };

            $scope.DeletarEvento = function(evento) {
                var deleteCustomer = confirm('Você tem certeza que deseja excluir esse evento?');
                if (deleteCustomer) {
                    $http.delete('api/Eventos/' + evento.id);
                    $scope.activePath = $location.path('/Eventos');
                }
            };

        }
    ]);


    app.controller('listarFotosController', [
        '$scope','fileUpload', '$http', '$location', '$routeParams',
        function($scope, fileUpload, $http, $location, $routeParams) {
             var id = $routeParams.id; // Id do evento;

                $http.get('api/Fotos/'+ id).success(function(data) {
                    $scope.fotos = data;
                });


                
                

             $scope.adicionarFotos = function(){
                var files = $scope.novasFotos;
                var uploadUrl = "/api/fileUpload/" + id;
                    console.log(files);

                if(files){
                    for (var i = 0, f; f = files[i]; i++) {
                        fileUpload.uploadFileToUrl(f, uploadUrl);
                    }
                    $http.get('api/Fotos/'+ id).success(function(data) {
                        $scope.fotos = data;
                    });
                }else{
                    console.log(files);
                    alert("Selecione alguma foto!");
                }
                

            };

            $scope.escolherFoto = function(foto){
                 $http.put('api/Fotos', foto).success(function(data) {
                    $scope.activePath = $location.path('/ListarFotos/'+id);
                });

                  $http.get('api/Fotos/'+ id).success(function(data) {
                    $scope.fotos = data;
                });
            }

             $scope.finalizarEscolha = function(foto){
                  $http.get('api/EnviarFotos/'+ id).success(function(data) {
                    alert(data);
                });
            }

        }
    ]);


    app.directive('fileModel', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var model = $parse(attrs.fileModel);
                var modelSetter = model.assign;
                element.bind('change', function(){
                    scope.$apply(function(){
                        modelSetter(scope, element[0].files);
                    });
                });
            }
        };
    }]);

    app.service('fileUpload', ['$http', function ($http) {
        this.uploadFileToUrl = function(file, uploadUrl){
            var fd = new FormData();
            fd.append('file', file);
            $http.post(uploadUrl, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            })
            .success(function(){
            })
            .error(function(){
            });
        }
    }]);

