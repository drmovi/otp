<?php

namespace App\Services;

use App\repositories\UserPinRepository;

class FisherYatesPinService implements PinService
{

    private UserPinRepository $repository;

    public function __construct(UserPinRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generate(int $number): array
    {
        $pinData = $this->repository->getUserPinData(\Auth::id());
        if (!$pinData) {
            $pinData = [
                'pins_list' => $this->getNewPinArray()
            ];
        }
        $pinsList = $pinData['pins_list'];
        $pins = $this->getPins($number,$pinsList );
        $pinData['pins_list'] = $pinsList;
        $pinData['current_pins'] = $pins;
        $this->repository->saveUserPinData(\Auth::id(), $pinData);
        return $pins;
    }

    private function getPinRange(): array
    {
        $size = config('pin.size');
        if ($size == 0)
            throw new \Exception('Pin size cannot be zero');
        if ($size == 1)
            $range = [0, 9];
        else
            $range = [10, pow(10, $size) - 1];
        return $range;
    }

    private function getNewPinArray(): array
    {
        $range = $this->getPinRange();
        $pins = range(...$range);
        shuffle($pins);
        return $pins;
    }

    private function isExcluded(int $number): bool
    {
        return $this->hasSameDigits($number) || $this->hasSequentialDigits($number);
    }

    private function hasSameDigits(int $number): bool
    {
        $length = floor((log10($number))) + 1;
        $m = (pow(10, $length) - 1) / (10 - 1);
        $m *= ($number % 10);
        return $m == $number;
    }

    private function hasSequentialDigits(int $number): bool
    {
        $flag = true;
        $prev = -1;
        $type = -1;
        while ($number != 0) {
            if ($type == -1) {
                if ($prev == -1) {
                    $prev = $number % 10;
                    $number = (int)$number / 10;
                    continue;
                }
                if ($prev == $number % 10) {
                    $flag = false;
                    break;
                }
                if ($prev > $number % 10) {
                    $type = 1;
                    $prev = $number % 10;
                    $number = (int)$number / 10;
                    continue;
                }

                $prev = $number % 10;
                $number = (int)$number / 10;
            } else {
                if ($prev == $number % 10) {
                    $flag = false;
                    break;
                }
                if ($prev < $number % 10) {
                    $flag = false;
                    break;
                }

                $prev = $number % 10;
                $number = (int)$number / 10;
            }
        }
        return $flag;
    }

    private function getPins(int $number, array &$data): array
    {
        $counter = 0;
        $pins = [];
        while ($counter < $number) {
            if (count($data) === 0)
                $data = $this->getNewPinArray();
            $key = array_rand($data);
            $pin = $data[$key];
            if ($this->isExcluded($pin)) {
                $data[$key] = $data[count($data) - 1];
                array_pop($data);
                continue;
            }
            if (in_array($pin, $pins)) {
                continue;
            }
            $data[$key] = $data[count($data) - 1];
            array_pop($data);
            $pins[] = $pin;
            $counter++;
        }
        return array_map(function ($pin) {
            return str_pad($pin, config('pin.size'), "0", STR_PAD_LEFT);
        }, $pins);
    }
}
