<?php

namespace RedefineLab\Config;

use Enhance\Assert;

require_once __DIR__ . '/../../../vendor/autoload.php';

class ConfigTest extends \Enhance\TestFixture
{

    /**
     * @var Config
     */
    protected $object;

    /**
     * @var Config
     */
    protected $loadedObject;

    /**
     * @var Config
     */
    protected $overloadedObject;

    public function setUp()
    {
        $this->object = new Config;

        $this->loadedObject = new Config;
        $this->loadedObject->addYamlConfig(__DIR__ . '/../../yaml/first.yaml');

        $this->overloadedObject = new Config;
        $this->overloadedObject->addYamlConfig(__DIR__ . '/../../yaml/first.yaml');
        $this->overloadedObject->addYamlConfig(__DIR__ . '/../../yaml/second.yaml');
    }

    public function testLoadNonExistingFileReturnsFalse()
    {
        $actual = $this->object->addYamlConfig('non-existing-file.ext');
        Assert::isFalse($actual);
    }

    public function testLoadExistingFileReturnsTrue()
    {
        $actual = $this->object->addYamlConfig(__DIR__ . '/../../yaml/first.yaml');
        Assert::isTrue($actual);
    }

    public function testGetExistingLevel1KeyReturnsCorrectValue()
    {
        $expected = 'local';
        $actual = $this->loadedObject->get('environment');
        Assert::areIdentical($expected, $actual);

        $actual = $this->loadedObject->get('debug');
        Assert::isTrue($actual);
    }

    public function testGetExistingLevel2KeyReturnsCorrectValue()
    {
        $expected = 'localhost';
        $actual = $this->loadedObject->get('database.host');
        Assert::areIdentical($expected, $actual);
    }

    public function testGetExistingLevel3KeyReturnsCorrectValue()
    {
        $expected = 'Welcome to Config !';
        $actual = $this->loadedObject->get('page.welcome.title');
        Assert::areIdentical($expected, $actual);
    }

    public function testGetNotOverloadedLevel1KeyReturnsCorrectValue()
    {
        $expected = 'local';
        $actual = $this->overloadedObject->get('environment');
        Assert::areIdentical($expected, $actual);
    }

    public function testGetOverloadedLevel1KeyReturnsCorrectValue()
    {
        $actual = $this->overloadedObject->get('debug');
        Assert::isFalse($actual);
    }

    public function testGetNotOverloadedLevel2KeyReturnsCorrectValue()
    {
        $expected = 'localhost';
        $actual = $this->overloadedObject->get('database.host');
        Assert::areIdentical($expected, $actual);
    }

    public function testGetOverloadedLevel2KeyReturnsCorrectValue()
    {
        $expected = 'newpassword';
        $actual = $this->overloadedObject->get('database.password');
        Assert::areIdentical($expected, $actual);
    }

    public function testGetNotOverloadedLevel3KeyReturnsCorrectValue()
    {
        $expected = 'More info';
        $actual = $this->overloadedObject->get('page.info.title');
        Assert::areIdentical($expected, $actual);
    }

    public function testGetOverloadedLevel3KeyReturnsCorrectValue()
    {
        $expected = 'Welcome people !';
        $actual = $this->overloadedObject->get('page.welcome.title');
        Assert::areIdentical($expected, $actual);
    }

    public function testGetNewLevel3KeyReturnsCorrectValue()
    {
        $expected = 'Don\'t you like Config ?';
        $actual = $this->overloadedObject->get('page.welcome.body');
        Assert::areIdentical($expected, $actual);
    }

    public function testGetNonExistingKeyReturnsNull()
    {
        $actual = $this->object->get('mynonexistingkey');
        Assert::isNull($actual);
    }

    public function testGetNonExistingMultiLevelKeyReturnsNull()
    {
        $actual = $this->object->get('my.multi.level.non.existing.key');
        Assert::isNull($actual);
    }

    private function getRandomData()
    {
        return md5(uniqid('', true));
    }

}