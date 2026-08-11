<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

use Zete7\AudioSocket\Protocol\Exception\OverflowException;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
final readonly class AudioMessage extends AbstractMessage implements SentableMessage, ReceivableMessage
{
    /**
     * @throws OverflowException when payload size it too large
     */
    public function __construct(
        public AudioFormat $audioFormat,
        string $payload,
    ) {
        parent::__construct(
            kind: Kind::fromAudioFormat($audioFormat),
            payload: $payload,
        );
    }
}
