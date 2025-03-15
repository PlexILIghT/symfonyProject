<?php

use App\Form\PortfolioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserPortfolioType extends PortfolioType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'constraints' => [
                new Callback([$this, 'validatePortfolioLimit'])
            ],
        ]);
    }

    public function validatePortfolioLimit($portfolio, ExecutionContextInterface $context): void
    {
        $user = $portfolio->getUser();
        if ($user && count($user->getPortfolios()) >= 5) {
            $context->buildViolation('You cannot have more than 5 portfolios.')
                ->atPath('name')
                ->addViolation();
        }
    }
}