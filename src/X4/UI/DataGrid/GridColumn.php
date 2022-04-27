<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\GridColumn
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\Interface_Classable;
use AppUtils\Traits_Classable;
use X4\DataGrid\ObjectHandler;

/**
 * A column in the data grid: used to configure
 * how to access the cell values, as well as how
 * to format them.
 *
 * @package X4Core
 * @subpackage UserInterface
 */
class GridColumn implements Interface_Classable
{
    use Traits_Classable;

    public const ALIGN_RIGHT = 'align-right';
    public const ALIGN_CENTER = 'align-center';
    public const ALIGN_LEFT = 'align-left';

    private string $label;
    private string $keyName;
    private ObjectHandler $objectHandler;
    private FormatHandler $formatHandler;

    public function __construct(string $keyName, string $label)
    {
        $this->label = $label;
        $this->keyName = $keyName;
        $this->objectHandler = new ObjectHandler($this);
        $this->formatHandler = new FormatHandler($this);
    }

    public function getKeyName() : string
    {
        return $this->keyName;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function alignRight() : self
    {
        return $this->setAlign(self::ALIGN_RIGHT);
    }

    public function alignCenter() : self
    {
        return $this->setAlign(self::ALIGN_CENTER);
    }

    public function alignLeft() : self
    {
        return $this->setAlign(self::ALIGN_LEFT);
    }

    public function setAlign(string $align) : self
    {
        $this->removeClass(self::ALIGN_RIGHT);
        $this->removeClass(self::ALIGN_CENTER);
        $this->removeClass(self::ALIGN_LEFT);

        $this->addClass($align);
        return $this;
    }

    public function configObject() : ObjectHandler
    {
        return $this->objectHandler;
    }

    public function chooseFormat() : FormatHandler
    {
        return $this->formatHandler;
    }

    /**
     * Fetches the cell value from the target object,
     * using the configuration in the object handler.
     *
     * @param object $subject
     * @return mixed|NULL
     * @throws DataGridException
     */
    public function getValueFromObject(object $subject)
    {
        return $this->objectHandler->getValue($subject);
    }

    public function formatValue($value) : string
    {
        return $this->formatHandler->formatValue($value);
    }
}
