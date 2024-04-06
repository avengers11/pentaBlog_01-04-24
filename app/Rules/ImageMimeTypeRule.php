<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class ImageMimeTypeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $imageURL = $value;

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg', 'gif');
        $fileExtension = pathinfo($imageURL, PATHINFO_EXTENSION);

        if (!in_array($fileExtension, $allowedExtensions)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Only .jpg, .jpeg, .png and .svg file is allowed.';
    }
}
