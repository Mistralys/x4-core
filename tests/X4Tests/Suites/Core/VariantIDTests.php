<?php
/**
 * @package X4Tests
 * @subpackage Core
 * @see \Mistralys\X4\Database\Core\VariantID
 */

declare(strict_types=1);

namespace X4Tests\Suites\Core;

use Mistralys\X4\Database\Core\VariantID;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the VariantID class which handles variant identification
 * for game objects (ships, modules, wares, etc.)
 *
 * @package X4Tests
 * @subpackage Core
 */
final class VariantIDTests extends X4TestCase
{
    // region: fromID() tests

    /**
     * Test parsing a standard variant ID with all components:
     * number, qualifier, and mark (e.g., "01:a:mk2")
     */
    public function test_fromID_standardFormat(): void
    {
        $variantID = VariantID::fromID('01:a:mk2');

        $this->assertSame(1, $variantID->getNumber());
        $this->assertSame('01', $variantID->getNumberString());
        $this->assertSame('a', $variantID->getQualifier());
        $this->assertSame('mk2', $variantID->getMark());
        $this->assertSame('01:a:mk2', $variantID->getID());
    }

    /**
     * Test parsing a variant ID without qualifier (e.g., "02::mk3")
     */
    public function test_fromID_noQualifier(): void
    {
        $variantID = VariantID::fromID('02::mk3');

        $this->assertSame(2, $variantID->getNumber());
        $this->assertSame('02', $variantID->getNumberString());
        $this->assertNull($variantID->getQualifier());
        $this->assertSame('mk3', $variantID->getMark());
        $this->assertSame('02::mk3', $variantID->getID());
    }

    /**
     * Test parsing a variant ID without mark (e.g., "03:b:")
     */
    public function test_fromID_noMark(): void
    {
        $variantID = VariantID::fromID('03:b:');

        $this->assertSame(3, $variantID->getNumber());
        $this->assertSame('03', $variantID->getNumberString());
        $this->assertSame('b', $variantID->getQualifier());
        $this->assertNull($variantID->getMark());
        $this->assertSame('03:b:', $variantID->getID());
    }

    /**
     * Test parsing a minimal variant ID with only number (e.g., "04::")
     */
    public function test_fromID_minimalFormat(): void
    {
        $variantID = VariantID::fromID('04::');

        $this->assertSame(4, $variantID->getNumber());
        $this->assertSame('04', $variantID->getNumberString());
        $this->assertNull($variantID->getQualifier());
        $this->assertNull($variantID->getMark());
        $this->assertSame('04::', $variantID->getID());
    }

    /**
     * Test parsing an invalid format returns empty variant
     */
    public function test_fromID_invalidFormat(): void
    {
        $variantID = VariantID::fromID('invalid');

        $this->assertSame(0, $variantID->getNumber());
        $this->assertNull($variantID->getQualifier());
        $this->assertNull($variantID->getMark());
    }

    /**
     * Test parsing empty string returns empty variant
     */
    public function test_fromID_emptyString(): void
    {
        $variantID = VariantID::fromID('');

        $this->assertSame(0, $variantID->getNumber());
        $this->assertNull($variantID->getQualifier());
        $this->assertNull($variantID->getMark());
    }

    // endregion

    // region: Getter tests

    /**
     * Test getNumber() returns correct integer
     */
    public function test_getNumber(): void
    {
        $variantID = VariantID::fromID('05:c:mk1');

        $this->assertSame(5, $variantID->getNumber());
    }

    /**
     * Test getNumberString() returns zero-padded string
     */
    public function test_getNumberString(): void
    {
        $variantID = VariantID::fromID('07:d:mk2');

        $this->assertSame('07', $variantID->getNumberString());
    }

    /**
     * Test getQualifier() returns qualifier or null
     */
    public function test_getQualifier(): void
    {
        $withQualifier = VariantID::fromID('01:qualifier:mk1');
        $withoutQualifier = VariantID::fromID('01::mk1');

        $this->assertSame('qualifier', $withQualifier->getQualifier());
        $this->assertNull($withoutQualifier->getQualifier());
    }

    /**
     * Test getMark() returns mark or null
     */
    public function test_getMark(): void
    {
        $withMark = VariantID::fromID('01:a:mk3');
        $withoutMark = VariantID::fromID('01:a:');

        $this->assertSame('mk3', $withMark->getMark());
        $this->assertNull($withoutMark->getMark());
    }

    /**
     * Test getID() reconstructs the variant ID string
     */
    public function test_getID(): void
    {
        $variantID = VariantID::fromID('02:qualifier:mk2');

        $this->assertSame('02:qualifier:mk2', $variantID->getID());
    }

    // endregion

    // region: MARKS constant tests

    /**
     * Test that MARKS constant contains expected values
     */
    public function test_marksConstants(): void
    {
        $expectedMarks = ['mk1', 'mk2', 'mk3'];

        $this->assertSame($expectedMarks, VariantID::MARKS);
    }

    /**
     * Test that all MARKS constants are valid mark values
     */
    public function test_allMarksAreValid(): void
    {
        foreach (VariantID::MARKS as $mark) {
            $variantID = VariantID::fromID("01::{$mark}");

            $this->assertSame($mark, $variantID->getMark());
        }
    }

    // endregion

    // region: resolveWareVariantID() tests

    /**
     * Test resolving variant ID from ware ID with number and mark
     * Example: ship_arg_l_fighter_01_a_mk2
     */
    public function test_resolveWareVariantID_standardFormat(): void
    {
        $variantID = VariantID::resolveWareVariantID('ship_arg_l_fighter_01_a_mk2');

        $this->assertSame(1, $variantID->getNumber());
        $this->assertSame('a', $variantID->getQualifier());
        $this->assertSame('mk2', $variantID->getMark());
    }

    /**
     * Test resolving variant ID from ware ID without mark
     */
    public function test_resolveWareVariantID_noMark(): void
    {
        $variantID = VariantID::resolveWareVariantID('ship_arg_l_fighter_02_b');

        $this->assertSame(2, $variantID->getNumber());
        $this->assertSame('b', $variantID->getQualifier());
        $this->assertNull($variantID->getMark());
    }

    /**
     * Test resolving variant ID from ware ID with only number
     */
    public function test_resolveWareVariantID_numberOnly(): void
    {
        $variantID = VariantID::resolveWareVariantID('ship_arg_l_fighter_03');

        $this->assertSame(3, $variantID->getNumber());
        $this->assertSame('', $variantID->getQualifier());
        $this->assertNull($variantID->getMark());
    }

    /**
     * Test resolving variant ID from ware ID without number returns empty variant
     */
    public function test_resolveWareVariantID_noNumber(): void
    {
        $variantID = VariantID::resolveWareVariantID('ship_arg_l_fighter_mk2');

        $this->assertSame(0, $variantID->getNumber());
    }

    /**
     * Test resolving handles multiple marks in ware ID (takes last one)
     */
    public function test_resolveWareVariantID_multipleMark(): void
    {
        $variantID = VariantID::resolveWareVariantID('ship_arg_l_fighter_01_mk3');

        $this->assertSame(1, $variantID->getNumber());
        $this->assertSame('mk3', $variantID->getMark());
    }

    // endregion

    // region: appendConstantSuffix() tests

    /**
     * Test appending variant parts to a constant name
     * Example: SHIP_ARGON -> SHIP_ARGON_01_MK2_A
     */
    public function test_appendConstantSuffix(): void
    {
        $variantID = VariantID::fromID('01:a:mk2');

        $result = $variantID->appendConstantSuffix('SHIP_ARGON');

        $this->assertSame('SHIP_ARGON_01_MK2_A', $result);
    }

    /**
     * Test appending with no variant parts returns original constant
     */
    public function test_appendConstantSuffix_noVariants(): void
    {
        $variantID = VariantID::fromID('0::');

        $result = $variantID->appendConstantSuffix('SHIP_ARGON');

        $this->assertSame('SHIP_ARGON', $result);
    }

    /**
     * Test appending with exception suffix
     */
    public function test_appendConstantSuffix_withExceptionSuffix(): void
    {
        $variantID = VariantID::fromID('02:b:');

        $result = $variantID->appendConstantSuffix('SHIP_ARGON', 'SPECIAL');

        $this->assertSame('SHIP_ARGON_02_B_SPECIAL', $result);
    }

    /**
     * Test that hyphens in qualifiers are converted to underscores
     */
    public function test_appendConstantSuffix_hyphenConversion(): void
    {
        $variantID = VariantID::fromID('01:multi-part:');

        $result = $variantID->appendConstantSuffix('CONSTANT');

        $this->assertSame('CONSTANT_01_MULTI_PART', $result);
    }

    // endregion

    // region: appendMethodSuffix() tests

    /**
     * Test appending variant parts to a method name
     * Example: getShip -> getShip_01Mk2A
     */
    public function test_appendMethodSuffix(): void
    {
        $variantID = VariantID::fromID('01:a:mk2');

        $result = $variantID->appendMethodSuffix('getShip');

        $this->assertSame('getShip_01Mk2A', $result);
    }

    /**
     * Test appending with no variant parts returns original method name
     */
    public function test_appendMethodSuffix_noVariants(): void
    {
        $variantID = VariantID::fromID('0::');

        $result = $variantID->appendMethodSuffix('getShip');

        $this->assertSame('getShip', $result);
    }

    /**
     * Test appending with exception suffix
     */
    public function test_appendMethodSuffix_withExceptionSuffix(): void
    {
        $variantID = VariantID::fromID('02:b:');

        $result = $variantID->appendMethodSuffix('getShip', 'special');

        $this->assertSame('getShip_02BSpecial', $result);
    }

    /**
     * Test that hyphens in qualifiers are converted to underscores
     */
    public function test_appendMethodSuffix_hyphenConversion(): void
    {
        $variantID = VariantID::fromID('01:multi-part:');

        $result = $variantID->appendMethodSuffix('getMethod');

        $this->assertSame('getMethod_01Multi_part', $result);
    }

    // endregion

    // region: __toString() tests

    /**
     * Test that __toString() returns the same as getID()
     */
    public function test_toString(): void
    {
        $variantID = VariantID::fromID('03:qualifier:mk1');

        $this->assertSame('03:qualifier:mk1', (string)$variantID);
        $this->assertSame($variantID->getID(), (string)$variantID);
    }

    // endregion

    // region: Edge case tests

    /**
     * Test variant ID with high number (zero padding)
     */
    public function test_highNumber(): void
    {
        $variantID = VariantID::fromID('99:z:mk3');

        $this->assertSame(99, $variantID->getNumber());
        $this->assertSame('99', $variantID->getNumberString());
    }

    /**
     * Test variant ID with single digit number (zero padding maintained)
     */
    public function test_singleDigitNumber(): void
    {
        $variantID = VariantID::fromID('05:x:mk1');

        $this->assertSame('05', $variantID->getNumberString());
    }

    /**
     * Test reconstructed ID maintains format
     */
    public function test_idReconstruction(): void
    {
        $originalID = '08:test:mk2';
        $variantID = VariantID::fromID($originalID);

        $this->assertSame($originalID, $variantID->getID());
    }

    // endregion
}
