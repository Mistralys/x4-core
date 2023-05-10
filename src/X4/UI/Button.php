<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

use AppUtils\HTMLTag;
use AppUtils\Interface_Stringable;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\RenderableBufferedTrait;
use function AppUtils\sb;

class Button implements RenderableInterface
{
    use RenderableBufferedTrait;

    private string $label;
    private ?Icon $icon = null;
    private string $url = '';
    private string $urlTarget = '';
    private string $jsStatement = '';
    private string $tooltip = '';
    private string $colorType = 'secondary';
    private string $outline = '';
    private string $buttonType = 'button';
    private string $size = '';
    private string $name = '';
    private string $value = '';

    /**
     * @param string|Interface_Stringable|NULL $label
     */
    public function __construct($label)
    {
        $this->setLabel($label);
    }

    /**
     * @param string|Interface_Stringable|NULL $label
     * @return Button
     */
    public static function create($label='') : Button
    {
        return new Button($label);
    }

    /**
     * @param string|Interface_Stringable|NULL $label
     */
    public function setLabel($label) : self
    {
        $this->label = (string)$label;
        return $this;
    }

    public function setIcon(Icon $icon) : self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string|Interface_Stringable|NULL $tooltip
     * @return $this
     */
    public function setTooltip($tooltip) : self
    {
        $this->tooltip = (string)$tooltip;
        return $this;
    }

    public function makeSubmit(string $name, string $value='') : self
    {
        if(empty($value)) {
            $value = 'yes';
        }

        $this->buttonType = 'submit';
        $this->name = $name;
        $this->value = $value;

        return $this;
    }

    public function link(string $url, bool $newTab=false) : self
    {
        $this->url = $url;

        if($newTab)
        {
            $this->urlTarget = '_blank';
        }

        return $this;
    }

    /**
     * @param string|Interface_Stringable|NULL $statement
     * @return $this
     */
    public function click($statement) : self
    {
        $this->jsStatement = (string)$statement;
        return $this;
    }

    public function colorPrimary() : self
    {
        return $this->setColorType('primary');
    }

    public function colorSuccess() : self
    {
        return $this->setColorType('success');
    }

    public function colorDanger() : self
    {
        return $this->setColorType('danger');
    }

    public function colorWarning() : self
    {
        return $this->setColorType('warning');
    }

    public function colorInfo() : self
    {
        return $this->setColorType('info');
    }

    /**
     * Switches to the outline style, where the button
     * does not have a fill color, but only a border.
     *
     * @return $this
     * https://getbootstrap.com/docs/5.3/components/buttons/#outline-buttons
     */
    public function makeOutline() : self
    {
        $this->outline = '-outline';
        return $this;
    }

    public function setColorType(string $type) : self
    {
        $this->colorType = $type;
        return $this;
    }

    public function sizeLarge() : self
    {
        $this->size = 'btn-lg';
        return $this;
    }

    public function sizeSmall() : self
    {
        $this->size = 'btn-sm';
        return $this;
    }

    public function sizeExtraSmall() : self
    {
        $this->size = 'btn-xs';
        return $this;
    }

    protected function generateOutput() : void
    {
        if(!empty($this->url))
        {
            $tag = HTMLTag::create('a')
                ->attr('href', $this->url)
                ->attr('role', 'button')
                ->attr('target', $this->urlTarget);
        }
        else
        {
            $tag = HTMLTag::create('button')
                ->attr('type', $this->buttonType)
                ->attr('onclick', $this->jsStatement)
                ->attr('name', $this->name)
                ->attr('value', $this->value);
        }

        if(!empty($this->tooltip)) {
            $tag
                ->attr('data-bs-togle', 'tooltip')
                ->attr('data-bs-title', $this->tooltip);
        }

        $tag
            ->addClass('btn')
            ->addClass('btn'.$this->outline.'-'.$this->colorType)
            ->addClass($this->size)
            ->setContent(sb()->add($this->icon)->add($this->label));

        echo $tag->render();
    }
}
