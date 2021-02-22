<?php
class Default_Decorator_Submit extends Zend_Form_Decorator_Abstract
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
    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) {
            return '';
        }
        return $desc;
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
        $desc      = $this->buildDescription();
        $output = '<div class="button-wrapper">'
                . $input . $desc . '</div>';

        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}
