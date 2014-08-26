<?php

namespace Extension\Shop\Action\Import;

use BinCMS\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorAction implements ActionInterface
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute(Request $request)
    {
        return new Response(implode(PHP_EOL, [
            'failure'
        ]));
    }
}