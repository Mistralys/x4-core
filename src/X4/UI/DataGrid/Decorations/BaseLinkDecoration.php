<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid\Decorations;

use AppUtils\AttributeCollection;
use AppUtils\OutputBuffering;

abstract class BaseLinkDecoration extends BaseDecoration
{
    protected function renderLink(string $value, string $link, bool $newTab, ?AttributeCollection $attributes) : string
    {
        if($attributes === null) {
            $attributes = AttributeCollection::create();
        }

        $attributes->href($link);

        if($newTab) {
            $attributes->attr('target', '_blank');
        }

        return $this->renderWithAttributes($attributes, $value);
    }

    private function renderWithAttributes(AttributeCollection $attributes, string $content) : string
    {
        OutputBuffering::start();

        ?>
        <a <?php echo $attributes->render() ?>>
            <?php echo $content ?>
        </a>
        <?php

        return OutputBuffering::get();
    }
}
