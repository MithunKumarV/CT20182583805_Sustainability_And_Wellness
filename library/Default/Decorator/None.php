<?php
class Default_Decorator_None extends Zend_Form_Decorator_Abstract
{
	   public function buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getAttribs(),
            $element->options
        );
    }
    public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return "<div class='validation' style='color:red;'>" .
               strip_tags($element->getView()->formErrors($messages)) . "</div>";
    }

    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }


        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();

		 $output = $input . $errors;


        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}
