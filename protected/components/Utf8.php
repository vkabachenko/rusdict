<?php
 class Utf8 {


     /*
      * Первая буква в строке (с учетом многобайтовой кодировки)
      */

     public static function mb_firstLetter($str, $encoding = 'utf-8') {
         if($encoding === NULL)
         {
             $encoding    = mb_internal_encoding();
         }

         return mb_substr(mb_strtoupper($str, $encoding), 0, 1, $encoding);

     }

     /*
      * Заменить первую букву на заглавную
      */

     public static function mb_ucfirst($str, $encoding = 'utf-8')
     {
         if($encoding === NULL)
         {
             $encoding    = mb_internal_encoding();
         }

         return Utf8::mb_firstLetter($str) . mb_substr($str, 1, mb_strlen($str)-1, $encoding);
     }

     /**
      * Преобразование кода символа Юникода в символ в UTF-8
      *
      * @param int $code
      * Код символа из диапазона Юникода.
      *
      * @return string
      * Символ с соответствующим кодом в кодировке UTF-8.
      *
      * @throws RangeException
      */
     public static function codeToUtf8($code) {
         $code = (int) $code;

         if ($code < 0) {
             throw new RangeException("Negative value was passed");
         }
         # 0-------
         elseif ($code <= 0x7F) {
             return chr($code);
         }
         # 110----- 10------
         elseif ($code <= 0x7FF) {
             return chr($code >> 6 | 0xC0)
             . chr($code & 0x3F | 0x80)
                 ;
         }
         # 1110---- 10------ 10------
         elseif ($code <= 0xFFFF) {
             return chr($code >> 12 | 0xE0)
             . chr($code >> 6 & 0x3F | 0x80)
             . chr($code & 0x3F | 0x80)
                 ;
         }
         # 11110--- 10------ 10------ 10------
         elseif ($code <= 0x1FFFFF) {
             return chr($code >> 18 | 0xF0)
             . chr($code >> 12 & 0x3F | 0x80)
             . chr($code >> 6 & 0x3F | 0x80)
             . chr($code & 0x3F | 0x80)
                 ;
         }
         # 111110-- 10------ 10------ 10------ 10------
         elseif ($code <= 0x3FFFFFF) {
             return chr($code >> 24 | 0xF8)
             . chr($code >> 18 & 0x3F | 0x80)
             . chr($code >> 12 & 0x3F | 0x80)
             . chr($code >> 6 & 0x3F | 0x80)
             . chr($code & 0x3F | 0x80)
                 ;
         }
         # 1111110- 10------ 10------ 10------ 10------ 10------
         elseif ($code <= 0x7FFFFFFF) {
             return chr($code >> 30 | 0xFC)
             . chr($code >> 24 & 0x3F | 0x80)
             . chr($code >> 18 & 0x3F | 0x80)
             . chr($code >> 12 & 0x3F | 0x80)
             . chr($code >> 6 & 0x3F | 0x80)
             . chr($code & 0x3F | 0x80)
                 ;
         }
         else {
             throw new RangeException("Invalid character code");
         }
     }

     /**
      * Получение кода символа Юникода
      *
      * @param string $utf8Char
      * Символ в кодировке UTF-8. Если в строке содержится больше одного символа
      * UTF-8, то учитывается только первый.
      *
      * @return int
      * Код символа из Юникода.
      *
      * @throws InvalidArgumentException
      */
     public static function utf8ToCode($utf8Char) {
         $utf8Char = (string) $utf8Char;

         if ("" == $utf8Char) {
             throw new InvalidArgumentException("Empty string is not valid character");
         }

         # [a, b, c, d, e, f]
         $bytes = array_map('ord', str_split(substr($utf8Char, 0, 6), 1));

         # a, [b, c, d, e, f]
         $first = array_shift($bytes);

         # 0-------
         if ($first <= 0x7F) {
             return $first;
         }
         # 110----- 10------
         elseif ($first >= 0xC0 && $first <= 0xDF) {
             $tail = 1;
         }
         # 1110---- 10------ 10------
         elseif ($first >= 0xE0 && $first <= 0xEF) {
             $tail = 2;
         }
         # 11110--- 10------ 10------ 10------
         elseif ($first >= 0xF0 && $first <= 0xF7) {
             $tail = 3;
         }
         # 111110-- 10------ 10------ 10------ 10------
         elseif ($first >= 0xF8 && $first <= 0xFB) {
             $tail = 4;
         }
         # 1111110- 10------ 10------ 10------ 10------ 10------
         elseif ($first >= 0xFC && $first <= 0xFD) {
             $tail = 5;
         }
         else {
             throw new InvalidArgumentException("First byte is not valid");
         }

         if (count($bytes) < $tail) {
             throw new InvalidArgumentException("Corrupted character: $tail tail bytes required");
         }

         $code = ($first & (0x3F >> $tail)) << ($tail * 6);

         $tails = array_slice($bytes, 0, $tail);
         foreach ($tails as $i => $byte) {
             $code |= ($byte & 0x3F) << (($tail - 1 - $i) * 6);
         }

         return $code;
     }




 }