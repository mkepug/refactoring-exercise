<?php
/**
 * Demonstration file for refactoring of the MKEPUG Plugin demo (the contract to make our code easier to read)
 */
declare(strict_types=1);

namespace App;

/**
 * Interface ParamsContract
 * @package App
 */
interface ParamsContract
{
    /**
     * Get the value
     *
     * @param string $incoming
     * @return string
     */
    public function get(string $incoming): string;
}