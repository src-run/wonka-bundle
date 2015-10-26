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
use Scribe\Wonka\Utility\Error\DeprecationErrorHandler;

/**
 * @deprecated
 *
 * Class SecurityDeprecated.
 */
class SecurityDeprecated
{
    /**
     * @deprecated
     *
     * @param int         $bytes
     * @param bool        $base64
     * @param string|null $limitRegularExpression
     *
     * @return string
     */
    public static function generateRandom($bytes = 10000000, $base64 = false, $limitRegularExpression = null)
    {
        DeprecationErrorHandler::trigger(__METHOD__, __LINE__, 'New method with new signature should be used: Security::getRandomBytes().', '2015-10-26', '2015-12-01');

        $random = openssl_random_pseudo_bytes($bytes);

        if (true === $base64) {
            $random = base64_encode($random);
        }

        if ($limitRegularExpression !== null) {
            $return = preg_replace($limitRegularExpression, '', $random);
        }

        return $random;
    }

    /**
     * @deprecated
     *
     * @param string $hashAlgorithm
     * @param bool   $hashReturnRaw
     * @param int    $bytes
     *
     * @return string
     */
    public static function generateRandomHash($hashAlgorithm = 'sha512', $hashReturnRaw = false, $bytes = 10000000)
    {
        DeprecationErrorHandler::trigger(__METHOD__, __LINE__, 'New method with new signature should be used: Security::getRandomHash($algorithm, $entropy, $raw).', '2015-10-26', '2015-12-01');

        $random = self::generateRandom($bytes);

        return hash(
            $hashAlgorithm,
            $random,
            $hashReturnRaw
        );
    }

    /**
     * @deprecated
     *
     * @param string $password
     * @param string $pattern
     *
     * @return bool
     */
    public static function doesPasswordMeetRequirements($password, $pattern = '#.*^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#')
    {
        DeprecationErrorHandler::trigger(__METHOD__, __LINE__, 'New method with new signature should be used: Security::isPasswordSecure().', '2015-10-26', '2015-12-01');

        if (preg_match($pattern, $password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @deprecated
     *
     * @param int $length
     *
     * @return string
     */
    public static function generateRandomPassword($length = 12)
    {
        DeprecationErrorHandler::trigger(__METHOD__, __LINE__, 'New method with new signature should be used: Security::getRandomPassword().', '2015-10-26', '2015-12-01');

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
