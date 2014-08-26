<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Document\ImportProcess;
use Symfony\Component\Validator\Constraints\DateTime;

class ImportProcessRepository extends DocumentRepository
{
    use RepositoryExtendTrait;

    public function start()
    {
        $importProcess = new ImportProcess();
        $this->saveEntity($importProcess);
        return $importProcess;
    }

    public function finish($importProcessId)
    {
        $importProcess = $this->find($importProcessId);

        if(null !== $importProcess) {
            $importProcess->setStatus(ImportProcess::ST_FINISHED);
            $importProcess->setFinished(new \MongoDate());
            $this->saveEntity($importProcess);
        }
    }

    public function finishError($importProcessId, $text = '')
    {
        $importProcess = $this->find($importProcessId);

        if(null !== $importProcess) {
            $importProcess->setStatus(ImportProcess::ST_ERROR);
            $importProcess->setText($text);
            $importProcess->setFinished(new DateTime());
            $this->saveEntity($importProcess);
        }

    }
} 