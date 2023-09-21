<?php

namespace App\Traits\Http\Requests;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\ParameterBag;

trait FiltersRequestInput
{
    /** @var bool */
    private $hasFiltered = false;

    /**
     * Override `Illuminate\Http\Request::getInputSource()` to perform the
     * filtering.
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected function getInputSource(): ParameterBag
    {
        if ($this->hasFiltered) {
            return parent::getInputSource();
        }

        $input = parent::getInputSource();

        foreach ($this->filters() as $key => $filter) {
            if (!$input->has($key)) {
                continue;
            }

            $fn = $this->getCallable($filter, $key);

            $input->set($key, $fn($input->get($key)));
        }

        $this->hasFiltered = true;

        return $input;
    }

    /**
     * Get the filters for this request.
     *
     * @return array
     */
    abstract protected function filters(): array;

    /**
     * Get the callable from a filter.
     *
     * @param  callable|string  $filter
     * @param  string           $key
     *
     * @return callable
     *
     * @throws \InvalidArgumentException
     */
    private function getCallable($filter, string $key): callable
    {
        if (is_callable($filter)) {
            return $filter;
        }

        if (substr_count($filter, '@', 1) === 1) {
            $fn = explode('@', $filter);

            if (is_callable($fn)) {
                return $fn;
            }
        }

        if (method_exists($this, $filter)) {
            return [$this, $filter];
        }

        throw new InvalidArgumentException(sprintf(
            'Invalid filter [%s] defined for input key [%s] on request [%s]',
            $filter, $key, static::class
        ));
    }
}
