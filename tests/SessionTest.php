<?php

class SessionTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
   {
		$this->config = [
			"name"		=> "Aurora",
			"lifetime"	=> 86400,
			"path"		=> '/',
			"domain"		=> null,
			"secure"		=> false,
			"httponly"	=> false
		];
	}

   public function testInit()
   {
		$this->assertInstanceOf('\Aurora\Session', new \Aurora\Session());
	}

	public function testFileHandler(){
		$this->assertInstanceOf(
			'\Aurora\Session',
			new \Aurora\Session(
				$this->assertInstanceOf(
					'\Aurora\Session\Handler\File',
					new \Aurora\Session\Handler\File()
				)
			)
		);
	}
}
