<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\RenderableBufferedTrait;

class Icon implements RenderableInterface
{
    use RenderableBufferedTrait;

    private string $name;
    private string $type;
    private string $textClass = '';
    private string $tooltip = '';

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public static function typeSolid(string $name) : Icon
    {
        return new Icon($name, 'fas');
    }

    public static function typeRegular(string $name) : Icon
    {
        return new Icon($name, 'far');
    }

    protected function generateOutput() : void
    {
        ?>
        <i <?php echo $this->compileAttributes() ?>></i>
        <?php
    }

    private function compileAttributes() : string
    {
        $list = array();
        $attributes = $this->resolveAttributes();

        foreach($attributes as $name => $value)
        {
            $list[] = $name.'="'.htmlspecialchars($value, ENT_QUOTES).'"';
        }

        return implode(' ', $list);
    }

    private function resolveAttributes() : array
    {
        $attributes = array(
            'class' => implode(' ', $this->resolveClasses())
        );

        if(!empty($this->tooltip)) {
            $attributes['data-bs-toggle'] = 'tooltip';
            $attributes['data-bs-title'] = $this->tooltip;
        }

        return $attributes;
    }

    /**
     * @return string[]
     */
    private function resolveClasses() : array
    {
        $classes = array(
            $this->type,
            'fa-'.$this->name
        );

        if(!empty($this->textClass)) {
            $classes[] = 'text-'.$this->textClass;
        }

        return $classes;
    }

    // region: Customizing

    public function colorPrimary() : self
    {
        return $this->setColorClass('primary');
    }

    public function colorSuccess() : self
    {
        return $this->setColorClass('success');
    }

    public function colorDanger() : self
    {
        return $this->setColorClass('danger');
    }

    public function colorMuted() : self
    {
        return $this->setColorClass('secondary');
    }

    public function colorWarning() : self
    {
        return $this->setColorClass('warning');
    }

    public function colorInfo() : self
    {
        return $this->setColorClass('info');
    }

    public function setColorClass(string $class) : self
    {
        $this->textClass = $class;
        return $this;
    }

    public function setTooltip(string $text) : self
    {
        $this->tooltip = $text;
        return $this;
    }

    // endregion

    // region: Icons

    public static function yes() : Icon
    {
        return self::typeSolid('check-circle');
    }

    public static function no() : Icon
    {
        return self::typeSolid('times-circle');
    }

    public static function unpack() : Icon
    {
        return self::typeSolid('box-open');
    }

    public static function backup() : Icon
    {
        return self::typeRegular('hdd');
    }

    // endregion
}
