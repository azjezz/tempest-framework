<?php

declare(strict_types=1);

namespace Tempest\Router\Tests\Routing\Matching;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Tempest\Router\Routing\Matching\MatchingRegex;

/**
 * @internal
 */
final class MatchingRegexTest extends TestCase
{
    private MatchingRegex $subject;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new MatchingRegex([
            '#^(a)(*MARK:a)$#',
            '#^(b)(*MARK:b)$#',
            '#^(c)(*MARK:c)$#',
        ]);
    }

    public function test_empty(): void
    {
        $subject = new MatchingRegex([]);

        static::assertNull($subject->match(''));
    }

    #[TestWith(['a'])]
    #[TestWith(['b'])]
    #[TestWith(['c'])]
    public function test_match(string $expectedMatch): void
    {
        $match = $this->subject->match($expectedMatch);

        static::assertNotNull($match);
        static::assertEquals($expectedMatch, $match->mark);
        static::assertEquals($expectedMatch, $match->matches[1]);
    }

    #[TestWith([''])]
    #[TestWith(['d'])]
    public function test_non_match(string $expectedNonMatch): void
    {
        $match = $this->subject->match($expectedNonMatch);

        static::assertNull($match);
    }
}
