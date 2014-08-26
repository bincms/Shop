<?php

namespace Extension\Shop\Action\Import;

use BinCMS\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationAction implements ActionInterface
{
    public $successor = null;

    public function execute(Request $request)
    {
//        if (isset($_SERVER['PHP_AUTH_USER'])) {
//            $username = trim($_SERVER['PHP_AUTH_USER']);
//            $password = trim($_SERVER['PHP_AUTH_PW']);
//        }

        $key = 'import-key';

        return new Response(implode(PHP_EOL, [
            'success',
            $key,
            md5(time())
        ]));
    }
}