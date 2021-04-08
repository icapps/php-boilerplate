<?php

namespace App\Utils;

use JetBrains\PhpStorm\ArrayShape;

class CompanyHelper
{

    /**
     * @return array
     */
    public function getBaseCompanyInfo(): array
    {
        $brand = $_ENV['BRAND'];
        $url = $_ENV['BASE_URL'];
        $publicLogoURL = $_ENV['PUBLIC_LOGO_URL'];
        $logoURL = $_ENV['LOGO_URL'];
        $companyEmail = $_ENV['PUBLIC_EMAIL'];

        return [
            'brand' => $brand,
            'url' => $url,
            'logoURL' => $logoURL,
            'publicLogoURL' => $publicLogoURL,
            'email' => $companyEmail,
        ];
    }
}
