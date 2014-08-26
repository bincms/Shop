<?php

namespace Extension\Shop\SphinxXml;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class SphinxLoader
{
    /**
     * @var ReaderInterface[]
     */
    private $readers = [];

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ? $logger : new NullLogger();
    }

    public function process()
    {
        $result = [];

        $result[] = '<sphinx:schema>
            <sphinx:field name="content"/>
            <sphinx:attr name="_id" type="string"/>
            <sphinx:attr name="_type" type="string"/>
            <sphinx:attr name="_description" type="string"/>
            </sphinx:schema>';

        foreach ($this->readers as $index => $reader) {
            /** @var SphinxDocument[] $documents */
            $documents = $reader->getAll();
            foreach($documents as $docIndex => $document) {
                $documentId = ($index+1) . ($docIndex+1);
                $result[] = $this->generateXmlSphinxDocument($documentId, $document);
            }
        }
        return '<?xml version="1.0" encoding="utf-8"?><sphinx:docset>'.implode(PHP_EOL, $result).'</sphinx:docset>';
    }

    public function registerReader(ReaderInterface $reader)
    {
        $this->readers[] = $reader;
    }

    private function generateXmlSphinxDocument($documentId, SphinxDocument $document)
    {
        return '<sphinx:document id="'.$documentId.'">
                <content><![CDATA[['.$document->getContent().']]></content>
                <_id>'.$document->getId().'</_id>
                <_type>'.$document->getType().'</_type>
                <_description><![CDATA[['.$document->getDescription().']]></_description>
                </sphinx:document>';
    }

} 