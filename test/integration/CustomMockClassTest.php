<?php
declare(strict_types=1);

namespace test\integration;

use Ingenerator\StubObjects\Attribute\StubMockClass;
use Ingenerator\StubObjects\StubObjects;
use PHPUnit\Framework\TestCase;

class CustomMockClassTest extends TestCase
{


    public function test_it_can_stub_as_a_custom_mock_class()
    {
        $inst = $this->newSubject()->stub(MyObjectWithDependencies::class, [
            'params_file' => 'unused',
            'params' => ['foo' => 'bar'],
        ]);
        $this->assertInstanceOf(MyObjectWithDependenciesMock::class, $inst);

        $this->assertSame(
            [
                'params_file' => 'unused',
                'params' => ['foo' => 'bar'],
            ],
            [
                'params_file' => $inst->params_file,
                'params' => $inst->getParams(),
            ]
        );
    }

    private function newSubject(array $stubbable_class_patterns = ['*']): StubObjects
    {
        return new StubObjects(...get_defined_vars());
    }
}

#[StubMockClass(MyObjectWithDependenciesMock::class)]
class MyObjectWithDependencies
{
    public string $params_file;

    public function getParams()
    {
        return file_get_contents($this->params_file);
    }
}

class MyObjectWithDependenciesMock extends MyObjectWithDependencies
{
    private readonly array $params;

    public function getParams()
    {
        return $this->params;
    }

}