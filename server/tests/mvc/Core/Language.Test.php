<?php


use \MVC\Core\Language;
use \MVC\Core\Config;
use \MVC\Core\Session;
define('APP_ROOT', __DIR__);

class LanguageTests extends \PHPUnit\Framework\TestCase {

  public $config = [
    'hello' => 'world',
    'nested' => [
        'key' => 'value',
        'deep' => [
            'item' => [
                'hello' => 'world'
            ]
        ]
    ],
      'database' => [
          'host' => '127.0.0.1',
          'database' => 'mvc',
          'username' => 'root',
          'password' => ''
      ],
      'usergroups' => [
        'student' => 1,
        'lecturer' => 2,
        'moderator' => 3,
        'admin' => 4,
        'owner' => 5
      ],
      'defaults' => [
          'language' => [
              'default' => 'language',
              'path' => 'testfiles'
          ],
          'view' => [
              'path' => 'Views',
              'engine' => 'twig'
          ]
      ]
  ];
  public $dump = [
    'hello' => 'world',
    'nested' => [
      'key' => 'value',
      'deep' => [
        'item' => [
          'hello' => 'world'
        ]
      ]
    ]
  ];

    public function testInit() {
        Config::init($this->config);
        $this->assertTrue(Language::init());
        Session::write('language' , 'language2');
        $this->assertTrue(Language::init());
        Session::write('language' , 'garbage');
        $this->assertTrue(Language::init());
    }
    public function testGet() {
      Config::init($this->config);
      $this->assertEquals(Language::get('hello'), $this->config['hello']);
      $this->assertEquals(Language::get('nested'), $this->config['nested']);
      $this->assertEquals(Language::get('nested.key'), $this->config['nested']['key']);
      $this->assertEquals(Language::get('nested.deep.item.hello'), $this->config['nested']['deep']['item']['hello']);
      $this->assertFalse(Language::get('nested.key.item.hello'));
      $this->assertFalse(Language::get('bogus name'));
      $this->assertFalse(Language::get(''));

    }
    public function testSetKey() {
        Config::init($this->config);

        $this->assertTrue(Language::set('someKey', 'SomeValue'));
        $this->assertEquals(Language::get('someKey'), 'SomeValue');

        $this->assertTrue(Language::set('someNestedKey', ['hello' => 'world']));
        $this->assertEquals(Language::get('someNestedKey'), ['hello' => 'world']);
        $this->assertEquals(Language::get('someNestedKey.hello'), 'world');
    }
    public function testDump() {
        Language::init($this->config);
        $this->assertEquals(Language::dump(), $this->dump);
    }

}
