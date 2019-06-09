<?php
/**
* Setting type.
*/

namespace App\Form;

use App\Entity\User;
use App\Entity\Setting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
* Class SettingType.
*/
class SettingType extends AbstractType
{
    /**
    * Builds the form.
    *
    * This method is called for each type in the hierarchy starting from the
    * top most type. Type extensions can further modify the form.
    *
    * @see FormTypeExtensionInterface::buildForm()
    *
    * @param FormBuilderInterface $builder The form builder
    * @param array                $options The options
    */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'value',
            TextType::class,
            [
                'label' => 'settings.value',
                'required' => true,
                'attr' => ['max_length' => 4500],
            ]
        );
    }

    /**
    * Configures the options for this type.
    *
    * @param OptionsResolver $resolver The resolver for the options
    */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Setting::class]);
    }

    /**
    * Returns the prefix of the template block name for this type.
    *
    * The block prefix defaults to the underscored short class name with
    * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
    *
    * @return string The prefix of the template block name
    */
    public function getBlockPrefix(): string
    {
        return 'setting';
    }
}