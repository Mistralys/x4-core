<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\Column\DecorationHandler
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid\Column;

use AppUtils\AttributeCollection;
use Mistralys\X4\UI\DataGrid\GridCell;
use Mistralys\X4\UserInterface\DataGrid\Column\BaseHandler;
use Mistralys\X4\UserInterface\DataGrid\Decorations\BaseDecoration;
use Mistralys\X4\UserInterface\DataGrid\Decorations\LinkByCallback;
use Mistralys\X4\UserInterface\DataGrid\Decorations\LinkByMethod;
use Mistralys\X4\UserInterface\DataGrid\GridColumn;

/**
 * Handles adding decorations to cells in the
 * data grid, like linking the cell content.
 *
 * @package X4Core
 * @subpackage UserInterface
 */
class DecorationHandler extends BaseHandler
{
    private ?BaseDecoration $decoration = null;

    public function linkByCallback(callable $callback, bool $newTab=false, ?AttributeCollection $attributes=null) : GridColumn
    {
        return $this->setDecoration(new LinkByCallback($callback, $newTab, $attributes));
    }

    /**
     * @param array{0:string,1:string} $callback
     * @param bool $newTab
     * @param AttributeCollection|null $attributes
     * @return GridColumn
     */
    public function linkByMethod(array $callback, bool $newTab=false, ?AttributeCollection $attributes=null) : GridColumn
    {
        return $this->setDecoration(new LinkByMethod($callback[1], $newTab, $attributes));
    }

    private function setDecoration(BaseDecoration $decoration) : GridColumn
    {
        $this->decoration = $decoration;
        return $this->getColumn();
    }

    public function decorate(GridCell $cell, $value) : string
    {
        if(isset($this->decoration))
        {
            return $this->decoration->decorate($cell, $value);
        }

        return $value;
    }
}
