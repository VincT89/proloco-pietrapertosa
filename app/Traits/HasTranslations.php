<?php

namespace App\Traits;

trait HasTranslations
{
    /**
     * Get the translated value for a field.
     * Falls back to the primary language (Italian) if the translation is missing.
     *
     * @param string $field
     * @return mixed
     */
    public function getTranslation(string $field)
    {
        $locale = app()->getLocale();
        $enField = $field . '_en';

        if ($locale === 'en' && !empty($this->{$enField})) {
            return $this->{$enField};
        }

        return $this->{$field};
    }
}
