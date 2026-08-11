<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

use Symfony\Component\Uid\Uuid;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
final readonly class UuidMessage extends AbstractMessage implements ReceivableMessage
{
    public function __construct(
        public Uuid $uuid,
    ) {
        parent::__construct(
            kind: Kind::Uuid,
            payload: $this->uuid->toBinary(),
        );
    }
}
