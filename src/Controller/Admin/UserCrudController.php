<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id'),
    //         EmailField::new('email'),
    //         BooleanField::new('active')
    //     ];
    // }


    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add('firstname');
    }
}
