# 7-zete-7/audiosocket

This library provides strong typed implementation of [Asterisk's AudioSocket protocol](https://docs.asterisk.org/Configuration/Channel-Drivers/AudioSocket/).

Provides ability to encode messages to binary data and decode messages from binary buffer.
This library was made for give some level of abstraction to extract messages from AudioSocket's binary stream.

## Installation

```shell
composer require 7-zete-7/audiosocket
```

## Basic usage

### Encoding messages

```php
use Zete7\AudioSocket\BinaryMessageEncoder;
use Zete7\AudioSocket\Protocol\AudioFormat
use Zete7\AudioSocket\Protocol\AudioMessage
use Zete7\AudioSocket\Protocol\DtmfMessage
use Zete7\AudioSocket\Protocol\DtmfSignal
use Zete7\AudioSocket\Protocol\HangupMessage;

$encoder = new BinaryMessageEncoder();

$encoder->encodeMessage(new HangupMessage());
// => "\x00\x00\x00"

$encoder->encodeMessage(new DtmfMessage(
    signal: DtmfSignal::Digit7,
));
// => "\x03\x00\x01\x37"

$encoder->encodeMessage(new AudioMessage(
    audioFormat: AudioFormat::Slin,
    payload: 'foobar',
));
// => "\x10\x00\x06foobar"
```

### Decoding messages from binary buffer

```php
use Zete7\AudioSocket\BinaryMessageEncoder;
use Zete7\AudioSocket\Protocol\DtmfMessage;
use Zete7\AudioSocket\Protocol\UuidMessage;

$encoder = new BinaryMessageEncoder();

$buffer = <<<'EOF'
AQAQby80FMfKT6aDvqmOAYKHmwMAATADAAExAwABNQ==
EOF;

while ($message = $encoder->decodeMessage($buffer)) {
    echo match (true) {
        $message instanceof UuidMessage => "UUID: {$message->uuid->toString()}\n",
        $message instanceof DtmfMessage => "DTMF: {$message->signal->value}\n",
    }
}

echo "Done\n";

// => UUID: 6f2f3414-c7ca-4fa6-83be-a98e0182879b
// => DTMF: 0
// => DTMF: 1
// => DTMF: 5
// => Done
```
