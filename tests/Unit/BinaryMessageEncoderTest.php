<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Test\Unit;

use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Zete7\AudioSocket\BinaryMessageEncoder;
use Zete7\AudioSocket\Protocol\AudioFormat;
use Zete7\AudioSocket\Protocol\AudioMessage;
use Zete7\AudioSocket\Protocol\DtmfMessage;
use Zete7\AudioSocket\Protocol\DtmfSignal;
use Zete7\AudioSocket\Protocol\ErrorMessage;
use Zete7\AudioSocket\Protocol\HangupMessage;
use Zete7\AudioSocket\Protocol\Message;
use Zete7\AudioSocket\Protocol\UuidMessage;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
#[PHPUnit\CoversClass(BinaryMessageEncoder::class)]
final class BinaryMessageEncoderTest extends TestCase
{
    private const string BYTES_BASE64 = <<<'EOF'
        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
        EOF;

    private BinaryMessageEncoder $binaryMessageEncoder;

    protected function setUp(): void
    {
        $this->binaryMessageEncoder = new BinaryMessageEncoder();
    }

    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('provideValidMessageEncodings')]
    public function testSuccessMessageEncoding(Message $message, string $expectedEncodedMessage): void
    {
        $encodedMessage = $this->binaryMessageEncoder->encodeMessage($message);

        self::assertSame($expectedEncodedMessage, $encodedMessage);
    }

    /**
     * @param list<Message> $expectedDecodedMessages
     */
    #[PHPUnit\Test]
    #[PHPUnit\DataProvider('provideDecodableMessages')]
    public function testSuccessMessageDecoding(string $buffer, array $expectedDecodedMessages, string $expectedRestBuffer = ''): void
    {
        /** @var list<Message> $decodedMessages */
        $decodedMessages = [];

        while ($decodedMessage = $this->binaryMessageEncoder->decodeMessage($buffer)) {
            $decodedMessages[] = $decodedMessage;
        }

        self::assertEquals($expectedDecodedMessages, $decodedMessages);
        self::assertSame($expectedRestBuffer, $buffer);
    }

    #[PHPUnit\Test]
    public function testMaxPayloadSize(): void
    {
        $payload = random_bytes(65535);
        $bytes = "\x10\xFF\xFF".$payload;

        $decodedMessage = $this->binaryMessageEncoder->decodeMessage($bytes);

        self::assertInstanceOf(AudioMessage::class, $decodedMessage);
        self::assertSame(AudioFormat::Slin, $decodedMessage->audioFormat);
        self::assertSame($payload, $decodedMessage->payload);
        self::assertSame('', $bytes);
    }

    /**
     * @return iterable<string, array{ Message, string }>
     */
    public static function provideValidMessageEncodings(): iterable
    {
        yield 'hangup' => [
            new HangupMessage(),
            "\x00\x00\x00",
        ];

        yield 'v1 UUID' => [
            new UuidMessage(
                uuid: Uuid::fromString('4febc400-958b-11f1-8cec-7be7cb72d386'),
            ),
            "\x01\x00\x10\x4F\xEB\xC4\x00\x95\x8B\x11\xF1\x8C\xEC\x7B\xE7\xCB\x72\xD3\x86",
        ];

        yield 'v3 UUID' => [
            new UuidMessage(
                uuid: Uuid::fromString('61df151d-7508-321d-ada6-27936752b809'),
            ),
            "\x01\x00\x10\x61\xDF\x15\x1D\x75\x08\x32\x1D\xAD\xA6\x27\x93\x67\x52\xB8\x09",
        ];

        yield 'v4 UUID' => [
            new UuidMessage(
                uuid: Uuid::fromString('84462926-fdc9-4660-8978-3583ae7547a3'),
            ),
            "\x01\x00\x10\x84\x46\x29\x26\xFD\xC9\x46\x60\x89\x78\x35\x83\xAE\x75\x47\xA3",
        ];

        yield 'v5 UUID' => [
            new UuidMessage(
                uuid: Uuid::fromString('b428b5d9-df19-5bb9-a1dc-115e071b836c'),
            ),
            "\x01\x00\x10\xB4\x28\xB5\xD9\xDF\x19\x5B\xB9\xA1\xDC\x11\x5E\x07\x1B\x83\x6C",
        ];

        yield 'v6 UUID' => [
            new UuidMessage(
                uuid: Uuid::fromString('1f1958be-380a-6e10-ba5e-617004bf20aa'),
            ),
            "\x01\x00\x10\x1F\x19\x58\xBE\x38\x0A\x6E\x10\xBA\x5E\x61\x70\x04\xBF\x20\xAA",
        ];

        yield 'v7 UUID' => [
            new UuidMessage(
                uuid: Uuid::fromString('019ff119-a659-74fe-9a0b-0e0b2aa6dbef'),
            ),
            "\x01\x00\x10\x01\x9F\xF1\x19\xA6\x59\x74\xFE\x9A\x0B\x0E\x0B\x2A\xA6\xDB\xEF",
        ];

        yield 'v8 UUID' => [
            new UuidMessage(
                uuid: Uuid::fromString('d73c6244-0bd3-83f3-9acf-e2660f1ee84f'),
            ),
            "\x01\x00\x10\xD7\x3C\x62\x44\x0B\xD3\x83\xF3\x9A\xCF\xE2\x66\x0F\x1E\xE8\x4F",
        ];

        foreach (DtmfSignal::cases() as $dtmfSignal) {
            yield \sprintf('"%s" DTMF', $dtmfSignal->value) => [
                new DtmfMessage(
                    signal: $dtmfSignal,
                ),
                "\x03\x00\x01".$dtmfSignal->value,
            ];
        }

        foreach (AudioFormat::cases() as $audioFormat) {
            yield \sprintf('"%s" audio format', $audioFormat->getFormat()) => [
                new AudioMessage(
                    audioFormat: $audioFormat,
                    payload: self::getBytes(),
                ),
                $audioFormat->value."\x1e\x00".self::getBytes(),
            ];
        }

        yield 'empty payload error' => [
            new ErrorMessage(
                payload: '',
            ),
            "\xFF\x00\x00",
        ];

        yield 'error with code' => [
            new ErrorMessage(
                payload: "\x00",
            ),
            "\xFF\x00\x01\x00",
        ];
    }

    /**
     * @return iterable<string, array{ string, list<Message> }>
     */
    public static function provideDecodableMessages(): iterable
    {
        yield 'none' => [
            '',
            [],
        ];

        yield 'partial none' => [
            "\x00\x00",
            [],
            "\x00\x00",
        ];

        foreach (self::provideValidMessageEncodings() as $name => [$message, $bytes]) {
            yield $name => [$bytes, [$message]];
        }

        yield 'uuid + 2x slin audio' => [
            "\x01\x00\x10\xE8\x5A\xDD\xCF\xFA\xBF\x45\xE4\x9E\x04\x5A\x1A\xC8\xD8\x05\xF2".
            "\x10\x01\x40\xcb\x59\xa3\xaf\xda\x1a\x36\xbd\xff\x40\x28\x6b\x5b\x8f\x67\x93\xcf\xf7\xd5\xec\x5b\xa5\x11\x70\x04\xd0\x7f\x97\x31\x1f\xc2\xa6\x4f\x03\x0a\x79\x23\x4f\x61\x4f\xbb\xe4\x55\x62\x6d\xd4\x40\xa6\xaa\xfb\x65\x7e\x11\x3a\x8d\x66\x60\xdf\x1c\xcc\x39\x2a\xe2\x2a\xc2\xdd\x2d\x46\xd6\xd4\x64\xe9\x46\xe4\x56\xb7\xe7\x95\xb3\x77\x9e\x78\x06\xe1\x18\x54\xb5\x37\x46\x5b\x05\x84\x93\xa4\xf5\xa1\xf4\xed\x35\xf5\x80\xec\x95\xd9\x55\x50\x10\xe1\xef\xf9\xa8\xe4\xd6\x6a\xcd\xb9\x0a\x7d\xec\x09\x22\x24\x1c\xbe\x18\x3a\x53\x51\xc0\x28\xb2\x5a\xac\xbd\x0c\xab\xf8\xd7\x03\x9c\x2d\x25\x1b\xba\xd9\xa0\xa9\x4a\xe2\x25\x27\x5e\xa0\x4f\xf7\xc1\x70\x11\xbb\xbb\x46\xee\xdc\x1d\x5e\xf6\x3b\x7d\xe3\x7b\x64\xc4\x31\x86\x25\x62\x53\xaf\xbc\x20\x9d\xba\x65\xa6\xf8\x77\x3f\x06\x05\x7e\xc7\x38\x8d\x7b\x25\x72\x1a\xb3\xf2\x4a\xdf\x93\x8a\x45\x4d\x89\x34\x74\x6c\xd6\x2c\xfd\xcd\xd2\xb3\xbc\xe0\x84\xf1\x83\x7f\xcc\xcd\x78\x19\xee\x2e\x0e\x14\x7c\xc7\xde\x8e\xe7\x34\x8c\x1e\x76\x23\x75\x00\xe4\xeb\x6c\x8e\x25\x2f\x95\xee\xb4\x09\x79\x6c\xae\x3b\xdc\xe7\xe3\xa6\x8d\xd4\x4e\x37\x5d\xd0\x7f\x29\x65\x04\x30\xab\x5b\x13\x70\x4b\x62\x44\x30\xe2\xa9\xcd\xf8\x94\x34\x58\x81\x02\x38\x2e\x6c\x37\x62\xc0\xd6\x04\xff\xc0\xde\xa9\x6b\xf4\x61\xe0\xd2\x44\xa6\x6a\x3e\xc6\x80\x89\xb6\xb4\x52\xf3\x08\xa5\x0d\x2e\x71".
            "\x10\x01\x40\x0a\xa8\xdc\x5f\x83\x3f\x45\x82\x4f\xea\xa3\xb3\x5a\xd4\x2a\x7b\xf6\x20\x94\x8a\xd1\xbb\x9c\x77\x80\xef\x18\xe9\x12\xa8\xbe\x1b\xa3\xce\x43\x95\x0c\xe2\xe5\x6f\x71\xf2\x35\xfa\x64\x47\x95\x4c\x71\x52\xcf\xc4\x8a\x26\x04\xd2\x1e\x9a\x84\xe3\x97\x41\x37\xf5\xb7\xa3\x0d\x4b\x7a\x64\x12\x90\x2f\xe7\x5f\x08\xf8\xed\x12\x35\x47\x0e\xcc\x38\x2c\xa9\x26\x02\x7b\xb2\x57\x99\x50\x00\x0b\x0b\x5b\x70\x69\x5f\xe0\xff\x44\x0d\x6f\x6f\xb5\xb7\xfb\x30\x73\x88\x16\x43\x06\xcb\x75\x13\x22\x99\x4e\x42\x09\x91\xce\xf8\x18\x51\x36\x5e\xd7\x46\xa2\xb5\xa9\x77\x21\x1f\x57\xd7\xbe\x20\x76\x3c\x95\xb1\xf8\xe8\xe5\x77\xcf\xea\xc9\x23\x83\x08\x40\x4f\xa9\xb0\x70\xc6\x57\x70\x43\x11\x28\x81\x35\x71\x3d\x3d\x18\x92\x2e\x28\x54\x9a\x05\xa6\x7b\xa3\xbf\xef\xe3\x40\x8d\x58\x96\xaf\x67\xec\x78\xed\x5e\x56\x82\x3d\xbd\x04\x08\x6f\x22\xfb\xb2\x7c\x02\x4a\xad\x3a\x17\x22\x27\x93\x4e\x7c\x34\x95\x5b\xcb\x66\xee\x17\x38\x8e\x50\x66\xbc\x7c\xd8\x60\xfa\x9b\x60\xce\xcd\x06\xcd\x9c\x3f\xf3\xf4\x2b\x8a\x6b\x25\x70\xbf\x90\x81\xc7\x41\x06\x3f\xae\x5d\x70\x8e\x8e\xa9\x54\x48\xe5\xf7\xd4\x4f\xdf\x8e\x2f\xc1\xd3\x29\x5b\xc9\xed\x76\x7b\xbf\x63\xd3\xf0\x62\xed\x4f\xbb\x13\x4c\x1b\x25\x2c\xde\x0b\x12\x5a\x69\x59\xb0\xd9\xa6\x4b\x12\xee\x16\xb7\x7d\x1a\xf2\x40\xbf\xb5\x0d\xf2\x17\xa5\xf8\x16\x20\xe1\x0b\x32",
            [
                new UuidMessage(
                    uuid: Uuid::fromString('e85addcf-fabf-45e4-9e04-5a1ac8d805f2'),
                ),
                new AudioMessage(
                    audioFormat: AudioFormat::Slin,
                    payload: "\xcb\x59\xa3\xaf\xda\x1a\x36\xbd\xff\x40\x28\x6b\x5b\x8f\x67\x93\xcf\xf7\xd5\xec\x5b\xa5\x11\x70\x04\xd0\x7f\x97\x31\x1f\xc2\xa6\x4f\x03\x0a\x79\x23\x4f\x61\x4f\xbb\xe4\x55\x62\x6d\xd4\x40\xa6\xaa\xfb\x65\x7e\x11\x3a\x8d\x66\x60\xdf\x1c\xcc\x39\x2a\xe2\x2a\xc2\xdd\x2d\x46\xd6\xd4\x64\xe9\x46\xe4\x56\xb7\xe7\x95\xb3\x77\x9e\x78\x06\xe1\x18\x54\xb5\x37\x46\x5b\x05\x84\x93\xa4\xf5\xa1\xf4\xed\x35\xf5\x80\xec\x95\xd9\x55\x50\x10\xe1\xef\xf9\xa8\xe4\xd6\x6a\xcd\xb9\x0a\x7d\xec\x09\x22\x24\x1c\xbe\x18\x3a\x53\x51\xc0\x28\xb2\x5a\xac\xbd\x0c\xab\xf8\xd7\x03\x9c\x2d\x25\x1b\xba\xd9\xa0\xa9\x4a\xe2\x25\x27\x5e\xa0\x4f\xf7\xc1\x70\x11\xbb\xbb\x46\xee\xdc\x1d\x5e\xf6\x3b\x7d\xe3\x7b\x64\xc4\x31\x86\x25\x62\x53\xaf\xbc\x20\x9d\xba\x65\xa6\xf8\x77\x3f\x06\x05\x7e\xc7\x38\x8d\x7b\x25\x72\x1a\xb3\xf2\x4a\xdf\x93\x8a\x45\x4d\x89\x34\x74\x6c\xd6\x2c\xfd\xcd\xd2\xb3\xbc\xe0\x84\xf1\x83\x7f\xcc\xcd\x78\x19\xee\x2e\x0e\x14\x7c\xc7\xde\x8e\xe7\x34\x8c\x1e\x76\x23\x75\x00\xe4\xeb\x6c\x8e\x25\x2f\x95\xee\xb4\x09\x79\x6c\xae\x3b\xdc\xe7\xe3\xa6\x8d\xd4\x4e\x37\x5d\xd0\x7f\x29\x65\x04\x30\xab\x5b\x13\x70\x4b\x62\x44\x30\xe2\xa9\xcd\xf8\x94\x34\x58\x81\x02\x38\x2e\x6c\x37\x62\xc0\xd6\x04\xff\xc0\xde\xa9\x6b\xf4\x61\xe0\xd2\x44\xa6\x6a\x3e\xc6\x80\x89\xb6\xb4\x52\xf3\x08\xa5\x0d\x2e\x71",
                ),
                new AudioMessage(
                    audioFormat: AudioFormat::Slin,
                    payload: "\x0a\xa8\xdc\x5f\x83\x3f\x45\x82\x4f\xea\xa3\xb3\x5a\xd4\x2a\x7b\xf6\x20\x94\x8a\xd1\xbb\x9c\x77\x80\xef\x18\xe9\x12\xa8\xbe\x1b\xa3\xce\x43\x95\x0c\xe2\xe5\x6f\x71\xf2\x35\xfa\x64\x47\x95\x4c\x71\x52\xcf\xc4\x8a\x26\x04\xd2\x1e\x9a\x84\xe3\x97\x41\x37\xf5\xb7\xa3\x0d\x4b\x7a\x64\x12\x90\x2f\xe7\x5f\x08\xf8\xed\x12\x35\x47\x0e\xcc\x38\x2c\xa9\x26\x02\x7b\xb2\x57\x99\x50\x00\x0b\x0b\x5b\x70\x69\x5f\xe0\xff\x44\x0d\x6f\x6f\xb5\xb7\xfb\x30\x73\x88\x16\x43\x06\xcb\x75\x13\x22\x99\x4e\x42\x09\x91\xce\xf8\x18\x51\x36\x5e\xd7\x46\xa2\xb5\xa9\x77\x21\x1f\x57\xd7\xbe\x20\x76\x3c\x95\xb1\xf8\xe8\xe5\x77\xcf\xea\xc9\x23\x83\x08\x40\x4f\xa9\xb0\x70\xc6\x57\x70\x43\x11\x28\x81\x35\x71\x3d\x3d\x18\x92\x2e\x28\x54\x9a\x05\xa6\x7b\xa3\xbf\xef\xe3\x40\x8d\x58\x96\xaf\x67\xec\x78\xed\x5e\x56\x82\x3d\xbd\x04\x08\x6f\x22\xfb\xb2\x7c\x02\x4a\xad\x3a\x17\x22\x27\x93\x4e\x7c\x34\x95\x5b\xcb\x66\xee\x17\x38\x8e\x50\x66\xbc\x7c\xd8\x60\xfa\x9b\x60\xce\xcd\x06\xcd\x9c\x3f\xf3\xf4\x2b\x8a\x6b\x25\x70\xbf\x90\x81\xc7\x41\x06\x3f\xae\x5d\x70\x8e\x8e\xa9\x54\x48\xe5\xf7\xd4\x4f\xdf\x8e\x2f\xc1\xd3\x29\x5b\xc9\xed\x76\x7b\xbf\x63\xd3\xf0\x62\xed\x4f\xbb\x13\x4c\x1b\x25\x2c\xde\x0b\x12\x5a\x69\x59\xb0\xd9\xa6\x4b\x12\xee\x16\xb7\x7d\x1a\xf2\x40\xbf\xb5\x0d\xf2\x17\xa5\xf8\x16\x20\xe1\x0b\x32",
                ),
            ],
        ];

        yield 'uuid + error + partial' => [
            "\x01\x00\x10\x36\x58\x7A\x72\x69\x9E\x46\x03\x86\x84\xF9\xF5\xE4\xDF\xC2\xF1".
            "\xFF\x00\x01\xFF".
            "\xFF\x00\x01",
            [
                new UuidMessage(
                    uuid: Uuid::fromString('36587a72-699e-4603-8684-f9f5e4dfc2f1'),
                ),
                new ErrorMessage(
                    payload: "\xFF",
                ),
            ],
            "\xFF\x00\x01",
        ];
    }

    private static function getBytes(): string
    {
        /** @var string|null $bytes */
        static $bytes = null;

        if (null !== $bytes) {
            return $bytes;
        }

        if (false === $decodedBytes = base64_decode(self::BYTES_BASE64, true)) {
            throw new \LogicException('Failed to parse base64-encoded bytes.');
        }

        $bytes = $decodedBytes;

        return $decodedBytes;
    }
}
