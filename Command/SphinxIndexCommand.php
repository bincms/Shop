<?php

namespace Extension\Shop\Command;

use Extension\Shop\SphinxXml\SphinxLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SphinxIndexCommand extends Command
{
    /**
     * @var \Extension\Shop\SphinxXml\SphinxLoader
     */
    private $sphinxLoader;

    public function __construct(SphinxLoader $sphinxLoader, $name = null)
    {
        parent::__construct($name);

        $this->sphinxLoader = $sphinxLoader;
    }

    protected function configure()
    {
        $this->setName('sphinx:xml');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write($this->sphinxLoader->process());
    }

}