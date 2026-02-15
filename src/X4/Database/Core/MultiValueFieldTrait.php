<?php
/**
 * @package X4 Database
 * @subpackage Core
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Core;

/**
 * Trait for handling multi-value fields in entity classes.
 * 
 * This trait provides generic methods for managing fields that support multiple values
 * (arrays) while maintaining backward compatibility with single-value getters.
 * 
 * ## Design Rationale
 * 
 * Entities in X4 (ships, modules, weapons, shields, engines) often have attributes
 * that can be associated with multiple values (e.g., builder factions, maker races).
 * Previously, this pattern was implemented separately in each entity class, leading
 * to ~120 lines of duplicated code across 5 classes.
 * 
 * This trait extracts the common pattern into reusable protected methods that entity
 * classes can leverage internally while maintaining their own public APIs.
 * 
 * ## Key Features
 * 
 * - **Format Migration**: Handles transition from old string format to new array format
 * - **Backward Compatibility**: Single-value getters return first element
 * - **Robust Parsing**: Handles whitespace, empty strings, and edge cases
 * - **Generic Resolution**: Type-safe entity resolution via callables
 * 
 * ## How to Use This Trait
 * 
 * ### Step 1: Use the trait in your entity class
 * 
 * ```php
 * class ShipDef
 * {
 *     use MultiValueFieldTrait;
 *     
 *     private array $builderFactionIDs = [];
 * }
 * ```
 * 
 * ### Step 2: Implement public API methods that call trait methods
 * 
 * ```php
 * // Backward-compatible single-value getter
 * public function getBuilderFactionID(): string
 * {
 *     return $this->getSingleValue(
 *         $this->builderFactionIDs,
 *         KnownFactions::FACTION_GENERIC
 *     );
 * }
 * 
 * // Multi-value getter
 * public function getBuilderFactionIDs(): array
 * {
 *     return $this->getMultipleValues($this->builderFactionIDs);
 * }
 * 
 * // Predicate method
 * public function hasMultipleBuilderFactions(): bool
 * {
 *     return $this->hasMultipleValues($this->builderFactionIDs);
 * }
 * 
 * // Entity resolution
 * public function getBuilderFactions(): array
 * {
 *     return $this->resolveEntities(
 *         $this->builderFactionIDs,
 *         fn($id) => FactionDefs::getInstance()->getByID($id)
 *     );
 * }
 * ```
 * 
 * ### Step 3: Use parseMultiValueField() in fromArray()
 * 
 * ```php
 * public static function fromArray(array $data): self
 * {
 *     $ship = new self();
 *     $ship->builderFactionIDs = self::parseMultiValueField(
 *         $data,
 *         self::KEY_BUILDER_FACTION_IDS,  // New key (plural)
 *         self::KEY_BUILDER_FACTION_ID,   // Old key (singular)
 *         KnownFactions::FACTION_GENERIC  // Default value
 *     );
 *     return $ship;
 * }
 * ```
 * 
 * ## Implementation Notes
 * 
 * - All methods are `protected` - they are internal utilities, not public API
 * - Entity classes expose their own public methods that call these internally
 * - The trait does NOT validate values (e.g., checking if faction IDs exist)
 * - Validation happens in the entity resolution methods (e.g., FactionDefs::getByID())
 * 
 * @package X4 Database
 * @subpackage Core
 */
trait MultiValueFieldTrait
{
    /**
     * Get the single (first) value from a multi-value array.
     * 
     * This provides backward-compatible behavior for code expecting a single value.
     * Returns the first element from the array, or the default value if empty.
     * 
     * @param string[] $values Array of values
     * @param string $default Fallback value if array is empty
     * @return string First element or default
     */
    protected function getSingleValue(array $values, string $default): string
    {
        if (empty($values)) {
            return $default;
        }
        return $values[0];
    }

    /**
     * Get all values from a multi-value array.
     * 
     * This is the multi-value getter that returns the complete array.
     * 
     * @param string[] $values Array of values
     * @return string[] The complete array
     */
    protected function getMultipleValues(array $values): array
    {
        return $values;
    }

    /**
     * Check if the array contains multiple values.
     * 
     * Returns true if the array has more than one element.
     * 
     * @param string[] $values Array of values
     * @return bool True if array has 2 or more elements
     */
    protected function hasMultipleValues(array $values): bool
    {
        return count($values) > 1;
    }

    /**
     * Resolve an array of IDs to an array of entity objects.
     * 
     * This is a generic method that uses a callable to resolve IDs to entities.
     * The callable receives a single ID and returns the corresponding entity object.
     * 
     * ## Usage Example
     * 
     * ```php
     * // Resolve faction IDs to FactionDef objects
     * $factions = $this->resolveEntities(
     *     $this->builderFactionIDs,
     *     fn($id) => FactionDefs::getInstance()->getByID($id)
     * );
     * ```
     * 
     * @template T
     * @param string[] $ids Array of entity IDs to resolve
     * @param callable(string): T $resolver Callable that resolves ID to entity
     * @return T[] Array of resolved entities
     */
    protected function resolveEntities(array $ids, callable $resolver): array
    {
        return array_map($resolver, $ids);
    }

    /**
     * Parse a multi-value field from array data, handling format migration.
     * 
     * This method handles three data format scenarios:
     * 
     * 1. **New array format**: `$data[$newKey] = ["argon", "teladi"]`
     * 2. **Old string format**: `$data[$oldKey] = "argon teladi"`
     * 3. **Mixed format**: `$data[$oldKey] = ["argon", "teladi"]` (intermediate rebuild)
     * 
     * ## Format Migration Logic
     * 
     * - **Step 1**: Check for new key (plural) with array value → use it
     * - **Step 2**: Check for old key (singular):
     *   - If value is an array → use it (intermediate rebuild scenario)
     *   - If value is a string → split on spaces and trim each element
     * - **Step 3**: If both keys missing or empty → return array with default value
     * 
     * ## Example Usage
     * 
     * ```php
     * $builderFactionIDs = self::parseMultiValueField(
     *     $data,
     *     'builderFactionIDs',           // New key (plural)
     *     'builderFactionID',            // Old key (singular)
     *     KnownFactions::FACTION_GENERIC // Default value
     * );
     * ```
     * 
     * ## Edge Cases Handled
     * 
     * - Empty strings in old format → returns [$default]
     * - Extra whitespace in space-separated strings → trimmed
     * - Empty elements after splitting → filtered out
     * - Missing keys → returns [$default]
     * 
     * @param array<string,mixed> $data Source data array (typically from JSON)
     * @param string $newKey Key name for new array format (plural name)
     * @param string $oldKey Key name for old string format (singular name)
     * @param string $default Default value to use if no data found
     * @return string[] Array of parsed values
     */
    protected static function parseMultiValueField(array $data, string $newKey, string $oldKey, string $default): array
    {
        // 1. Check new key (plural) with array
        if (isset($data[$newKey]) && is_array($data[$newKey])) {
            return $data[$newKey];
        }
        
        // 2. Check old key (singular)
        if (isset($data[$oldKey])) {
            $oldValue = $data[$oldKey];
            
            // Handle array in old key (intermediate rebuild scenario)
            if (is_array($oldValue)) {
                return $oldValue;
            }
            
            // Handle string in old key (legacy format)
            if (is_string($oldValue)) {
                $trimmed = trim($oldValue);
                if ($trimmed !== '') {
                    // Split on spaces and trim each element
                    $values = array_map('trim', explode(' ', $trimmed));
                    // Filter out empty strings
                    return array_values(array_filter($values, fn($v) => $v !== ''));
                }
            }
        }
        
        // 3. Fallback to default
        return [$default];
    }
}
