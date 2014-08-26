<?php

namespace Extension\Shop\Facade;

use BinCMS\DataImport\Reader\ArrayReader;
use BinCMS\DataImport\Reader\ReaderInterface;
use BinCMS\DataImport\WorkflowImport;
use BinCMS\DataImport\Writer\DoctrineWriter;
use Extension\Shop\CommerceML\CommerceMLLoader;
use Extension\Shop\CommerceML\Document;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class ImportFacadeAbstract implements ImportFacadeInterface
{
    /**
     * @var \BinCMS\DataImport\Writer\DoctrineWriter
     */
    private $doctrineWriter;

    /**
     * @var array
     */
    private $converters;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(DoctrineWriter $doctrineWriter, array $converters = [], array $filters = [], LoggerInterface $logger = null)
    {
        $this->doctrineWriter = $doctrineWriter;
        $this->converters = $converters;
        $this->filters = $filters;
        $this->logger = null === $logger ? new NullLogger() : $logger;
    }

    protected abstract function getData(ImportFacadeContextInterface $context);

    public function process(ImportFacadeContextInterface $context, $truncate = false)
    {
        $data = $this->getData($context);

        if(!is_array($data)) {
            throw new \RuntimeException('Data is not array!');
        }

        $this->doctrineWriter->setTruncate($truncate);

        $reader = new ArrayReader($data);

        $workflowImport = new WorkflowImport($reader, $this->getLogger());

        foreach($this->converters as $converter) {
            $workflowImport->addConverter($converter);
        }

        foreach($this->filters as $data) {
            list($filter, $priority, $isAfterConvert) = $data;
            $workflowImport->addFilter($filter, $priority, (bool) $isAfterConvert);
        }

        $workflowImport->addWriter($this->doctrineWriter);

        $workflowImport->process();
    }

    /**
     * @return \BinCMS\DataImport\Writer\DoctrineWriter
     */
    public function getDoctrineWriter()
    {
        return $this->doctrineWriter;
    }

    /**
     * @return array
     */
    public function getConverters()
    {
        return $this->converters;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
} 