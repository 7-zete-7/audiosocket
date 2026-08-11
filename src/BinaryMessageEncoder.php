<?php

declare(strict_types=1);

namespace Zete7\AudioSocket;

use Symfony\Component\Uid\Exception\InvalidArgumentException as UidInvalidArgumentException;
use Symfony\Component\Uid\Uuid;
use Zete7\AudioSocket\Protocol\AudioMessage;
use Zete7\AudioSocket\Protocol\DtmfMessage;
use Zete7\AudioSocket\Protocol\DtmfSignal;
use Zete7\AudioSocket\Protocol\ErrorMessage;
use Zete7\AudioSocket\Protocol\Exception\OverflowException;
use Zete7\AudioSocket\Protocol\Exception\UnsupportedValueException;
use Zete7\AudioSocket\Protocol\HangupMessage;
use Zete7\AudioSocket\Protocol\Kind;
use Zete7\AudioSocket\Protocol\Message;
use Zete7\AudioSocket\Protocol\UuidMessage;

/**
 * Encodes and decodes binary AudioSocket messages.
 *
 * Encoded message:
 *
 * ```
 *  0                   1                   2                   3
 *  0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1
 * +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
 * |      Kind       |        Content Length         |             |
 * +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+             |
 * |                            payload                            |
 * +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
 * ```
 *
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
final class BinaryMessageEncoder implements MessageEncoder
{
    private const int HEADER_LENGTH = 3;

    private const int HEADER_LAST_BYTE_OFFSET = 2; // = HEADER_LENGTH - 1

    private const int PAYLOAD_LENGTH_OFFSET = 1;

    #[\Override]
    public function encodeMessage(Message $message): string
    {
        return match ($message->kind) {
            Kind::Hangup => "\x00\x00\x00",
            Kind::Uuid => "\x01\x00\x10".$message->payload,
            Kind::Dtmf => "\x03\x00\x01".$message->payload,

            Kind::Slin,
            Kind::Slin12,
            Kind::Slin16,
            Kind::Slin24,
            Kind::Slin32,
            Kind::Slin44,
            Kind::Slin48,
            Kind::Slin96,
            Kind::Slin192 => $message->kind->value.pack('n', \strlen($message->payload)).$message->payload,

            Kind::Error => "\xFF".pack('n', \strlen($message->payload)).$message->payload,
        };
    }

    /**
     * @return ($buffer is non-empty-string ? Message|null : null)
     *
     * @throws OverflowException
     * @throws UnsupportedValueException
     * @throws UidInvalidArgumentException
     */
    #[\Override]
    public function decodeMessage(string &$buffer): ?Message
    {
        if (!$this->hasHeaders($buffer)) {
            return null;
        }

        $kind = $this->unpackKind($buffer);
        $length = $this->unpackLength($buffer);

        if (!$this->hasPayload($buffer, $length)) {
            return null;
        }

        $payload = substr($buffer, self::HEADER_LENGTH, $length);

        $message = match ($kind) {
            Kind::Hangup => new HangupMessage(),

            Kind::Uuid => new UuidMessage(
                uuid: Uuid::fromBinary($payload),
            ),

            Kind::Dtmf => new DtmfMessage(
                signal: $this->decodeDtmfSignal($payload),
            ),

            Kind::Slin,
            Kind::Slin12,
            Kind::Slin16,
            Kind::Slin24,
            Kind::Slin32,
            Kind::Slin44,
            Kind::Slin48,
            Kind::Slin96,
            Kind::Slin192 => new AudioMessage(
                audioFormat: $kind->getAudioFormat(),
                payload: $payload,
            ),

            Kind::Error => new ErrorMessage(
                payload: $payload,
            ),
        };

        $buffer = substr($buffer, self::HEADER_LENGTH + $length);

        return $message;
    }

    #[\Override]
    public function hasDecodableMessage(string &$buffer): bool
    {
        return $this->hasHeaders($buffer)
            && $this->hasPayload($buffer, $this->unpackLength($buffer))
        ;
    }

    /**
     * @param int<0, 65535> $length
     */
    private function hasPayload(string &$buffer, int $length): bool
    {
        return isset($buffer[self::HEADER_LAST_BYTE_OFFSET + $length]);
    }

    /**
     * @phpstan-assert-if-true non-empty-string $buffer
     */
    private function hasHeaders(string &$buffer): bool
    {
        return isset($buffer[self::HEADER_LAST_BYTE_OFFSET]);
    }

    /**
     * @throws UnsupportedValueException
     */
    private function unpackKind(string &$buffer): Kind
    {
        \assert($this->hasHeaders($buffer));

        try {
            return Kind::from($buffer[0]);
        } catch (\ValueError $valueError) {
            throw new UnsupportedValueException(\sprintf('Unsupported message kind 0x%s given.', bin2hex($buffer[0])), 0, $valueError);
        }
    }

    /**
     * @return int<0, 65535>
     */
    private function unpackLength(string &$buffer): int
    {
        \assert($this->hasHeaders($buffer));

        if (false === $unpacked = unpack('n', $buffer, self::PAYLOAD_LENGTH_OFFSET)) {
            throw new \LogicException('Buffer is not contains header.');
        }

        if (null === $value = $unpacked[1] ?? null) {
            throw new \LogicException('Buffer is not contains header.');
        }

        \assert(\is_int($value));
        \assert(0 <= $value);
        \assert(65535 >= $value);

        return $value;
    }

    /**
     * @throws UnsupportedValueException
     */
    private function decodeDtmfSignal(string $payload): DtmfSignal
    {
        try {
            return DtmfSignal::from($payload);
        } catch (\ValueError $valueError) {
            throw throw new UnsupportedValueException(\sprintf('Unsupported DTMF signal 0x%s given.', bin2hex($payload)), 0, $valueError);
        }
    }
}
