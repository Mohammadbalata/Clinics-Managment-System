<?php

namespace App\Enums;

enum DoctorSpecialtyEnum: string
{
    case FamilyMedicine = 'family_medicine';
    case InternalMedicine = 'internal_medicine';
    case Pediatrics = 'pediatrics';
    case Geriatrics = 'geriatrics';
    case GeneralSurgery = 'general_surgery';
    case OrthopedicSurgery = 'orthopedic_surgery';
    case CardiothoracicSurgery = 'cardiothoracic_surgery';
    case Neurosurgery = 'neurosurgery';
    case PlasticSurgery = 'plastic_surgery';
    case Ophthalmology = 'ophthalmology';
    case Otolaryngology = 'otolaryngology';
    case Cardiology = 'cardiology';
    case Dermatology = 'dermatology';
    case Endocrinology = 'endocrinology';
    case Gastroenterology = 'gastroenterology';
    case Hematology = 'hematology';
    case InfectiousDisease = 'infectious_disease';
    case Nephrology = 'nephrology';

}