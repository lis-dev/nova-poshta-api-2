<?php

namespace LisDev\Services;

use LisDev\Common\PrintMarkingType;

class PrintLinksService
{
    protected $key;
    private PreparationDataService $preparationDataService;
    private NovaPoshtaApiClient $novaPoshtaApiClient;

    public function __construct()
    {
        $this->preparationDataService = new PreparationDataService();
        $this->novaPoshtaApiClient = new NovaPoshtaApiClient();
    }

    /**
     * Get only link on internet document for printing.
     *
     * @param string       $method       Called method of NovaPoshta API
     * @param array        $documentRefs Array of Documents IDs
     * @param string       $type         (html_link|pdf_link)
     *
     * @return mixed
     */
    protected function printGetLink($method, $documentRefs, $type)
    {
        $data = 'https://my.novaposhta.ua/orders/'.$method.'/orders[]/'.implode(',', $documentRefs)
            .'/type/'.str_replace('_link', '', $type)
            .'/apiKey/'.$this->key;
        // Return data in same format like NovaPoshta API
        return $this->preparationDataService->prepare(
            array(
                'success' => true,
                'data' => array($data),
                'errors' => array(),
                'warnings' => array(),
                'info' => array(),
            )
        );
    }

    /**
     * printDocument method of InternetDocument model.
     *
     * @param array|string $documentRefs Array of Documents IDs
     * @param string|PrintMarkingType $type         (pdf|html|html_link|pdf_link)
     *
     * @return mixed
     */
    public function printDocument($documentRefs, string|PrintMarkingType $type = PrintMarkingType::HtmlLink)
    {
        $documentRefs = (array) $documentRefs;
        // If needs link
        if (PrintMarkingType::HtmlLink == $type || PrintMarkingType::PdfLink == $type) {
            return $this->printGetLink('printDocument', $documentRefs, $type);
        }
        // If needs data
        return $this->request('InternetDocument', 'printDocument', array('DocumentRefs' => $documentRefs, 'Type' => $type));
    }

    /**
     * printMarkings method of InternetDocument model.
     *
     * @param array|string $documentRefs Array of Documents IDs
     * @param string|PrintMarkingType $type         (pdf|new_pdf|new_html|old_html|html_link|pdf_link)
     *
     * @return mixed
     */
    public function printMarkings($documentRefs, string|PrintMarkingType $type = PrintMarkingType::NewHtml, $size = '85x85')
    {
        $documentRefs = (array) $documentRefs;
        $documentSize = $size === '85x85' ? '85x85' : '100x100';
        $method = 'printMarking'.$documentSize;
        // If needs link
        if ('html_link' == $type or 'pdf_link' == $type) {
            return $this->printGetLink($method, $documentRefs, $type);
        }
        // If needs data
        return $this->novaPoshtaApiClient->request('InternetDocument', $method, array('DocumentRefs' => $documentRefs, 'Type' => $type));
    }
}