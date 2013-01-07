<?php
namespace Dizda\BankManager\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use JMS\DiExtraBundle\Annotation\FormType;

/**
 * DI Registering this form with Symfony2’s Form Component with name bellow
 *
 * @FormType
 */
class OptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('allow_email', null, array('required' => false));
        $builder->add('alert_amount');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Dizda\BankManager\UserBundle\Document\Options',
        );
    }

    public function getName()
    {
        return 'dizda_user_options';
    }
}