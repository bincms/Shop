<?php

namespace Extension\Shop\Command;

use Extension\Shop\CommerceML\Document;
use Extension\Shop\CommerceML\Offers;
use Extension\Shop\Facade\ImportFacadeContext;
use Extension\Shop\Facade\ImportOfferFacade;
use Extension\Shop\Facade\ImportWarehouseFacade;
use Silex\Application;
use Extension\Shop\Facade\ImportFacadeInterface;
use Extension\Shop\Repository\ImportProcessRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCatalogCommand extends Command
{
    /**
     * @var array
     */
    private $importFacades;

    /**
     * @var \Extension\Shop\Repository\ImportProcessRepository
     */
    private $importProcessRepository;
    /**
     * @var \Extension\Shop\Facade\ImportOfferFacade
     */
    private $importOfferFacade;
    /**
     * @var \Extension\Shop\Facade\ImportWarehouseFacade
     */
    private $importWarehouseFacade;

    public function __construct(ImportProcessRepository $importProcessRepository, array $importFacades,
                                ImportOfferFacade $importOfferFacade, ImportWarehouseFacade $importWarehouseFacade, $name = null)
    {
        parent::__construct($name);

        $this->importFacades = $importFacades;
        $this->importProcessRepository = $importProcessRepository;
        $this->importOfferFacade = $importOfferFacade;
        $this->importWarehouseFacade = $importWarehouseFacade;
    }

    protected function configure()
    {
        $this
            ->setName('shop:import:catalog')
            ->setDefinition([
                new InputArgument(
                    'path', null, InputArgument::REQUIRED,
                    'A imported CommerceML base path.'
                ),
                new InputArgument(
                    'importProcessId', null, InputArgument::REQUIRED,
                    ''
                )
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importPath = $input->getArgument('path');
        $importProcessId = $input->getArgument('importProcessId');

        $importFilePath = realpath($importPath.'/import.xml');

        if(!file_exists($importFilePath)) {
            $output->writeln(sprintf('File "%s" not found', $importPath));
            return;
        }

        if ($importProcessId !== null) {

            try {

                $productOffers = [];
                $warehouses = [];

                $importOffersFilePath = realpath($importPath.'/offers.xml');

                if(file_exists($importOffersFilePath)) {
                    $docOffers = new \DOMDocument();
                    $docOffers->load($importOffersFilePath);
                    $offers = new Offers($docOffers);
                    $productOffers = $offers->getPackageOffer()->getProductOffers();
                    $warehouses = $offers->getPackageOffer()->getWarehouses();
                }

                $doc = new \DOMDocument();
                $doc->load($importFilePath);

                $document = new Document($doc);

                $importFacadeContext = new ImportFacadeContext(
                    $document->getCatalog()->getProducts(),
                    $document->getClassifier()->getGroups(),
                    $document->getClassifier()->getProperties(),
                    $productOffers,
                    $warehouses
                );

                foreach ($this->importFacades as $facade) {
                    if ($facade instanceof ImportFacadeInterface) {
                        $facade->process($importFacadeContext, !$document->getCatalog()->isOnlyUpdate());
                    }
                }

                $this->importWarehouseFacade->process($importFacadeContext, true);

                $this->importOfferFacade->process($importFacadeContext);

                $this->importProcessRepository->finish($importProcessId);

            } catch (\Exception $e) {
                $this->importProcessRepository->finishError($importProcessId, $e->getMessage());
            }
        }
    }
}