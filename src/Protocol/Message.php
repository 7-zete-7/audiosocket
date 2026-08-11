<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

/**
 * An AudioSocket message.
 *
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
interface Message
{
    public Kind $kind { get; }

    public string $payload { get; }
}
