<?php


class Deposit {
    public readonly float $rate;
    public readonly string $description;
    public readonly string $publicationMonth;

    public function __construct(array $rawDataEl, array $headerData) {
        $this->rate = $rawDataEl['obs_val'];

        $this->description = current(
            array_filter($headerData, function ($headerDataElem) use ($rawDataEl) {
            return $rawDataEl['element_id'] === $headerDataElem['id'];
        }))['elname'];

        $this->publicationMonth = $rawDataEl['dt'];
    }
}
