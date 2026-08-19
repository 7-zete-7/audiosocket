<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

use Zete7\AudioSocket\Protocol\Exception\OverflowException;

/**
 * @internal
 *
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
abstract readonly class AbstractMessage implements Message
{
    /**
     * @throws OverflowException when payload size it too large
     */
    public function __construct(
        public Kind $kind,

        public string $payload,
    ) {
        if (isset($payload[65535])) {
            throw new OverflowException('Payload size must not be greater than than 65535.');
        }
    }
}
