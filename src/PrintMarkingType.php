<?php

declare(strict_types=1);

namespace LisDev;

enum PrintMarkingType: string
{
    case Pdf = 'pdf';
    case HtmlLink = 'html_link';
    case PdfLink = 'pdf_link';
    case NewPdf = 'new_pdf';
    case NewHtml = 'new_html';
    case OldHtml = 'old_html';
}