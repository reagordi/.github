<?php

/**
 * Reagordi Component
 *
 * @link https://reagordi.com
 * @copyright Copyright (c) 2026 Reagordi
 * @license https://reagordi.com/license
 */

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Config\RectorConfig;

/**
 * Rector configuration for the Reagordi Object package.
 *
 * Rector is an automated refactoring tool: it rewrites the source code
 * according to a set of rules. Unlike static analysers (Psalm, PHPStan),
 * Rector does not report issues — it fixes them, or rather applies
 * transformations. Run it locally before committing, and in CI as a
 * check that would fail on any modification (`--dry-run`).
 *
 * The configuration below targets PHP 8.3 as the minimum supported
 * version, applies a small set of code-quality transformations, and
 * skips three rules that would either change semantics or conflict
 * with the intentional design of the framework.
 *
 * @author aRl3k3N0 <reagordi@rufov.com>
 * @since 1.0
 */
return RectorConfig::configure()
    // Paths to process. Keep tests in sync with src so that fixtures
    // and assertions also use modern syntax. Add specific fixture
    // paths to the "withSkip" list below if any of them must retain
    // legacy syntax on purpose.
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])

    // Apply every rule set up to and including PHP 8.3. This brings
    // the codebase in line with the target language version: match,
    // readonly, first-class callables, constructor promotion, and
    // so on. The explicit "php83: true" avoids relying on composer.json
    // which may be absent in some build environments.
    ->withPhpSets(php83: true)

    // Additional code-quality rules applied on top of the PHP sets.
    // InlineConstructorDefaultToProperty promotes constructor defaults
    // to property declarations, keeping the default value visible
    // where the property is defined.
    ->withRules([
        InlineConstructorDefaultToPropertyRector::class,
    ])

    // House-keeping: remove unused "use" statements left behind by
    // previous transformations. Keeps diffs clean.
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true,
    )

    // Rules that are intentionally disabled. Each entry below is here
    // for a specific reason, not by accident.
    ->withSkip([
        // Arrow functions capture variables implicitly, which can
        // change semantics when a closure relies on an explicit
        // "use (...)" clause. Keep explicit closures where the
        // intent matters.
        ClosureToArrowFunctionRector::class,

        // readOnly on properties is not always safe: some properties
        // are initialised in init() rather than in the constructor,
        // and Rector does not always track that path correctly.
        // Mark properties as readonly by hand where appropriate.
        ReadOnlyPropertyRector::class,

        // Replacing null with '' before calling str_* functions hides
        // type errors instead of surfacing them. With strict_types=1
        // enabled, passing null to a string function is a bug that
        // should be caught, not silently patched.
        NullToStrictStringFuncCallArgRector::class,
    ]);
