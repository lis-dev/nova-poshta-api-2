<?php

declare(strict_types=1);

namespace LisDev;

enum InternetDocumentType: string
{
    case Pdf = 'pdf';
    case Html = 'html';
    case HtmlLink = 'html_link';
    case PdfLink = 'pdf_link';
}