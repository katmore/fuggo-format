<?php
namespace FuggoTemplate\Tests\Unit;

/* @formatter:off */
declare(strict_types = 1); /* @formatter:on */


use PHPUnit\Framework\TestCase;
use FuggoTemplate\LineParserTrait;

class LineParserTraitTest extends TestCase {
   public function templateLineProvider(): array {
      $arg = [];

      $a = [];
      $line = '1';
      $paramKey = 'param-1';
      $a[] = 'line ' . $line . ' stuff {%' . $paramKey . '%} more stuff';
      $a[] = $paramVal = [
         $paramKey => 'my param 1 value',
      ];
      $a[] = 'line ' . $line . ' stuff ' . $paramVal[$paramKey] . ' more stuff';
      $arg[] = $a;

      $a = [];
      $line = '2';
      $paramKey = 'param-2';
      $a[] = 'line ' . $line . ' stuff {%' . $paramKey . '%} more stuff';
      $a[] = $paramVal = [
         $paramKey => 'my param 1 value',
      ];
      $a[] = 'line ' . $line . ' stuff ' . $paramVal[$paramKey] . ' more stuff';
      $arg[] = $a;

      return $arg;
   }
   /**
    * @dataProvider bodyStringProvider
    */
   public function testBodyFromString(string $unparsedLine, array $paramVal, string $expectedParsedValue) {
      $lineParser = new class($unparsedLine) {
         use LineParserTrait;
         public $parsedLine;
         public function __construct(string $line) {
            $this->parsedLine = static::parseTemplateLine($line);
         }
      };
      $this->assertEquals($lineParser->getLine,$expectedParsedValue);
   }
}