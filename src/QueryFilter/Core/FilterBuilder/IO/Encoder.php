<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\IO;

/**
 *
 */
trait Encoder
{

    /**
     * @param $string
     * @param $salt
     * @return array|string|string[]
     */
    public function encodeWithSalt($string, $salt)
    {
        // Combine the string with the salt
        $saltedString = $salt . $string;

        // Base64 encode the salted string
        $encoded = base64_encode($saltedString);

        // Replace characters to make it URL-safe
        $encoded = str_replace(['+', '/', '='], ['-', '_', ''], $encoded);

        return $encoded;
    }

    /**
     * @param $encodedString
     * @param $salt
     * @return string|null
     */
    public function decodeWithSalt($encodedString, $salt)
    {
        // Replace URL-safe characters back to base64 characters
        $encodedString = str_replace(['-', '_'], ['+', '/'], $encodedString);

        // Add padding if necessary
        $paddedString = str_pad($encodedString, strlen($encodedString) % 4, '=', STR_PAD_RIGHT);

        // Base64 decode the string
        $decoded = base64_decode($paddedString);

        // Remove the salt from the beginning of the decoded string
        if (strpos($decoded, $salt) === 0) {
            return substr($decoded, strlen($salt));
        }

        // If the salt is not found, return null or an error message
        return null;
    }

}
