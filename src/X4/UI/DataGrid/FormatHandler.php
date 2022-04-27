<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\FormatHandler
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\ConvertHelper;
use DateTime;

/**
 * Handles the value formatting configuration of
 * a column in the grid.
 *
 * @package X4Core
 * @subpackage UserInterface
 */
class FormatHandler extends BaseHandler
{
    /**
     * @var callable|NULL
     */
    private $formatCallback;

    public function boolean(bool $yesNo=false, bool $uppercase=false) : self
    {
        return $this->callback(static function ($value) use($yesNo, $uppercase) : string
        {
            $result = ConvertHelper::bool2string($value, $yesNo);

            if($uppercase) {
                return mb_strtoupper($result);
            }

            return $result;
        });
    }

    /**
     * Converts the date using the specified date format.
     *
     * @param string $format
     * @return $this
     */
    public function dateFormat(string $format) : self
    {
        return $this->callback(static function($value) use($format) : string
        {
            if($value instanceof DateTime)
            {
                return $value->format($format);
            }

            return (string)$value;
        });
    }

    /**
     * Automatically converts the date to a standard
     * human-readable format with month name.
     *
     * @param bool $includeTime
     * @param bool $shortMonth
     * @return $this
     */
    public function dateAuto(bool $includeTime=false, bool $shortMonth=false) : self
    {
        return $this->callback(static function($value) use($includeTime, $shortMonth) : string
        {
            if($value instanceof DateTime)
            {
                return ConvertHelper::date2listLabel($value, $includeTime, $shortMonth);
            }

            return (string)$value;
        });
    }

    /**
     * Uses a conversion callback to convert the target
     * value to a string usable in the grid.
     *
     * NOTE: The resulting value is cast to string.
     *
     * @param callable $callback
     * @return $this
     */
    public function callback(callable $callback) : self
    {
        $this->formatCallback = $callback;
        return $this;
    }

    /**
     * @param mixed|NULL $value
     * @return string
     */
    public function formatValue($value) : string
    {
        if(isset($this->formatCallback))
        {
            return (string)call_user_func($this->formatCallback, $value);
        }

        return (string)$value;
    }
}
