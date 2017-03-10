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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Metadata;

/**
 * Document protection class
 *
 * @since 0.12.0
 * @link http://www.datypic.com/sc/ooxml/t-w_CT_DocProtect.html
 */
class Protection
{
    static $algorithmMapping = [
        1 => 'md2',
        2 => 'md4',
        3 => 'md5',
        4 => 'sha1',
        5 => '', // 'mac' -> not possible with hash()
        6 => 'ripemd',
        7 => 'ripemd160',
        8 => '',
        9 => '', //'hmac' -> not possible with hash()
        10 => '',
        11 => '',
        12 => 'sha256',
        13 => 'sha384',
        14 => 'sha512',
    ];
    static $initialCodeArray = [
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
        0x4EC3
    ];
    static $encryptionMatrix =
        [
            [0xAEFC, 0x4DD9, 0x9BB2, 0x2745, 0x4E8A, 0x9D14, 0x2A09],
            [0x7B61, 0xF6C2, 0xFDA5, 0xEB6B, 0xC6F7, 0x9DCF, 0x2BBF],
            [0x4563, 0x8AC6, 0x05AD, 0x0B5A, 0x16B4, 0x2D68, 0x5AD0],
            [0x0375, 0x06EA, 0x0DD4, 0x1BA8, 0x3750, 0x6EA0, 0xDD40],
            [0xD849, 0xA0B3, 0x5147, 0xA28E, 0x553D, 0xAA7A, 0x44D5],
            [0x6F45, 0xDE8A, 0xAD35, 0x4A4B, 0x9496, 0x390D, 0x721A],
            [0xEB23, 0xC667, 0x9CEF, 0x29FF, 0x53FE, 0xA7FC, 0x5FD9],
            [0x47D3, 0x8FA6, 0x0F6D, 0x1EDA, 0x3DB4, 0x7B68, 0xF6D0],
            [0xB861, 0x60E3, 0xC1C6, 0x93AD, 0x377B, 0x6EF6, 0xDDEC],
            [0x45A0, 0x8B40, 0x06A1, 0x0D42, 0x1A84, 0x3508, 0x6A10],
            [0xAA51, 0x4483, 0x8906, 0x022D, 0x045A, 0x08B4, 0x1168],
            [0x76B4, 0xED68, 0xCAF1, 0x85C3, 0x1BA7, 0x374E, 0x6E9C],
            [0x3730, 0x6E60, 0xDCC0, 0xA9A1, 0x4363, 0x86C6, 0x1DAD],
            [0x3331, 0x6662, 0xCCC4, 0x89A9, 0x0373, 0x06E6, 0x0DCC],
            [0x1021, 0x2042, 0x4084, 0x8108, 0x1231, 0x2462, 0x48C4]
        ];
    static $passwordMaxLength = 15;

    /**
     * Editing restriction none|readOnly|comments|trackedChanges|forms
     *
     * @var string
     * @link http://www.datypic.com/sc/ooxml/a-w_edit-1.html
     */
    private $editing;

    /**
     * Hashed password
     *
     * @var string
     */
    private $password;

    /**
     * Number of hashing iterations
     *
     * @var int
     */
    private $spinCount = 100000;

    /**
     * Algorithm-SID according to self::$algorithmMapping
     *
     * @var int
     */
    private $algorithmSid = 4;

    /**
     * Hashed salt
     *
     * @var string
     */
    private $salt;

    /**
     * Create a new instance
     *
     * @param string $editing
     */
    public function __construct($editing = null)
    {
        $this->setEditing($editing);
    }

    /**
     * Get editing protection
     *
     * @return string
     */
    public function getEditing()
    {
        return $this->editing;
    }

    /**
     * Set editing protection
     *
     * @param string $editing
     * @return self
     */
    public function setEditing($editing = null)
    {
        $this->editing = $editing;

        return $this;
    }

    /**
     * Get password hash
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $this->getPasswordHash($password);

        return $this;
    }

    /**
     * Get count for hash iterations
     *
     * @return int
     */
    public function getSpinCount()
    {
        return $this->spinCount;
    }

    /**
     * Set count for hash iterations
     *
     * @param $spinCount
     * @return self
     */
    public function setSpinCount($spinCount)
    {
        $this->spinCount = $spinCount;

        return $this;
    }

    /**
     * Get algorithm-sid
     *
     * @return int
     */
    public function getAlgorithmSid()
    {
        return $this->algorithmSid;
    }

    /**
     * Set algorithm-sid (see self::$algorithmMapping)
     *
     * @param $algorithmSid
     * @return self
     */
    public function setAlgorithmSid($algorithmSid)
    {
        $this->algorithmSid = $algorithmSid;

        return $this;
    }

    /**
     * Get salt hash
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set salt hash
     *
     * @param $salt
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get algorithm from self::$algorithmMapping
     *
     * @return string
     */
    private function getAlgorithm()
    {
        $algorithm = self::$algorithmMapping[$this->algorithmSid];
        if ($algorithm == '') {
            $algorithm = 'sha1';
        }

        return $algorithm;
    }

    /**
     * Create a hashed password that MS Word will be able to work with
     *
     * @param string $password
     * @return string
     */
    private function getPasswordHash($password)
    {
        $orig_encoding = mb_internal_encoding();
        mb_internal_encoding("UTF-8");

        if (empty($password)) {
            return '';
        }

        $password = mb_substr($password, 0, min(self::$passwordMaxLength, mb_strlen($password)));

        // Construct a new NULL-terminated string consisting of single-byte characters:
        //   Get the single-byte values by iterating through the Unicode characters of the truncated password.
        //   For each character, if the low byte is not equal to 0, take it. Otherwise, take the high byte.
        $pass_utf8 = mb_convert_encoding($password, 'UCS-2LE', 'UTF-8');
        $byteChars = [];
        for ($i = 0; $i < mb_strlen($password); $i++) {
            $byteChars[$i] = ord(substr($pass_utf8, $i * 2, 1));
            if ($byteChars[$i] == 0) {
                $byteChars[$i] = ord(substr($pass_utf8, $i * 2 + 1, 1));
            }
        }

        // build low-order word and hig-order word and combine them
        $combinedKey = $this->buildCombinedKey($byteChars);
        // build reversed hexadecimal string
        $hex = strtoupper(dechex($combinedKey & 0xFFFFFFFF));
        $reversedHex = $hex[6].$hex[7].$hex[4].$hex[5].$hex[2].$hex[3].$hex[0].$hex[1];

        $generatedKey = mb_convert_encoding($reversedHex, 'UCS-2LE', 'UTF-8');

        // Implementation Notes List:
        //   Word requires that the initial hash of the password with the salt not be considered in the count.
        //   The initial hash of salt + key is not included in the iteration count.
        $generatedKey = hash($this->getAlgorithm(), base64_decode($this->getSalt()) . $generatedKey, true);
        for ($i = 0; $i < $this->getSpinCount(); $i++) {
            $generatedKey = hash($this->getAlgorithm(), $generatedKey . pack("CCCC", $i, $i>>8, $i>>16, $i>>24), true);
        }
        $generatedKey = base64_encode($generatedKey);

        mb_internal_encoding($orig_encoding);

        return $generatedKey;
    }

    /**
     * Build combined key from low-order word and high-order word
     *
     * @param array $byteChars -> byte array representation of password
     * @return int
     */
    private function buildCombinedKey($byteChars)
    {
        // Compute the high-order word
        // Initialize from the initial code array (see above), depending on the passwords length.
        $highOrderWord = self::$initialCodeArray[sizeof($byteChars) - 1];

        // For each character in the password:
        //   For every bit in the character, starting with the least significant and progressing to (but excluding)
        //   the most significant, if the bit is set, XOR the key’s high-order word with the corresponding word from
        //   the Encryption Matrix
        for ($i = 0; $i < sizeof($byteChars); $i++) {
            $tmp       = self::$passwordMaxLength - sizeof($byteChars) + $i;
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
        for ($i = sizeof($byteChars) - 1; $i >= 0; $i--) {
            // low-order word = (((low-order word SHR 14) AND 0x0001) OR (low-order word SHL 1) AND 0x7FFF)) XOR character
            $lowOrderWord = (((($lowOrderWord >> 14) & 0x0001) | (($lowOrderWord << 1) & 0x7FFF)) ^ $byteChars[$i]);
        }
        // Lastly, low-order word = (((low-order word SHR 14) AND 0x0001) OR (low-order word SHL 1) AND 0x7FFF)) XOR strPassword length XOR 0xCE4B.
        $lowOrderWord = (((($lowOrderWord >> 14) & 0x0001) | (($lowOrderWord << 1) & 0x7FFF)) ^ sizeof($byteChars) ^ 0xCE4B);

        // Combine the Low and High Order Word
        return $this->int32(($highOrderWord << 16) + $lowOrderWord);
    }

    /**
     * simulate behaviour of int32
     *
     * @param int $value
     * @return int
     */
    private function int32($value)
    {
        $value = ($value & 0xFFFFFFFF);

        if ($value & 0x80000000) {
            $value = -((~$value & 0xFFFFFFFF) + 1);
        }

        return $value;
    }
}
