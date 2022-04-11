<?php

namespace app\core\form;

use app\core\Model;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type= self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    // CONVERT FIELD OBJECT TO A STRING

    /*
    public function __toString()
    {
        // format string to return as a generic field string
        return sprintf('
        <div class="mb-3">
          <label class="form-label">%s</label>
          %s
            <div class="invalid-feedback">
                %s
            </div>
        </div>
        ',
            //$this->attribute, // These $this' correspond with above %s
        //$this->model->labels()[$this->attribute] ?? $this->attribute, // pass this attribute as a fallback

            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)

        );
    } // END OF TO STRING FUNCTION

    */

    public function passwordField()
    {
        $this->type =self::TYPE_PASSWORD;
        return $this;

    }

    public function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control%s">',
    $this->type,
            $this->attribute, // the name
           $this->model->{$this->attribute},    // RegisterModel
           $this->model->hasError($this->attribute) ? ' is-invalid': '' // invalid feedback error %s
        );
    }
}