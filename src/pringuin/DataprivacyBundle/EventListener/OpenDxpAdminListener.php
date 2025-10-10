<?php
namespace pringuin\DataprivacyBundle\EventListener;

use OpenDxp\Event\BundleManager\PathsEvent;

class OpenDxpAdminListener
{
    public function addCSSFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                    '/bundles/pringuindataprivacy/css/admin-style.css'
                ]
            )
        );
    }

    public function addJSFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                    '/bundles/pringuindataprivacy/js/opendxp/startup.js'
                ]
            )
        );
    }
}
