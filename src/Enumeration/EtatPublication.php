<?php
namespace App\Enumeration;

enum EtatPublication: string{
    case brouillon = 'Brouillon';
    case prive = 'Privé';
    case publie = 'Publié';
}