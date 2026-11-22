<?php
/**
 * @package X4 Database
 * @subpackage Build Tools
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Builder;

use Mistralys\X4\UI\Console;
use Mistralys\X4\X4Application;
use Mistralys\X4\X4Exception;

/**
 * Normalizes the line endings of the files written by the database
 * builder to LF, which is the line ending used in the repository
 * (see `.gitattributes`).
 *
 * The JSON files are written with LF line breaks, but with a trailing
 * `PHP_EOL`, which is CRLF on Windows. The result is a file with mixed
 * line endings: One byte longer than the version in the repository, which
 * git then reports as modified even though the content is identical.
 * The generated PHP classes can carry the line endings of their
 * template source in the same way.
 *
 * @package X4 Database
 * @subpackage Build Tools
 */
class LineEndingNormalizer
{
    public const ERROR_CANNOT_READ_FILE = 183001;
    public const ERROR_CANNOT_WRITE_FILE = 183002;

    /**
     * Converts all CRLF line endings in the string to LF.
     */
    public static function normalizeString(string $content) : string
    {
        return str_replace("\r\n", "\n", $content);
    }

    /**
     * Normalizes the line endings of a file in place. The file
     * is only written to if it actually needs changes.
     *
     * @param string $path Absolute path to the file.
     * @return bool Whether the file had to be changed.
     * @throws X4Exception
     */
    public static function normalizeFile(string $path) : bool
    {
        $content = @file_get_contents($path);

        if($content === false) {
            throw new X4Exception(
                'Cannot read the file to normalize its line endings.',
                sprintf('Path: [%s]', $path),
                self::ERROR_CANNOT_READ_FILE
            );
        }

        $normalized = self::normalizeString($content);

        if($normalized === $content) {
            return false;
        }

        if(file_put_contents($path, $normalized) === false) {
            throw new X4Exception(
                'Cannot write the file with normalized line endings.',
                sprintf('Path: [%s]', $path),
                self::ERROR_CANNOT_WRITE_FILE
            );
        }

        return true;
    }

    /**
     * Normalizes all JSON files in the given folder (not recursive).
     *
     * @param string $folderPath Absolute path to the folder.
     * @return int The number of files that had to be changed.
     * @throws X4Exception
     */
    public static function normalizeJSONFilesInFolder(string $folderPath) : int
    {
        $files = glob(rtrim($folderPath, '/\\') . '/*.json');

        if($files === false) {
            return 0;
        }

        $changed = 0;
        foreach($files as $file) {
            if(self::normalizeFile($file)) {
                $changed++;
            }
        }

        return $changed;
    }

    /**
     * Normalizes the line endings of the data files of the database.
     *
     * @return int The number of files that had to be changed.
     * @throws X4Exception
     */
    public static function normalizeDataFiles() : int
    {
        $changed = self::normalizeJSONFilesInFolder(X4Application::getDataFolder()->getPath());

        Console::line1('Normalized the line endings of [%d] data files.', $changed);

        return $changed;
    }
}
