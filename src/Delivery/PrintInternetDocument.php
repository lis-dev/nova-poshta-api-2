<?php
namespace LisDev\Delivery;

// Вынес из NovaPoshtaApi2.php методы связанные с печатью интернет документов.
class PrintInternetDocument
{
    protected $prepare;

    public function __construct(PrepareReturnData $prepare)
    {
        $this->prepare = $prepare;
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
        return $this->prepare->prepare(
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
     * @param string       $type         (pdf|html|html_link|pdf_link)
     *
     * @return mixed
     */
    public function printDocument($documentRefs, $type = 'html')
    {
        $documentRefs = (array) $documentRefs;
        // If needs link
        if ('html_link' == $type or 'pdf_link' == $type) {
            return $this->printGetLink('printDocument', $documentRefs, $type);
        }
        // If needs data
        /*
         * R1KO указал на несуществующий метод реквест.
         * Но после вынесение метода printDocument из NovaPoshtaApi2 я не понял как использовать этот метод.
         * Была мысль создать объект NovaPoshtaApi2 и из него вызывать реквест, но это точно не то.
         * Оставляю так как и было, потому что не понял как лучше сделать.
         */
        return $this->request('InternetDocument', 'printDocument', array('DocumentRefs' => $documentRefs, 'Type' => $type));
    }

    /**
     * printMarkings method of InternetDocument model.
     *
     * @param array|string $documentRefs Array of Documents IDs
     * @param string       $type         (pdf|new_pdf|new_html|old_html|html_link|pdf_link)
     *
     * @return mixed
     */
    public function printMarkings($documentRefs, $type = 'new_html', $size = '85x85')
    {
        $documentRefs = (array) $documentRefs;
        $documentSize = $size === '85x85' ? '85x85' : '100x100';
        $method = 'printMarking'.$documentSize;
        // If needs link
        if ('html_link' == $type or 'pdf_link' == $type) {
            return $this->printGetLink($method, $documentRefs, $type);
        }
        // If needs data
        return $this->request('InternetDocument', $method, array('DocumentRefs' => $documentRefs, 'Type' => $type));
    }
}