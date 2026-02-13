<?php

declare(strict_types=1);

namespace Mistralys\X4\Database;

use Mistralys\X4\XML\ElementExtended;

/**
 * Base class for data extractors.
 * 
 * Provides common utility methods for extracting data from XML structures.
 */
abstract class BaseExtractor
{
    /**
     * Resolves an attribute value from a nested child element within a parent element.
     *
     * Searches for a child element with the specified tag name within the given parent
     * element and extracts the specified attribute value. Returns 0.0 if the element or
     * attribute is not found.
     *
     * This is useful for extracting data from nested XML structures like:
     * ```xml
     * <parent>
     *   <child attr="value" />
     * </parent>
     * ```
     *
     * @param ElementExtended $element Parent element to search within
     * @param string $childTagName Tag name of the child element to find
     * @param string $attributeName Attribute name to extract from the child element
     * @return float The attribute value, or 0.0 if not found
     */
    protected function resolveNestedPropertyAttribute(
        ElementExtended $element,
        string $childTagName,
        string $attributeName
    ): float
    {
        $children = $element->getChildren();
        foreach ($children as $child) {
            $domElement = $child->getDOMElement();
            if ($domElement->nodeName === $childTagName) {
                $val = $child->getAttribute($attributeName);
                if ($val !== null && $val !== '') {
                    return (float)$val;
                }
            }
        }

        return 0.0;
    }
}
