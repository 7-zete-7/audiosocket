<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

use Zete7\AudioSocket\Protocol\Exception\OverflowException;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
final readonly class ErrorMessage extends AbstractMessage implements ReceivableMessage
{
    /**
     * @throws OverflowException when payload size it too large
     */
    public function __construct(string $payload)
    {
        parent::__construct(
            kind: Kind::Error,
            payload: $payload,
        );
    }
}
