<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use function AppUtils\t;

class ModuleCategories
{
    public const ERROR_UNKNOWN_CATEGORY_ID = 106401;

    private static ?ModuleCategories $instance = null;

    /**
     * @var array<string,ModuleCategory>
     */
    private array $categories = array();

    private function __construct()
    {
        $this
            ->add('dockarea', t('Docking modules'))
            ->add('pier', t('Docking modules'))
            ->add('dockingbay', t('Docking modules'))
            ->add('prod', t('Production modules'))
            ->add('hab', t('Habitats'))
            ->add('struct', t('Structural elements'))
            ->add('storage', t('Storage modules'))
            ->add('defence', t('Defence'))
            ->add('welfare', t('Welfare'))
            ->add('proc', t('Processing'))
            ->add('buildmodule', t('Build module'));
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
