<?php
require_once dirname(__FILE__).'/../src/NovaPoshtaApi2.php';
/**
 * phpUnit test class
 * 
 * @author lis-dev
 * @version 0.01
 */
class NovaPoshtaApi2Test extends PHPUnit_Framework_TestCase
{
	/**
	 * Key for connection
	 * 
	 * @see https://my.novaposhta.ua/settings/index#apikeys
	 */
	private $key = '';
	/**
	 * Instace of tested class
	 */
	private $np;
	/**
	 * Set up before each test
	 */
	function setUp() {
		// Create new instance
		$this->np = new NovaPoshtaApi2($this->key);
	}
	/**
	 * getKey()
	 */
	function testGetKey() {
		$result = $this->np->getKey();
		$this->assertTrue($result != '');
	}
	
	/**
	 * getFormat()
	 */
	function testGetFormat() {
		$result = $this->np->getFormat();
		$this->assertTrue($result != '');
	}
	
	/**
	 * documentsTracking() result in array
	 */
	function testDocumentsTrackingResultArray() {
		$result = $this->np->documentsTracking('59000082032106');
		$this->assertTrue($result['success']);
	}
	
	/**
	 * documentsTracking() result in json
	 */
	function testDocumentsTrackingResultJson() {
		$result = $this->np->setFormat('json')->documentsTracking('59000082032106');
		$result = json_decode($result, 1);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * documentsTracking() result in xml
	 */
	function testDocumentsTrackingResultJsonXml() {
		$result = $this->np->setFormat('xml')->documentsTracking('59000082032106');
		$result = simplexml_load_string($result);
		$result = json_encode($result);
		$result = json_decode($result, 1);
		$this->assertEquals($result['success'], 'true');
	}
}
