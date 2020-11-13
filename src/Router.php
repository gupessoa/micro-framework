<?php

namespace GuPes\Framework;

class Router
{
    // essa variávei vai guardar as rotas registradas
    private $routes = [];
    // o método HTTP atual
    private $method;
    // a url atual
    private $path;
    private $params; // uma variável nova para armazenar os valores

    // injeto o método HTTP e a url atual na minha classe
    public function __construct($method, $path)
    {
        $this->method = $method;
        $this->path = $path;
    }

    // este método recebe dois parâmetros, $route e $path
    // $route é a rota registrada que vamos testar
    // $path e a url amigável que quero procurar
    private function checkUrl(string $route, $path)
    {
        // aqui eu pego tudo o que tiver entre {} (chaves)
        // na rota e armazeno na variável $variables, por exemplo:
        // a rota "/ola-{nome}" (na variáve $route)
        // vai retornar "nome" dentro do array $variables
        preg_match_all('/\{([^\}]*)\}/', $route, $variables);

        // Eu preciso transformar a rota em expressão regular
        // mas o caracter / pode ter significado.
        // Eu estou trocando todos para \/, isso vai dizer
        // para a expressão regular que o / significa / e não
        // adiciona nenhuma regra especial.
        $regex = str_replace('/', '\/', $route);

        // agora eu vou pegar TODAS as variáves entre {} em
        // um valor de regex (expressão regular) real
        // eu quero que entre os possíveis caracteres estejam
        // letras entre a e z (maiúsculas e minúsculas)
        // números de 0 a 9, híphen, underline e espaços
        // qualquer outro caracter não será encontrado
        foreach ($variables[0] as $k => $variable) {
            $replacement = '([a-zA-Z0-9\-\_\ ]+)';
            $regex = str_replace($variable, $replacement, $regex);
        }
        // removo todas as chaves ({})
        $regex = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9+])', $regex);
        // executo (finalmente) as expressões regulares
        // adicionei a variável $params para "guardar" os parâmetros variáveis
        // que a regex encontrar
        $result = preg_match('/^' . $regex . '$/', $path, $params);
        // guardo na variável params da classe
        $this->params = $params;

        // O $result retorna a quantidade de valores encontrados
        return $result;
    }

    // para facilitar o uso eu criei esse método
    // atalho para o add
    public function get(string $route, $action)
    {
        $this->add('GET', $route, $action);
    }

    // para facilitar o uso eu criei esse método
    // atualho para o add
    public function post(string $route, $action)
    {
        $this->add('POST', $route, $action);
    }

    /*
        este método é o que registra as rotas, eu crio um array com dois níveis
        o primeiro com o método HTTP o segundo com a url registrada
        o valor é a ação a ser executada

        Note que eu eu passei o método e a url da rota diretamente na chave
        do array, isso vai evitar duplicidade.

        Como exercício, você pode adicionar uma verificação para ver se a rota
        já foi registrada ANTES.
    */
    public function add(string $method, string $route, $action)
    {
        $this->routes[$method][$route] = $action;
    }

    // um método para retornar os parâmetros sem permitir
    // que sejam alterados fora da classe
    public function getParams()
    {
        return $this->params;
    }

    public function handler()
    {
        if (empty($this->routes[$this->method])) {
            return false;
        }

        if (isset($this->routes[$this->method][$this->path])) {
            return $this->routes[$this->method][$this->path];
        }

        // Pego TODAS as rotas dentro do método http informado
        foreach ($this->routes[$this->method] as $route => $action) {
            // e testo cada uma
            $result = $this->checkUrl($route, $this->path);
            // se encontrar um resultado
            if ($result >= 1) {
                // retorno a $action (assim como no if anterior)
                return $action;
            }
        }

        // se não achar nada, retorno false
        return false;
    }
}
