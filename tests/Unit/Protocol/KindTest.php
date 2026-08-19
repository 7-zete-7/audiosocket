<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Test\Unit\Protocol;

use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;
use Zete7\AudioSocket\Protocol\AudioFormat;
use Zete7\AudioSocket\Protocol\Exception\NotAudioMessageException;
use Zete7\AudioSocket\Protocol\Kind;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
#[PHPUnit\CoversClass(Kind::class)]
final class KindTest extends TestCase
{
    #[PHPUnit\Test]
    #[PHPUnit\TestWith([AudioFormat::Slin, Kind::Slin])]
    #[PHPUnit\TestWith([AudioFormat::Slin12, Kind::Slin12])]
    #[PHPUnit\TestWith([AudioFormat::Slin16, Kind::Slin16])]
    #[PHPUnit\TestWith([AudioFormat::Slin24, Kind::Slin24])]
    #[PHPUnit\TestWith([AudioFormat::Slin32, Kind::Slin32])]
    #[PHPUnit\TestWith([AudioFormat::Slin44, Kind::Slin44])]
    #[PHPUnit\TestWith([AudioFormat::Slin48, Kind::Slin48])]
    #[PHPUnit\TestWith([AudioFormat::Slin96, Kind::Slin96])]
    #[PHPUnit\TestWith([AudioFormat::Slin192, Kind::Slin192])]
    public function testFromAudioFormat(AudioFormat $audioFormat, Kind $expectedKind): void
    {
        self::assertSame($expectedKind, Kind::fromAudioFormat($audioFormat));
    }

    #[PHPUnit\Test]
    #[PHPUnit\TestWith([Kind::Slin, AudioFormat::Slin])]
    #[PHPUnit\TestWith([Kind::Slin12, AudioFormat::Slin12])]
    #[PHPUnit\TestWith([Kind::Slin16, AudioFormat::Slin16])]
    #[PHPUnit\TestWith([Kind::Slin24, AudioFormat::Slin24])]
    #[PHPUnit\TestWith([Kind::Slin32, AudioFormat::Slin32])]
    #[PHPUnit\TestWith([Kind::Slin44, AudioFormat::Slin44])]
    #[PHPUnit\TestWith([Kind::Slin48, AudioFormat::Slin48])]
    #[PHPUnit\TestWith([Kind::Slin96, AudioFormat::Slin96])]
    #[PHPUnit\TestWith([Kind::Slin192, AudioFormat::Slin192])]
    public function testGetAudioFormatFromSlinKind(Kind $kind, AudioFormat $expectedAudioFormat): void
    {
        self::assertSame($expectedAudioFormat, $kind->getAudioFormat());
    }

    #[PHPUnit\Test]
    #[PHPUnit\TestWith([Kind::Hangup])]
    #[PHPUnit\TestWith([Kind::Uuid])]
    #[PHPUnit\TestWith([Kind::Dtmf])]
    #[PHPUnit\TestWith([Kind::Error])]
    public function testGetAudioFormatFromNonSlinKind(Kind $kind): void
    {
        self::expectException(NotAudioMessageException::class);

        $kind->getAudioFormat();
    }
}
