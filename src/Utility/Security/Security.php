<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Utility\Security;

use Scribe\Wonka\Exception\RuntimeException;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * Class Security.
 */
class Security
{
    /**
     * @param int         $bytes
     * @param bool        $base64
     * @param string|null $limitRegularExpression
     *
     * @return string
     */
    public static function generateRandom($bytes = 10000000, $base64 = false, $limitRegularExpression = null)
    {
        $generator = new SecureRandom();
        $return = $generator->nextBytes($bytes);

        if (true === $base64) {
            $return = base64_encode($return);
        }

        if ($limitRegularExpression !== null) {
            $return = preg_replace($limitRegularExpression, '', $return);
        }

        return $return;
    }

    /**
     * @param string $hashAlgorithm
     * @param bool   $hashReturnRaw
     * @param int    $bytes
     *
     * @return string
     */
    public static function generateRandomHash($hashAlgorithm = 'sha512', $hashReturnRaw = false, $bytes = 10000000)
    {
        $random = self::generateRandom($bytes);

        return hash(
            $hashAlgorithm,
            $random,
            $hashReturnRaw
        );
    }

    /**
     * @param string $password
     * @param string $pattern
     *
     * @return bool
     */
    public static function doesPasswordMeetRequirements($password, $pattern = '#.*^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#')
    {
        if (preg_match($pattern, $password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public static function generateRandomPassword($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?';
        $characterCount = strlen($characters);
        $password = '';
        $loopLimit = 1000;
        $loopCount = 0;

        while (true) {
            $password .= substr(str_shuffle($characters), 0, ($length > $characterCount ? $characterCount : $length));

            if (strlen($password) <= $length) {
                continue;
            }

            $password = substr($password, 0, $length);

            if (true === self::doesPasswordMeetRequirements($password)) {
                break;
            }

            if (++$loopCount > $loopLimit) {
                throw new RuntimeException('Reached loop count trying to create random password in "%s". Lower requirements.',
                    null, null, __METHOD__);
            }

            $password = '';
        }

        return $password;
    }
}

/* EOF */
