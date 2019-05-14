<?php


namespace App\Inspections;


class KeyHeldDown
{
    protected $patterns = [
        '/(.)\\1{4,}/u'
    ];

    public function detect($body)
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $body)) {
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}
