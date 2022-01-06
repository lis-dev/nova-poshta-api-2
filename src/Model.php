<?php

declare(strict_types=1);

namespace LisDev;

enum Model: string
{
    case Address = 'Address';
    case TrackingDocument = 'TrackingDocument';
    case Counterparty = 'Counterparty';
    case InternetDocument = 'InternetDocument';
    case Common = 'Common';
}