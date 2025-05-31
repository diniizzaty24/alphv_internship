<?php
// password_utils.php
// Password hashing and verification utilities

/**
 * Hashes a password using bcrypt algorithm
 * @param string $password The plain text password to hash
 * @return string The hashed password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verifies a password against a hash
 * @param string $password The plain text password to verify
 * @param string $hash The hashed password to compare against
 * @return bool True if password matches, false otherwise
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}