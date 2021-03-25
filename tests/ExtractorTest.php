<?php

namespace Tests;

use Fabiomez\DataExtractor\DocBlockParser;
use Fabiomez\DataExtractor\Extractor;
use Fabiomez\DataExtractor\ValueGetters\ValueGetterInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ExtractorTest extends TestCase
{
    public function testExtractShouldThowInvalidArgumentExceptionWhenSameExceptionWasThrown()
    {
        $docBlockParserMock = $this->createMock(DocBlockParser::class);
        $valueGetterMock = $this->createMock(ValueGetterInterface::class);

        $docBlockParserMock
            ->method('parse')
            ->willReturn([
                'prop1' => [
                    'extractable' => []
                ]
            ]);

        $valueGetterMock
            ->method('getValue')
            ->willThrowException(new InvalidArgumentException('Exception message.'));

        $extractor = new Extractor($docBlockParserMock, $valueGetterMock);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Exception message. For stdClass::$prop1.');

        $extractor->extract(new stdClass(), 'a source string');
    }

    public function testExtractShouldPopulateTheModelWithExtractedData()
    {
        $docBlockParserMock = $this->createMock(DocBlockParser::class);
        $valueGetterMock = $this->createMock(ValueGetterInterface::class);

        $docBlockParserMock
            ->method('parse')
            ->willReturn([
                'prop1' => [
                    'extractable' => []
                ],
                'prop2' => [
                    'extractable' => []
                ],
            ]);

        $valueGetterMock
            ->method('getValue')
            ->will($this->onConsecutiveCalls('First value', 'Seccond value'));

        $extractor = new Extractor($docBlockParserMock, $valueGetterMock);

        $model = new stdClass();
        $model->prop1 = null;
        $model->prop2 = null;

        $newModel = $extractor->extract($model, 'Any string');

        $this->assertNull($model->prop1);
        $this->assertNull($model->prop2);
        $this->assertEquals('First value', $newModel->prop1);
        $this->assertEquals('Seccond value', $newModel->prop2);
    }
}
