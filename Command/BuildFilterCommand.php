<?php

namespace Extension\Shop\Command;

use Extension\Shop\Document\Filter;
use Extension\Shop\Document\FilterValue;
use Extension\Shop\Document\ProductProperty;
use Extension\Shop\Repository\FilterRepository;
use Extension\Shop\Repository\Interfaces\ProductRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildFilterCommand extends Command
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \Extension\Shop\Repository\FilterRepository
     */
    private $filterRepository;

    public function __construct(ProductRepositoryInterface $productRepository, FilterRepository $filterRepository)
    {
        parent::__construct();

        $this->productRepository = $productRepository;
        $this->filterRepository = $filterRepository;
    }

    protected function configure()
    {
        $this
            ->setName('shop:build:filter')
            ->setDefinition([
                new InputArgument(
                    'categoryId', null, InputArgument::OPTIONAL,
                    'A category ID for build or rebuild filter.'
                )
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}