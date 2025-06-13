<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Core\Finder;

use Mistralys\X4\Database\DataSources\DataSourceDef;

interface DataSourceSelectionInterface extends FinderInterface
{
    /**
     * Adds a data source to the list of data sources that will be used to filter ships.
     *
     * @param string|DataSourceDef $source Data source ID or instance.
     * @return $this
     */
    public function selectDataSource(string|DataSourceDef $source) : self;
}
