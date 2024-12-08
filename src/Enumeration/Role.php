<?php
namespace App\Enumeration;

enum Role: string{
    case etudiant = 'Étudiant';
    case visiteur = 'Visiteur';
    case professeur = 'Professeur';
}