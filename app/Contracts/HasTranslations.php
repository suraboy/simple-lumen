<?php

namespace App\Contracts;

use Spatie\Translatable\Events\TranslationHasBeenSet;

/**
 * Trait HasTranslations
 * @package App\Contracts
 */
trait HasTranslations
{
    use \Spatie\Translatable\HasTranslations;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        if (!$this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        $value = [];
        foreach (explode(',', env('APP_SUPPORT_LOCALE','en,th')) as $lang) {
            $value[$lang] = $this->getTranslation($key, $lang);
        }
        return $value;
    }

    /**
     * @param string $key
     * @param string $locale
     * @param $value
     * @return HasTranslations
     */
    public function setTranslation(string $key, string $locale, $value): self
    {
        $translations = $this->getTranslations($key);

        $oldValue = $translations[$locale] ?? '';

        if ($this->hasSetMutator($key)) {
            $method = 'set' . Str::studly($key) . 'Attribute';
            $this->{$method}($value, $locale);
            $value = $this->attributes[$key];
        }

        $translations[$locale] = $value;

        $this->attributes[$key] = json_encode($translations, JSON_UNESCAPED_UNICODE);

        event(new TranslationHasBeenSet($this, $key, $locale, $oldValue, $value));

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this|HasTranslations|mixed
     */
    public function setAttribute($key, $value)
    {
        // pass arrays and untranslatable attributes to the parent method
        if (!$this->isTranslatableAttribute($key) || is_array($value)) {
            if (is_array($value)) {
                foreach ($value as $locale => $val) {
                    $this->setTranslation($key, $locale, $val);
                }

                return $this;
            }

            return parent::setAttribute($key, $value);
        }
        // if the attribute is translatable and not already translated (=array),
        // set a translation for the current app locale
        return $this->setTranslation($key, $this->getLocale(), $value);
    }

    /**
     * @return string
     */
    protected function getLocale(): string
    {
        return config('app.locale');
    }
}
