<?php

namespace pringuin\DataprivacyBundle;

use OpenDxp\Extension\Bundle\AbstractOpenDxpBundle;

class pringuinDataprivacyBundle extends AbstractOpenDxpBundle
{
    const PACKAGE_NAME = 'pringuin/dataprivacybundle';

    public function getJsPaths(): array
    {
        return [
            '/bundles/pringuindataprivacy/js/opendxp/startup.js'
        ];
    }

    public function getNiceName(): string
    {
        return 'OpenDxp Dataprivacy Bundle';
    }

    public function getDescription(): string
    {
        return 'Dataprivacy Bundle for OpenDxp';
    }

    protected function getComposerPackageName(): string
    {
        return self::PACKAGE_NAME;
    }

}
