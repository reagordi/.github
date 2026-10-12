<?php

/**
 * Reagordi Contracts Exceptions
 *
 * @link https://reagordi.com
 * @copyright Copyright (c) 2023 Reagordi
 * @license https://reagordi.com/license
 */

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

/**
 * Configuration for Composer Dependency Analyser.
 *
 * The analyser inspects the source code and compares the classes it finds
 * with the dependencies declared in composer.json. It reports two kinds of
 * issues:
 *
 * - unused dependencies declared in composer.json but never referenced;
 * - shadow dependencies used in code but not declared in composer.json.
 *
 * Both issues are treated as errors in CI.
 *
 * Notes on the configuration below:
 *
 * - Composer's own autoload paths are disabled because packages in this
 *   repository are self-contained and use a custom autoload.xml. All code
 *   paths are therefore listed explicitly via addPathToScan().
 * - Tests are scanned with isDev: true so that their dependencies are
 *   expected in require-dev, not in require.
 * - If a package ever adds a "files" autoload entry (e.g. src/functions.php
 *   with helper functions), it MUST be added here explicitly with
 *   addFileToScan(), because path scanning only discovers classes.
 *
 * @author aRl3k3N0 <reagordi@rufov.com>
 * @since 1.0
 */
return (new Configuration())
    // Do not rely on composer.json autoload paths: the project uses
    // a custom autoloader and explicit paths. Without this call the
    // analyser would try to infer paths from composer.json, which
    // may not reflect the actual layout.
    ->disableComposerAutoloadPathScan()

    // Production code: dependencies MUST be declared in "require".
    ->addPathToScan(__DIR__ . '/src', isDev: false)

    // Test code: dependencies MUST be declared in "require-dev".
    ->addPathToScan(__DIR__ . '/tests', isDev: true);