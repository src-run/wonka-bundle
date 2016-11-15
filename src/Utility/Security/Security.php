<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 * (c) Scribe Inc      <scr@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Utility\Security;

use SR\Exception\Runtime\RuntimeException;

/**
 * Class Security.
 */
class Security
{
    /**
     * @var string
     */
    const PASSWORD_SECURE_REGEX = '{.*^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$}';

    /**
     * @var string
     */
    const PASSWORD_CRACK_DICT = '/var/cache/cracklib/cracklib_dict';

    /**
     * @param int           $length
     * @param bool|false    $raw
     * @param \Closure|null $filter
     *
     * @return string
     */
    public static function getRandomBytes($length = 100, $raw = false, \Closure $filter = null)
    {
        if ($length < 1) {
            throw new RuntimeException('%s: Cannot generate random bytes of less than 1.', __METHOD__);
        }

        $random = random_bytes($length);

        if (false === $raw) {
            $random = bin2hex($random);
        }
        if (is_callable($filter)) {
            $random = $filter($random);
        }

        return $random;
    }

    /**
     * @param string     $algorithm
     * @param int        $entropy
     * @param bool|false $raw
     *
     * @return string
     */
    public static function getRandomHash($algorithm = 'sha512', $entropy = 1000000, $raw = false)
    {
        if (!in_array($algorithm, hash_algos())) {
            throw RuntimeException::create()
                ->setMessage('Invalid hash algorithm "%s".', $algorithm);
        }

        return hash($algorithm, self::getRandomBytes($entropy), $raw);
    }

    /**
     * @param string     $password
     * @param string     $username
     * @param bool|false $throwException
     *
     * @throws RuntimeException
     *
     * @return bool
     */
    public static function isSecurePassword($password, $username = '', $throwException = false)
    {
        $crackDictionary = null;

        try {
            if (!preg_match(self::PASSWORD_SECURE_REGEX, $password)) {
                throw RuntimeException::create()
                    ->setMessage('Password must meet this requirement: %s.', self::PASSWORD_SECURE_REGEX);
            }
        } catch (RuntimeException $exception) {
            if ($throwException) {
                throw $exception;
            }

            return false;
        }

        return true;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public static function getRandomPassword($length = 12)
    {
        if ($length < 8) {
            throw RuntimeException::create()
                ->setMessage('%s: Cannot generate secure password less than 8 characters.', __METHOD__);
        }

        $specialCharacters = '!@#$%';

        do {
            $randomPassword = substr(self::getRandomHash(), 0, $length);

            for ($i = 0; $i < $length / 2; ++$i) {
                /* @noinspection PhpUndefinedVariableInspection */
                $randomPassword[$index = mt_rand(0, $length - 1)] = strtoupper($randomPassword[$index]);
            }

            for ($i = 0; $i < $length / 4; ++$i) {
                $randomPassword[mt_rand(0, $length - 1)] = substr(str_shuffle($specialCharacters), 0, 1);
            }
        } while (false === self::isSecurePassword($randomPassword));

        return $randomPassword;
    }
}

/* EOF */
