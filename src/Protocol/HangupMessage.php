<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
final readonly class HangupMessage extends AbstractMessage implements SentableMessage
{
    public function __construct()
    {
        parent::__construct(
            kind: Kind::Hangup,
            payload: '',
        );
    }
}
