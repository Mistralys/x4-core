<?php

declare(strict_types=1);

namespace Mistralys\X4;

/**
 * Absolute path to the X4 game folder.
 */
const X4_GAME_FOLDER = '/path/to/x4';

/**
 * Absolute path to the folder where the extracted
 * game files are stored (after unpacking them using
 * the XRCatTool).
 *
 * This is required to extract the data used in
 * parts of the package.
 */
const X4_EXTRACTED_CAT_FILES_FOLDER = '/path/to/folder';

/**
 * Used only to run the tests.
 *
 * This is the URL in the local webserver where
 * the X4 Core library clone is installed.
 */
const X4_CORE_INSTALL_URL = 'http://127.0.0.1/tools/x4-core';
