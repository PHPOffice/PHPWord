<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared\Microsoft;

/**
 * Password encoder for microsoft office applications
 */
class PasswordEncoder
{
    private static $algorithmMapping = array(
        1  => 'md2',
        2  => 'md4',
        3  => 'md5',
        4  => 'sha1',
        5  => '', // 'mac' -> not possible with hash()
        6  => 'ripemd',
        7  => 'ripemd160',
        8  => '',
        9  => '', //'hmac' -> not possible with hash()
        10 => '',
        11 => '',
        12 => 'sha256',
        13 => 'sha384',
        14 => 'sha512',
    );

    private static $initialCodeArray = array(
        0xE1F0,
        0x1D0F,
        0xCC9C,
        0x84C0,
        0x110C,
        0x0E10,
        0xF1CE,
        0x313E,
        0x1872,
        0xE139,
        0xD40F,
        0x84F9,
        0x280C,
        0xA96A,
        0x4EC3,
    );

    private static $encryptionMatrix = array(
        array(0xAEFC, 0x4DD9, 0x9BB2, 0x2745, 0x4E8A, 0x9D14, 0x2A09),
        array(0x7B61, 0xF6C2, 0xFDA5, 0xEB6B, 0xC6F7, 0x9DCF, 0x2BBF),
        array(0x4563, 0x8AC6, 0x05AD, 0x0B5A, 0x16B4, 0x2D68, 0x5AD0),
        array(0x0375, 0x06EA, 0x0DD4, 0x1BA8, 0x3750, 0x6EA0, 0xDD40),
        array(0xD849, 0xA0B3, 0x5147, 0xA28E, 0x553D, 0xAA7A, 0x44D5),
        array(0x6F45, 0xDE8A, 0xAD35, 0x4A4B, 0x9496, 0x390D, 0x721A),
        array(0xEB23, 0xC667, 0x9CEF, 0x29FF, 0x53FE, 0xA7FC, 0x5FD9),
        array(0x47D3, 0x8FA6, 0x0F6D, 0x1EDA, 0x3DB4, 0x7B68, 0xF6D0),
        array(0xB861, 0x60E3, 0xC1C6, 0x93AD, 0x377B, 0x6EF6, 0xDDEC),
        array(0x45A0, 0x8B40, 0x06A1, 0x0D42, 0x1A84, 0x3508, 0x6A10),
        array(0xAA51, 0x4483, 0x8906, 0x022D, 0x045A, 0x08B4, 0x1168),
        array(0x76B4, 0xED68, 0xCAF1, 0x85C3, 0x1BA7, 0x374E, 0x6E9C),
        array(0x3730, 0x6E60, 0xDCC0, 0xA9A1, 0x4363, 0x86C6, 0x1DAD),
        array(0x3331, 0x6662, 0xCCC4, 0x89A9, 0x0373, 0x06E6, 0x0DCC),
        array(0x1021, 0x2042, 0x4084, 0x8108, 0x1231, 0x2462, 0x48C4),
    );

    private static $passwordMaxLength = 15;

    /**
     * Create a hashed password that MS Word will be able to work with
     * @see https://blogs.msdn.microsoft.com/vsod/2010/04/05/how-to-set-the-editing-restrictions-in-word-using-open-xml-sdk-2-0/
     *
     * @param string $password
     * @param number $algorithmSid
     * @param string $salt
     * @param number $spinCount
     * @return string
     */
    public static function hashPassword($password, $algorithmSid = 4, $salt = null, $spinCount = 10000)
    {
        $origEncoding = mb_internal_encoding();
        mb_internal_encoding('UTF-8');

        $password = mb_substr($password, 0, min(self::$passwordMaxLength, mb_strlen($password)));

        //   Get the single-byte values by iterating through the Unicode characters of the truncated password.
        //   For each character, if the low byte is not equal to 0, take it. Otherwise, take the high byte.
        $passUtf8 = mb_convert_encoding($password, 'UCS-2LE', 'UTF-8');
        $byteChars = array();

        for ($i = 0; $i < mb_strlen($password); $i++) {
            $byteChars[$i] = ord(substr($passUtf8, $i * 2, 1));

            if ($byteChars[$i] == 0) {
                $byteChars[$i] = ord(substr($passUtf8, $i * 2 + 1, 1));
            }
        }

        // build low-order word and hig-order word and combine them
        $combinedKey = self::buildCombinedKey($byteChars);
        // build reversed hexadecimal string
        $hex = str_pad(strtoupper(dechex($combinedKey & 0xFFFFFFFF)), 8, '0', \STR_PAD_LEFT);
        $reversedHex = $hex[6] . $hex[7] . $hex[4] . $hex[5] . $hex[2] . $hex[3] . $hex[0] . $hex[1];

        $generatedKey = mb_convert_encoding($reversedHex, 'UCS-2LE', 'UTF-8');

        // Implementation Notes List:
        //   Word requires that the initial hash of the password with the salt not be considered in the count.
        //   The initial hash of salt + key is not included in the iteration count.
        $algorithm = self::getAlgorithm($algorithmSid);
        $generatedKey = hash($algorithm, $salt . $generatedKey, true);

        for ($i = 0; $i < $spinCount; $i++) {
            $generatedKey = hash($algorithm, $generatedKey . pack('CCCC', $i, $i >> 8, $i >> 16, $i >> 24), true);
        }
        $generatedKey = base64_encode($generatedKey);

        mb_internal_encoding($origEncoding);

        return $generatedKey;
    }

    /**
     * Get algorithm from self::$algorithmMapping
     *
     * @param int $sid
     * @return string
     */
    private static function getAlgorithm($sid)
    {
        $algorithm = self::$algorithmMapping[$sid];
        if ($algorithm == '') {
            $algorithm = 'sha1';
        }

        return $algorithm;
    }

    /**
     * Build combined key from low-order word and high-order word
     *
     * @param array $byteChars byte array representation of password
     * @return int
     */
    private static function buildCombinedKey($byteChars)
    {
        // Compute the high-order word
        // Initialize from the initial code array (see above), depending on the passwords length.
        $highOrderWord = self::$initialCodeArray[count($byteChars) - 1];

        // For each character in the password:
        //   For every bit in the character, starting with the least significant and progressing to (but excluding)
        //   the most significant, if the bit is set, XOR the key’s high-order word with the corresponding word from
        //   the Encryption Matrix
        for ($i = 0; $i < count($byteChars); $i++) {
            $tmp = self::$passwordMaxLength - count($byteChars) + $i;
            $matrixRow = self::$encryptionMatrix[$tmp];
            for ($intBit = 0; $intBit < 7; $intBit++) {
                if (($byteChars[$i] & (0x0001 << $intBit)) != 0) {
                    $highOrderWord = ($highOrderWord ^ $matrixRow[$intBit]);
                }
            }
        }

        // Compute low-order word
        // Initialize with 0
        $lowOrderWord = 0;
        // For each character in the password, going backwards
        for ($i = count($byteChars) - 1; $i >= 0; $i--) {
            // low-order word = (((low-order word SHR 14) AND 0x0001) OR (low-order word SHL 1) AND 0x7FFF)) XOR character
            $lowOrderWord = (((($lowOrderWord >> 14) & 0x0001) | (($lowOrderWord << 1) & 0x7FFF)) ^ $byteChars[$i]);
        }
        // Lastly, low-order word = (((low-order word SHR 14) AND 0x0001) OR (low-order word SHL 1) AND 0x7FFF)) XOR strPassword length XOR 0xCE4B.
        $lowOrderWord = (((($lowOrderWord >> 14) & 0x0001) | (($lowOrderWord << 1) & 0x7FFF)) ^ count($byteChars) ^ 0xCE4B);

        // Combine the Low and High Order Word
        return self::int32(($highOrderWord << 16) + $lowOrderWord);
    }

    /**
     * Simulate behaviour of (signed) int32
     *
     * @codeCoverageIgnore
     * @param int $value
     * @return int
     */
    private static function int32($value)
    {
        $value = ($value & 0xFFFFFFFF);

        if ($value & 0x80000000) {
            $value = -((~$value & 0xFFFFFFFF) + 1);
        }

        return $value;
    }
}
