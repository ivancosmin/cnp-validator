<?php

namespace App\Service;

use App\Entity\Counties;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CnpValidatorService extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isCnpValid(string $value): bool
    {
        if(!$this->isLengthValid($value) || !$this->isNumeric($value)) {
            return false;
        }

        if(!$this->isDateValid($value)){
            return false;
        }

        if(!$this->isCountyCodeValid($value)){
            return false;
        }

        if(!$this->isUniqueCodeValid($value)){
            return false;
        }

        if(!$this->isCheckDigitValid($value)){
            return false;
        }

        return true;
    }

    private function isLengthValid(string $value): bool
    {
        return strlen($value) === 13;
    }

    private function isNumeric(string $value): bool
    {
        return is_numeric($value);
    }

    private function isDateValid(string $value):bool
    {
        $year = (int)substr($value, 1, 2);
        $month = (int)substr($value, 3, 2);
        $day = (int)substr($value, 5, 2);
        $sex = (int)$value[0];

        if (in_array($sex, [1, 2, 7, 8, 9])) {
            $year += 1900;
        } else if (in_array($sex, [3, 4])) {
            $year += 1800;
        } else if (in_array($sex, [5, 6])) {
            $year += 2000;
        }

        if(!checkdate($month, $day, $year)){
            return false;
        }

        return true;
    }

    private function isCountyCodeValid(string $value): bool
    {
        $code = substr($value, 7, 2);
        $county = $this->entityManager->getRepository(Counties::class)->findOneBy(['code' => $code]);

        if (!is_null($county)) {
            return true;
        }

        return false;
    }

    private function isUniqueCodeValid(string $value): bool
    {
        return (int)$value[11] > 0;
    }

    private function isCheckDigitValid(string $value): bool
    {
        $key = '279146358279';
        $sum = 0;
        for($i = 0; $i < 12; $i++) {
            $sum += (int)$value[$i] * (int)$key[$i];
        }

        $controlNumber = $sum % 11;
        if($controlNumber === 10) {
            $controlNumber = 1;
        }

        return $controlNumber === (int)$value[12];
    }

}