<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\DataSources;

use AppUtils\FileHelper\FolderInfo;
use Mistralys\X4\Database\Builder\KnownItemsClassGenerator;
use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\UI\Console;
use function AppLocalize\t;

class DataSourcesExtractor
{
    public function extract() : void
    {
        $this->extractSources();
        $this->generateKnownDataSources();
    }

    private function extractSources() : void
    {
        Console::header('Extracting data sources');

        $data = array();
        foreach(DatabaseBuilder::getDataFolders()->getAll() as $dataFolder) {
            $data[] = DataSourceDef::toArray($dataFolder);
        }

        ksort($data);

        DataSourceDefs::getInstance()
            ->getDataFile()
            ->putData($data);
    }

    private function generateKnownDataSources() : void
    {
        $generator = new KnownItemsClassGenerator(
            DataSourceDefs::class,
            DataSourceDef::class,
            FolderInfo::factory(__DIR__)
        );

        foreach(DataSourceDefs::getInstance()->getAll() as $sourceDef) {
            $generator->addItem($sourceDef->getID(), $sourceDef->getLabel());
        }

        $generator->setDescription(t('This class contains constants and method for all known game data sources.'));

        $generator->generate();
    }
}
