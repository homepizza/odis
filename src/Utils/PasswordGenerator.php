<?php


namespace App\Utils;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class PasswordGenerator
{
    private $password;

    public function __construct()
    {
        $password = new ComputerPasswordGenerator();
        $password
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, false)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LENGTH, 6);
        $this->password = $password;
    }

    /**
     * Generate password
     *
     * @return string
     */
    public function generatePassword()
    {
        return $this->password->generatePassword();
    }

}