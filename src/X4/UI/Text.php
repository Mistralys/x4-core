<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

use AppUtils\Interface_Stringable;
use AppUtils\StringBuilder;

class Text extends StringBuilder
{
    private string $color = '';
    private bool $emphasis = false;

    /**
     * @param string|number|Interface_Stringable|NULL $label
     */
    public function __construct($label)
    {
        parent::__construct();

        $this->add($label);
    }

    /**
     * @param string|number|Interface_Stringable|NULL $label
     * @return Text
     */
    public static function create($label=null) : Text
    {
        return new Text($label);
    }

    public function colorSuccess() : self
    {
        return $this->setColorName('success');
    }

    public function colorWarning() : self
    {
        return $this->setColorName('warning');
    }

    public function colorPrimary() : self
    {
        return $this->setColorName('primary');
    }

    public function colorMuted() : self
    {
        return $this->setColorName('secondary');
    }

    public function colorDanger() : self
    {
        return $this->setColorName('danger');
    }

    public function colorInfo() : self
    {
        return $this->setColorName('info');
    }

    public function setColorName(string $name) : self
    {
        $this->color = $name;
        return $this;
    }

    public function render() : string
    {
        $html = parent::render();

        if(empty($this->color)) {
            return $html;
        }

        $color = $this->color;
        if($this->emphasis) {
            $color .= '-emphasis';
        }

        return sprintf(
            '<span class="text-%s">%s</span>',
            $color,
            $html
        );
    }
}