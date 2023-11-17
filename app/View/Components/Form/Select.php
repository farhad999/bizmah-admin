<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $label;

    public $id;

    public $name;

    public $options;

    public $class;

    public $groupClass;

    public $placeholder;

    public $value;

    public $required;

    public $noPlaceholder;

    public function __construct($name, $options, $id = null, $value = '', $label = '', $placeholder = null, $class = null, $required=null, $noPlaceholder=false)
    {
        $this->name = $name;
        $this->id = $id;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->label = $label;
        $this->class = $class;
        $this->noPlaceholder = $noPlaceholder;
        if (gettype($options) == 'string') {

            $this->options = $this->convertToAssociativeArray($options);

        }else {
            $this->options = $options;
        }
        $this->required =$required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.form.select');
    }

    private function convertToAssociativeArray($stringArray)
    {
        // Remove the opening and closing brackets
        $stringArray = substr($stringArray, 1, -1);

// Split the string into key-value pairs
        $pairs = explode(', ', $stringArray);

// Create the associative array
        $associativeArray = [];

// Convert key-value pairs into an associative array
        foreach ($pairs as $pair) {
            // Split each pair into key and value
            list($key, $value) = explode('=>', $pair);

            // Remove any leading/trailing spaces
            $key = trim($key);

            // Assign value to the corresponding key in the associative array
            $associativeArray[$key] = $value;
        }

        return $associativeArray;
    }

}
