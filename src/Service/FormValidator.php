<?php

namespace App\Service;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

/**
 * To avoid duplications for form validations.
 */
class FormValidator
{
    /**
     * Validate entity object according to constraints declared in object.
     *
     * @return array list of errors or an empty array
     */
    public function getErrors(FormInterface $form): array
    {
        $errorMessages = [];

        if (!$form->isValid()) {
            /**
             * @var FormError $error
             */
            foreach ($form->getErrors(true, true) as $error) {
                $errorMessages[$error->getOrigin()->getPropertyPath()->__toString()] = $error->getMessage();
            }
        }

        return $errorMessages;
    }
}
