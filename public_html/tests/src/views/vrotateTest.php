<?php 
	class vrotateTest extends PHPUnit_Framework_TestCase{
		public function setUp(){ }
		public function tearDown(){ }

		/**
		 * @dataProvider struck_provider
		 */
		public function test_struck_through($text){
			return $this->AssertEquals($text, 'bla');
		}



		public function struck_provider(){
			return array(
				'text' => array('bla'),
				'text' => array('bla')
				);
		}
	}

 ?>