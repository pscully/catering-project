<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum cateringOrderTimes: string implements HasLabel
{
    case SevenThirty = '7:30 AM';
    case Eight = '8:00 AM';
    case EightThirty = '8:30 AM';
    case Nine = '9:00 AM';
    case NineThirty = '9:30 AM';
    case Ten = '10:00 AM';
    case TenThirty = '10:30 AM';
    case Eleven = '11:00 AM';
    case ElevenThirty = '11:30 AM';
    case Twelve = '12:00 PM';
    case TwelveThirty = '12:30 PM';
    case One = '1:00 PM';
    case OneThirty = '1:30 PM';
    case Two = '2:00 PM';
    case TwoThirty = '2:30 PM';
    case Three = '3:00 PM';
    case ThreeThirty = '3:30 PM';
    case Four = '4:00 PM';

    public function getLabel(): ?string
    {
        return $this->value;

    }
}
