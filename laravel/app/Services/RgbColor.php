<?php

namespace App\Services;

use InvalidArgumentException;

class RgbColor
{
    public $red;
    public $green;
    public $blue;

    public function __construct(string|int $redOrHex, int $green = null, int $blue = null)
    {
        if (is_int($redOrHex)) {
            $this->setRed($redOrHex);
            $this->setGreen($green);
            $this->setBlue($blue);
        } else {
            $hex = ltrim($redOrHex, '#');
            if (strlen($hex) == 6) {
                $this->setRed(hexdec(substr($hex, 0, 2)));
                $this->setGreen(hexdec(substr($hex, 2, 2)));
                $this->setBlue(hexdec(substr($hex, 4, 2)));
            } elseif (strlen($hex) == 3) {
                $this->setRed(hexdec(str_repeat(substr($hex, 0, 1), 2)));
                $this->setGreen(hexdec(str_repeat(substr($hex, 1, 1), 2)));
                $this->setBlue(hexdec(str_repeat(substr($hex, 2, 1), 2)));
            } else {
                throw new InvalidArgumentException("Invalid hex color");
            }
        }
    }

    public function getRed()
    {
        return $this->red;
    }

    public function getGreen()
    {
        return $this->green;
    }

    public function getBlue()
    {
        return $this->blue;
    }

    public function setRed($red)
    {
        $this->red = $this->validateColorValue($red);
    }

    public function setGreen($green)
    {
        $this->green = $this->validateColorValue($green);
    }

    public function setBlue($blue)
    {
        $this->blue = $this->validateColorValue($blue);
    }

    private function validateColorValue($value)
    {
        if ($value < 0 || $value > 255) {
            throw new InvalidArgumentException('Color value must be between 0 and 255.');
        }
        return $value;
    }

    public function toHex()
    {
        return sprintf("#%02x%02x%02x", $this->red, $this->green, $this->blue);
    }

    public function __toString()
    {
        return $this->toHex();
    }
}
