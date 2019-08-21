<?php
namespace FuggoFormat\DataPopulate;

interface DocBlockTypeInterface {
   /**
    * @var string[][] array with assoc key for every PHP type that matches one or more DocBlock primitive types
    * @link https://docs.phpdoc.org/guides/types.html#primitives
    */
   const PRIMITIVE_DOCBLOCKTYPE = [
      'string' => [
         'string'
      ],
      'integer' => [
         'int',
         'integer'
      ],
      'double' => [
         'float',
         'double'
      ],
      'boolean' => [
         'bool',
         'boolean'
      ],
      'array' => [
         'array'
      ],
      'NULL' => [
         'null'
      ]
   ];
   
}