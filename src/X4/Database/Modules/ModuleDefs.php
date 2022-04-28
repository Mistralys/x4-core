<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use function AppUtils\t;

class ModuleDefs
{
    private static ?ModuleDefs $instance = null;

    /**
     * @var array<string,Module>
     */
    private static array $types = array();

    private function __construct()
    {
        $this
            ->add('struct_arg_base_01_macro', t('Base Connection Structure %1$s', '01'))
            ->add('struct_arg_base_02_macro', t('Base Connection Structure %1$s', '02'))
            ->add('struct_arg_cross_01_macro', t('Cross Connection Structure %1$s', '01'));
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

    private function add(string $id, string $label) : self
    {
        self::$types[$id] = new Module($id, $label);
        return $this;
    }

    public function getType(string $typeID) : Module
    {
        if(!isset(self::$types[$typeID]))
        {
            self::$types[$typeID] = new Module($typeID, $typeID);
        }

        return self::$types[$typeID];
    }
}
