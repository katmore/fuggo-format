<?php
namespace FuggoFormat\DataPopulate;


trait DocBlockTypeTrait {

   /**
    * Determine if a value is a DocBlock type.
    *
    * @param mixed $value
    *           value to check
    * @param string $docBlockType
    *           DocBlock type
    *           
    * @return bool true if value type matches the DocBlock type, false otherwise
    */
   protected static function valueIsDocBlockType($value, string $docBlockType): bool {

      /*
       * case insensitive type
       */
      $ciType = strtolower($docBlockType);

      /*
       * recursive check if type is "array-of" (i.e. has '[]' suffix)
       */
      if (substr($docBlockType,-2) === '[]') {

         $elemType = substr($docBlockType,0,-2);

         if (!is_array($value)) {
            return false;
         }
         if (!count($value)) {
            return true;
         }
         return !!count(
            array_filter($value,function ($v) use ($elemType) {
               return static::valueIsDocBlockType($v,$elemType);
            }));
      }

      /*
       * check keyword types
       */
      switch ($ciType) {
         case 'mixed' :
            return true;
         case 'void' :
            return false;
         case 'object' :
            return is_object($value);
         case 'false' :
            return $value === false;
         case 'true' :
            return $value === true;
         case 'self' :
         case 'static' :
         case '$this' :
            return is_object($value) && is_a($value,get_called_class());
      }

      /*
       * check non-scalar primitive types
       */
      switch ($ciType) {
         case 'resource' :
            return is_resource($value);
         case 'null' :
            return $value === null;
         case 'callable' :
            return is_callable($value);
      }

      /**
       * @var string $vt value type
       */
      $vt = gettype($value);

      /*
       * check for match on PRIMITIVE_DOCBLOCKTYPE map
       */
      if (isset(DocBlockTypeInterface::PRIMITIVE_DOCBLOCKTYPE[$vt]) &&
            in_array($ciType,DocBlockTypeInterface::PRIMITIVE_DOCBLOCKTYPE[$vt],true)) {
         return true;
      }

      /*
       * if value is object, check for case sensitive match to type as class name
       */
      return $vt === 'object' && is_a($value,$docBlockType);
   }
}