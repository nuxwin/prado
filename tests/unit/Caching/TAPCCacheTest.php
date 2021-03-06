<?php

Prado::using('System.Caching.TAPCCache');

/**
 * @package System.Caching
 */
class TAPCCacheTest extends PHPUnit_Framework_TestCase {

	protected $app = null;
	protected static $cache = null;

	protected function setUp() {
		if(!extension_loaded('apc')) {
			self::markTestSkipped('The APC extension is not available');
		} else {
				$basePath = dirname(__FILE__).'/mockapp';
				$runtimePath = $basePath.'/runtime';
				if(!is_writable($runtimePath)) {
					self::markTestSkipped("'$runtimePath' is writable");
				}
				try {
					$this->app = new TApplication($basePath);
					self::$cache = new TAPCCache();
					self::$cache->init(null);
				} catch(TConfigurationException $e) {
					self::markTestSkipped($e->getMessage());
				}
				
		}
	}

	protected function tearDown() {
		$this->app = null;
		self::$cache = null;
	}

	public function testInit() {
		throw new PHPUnit_Framework_IncompleteTestError();
	}
	
	public function testPrimaryCache() {
		self::$cache->PrimaryCache = true;
		self::assertEquals(true, self::$cache->PrimaryCache);
		self::$cache->PrimaryCache = false;
		self::assertEquals(false, self::$cache->PrimaryCache);
	}
	
	public function testKeyPrefix() {
		self::$cache->KeyPrefix = 'prefix';
		self::assertEquals('prefix', self::$cache->KeyPrefix);
	}
	
	public function testSetAndGet() {
		self::$cache->set('key', 'value');
		self::assertEquals('value', self::$cache->get('key'));
	}
	
	public function testAdd() {
		try {
			self::$cache->add('anotherkey', 'value');
		} catch(TNotSupportedException $e) {
			self::markTestSkipped('apc_add is not supported');
			return;
		}
		self::assertEquals('value', self::$cache->get('anotherkey'));
	}
	
	public function testDelete() {
		self::$cache->delete('key');
		self::assertEquals(false, self::$cache->get('key'));
	}
	
	public function testFlush() {
		$this->testSetAndGet();
		self::assertEquals(true, self::$cache->flush());
	}

}

?>
