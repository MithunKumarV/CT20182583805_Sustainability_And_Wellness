<?php
class Default_Decorator_JqueryFront extends ZendX_JQuery_Form_Decorator_UiWidgetElement
{
    public function buildLabel()
    {
        $element = $this->getJQueryParams();
        $label = $element->getLabel();
        if ($translator = $element->getTranslator()) {
            $label = $translator->translate($label);
        }
        return $element->getView()
                       ->formLabel($element->getName(), $label);
    }

    public function buildInput()
    {
        $element = $this->getJQueryParams();
        $helper  = $element->helper;
		return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getElementAttribs(),
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
        return '<span>' .
               $element->getView()->formErrors($messages) . '</span>';
    }

    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) {
            return '';
        }
        return '<div class="description">' . $desc . '</div>';
    }

    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof ZendX_JQuery_Form_) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }


        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        //$label     = $this->buildLabel();
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();
        $desc      = $this->buildDescription();
        $output = $label
                . $input 
                . $desc;
        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}
