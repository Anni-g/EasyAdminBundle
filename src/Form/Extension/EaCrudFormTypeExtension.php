<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Form\Extension;

use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Extension that injects EasyAdmin related information in the view used to
 * render the form.
 *
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class EaCrudFormTypeExtension extends AbstractTypeExtension
{
    private AdminContextProvider $adminContextProvider;

    public function __construct(AdminContextProvider $adminContextProvider)
    {
        $this->adminContextProvider = $adminContextProvider;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['ea_crud_form']);
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if (null === $this->adminContextProvider->getContext()) {
            return;
        }

        $view->vars['ea_crud_form'] = [
            // TODO: remove this in EasyAdmin 5.0.0 when "form panels" are completely removed
            'form_panel' => $form->getConfig()->getAttribute('ea_form_panel'),
            'form_fieldset' => $form->getConfig()->getAttribute('ea_form_fieldset'),
            'form_tab' => $form->getConfig()->getAttribute('ea_form_tab'),
            'ea_field' => $form->getConfig()->getAttribute('ea_field'),
            'ea_entity' => $form->getConfig()->getAttribute('ea_entity'),
        ];
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
