<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
final readonly class DtmfMessage extends AbstractMessage implements ReceivableMessage
{
    public function __construct(
        public DtmfSignal $signal,
    ) {
        parent::__construct(
            kind: Kind::Dtmf,
            payload: $this->signal->value,
        );
    }
}
