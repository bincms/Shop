<?php

namespace Extension\Shop\Action\Import;

use BinCMS\Action\ActionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileAction implements \BinCMS\Action\ActionInterface
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
        $filename = $request->get('filename');

        $fileInfo = pathinfo($filename);

        $fileDir = $fileInfo['dirname'];
        $fileName = $fileInfo['basename'];

        if($fileDir == '.') $fileDir = '';

        $basePath = $this->importPath . '/' . $fileDir;

        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }

        $importFileResource = fopen($basePath . '/' . $fileName, "ab") or die ("File not opened");

        if ($importFileResource) {
            set_file_buffer($importFileResource, 20);
            fwrite($importFileResource, $request->getContent());
            fclose($importFileResource);

            return new Response(implode(PHP_EOL, [
                'success'
            ]));
        }

        return new Response(implode(PHP_EOL, [
            'failure'
        ]));

    }
}