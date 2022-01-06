<?php

declare(strict_types=1);

namespace LisDev;

enum ConnectionType: string
{
    case Curl = 'curl';
    case File_get_contents = 'file_get_contents';
}