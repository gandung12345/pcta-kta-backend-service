<?php

declare(strict_types=1);

namespace Pcta\Api\Schema;

/**
 * @author Paulus Gandung Prakosa <gandung@infradead.org>
 */
trait SchemaTrait
{
    /**
     * @var string
     */
    private const string ISO8601_DATE_PATTERN = <<<REGEX
    /^(?:\d{4})\-(?:\d{2})\-(?:\d{2})$/
    REGEX;

    /**
     * @var string
     */
    private const string ISO8601_YEAR_PATTERN = <<<REGEX
    /^(?:\d{4})$/
    REGEX;

    /**
     * @var string
     */
    private const string PHONE_NUMBER_PATTERN = <<<REGEX
    /^(\+62|0|62)([0-9]{10,13})$/
    REGEX;

    /**
     * @var string
     */
    private const string EMAIL_PATTERN = <<<REGEX
    /^(?:[a-z]{1})(?:[a-zA-Z0-9\.\-\_]*)(?:\@)(?:[a-zA-Z0-9\-\_]+)(?:\.(?:[a-z]+))+/
    REGEX;

    /**
     * @var string
     */
    private const string IDENTITY_NUMBER_PATTERN = <<<REGEX
    /^(?:\d{16})$/
    REGEX;

    /**
     * @var string
     */
    private const string POSTAL_CODE_PATTERN = <<<REGEX
    /^(?:\d{1,5})^/
    REGEX;
}
