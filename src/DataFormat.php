<?php

declare(strict_types=1);

namespace LisDev;

enum DataFormat: string
{
    case Array = 'array';
    case Json = 'json';
    case Xml = 'xml';
}