<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Test\Unit\Protocol;

use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;
use Zete7\AudioSocket\Protocol\AbstractMessage;
use Zete7\AudioSocket\Protocol\Exception\OverflowException;
use Zete7\AudioSocket\Protocol\Kind;
use Zete7\AudioSocket\Test\Stub\MessageStub;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
#[PHPUnit\CoversClass(AbstractMessage::class)]
final class MessageTest extends TestCase
{
    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('provideKinds')]
    public function testPayloadBelowOverflow(Kind $kind): void
    {
        $message = new MessageStub(
            kind: $kind,
            payload: random_bytes(65535),
        );

        self::assertSame($kind, $message->kind);
        self::assertSame(65535, \strlen($message->payload));
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('provideKinds')]
    public function testPayloadAboveOverflow(Kind $kind): void
    {
        self::expectException(OverflowException::class);
        self::expectExceptionMessageIs('Payload size must be less than 65535.');

        new MessageStub(
            kind: $kind,
            payload: random_bytes(65536 /* = 65535 + 1 */),
        );
    }

    /**
     * @return iterable<string, array{ Kind }>
     */
    public static function provideKinds(): iterable
    {
        foreach (Kind::cases() as $kind) {
            yield $kind->name => [$kind];
        }
    }
}
