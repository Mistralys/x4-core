<?php
/**
 * @package X4Tests
 * @subpackage Database\Builder
 * @see \Mistralys\X4\Database\Builder\LineEndingNormalizer
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Builder;

use Mistralys\X4\Database\Builder\LineEndingNormalizer;
use Mistralys\X4\X4Exception;
use PHPUnit\Framework\TestCase;

/**
 * @package X4Tests
 * @subpackage Database\Builder
 */
final class LineEndingNormalizerTests extends TestCase
{
    private string $tempFolder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempFolder = sys_get_temp_dir() . '/x4-core-eol-' . uniqid('', true);
        mkdir($this->tempFolder);
    }

    protected function tearDown(): void
    {
        foreach(glob($this->tempFolder . '/*') ?: array() as $file) {
            unlink($file);
        }

        rmdir($this->tempFolder);

        parent::tearDown();
    }

    public function test_normalizeString(): void
    {
        $this->assertSame("a\nb\nc\n", LineEndingNormalizer::normalizeString("a\r\nb\nc\r\n"));
    }

    public function test_normalizeString_leavesLoneLineFeedsAlone(): void
    {
        $this->assertSame("a\nb\n", LineEndingNormalizer::normalizeString("a\nb\n"));
    }

    /**
     * The exact shape of the files written by the database builder:
     * LF line breaks with a trailing PHP_EOL, which is CRLF on Windows.
     */
    public function test_normalizeFile_fixesMixedTrailingLineBreak(): void
    {
        $path = $this->writeFile('mixed.json', "[\n    1\n]\r\n");

        $this->assertTrue(LineEndingNormalizer::normalizeFile($path));
        $this->assertSame("[\n    1\n]\n", file_get_contents($path));
    }

    public function test_normalizeFile_leavesNormalizedFileUntouched(): void
    {
        $path = $this->writeFile('clean.json', "[\n    1\n]\n");

        $this->assertFalse(LineEndingNormalizer::normalizeFile($path));
        $this->assertSame("[\n    1\n]\n", file_get_contents($path));
    }

    public function test_normalizeFile_isIdempotent(): void
    {
        $path = $this->writeFile('twice.json', "[]\r\n");

        LineEndingNormalizer::normalizeFile($path);

        $this->assertFalse(LineEndingNormalizer::normalizeFile($path));
    }

    public function test_normalizeFile_missingFile(): void
    {
        $this->expectException(X4Exception::class);
        $this->expectExceptionCode(LineEndingNormalizer::ERROR_CANNOT_READ_FILE);

        LineEndingNormalizer::normalizeFile($this->tempFolder . '/does-not-exist.json');
    }

    public function test_normalizeJSONFilesInFolder(): void
    {
        $mixed = $this->writeFile('a.json', "{}\r\n");
        $clean = $this->writeFile('b.json', "{}\n");
        $other = $this->writeFile('c.txt', "text\r\n");

        $this->assertSame(1, LineEndingNormalizer::normalizeJSONFilesInFolder($this->tempFolder));

        $this->assertSame("{}\n", file_get_contents($mixed));
        $this->assertSame("{}\n", file_get_contents($clean));
        $this->assertSame("text\r\n", file_get_contents($other), 'Only JSON files must be touched.');
    }

    private function writeFile(string $name, string $content): string
    {
        $path = $this->tempFolder . '/' . $name;
        file_put_contents($path, $content);

        return $path;
    }
}
