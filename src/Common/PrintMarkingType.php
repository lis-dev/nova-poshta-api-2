<?php

namespace LisDev\Common;

enum PrintMarkingType: string
{
    case Pdf = 'pdf';
    case NewPdf = 'new_pdf';
    case PdfLink = 'pdf_link';
    case NewHtml = 'new_html';
    case OldHtml = 'old_html';
    case HtmlLink = 'html_link';
}