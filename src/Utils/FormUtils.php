<?php

namespace jidaikobo\kontiki\Utils;

class FormUtils
{
    /**
     * Convert a name attribute to an id-compatible string.
     *
     * @param string $name The name attribute.
     * @return string The converted id.
     */
    public static function nameToId(string $name): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '_', $name);
    }
}