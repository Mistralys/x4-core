<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use function AppUtils\t;

class ModuleCategories
{
    public const ERROR_UNKNOWN_CATEGORY_ID = 106401;

    public const CATEGORY_PRODUCTION = 'prod';
    public const CATEGORY_HABITATS = 'hab';
    public const CATEGORY_STRUCTURAL_ELEMENTS = 'struct';
    public const CATEGORY_STORAGE = 'storage';
    public const CATEGORY_DEFENCE = 'defence';
    public const CATEGORY_WELFARE = 'welfare';
    public const CATEGORY_PROCESSING = 'proc';
    public const CATEGORY_BUILD_MODULE = 'buildmodule';
    public const CATEGORY_DOCKING_BAY = 'dockingbay';
    public const CATEGORY_DOCKING_PIER = 'pier';
    public const CATEGORY_DOCKING_AREA = 'dockarea';

    private static ?ModuleCategories $instance = null;

    /**
     * @var array<string,ModuleCategory>
     */
    private array $categories = array();

    private function __construct()
    {
        $this
            ->add(self::CATEGORY_DOCKING_AREA, t('Docking modules'))
            ->add(self::CATEGORY_DOCKING_PIER, t('Docking modules'))
            ->add(self::CATEGORY_DOCKING_BAY, t('Docking modules'))
            ->add(self::CATEGORY_PRODUCTION, t('Production modules'))
            ->add(self::CATEGORY_HABITATS, t('Habitats'))
            ->add(self::CATEGORY_STRUCTURAL_ELEMENTS, t('Structural elements'))
            ->add(self::CATEGORY_STORAGE, t('Storage modules'))
            ->add(self::CATEGORY_DEFENCE, t('Defence'))
            ->add(self::CATEGORY_WELFARE, t('Welfare'))
            ->add(self::CATEGORY_PROCESSING, t('Processing'))
            ->add(self::CATEGORY_BUILD_MODULE, t('Build module'));
    }

    public static function getInstance() : ModuleCategories
    {
        if(isset(self::$instance))
        {
            return self::$instance;
        }

        $instance = new ModuleCategories();
        self::$instance = $instance;
        return $instance;
    }

    private function add(string $id, string $label) : self
    {
        $this->categories[$id] = new ModuleCategory($id, $label);
        return $this;
    }

    public function getIDs() : array
    {
        $ids = array_keys($this->categories);
        sort($ids);
        return $ids;
    }

    public function idExists(string $categoryID) : bool
    {
        return isset($this->categories[strtolower($categoryID)]);
    }

    /**
     * @param string $categoryID
     * @return ModuleCategory
     * @throws ModuleException
     */
    public function getByID(string $categoryID) : ModuleCategory
    {
        $categoryID = strtolower($categoryID);

        if(isset($this->categories[$categoryID]))
        {
            return $this->categories[$categoryID];
        }

        throw new ModuleException(
            'Unknown module category.',
            sprintf(
                'The category ID [%s] does not exist. Known IDs are: [%s].',
                $categoryID,
                implode(', ', $this->getIDs())
            ),
            self::ERROR_UNKNOWN_CATEGORY_ID
        );
    }
}
