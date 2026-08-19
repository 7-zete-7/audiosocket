<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
enum AudioFormat: string
{
    private const array FORMAT_MAP = [
        self::Slin->value => 'slin',
        self::Slin12->value => 'slin12',
        self::Slin16->value => 'slin16',
        self::Slin24->value => 'slin24',
        self::Slin32->value => 'slin32',
        self::Slin44->value => 'slin44',
        self::Slin48->value => 'slin48',
        self::Slin96->value => 'slin96',
        self::Slin192->value => 'slin192',
    ];

    private const array BITRATE_MAP = [
        self::Slin->value => 8_000,
        self::Slin12->value => 12_000,
        self::Slin16->value => 16_000,
        self::Slin24->value => 24_000,
        self::Slin32->value => 32_000,
        self::Slin44->value => 44_100,
        self::Slin48->value => 48_000,
        self::Slin96->value => 96_000,
        self::Slin192->value => 192_000,
    ];

    private const array CHUNK_SIZE_MAP = [
        self::Slin->value => 320,     //   8kHz * 20ms * 2 bytes
        self::Slin12->value => 480,   //  12kHz * 20ms * 2 bytes
        self::Slin16->value => 640,   //  16kHz * 20ms * 2 bytes
        self::Slin24->value => 960,   //  24kHz * 20ms * 2 bytes
        self::Slin32->value => 1280,  //  32kHz * 20ms * 2 bytes
        self::Slin44->value => 1764,  //  44kHz * 20ms * 2 bytes
        self::Slin48->value => 1920,  //  48kHz * 20ms * 2 bytes
        self::Slin96->value => 3840,  //  96kHz * 20ms * 2 bytes
        self::Slin192->value => 7680, // 192kHz * 20ms * 2 bytes
    ];

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

    /**
     * @return value-of<self::FORMAT_MAP>
     */
    public function getFormat(): string
    {
        return self::FORMAT_MAP[$this->value];
    }

    /**
     * @return 16
     */
    public function getBitDepth(): int
    {
        return 16;
    }

    /**
     * @return value-of<self::BITRATE_MAP>
     */
    public function getBitRate(): int
    {
        return self::BITRATE_MAP[$this->value];
    }

    public function isSigned(): bool
    {
        return true;
    }

    public function isUnsigned(): bool
    {
        return !$this->isSigned();
    }

    /**
     * @return value-of<self::CHUNK_SIZE_MAP>
     */
    public function getChunkSize(): int
    {
        return self::CHUNK_SIZE_MAP[$this->value];
    }
}
