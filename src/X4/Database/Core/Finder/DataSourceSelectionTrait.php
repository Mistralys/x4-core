<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Core\Finder;

use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;

trait DataSourceSelectionTrait
{
    /**
     * @var string[]
     */
    protected array $dataSources = array();

    public function selectDataSource(string|DataSourceDef $source) : self
    {
        if(!$source instanceof DataSourceDef) {
            $source = DataSourceDefs::getInstance()->getByID($source);
        }

        $sourceID = $source->getID();
        if(!in_array($sourceID, $this->dataSources, true)) {
            $this->dataSources[] = $sourceID;
        }

        return $this;
    }

    protected function isDataSourceMatch(string $dataSourceID) : bool
    {
        return empty($this->dataSources) || in_array($dataSourceID, $this->dataSources, true);
    }
}
