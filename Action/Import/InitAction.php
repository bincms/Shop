<?php

namespace Extension\Shop\Action\Import;

use BinCMS\Action\ActionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InitAction implements ActionInterface
{
    /**
     * @var
     */
    private $importPath;

    public function __construct($importPath)
    {
        $this->importPath = $importPath;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute(Request $request)
    {
        if(is_dir($this->importPath)) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->importPath);
        }

        return new Response(implode(PHP_EOL, [
            'zip=no',
            'file_limit=5242880'
        ]));
    }
}