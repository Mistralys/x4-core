<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\GridColumn
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\AttributeCollection;
use AppUtils\HTMLTag;
use AppUtils\Interfaces\AttributableInterface;
use AppUtils\Interfaces\ClassableAttributeInterface;
use AppUtils\OutputBuffering;
use AppUtils\Traits\AttributableTrait;
use AppUtils\Traits\ClassableAttributeTrait;
use Mistralys\X4\UI\DataGrid\GridCell;
use Mistralys\X4\UserInterface\DataGrid\Column\DecorationHandler;
use Mistralys\X4\UserInterface\DataGrid\Column\FormatHandler;
use Mistralys\X4\UserInterface\DataGrid\Column\ObjectHandler;

/**
 * A column in the data grid: used to configure
 * how to access the cell values, as well as how
 * to format them.
 *
 * @package X4Core
 * @subpackage UserInterface
 */
class GridColumn implements AttributableInterface, ClassableAttributeInterface
{
    use AttributableTrait;
    use ClassableAttributeTrait;

    public const ALIGN_RIGHT = 'align-right';
    public const ALIGN_CENTER = 'align-center';
    public const ALIGN_LEFT = 'align-left';

    private string $label;
    private string $keyName;
    private Column\ObjectHandler $objectHandler;
    private FormatHandler $formatHandler;
    private DecorationHandler $decorationHandler;

    /**
     * @var callable|NULL
     */
    private $linkCallback;
    private AttributeCollection $attributes;

    public function __construct(string $keyName, string $label)
    {
        $this->label = $label;
        $this->keyName = $keyName;
        $this->objectHandler = new ObjectHandler($this);
        $this->formatHandler = new FormatHandler($this);
        $this->decorationHandler = new DecorationHandler($this);
        $this->attributes = AttributeCollection::create();
    }

    public function getAttributes() : AttributeCollection
    {
        return $this->attributes;
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

    public function useObjectValues() : ObjectHandler
    {
        return $this->objectHandler;
    }

    public function chooseFormat() : FormatHandler
    {
        return $this->formatHandler;
    }

    public function decorateWith() : DecorationHandler
    {
        return $this->decorationHandler;
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

    /**
     * @param mixed|NULL $value
     * @return string
     */
    public function applyFormattings($value) : string
    {
        return $this->formatHandler->formatValue($value);
    }

    public function applyDecorations(GridCell $cell, string $value) : string
    {
        return $this->decorationHandler->decorate($cell, $value);
    }

    public function renderCell(GridCell $cell) : string
    {
        $attributes = AttributeCollection::create(array_merge(
            $this->getAttributes()->getRawAttributes(),
            $cell->getAttributes()->getRawAttributes()
        ));

        $attributes->addClasses($this->getClasses());
        $attributes->addClasses($cell->getClasses());

        OutputBuffering::start();

        ?>
        <td <?php echo $attributes->render() ?>>
            <?php $cell->display(); ?>
        </td>
        <?php

        return OutputBuffering::get();
    }

    public function displayCell(GridCell $cell) : void
    {
        echo $this->renderCell($cell);
    }
}
