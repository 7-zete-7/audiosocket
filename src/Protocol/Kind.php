<?php

declare(strict_types=1);

namespace Zete7\AudioSocket\Protocol;

use Zete7\AudioSocket\Protocol\Exception\NotAudioMessageException;

/**
 * A message type indicator.
 *
 * @see https://github.com/asterisk/asterisk/blob/master/include/asterisk/res_audiosocket.h
 *
 * @author Stanislau Kviatkouski <7zete7@gmail.com>
 */
enum Kind: string
{
    /**
     * Message indicates the channel should be hung up, direction: Sent only.
     */
    case Hangup = "\x00";

    /**
     * Message contains the connection's UUID, direction: Received only.
     */
    case Uuid = "\x01";

    /**
     * Message contains a DTMF digit, direction: Received only.
     */
    case Dtmf = "\x03";

    /**
     * Messages contains audio data, format: `slin`, direction: Sent and received.
     *
     * @see AudioFormat::Slin
     */
    case Slin = AudioFormat::Slin->value;

    /**
     * Messages contains audio data, format: `slin12`, direction: Sent and received.
     *
     * @see AudioFormat::Slin12
     */
    case Slin12 = AudioFormat::Slin12->value;

    /**
     * Messages contains audio data, format: `slin16`, direction: Sent and received.
     *
     * @see AudioFormat::Slin16
     */
    case Slin16 = AudioFormat::Slin16->value;

    /**
     * Messages contains audio data, format: `slin24`, direction: Sent and received.
     *
     * @see AudioFormat::Slin24
     */
    case Slin24 = AudioFormat::Slin24->value;

    /**
     * Messages contains audio data, format: `slin32`, direction: Sent and received.
     *
     * @see AudioFormat::Slin32
     */
    case Slin32 = AudioFormat::Slin32->value;

    /**
     * Messages contains audio data, format: `slin44`, direction: Sent and received.
     *
     * @see AudioFormat::Slin44
     */
    case Slin44 = AudioFormat::Slin44->value;

    /**
     * Messages contains audio data, format: `slin48`, direction: Sent and received.
     *
     * @see AudioFormat::Slin48
     */
    case Slin48 = AudioFormat::Slin48->value;

    /**
     * Messages contains audio data, format: `slin96`, direction: Sent and received.
     *
     * @see AudioFormat::Slin96
     */
    case Slin96 = AudioFormat::Slin96->value;

    /**
     * Messages contains audio data, format: `slin192`, direction: Sent and received.
     *
     * @see AudioFormat::Slin192
     */
    case Slin192 = AudioFormat::Slin192->value;

    /**
     * An Asterisk-side error occurred, direction: Received only.
     */
    case Error = "\xFF";

    /**
     * Returns {@see Kind} of the {@see AudioFormat}.
     */
    public static function fromAudioFormat(AudioFormat $audioFormat): Kind
    {
        return Kind::from($audioFormat->value);
    }

    /**
     * Returns the {@see AudioFormat} for this {@see Kind}.
     *
     * @throws NotAudioMessageException
     */
    public function getAudioFormat(): AudioFormat
    {
        try {
            return AudioFormat::from($this->value);
        } catch (\ValueError $valueError) {
            throw new NotAudioMessageException('Message kind is not an audio.', previous: $valueError);
        }
    }
}
