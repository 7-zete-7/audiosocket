<?php

declare(strict_types=1);

namespace Zete7\AudioSocket;

use Zete7\AudioSocket\Protocol\Exception\OverflowException;
use Zete7\AudioSocket\Protocol\Exception\UnsupportedValueException;
use Zete7\AudioSocket\Protocol\Message;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
interface MessageEncoder
{
    /**
     * Returns encoded message.
     */
    public function encodeMessage(Message $message): string;

    /**
     * Returns decoded {@see Message} if message is presents in `$buffer`, NULL otherwise.
     * Mutates a `$buffer` parameter if message was decoded.
     *
     * @throws OverflowException
     * @throws UnsupportedValueException
     */
    public function decodeMessage(string &$buffer): ?Message;

    /**
     * Returns TRUE if message is presents in `$buffer`.
     * Does not mutate a `$buffer`.
     */
    public function hasDecodableMessage(string &$buffer): bool;
}
