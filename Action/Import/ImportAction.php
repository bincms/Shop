<?php

namespace Extension\Shop\Action\Import;

use BinCMS\Action\ActionInterface;
use Extension\Shop\Repository\ImportProcessRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImportAction implements ActionInterface
{
    /**
     * @var
     */
    private $appBasePath;
    /**
     * @var
     */
    private $importPath;
    /**
     * @var \Extension\Shop\Repository\ImportProcessRepository
     */
    private $importProcessRepository;

    public function __construct($appBasePath, $importPath, ImportProcessRepository $importProcessRepository)
    {
        $this->appBasePath = $appBasePath;
        $this->importPath = $importPath;
        $this->importProcessRepository = $importProcessRepository;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute(Request $request)
    {
        $filename = $request->get('filename');

        switch($filename) {
            case 'import.xml':
                $importProcess = $this->importProcessRepository->start();

                $command = '(/usr/bin/php -f '. $this->appBasePath . '/bin/console.php shop:import:catalog ' .
                    $this->importPath . ' ' . $importProcess->getId() . ') > /dev/null &';

                exec($command, $output);
            break;
        }

        return new Response(implode(PHP_EOL, [
            'success'
        ]));
    }
}