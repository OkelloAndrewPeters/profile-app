<?php


class PostTestLogin extends PHPUnit_Framework_TestCase
{
    public function testPostLogin()
    {
        // instantiate action
        $action = new \App\Action\LoginAction();

        // We need a request and response object to invoke the action
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/login',
            'QUERY_STRING'=>'SELECT * FROM app_users WHERE phone_number = "0786625387"']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();

        // run the controller action and test it
        $response = $action($request, $response, []);
        $this->assertEquals($response->status(400), $request);
    }
}

?>