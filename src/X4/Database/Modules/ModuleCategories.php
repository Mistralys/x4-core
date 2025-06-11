<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\Collections\BaseStringPrimaryCollection;
use function AppUtils\t;

/**
 * @method ModuleCategory getByID(string $id)
 * @method ModuleCategory getAll()
 * @method ModuleCategory getDefault()
 */
class ModuleCategories extends BaseStringPrimaryCollection
{
    public const CATEGORY_PRODUCTION = 'production';
    public const CATEGORY_HABITATS = 'habitation';
    public const CATEGORY_STORAGE = 'storage';
    public const CATEGORY_VENTURE_PLATFORM = 'ventureplatform';
    public const CATEGORY_DEFENCE_MODULE = 'defencemodule';
    public const CATEGORY_WELFARE_MODULE = 'welfaremodule';
    public const CATEGORY_PROCESSING_MODULE = 'processingmodule';
    public const CATEGORY_BUILD_MODULE = 'buildmodule';
    public const CATEGORY_DOCKING_PIER = 'pier';
    public const CATEGORY_DOCKING_AREA = 'dockarea';
    public const CATEGORY_CONNECTION_MODULE = 'connectionmodule';
    public const CATEGORY_RADAR = 'radar';

    private function resolveCategories() : array
    {
        return array(
            self::CATEGORY_PRODUCTION => t('Production modules'),
            self::CATEGORY_HABITATS => t('Habitats'),
            self::CATEGORY_STORAGE => t('Storage modules'),
            self::CATEGORY_VENTURE_PLATFORM => t('Venture Platform'),
            self::CATEGORY_DEFENCE_MODULE => t('Defence'),
            self::CATEGORY_WELFARE_MODULE => t('Welfare'),
            self::CATEGORY_PROCESSING_MODULE => t('Processing'),
            self::CATEGORY_BUILD_MODULE => t('Build module'),
            self::CATEGORY_DOCKING_PIER => t('Docking modules'),
            self::CATEGORY_DOCKING_AREA => t('Docking modules'),
            self::CATEGORY_CONNECTION_MODULE => t('Connection module'),
            self::CATEGORY_RADAR => t('Radar')
        );
    }

    private static ?ModuleCategories $instance = null;

    public static function getInstance() : ModuleCategories
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach($this->resolveCategories() as $id => $label) {
            $this->registerItem(new ModuleCategory($id, $label));
        }
    }
}
