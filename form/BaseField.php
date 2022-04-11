<?php

namespace app\core\form;

use app\core\Model;

abstract class BaseField
{

    public string $type;
    public Model $model;
    public string $attribute;

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderInput():string;


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
}