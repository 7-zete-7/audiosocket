<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

/**
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
enum DtmfSignal: string
{
    case Digit0 = '0';

    case Digit1 = '1';

    case Digit2 = '2';

    case Digit3 = '3';

    case Digit4 = '4';

    case Digit5 = '5';

    case Digit6 = '6';

    case Digit7 = '7';

    case Digit8 = '8';

    case Digit9 = '9';

    case A = 'A';

    case B = 'B';

    case C = 'C';

    case D = 'D';

    case Star = '*';

    case Square = '#';
}
