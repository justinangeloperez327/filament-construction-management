<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum CompanyDocumentType: string
{
    use ProvidesOptions;

    case TradeLicense = 'trade_license';
    case VatCertificate = 'vat_certificate';
    case BankLetter = 'bank_letter';
    case RegistrationDocument = 'registration_document';
    case Insurance = 'insurance';
    case Prequalification = 'prequalification';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::TradeLicense => 'Trade License',
            self::VatCertificate => 'VAT Certificate',
            self::BankLetter => 'Bank Letter',
            self::RegistrationDocument => 'Registration Document',
            self::Insurance => 'Insurance',
            self::Prequalification => 'Prequalification',
            self::Other => 'Other',
        };
    }
}
