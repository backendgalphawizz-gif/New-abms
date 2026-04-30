<?php

declare(strict_types=1);

namespace App\Support;

final class IsoChecklistTemplates
{
    /**
     * @return array<string, array{source:string, items:array<int, string>}>
     */
    public static function all(): array
    {
        $iafCodeItems = [
            'Agriculture, fishing',
            'Mining and quarrying',
            'Food products, beverages and tobacco',
            'Textiles and textile products',
            'Leather and leather products',
            'Wood and wood products',
            'Pulp, paper and paper products',
            'Publishing companies',
            'Printing companies',
            'Manufacture of coke and refined petroleum products',
            'Nuclear fuel',
            'Chemicals, chemical products and fibers',
            'Pharmaceuticals',
            'Rubber and plastic products',
            'Non-metallic mineral products',
            'Concrete, cement, lime, plaster, etc.',
            'Basic metals and fabricated metal products',
            'Machinery and equipment',
            'Electrical and optical equipment',
            'Shipbuilding',
            'Aerospace',
            'Other transport equipment',
            'Manufacturing not elsewhere classified',
            'Recycling',
            'Electricity supply',
            'Gas supply',
            'Water supply',
            'Construction',
            'Wholesale and retail trade',
            'Repair of motor vehicles, motorcycles and personal and household goods',
            'Hotels and restaurants',
            'Transport, storage and communication',
            'Financial intermediation; real estate; renting',
            'Information technology',
            'Engineering services',
            'Other services',
            'Public administration',
            'Education',
            'Health and social work',
            'Other social services',
        ];

        return [
            'ISO9001' => [
                'source' => 'IAF_codes_table.doc',
                'items' => $iafCodeItems,
            ],
            'ISO14001' => [
                'source' => 'IAF_codes_table.doc',
                'items' => $iafCodeItems,
            ],
            'ISO45001' => [
                'source' => 'IAF_codes_table.doc',
                'items' => $iafCodeItems,
            ],
            'ISO22000' => [
                'source' => 'FSMS Categories NEW_2.docx',
                'items' => [
                    'A - Farming or handling of animals',
                    'B - Farming or handling of plants',
                    'C - Food, ingredient and pet food processing',
                    'D - Feed and animal food processing',
                    'E - Catering / food service',
                    'F - Trading, retail and e-commerce',
                    'G - Transport and storage services',
                    'H - Services',
                    'I - Production of packaging material',
                    'J - Equipment',
                    'K - Chemical and bio-chemical',
                ],
            ],
            'ISO13485' => [
                'source' => 'MD-QMS Category.docx',
                'items' => [
                    'AI - General non-active, non-implantable medical devices',
                    'AII - Non-active implants',
                    'AIII - Devices for wound care',
                    'AIV - Non-active dental devices and accessories',
                    'AV - Non-active medical devices other than specified above',
                    'BI - General active medical devices',
                    'BII - Devices for imaging',
                    'BIII - Monitoring devices',
                    'BIV - Devices for radiation therapy and thermo therapy',
                    'BV - Active (non-implantable) medical devices other than specified above',
                    'CI - General active implantable medical devices',
                    'CII - Implantable medical devices other than specified above',
                    'DI - Reagents and reagent products, calibrators and control materials',
                    'DII - In vitro diagnostic instruments and software',
                    'DIII - IVD medical devices other than specified above',
                    'EI - Ethylene oxide gas sterilization (EOG)',
                    'EII - Moist heat',
                    'EIII - Aseptic processing',
                    'EIV - Radiation sterilization',
                    'EV - Sterilization method other than specified above',
                    'FI - Medical devices incorporating medicinal substances',
                    'FII - Medical devices utilizing tissues of animal origin',
                    'FIII - Medical devices incorporating derivatives of human blood',
                    'FIV - Medical devices utilizing micromechanics',
                    'FV - Medical devices utilizing nanomaterials',
                    'FVI - Devices utilizing biological active coatings/materials',
                    'GI - Raw materials',
                    'GII - Components',
                    'GIII - Subassemblies',
                    'GIV - Calibration services',
                    'GV - Distribution services',
                    'GVI - Maintenance services',
                    'GVII - Transportation services',
                    'GVIII - Other services',
                ],
            ],
            'ISO21001' => [
                'source' => 'EOMS Technical Area.docx',
                'items' => [
                    'A - Early childhood education',
                    'B - Elementary and secondary education',
                    'C - Higher education',
                    'D - Lifelong education',
                    'E - Private teaching institute',
                ],
            ],
        ];
    }

    /**
     * @return array{source:string, items:array<int, string>}|null
     */
    public static function forCode(string $code): ?array
    {
        $normalized = self::normalize($code);

        return self::all()[$normalized] ?? null;
    }

    public static function normalize(string $code): string
    {
        $upper = strtoupper($code);
        if (preg_match('/ISO\s*\/?\s*(?:IEC\s*)?(\d{4,5})/', $upper, $match)) {
            return 'ISO'.$match[1];
        }

        $digits = preg_replace('/\D+/', '', $upper);

        return $digits !== '' ? 'ISO'.$digits : preg_replace('/\s+/', '', $upper);
    }
}

