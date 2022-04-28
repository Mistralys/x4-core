<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use function AppUtils\t;

class ModuleDefs
{
    private static ?ModuleDefs $instance = null;

    /**
     * @var array<string,ModuleDef>
     */
    private array $defs = array();

    private function __construct()
    {
        $this
            ->add('struct_arg_base_01_macro', t('Base Connection Structure %1$s', '01'))
            ->add('struct_arg_base_02_macro', t('Base Connection Structure %1$s', '02'))
            ->add('struct_arg_cross_01_macro', t('Cross Connection Structure %1$s', '01'));

        uasort($this->defs, static function(ModuleDef $a, ModuleDef $b) {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });
    }

    public static function getInstance() : ModuleDefs
    {
        if(isset(self::$instance))
        {
            return self::$instance;
        }

        $defs = new ModuleDefs();
        self::$instance = $defs;
        return $defs;
    }

    private function add(string $moduleID, string $label) : self
    {
        $this->defs[$moduleID] = new ModuleDef($moduleID, $label);
        return $this;
    }

    /**
     * @return ModuleDef[]
     */
    public function getAll() : array
    {
        return array_values($this->defs);
    }

    public function idExists(string $moduleID) : bool
    {
        return isset($this->defs[$moduleID]);
    }

    public function getByID(string $moduleID) : ModuleDef
    {
        if(!isset($this->defs[$moduleID]))
        {
            $this->defs[$moduleID] = new ModuleDef($moduleID, $moduleID);
        }

        return $this->defs[$moduleID];
    }
}
