<?php

namespace LisDev\Constants;

/**
 * Constants for API request params
 */
class Api
{
    const CITY_SENDER = 'CitySender';
    const CITY_RECIPIENT = 'CityRecipient';
    const SERVICE_TYPE = 'ServiceType';
    const WEIGHT = 'Weight';
    const COST = 'Cost';
    const DATETIME = 'DateTime';
    const REF = 'Ref';
    const DOCUMENT_REFS = 'DocumentRefs';
    const TYPE = 'Type';
    const DOCUMENTS = 'Documents';
    const DOCUMENT_NUMBER = 'DocumentNumber';
    const CITY = 'City';
    const REGION = 'Region';
    const WAREHOUSE = 'Warehouse';
    const CITY_REF = 'CityRef';
    const SENDER_ADDRESS = 'SenderAddress';
    const SENDER = 'Sender';
    const COUNTERPARTY_PROPERTY = 'CounterpartyProperty';
    const LASTNAME = 'LastName';
    const FIRSTNAME = 'FirstName';
    const MIDDLENAME = 'MiddleName';
    const DESCRIPTION = 'Description';
    const CONTACT_SENDER = 'ContactSender';
    const SENDERS_PHONE = 'SendersPhone';
    const PHONE = 'Phone';
    const PHONES = 'Phones';
    const RECIPIENT = 'Recipient';
    const RECIPIENTS_PHONE = 'RecipientsPhone';
    const RECIPIENT_ADDRESS = 'RecipientAddress';
    const CONTACT_RECIPIENT = 'ContactRecipient';
    const CONTACT_PERSON = 'ContactPerson';
    const COUNTERPARTY_TYPE = 'CounterpartyType';
    const PAYMENT_METHOD = 'PaymentMethod';
    const PAYER_TYPE = 'PayerType';
    const SEATS_AMOUNT = 'SeatsAmount';
    const CARGO_TYPE = 'CargoType';
    const VOLUME_GENERAL = 'VolumeGeneral';
    const VOLUME_WEIGHT = 'VolumeWeight';
    const PAGE = 'Page';
    const FIND_BY_STRING = 'FindByString';
    const EDRPOU = 'EDRPOU';
    const SEARCH_STRING_ARRAY = 'SearchStringArray';

    const DOCTYPE_HTML = 'html';
    const DOCTYPE_NEW_HTML = 'new_html';
    const DOCTYPE_OLD_HTML = 'old_html';
    const DOCTYPE_HTML_LINK = 'html_link';
    const DOCTYPE_PDF = 'pdf';
    const DOCTYPE_NEW_PDF = 'new_pdf';
    const DOCTYPE_PDF_LINK = 'pdf_link';

    const DOCSIZE_85 = '85x85';
    const DOCSIZE_100 = '100x100';

    const RESPONSE_SUCCESS = 'success';
    const RESPONSE_DATA = 'data';
    const RESPONSE_ERRORS = 'errors';
    const RESPONSE_WARNINGS = 'warnings';
    const RESPONSE_INFO = 'info';

    const DEFAULT_COUNTERPARTY_TYPE = 'PrivatePerson';
    const DEFAULT_DATE_FORMAT = 'd.m.Y';
    const DEFAULT_SERVICE_TYPE = 'WarehouseWarehouse';
    const DEFAULT_PAYMENT_METHOD = 'Cash';
    const DEFAULT_PAYER_TYPE = 'Recipient';
    const DEFAULT_SEATS_AMOUNT = '1';
    const DEFAULT_CARGO_TYPE = 'Cargo';
    const DEFAULT_VOLUME_GENERAL = '0.0004';
    const DEFAULT_VOLUME_WEIGHT = 'Weight';

    const PROPERTY_DESCRIPTION = 'Description';
    const PROPERTY_DESCRIPTION_RU = 'DescriptionRu';
    const PROPERTY_AREA = 'Area';
    const PROPERTY_AREA_RU = 'AreaRu';

    const XML_ITEM = 'item';
}