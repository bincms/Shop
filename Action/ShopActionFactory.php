<?php

namespace Extension\Shop\Action;

use BinCMS\Action\ActionFactoryInterface;
use BinCMS\Action\ActionInterface;
use Extension\Shop\Action\Import\AuthenticationAction;
use Extension\Shop\Action\Import\ErrorAction;
use Extension\Shop\Action\Import\FileAction;
use Extension\Shop\Action\Import\ImportAction;
use Extension\Shop\Action\Import\InitAction;
use Extension\Shop\Repository\ImportProcessRepository;
use Symfony\Component\HttpFoundation\Request;

class ShopActionFactory implements ActionFactoryInterface
{
    const MODE_CHECK_AUTH = 'checkauth';
    const MODE_INIT = 'init';
    const MODE_IMPORT = 'import';
    const MODE_FILE = 'file';
    /**
     * @var
     */
    private $importPath;
    /**
     * @var
     */
    private $appBasePath;
    /**
     * @var \Extension\Shop\Repository\ImportProcessRepository
     */
    private $importProcessRepository;

    public function __construct($importPath, $appBasePath, ImportProcessRepository $importProcessRepository)
    {
        $this->importPath = $importPath;
        $this->appBasePath = $appBasePath;
        $this->importProcessRepository = $importProcessRepository;
    }


    /**
     * @param Request $request
     * @return \BinCMS\Action\ActionInterface
     */
    public function make(Request $request)
    {
        switch($request->get('mode')) {
            case self::MODE_CHECK_AUTH:
                return new AuthenticationAction();
            break;
            case self::MODE_INIT:
                return new InitAction($this->importPath);
            break;
            case self::MODE_IMPORT:
                return new ImportAction($this->appBasePath, $this->importPath, $this->importProcessRepository);
                break;
            case self::MODE_FILE:
                return new FileAction($this->importPath);
                break;
            default:
                return new ErrorAction();
            break;
        }
    }
}