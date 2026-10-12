<?php

/**
 * Reagordi Component
 *
 * @link https://reagordi.com
 * @copyright Copyright (c) 2026 Reagordi
 * @license https://reagordi.com/license
 */

declare(strict_types=1);

use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use Reagordi\Component\CodeStyle\ConfigBuilder;

/**
 * Configuration for PHP-CS-Fixer.
 *
 * PHP-CS-Fixer rewrites PHP files according to a set of rules. It is
 * different from static analysers (Psalm, PHPStan) and from semantic
 * refactoring tools (Rector): it does not report issues, it applies
 * formatting transformations. Run it before committing, and in CI as
 * a dry-run check that fails on any modification.
 *
 * The rule set used here comes from the reagordi/component/code-style
 * package. It provides two predefined sets:
 *
 * - @Reagordi/Core        — the baseline style (PSR-12 plus framework
 *                           conventions: import order, spacing, etc.);
 * - @Reagordi/Core:risky  — optional rules that MAY change behaviour
 *                           (e.g. declare_strict_types, single_quote,
 *                           native_function_invocation). These require
 *                           setRiskyAllowed(true) to take effect.
 *
 * @author aRl3k3N0 <reagordi@rufov.com>
 * @since 1.0
 */

// Files to process. Both src/ and tests/ are scanned so that the test
// suite follows the same style as the code it tests. Excluded paths
// (e.g. intentionally malformed fixtures) should be added with
// ->notPath() or ->notName() below.
$finder = (new Finder())->in([
    __DIR__ . '/src',
    __DIR__ . '/tests',
]);

return ConfigBuilder::build()
    // Risky rules are allowed. Without this flag, all ":risky" rules in
    // the rule sets below would be silently ignored. Since risky rules
    // may change behaviour, always run the test suite AFTER a fixer run.
    ->setRiskyAllowed(true)

    // Enable parallel processing. ParallelConfigFactory::detect() picks
    // an optimal strategy based on the current environment (pcntl on
    // Unix, parallel on Windows, or a single-threaded fallback when
    // neither extension is available).
    ->setParallelConfig(ParallelConfigFactory::detect())

    // Rule sets. @Reagordi/Core is the baseline; @Reagordi/Core:risky
    // adds rules that may alter behaviour but are considered safe in
    // the context of this project. Individual rules can be overridden
    // by adding them to this array — later entries win.
    ->setRules([
        '@Reagordi/Core' => true,
        '@Reagordi/Core:risky' => true,
    ])

    // Assign the finder last so that any future extension of the rules
    // does not accidentally affect the list of scanned files.
    ->setFinder($finder);
