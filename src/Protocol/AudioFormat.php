<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
enum AudioFormat: string
{
    /**
     * Format: `slin`
     * Bitrate: 8kHz.
     */
    case Slin = "\x10";

    /**
     * Format: `slin12`
     * Bitrate: 12kHz.
     */
    case Slin12 = "\x11";

    /**
     * Format: `slin16`
     * Bitrate: 16kHz.
     */
    case Slin16 = "\x12";

    /**
     * Format: `slin24`
     * Bitrate: 24kHz.
     */
    case Slin24 = "\x13";

    /**
     * Format: `slin32`
     * Bitrate: 32kHz.
     */
    case Slin32 = "\x14";

    /**
     * Format: `slin44`
     * Bitrate: 44.1kHz.
     */
    case Slin44 = "\x15";

    /**
     * Format: `slin48`
     * Bitrate: 48kHz.
     */
    case Slin48 = "\x16";

    /**
     * Format: `slin96`
     * Bitrate: 96kHz.
     */
    case Slin96 = "\x17";

    /**
     * Format: `slin192`
     * Bitrate: 192kHz.
     */
    case Slin192 = "\x18";

    public function getFormat(): string
    {
        return match ($this) {
            self::Slin => 'slin',
            self::Slin12 => 'slin12',
            self::Slin16 => 'slin16',
            self::Slin24 => 'slin24',
            self::Slin32 => 'slin32',
            self::Slin44 => 'slin44',
            self::Slin48 => 'slin48',
            self::Slin96 => 'slin96',
            self::Slin192 => 'slin192',
        };
    }

    public function getBitDepth(): int
    {
        return 16;
    }

    public function getBitRate(): int
    {
        return match ($this) {
            self::Slin => 8_000,
            self::Slin12 => 12_000,
            self::Slin16 => 16_000,
            self::Slin24 => 24_000,
            self::Slin32 => 32_000,
            self::Slin44 => 44_100,
            self::Slin48 => 48_000,
            self::Slin96 => 96_000,
            self::Slin192 => 192_000,
        };
    }

    public function isSigned(): bool
    {
        return true;
    }

    public function isUnsigned(): bool
    {
        return !$this->isSigned();
    }

    public function getChunkSize(): int
    {
        return match ($this) {
            self::Slin => 320,     //   8kHz * 20ms * 2 bytes
            self::Slin12 => 480,   //  12kHz * 20ms * 2 bytes
            self::Slin16 => 640,   //  16kHz * 20ms * 2 bytes
            self::Slin24 => 960,   //  24kHz * 20ms * 2 bytes
            self::Slin32 => 1280,  //  32kHz * 20ms * 2 bytes
            self::Slin44 => 1764,  //  44kHz * 20ms * 2 bytes
            self::Slin48 => 1920,  //  48kHz * 20ms * 2 bytes
            self::Slin96 => 3840,  //  96kHz * 20ms * 2 bytes
            self::Slin192 => 7680, // 192kHz * 20ms * 2 bytes
        };
    }
}
