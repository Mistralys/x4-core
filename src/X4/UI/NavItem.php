<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

class NavItem
{
    private string $label;
    private string $url;

    public function __construct(string $label, string $url)
    {
        $this->label = $label;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getLabel() : string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    public function isActive() : bool
    {
        return false;
    }
}
