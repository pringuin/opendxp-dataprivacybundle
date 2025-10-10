<?php

namespace pringuin\DataprivacyBundle\Controller;

use OpenDxp\Controller\FrontendController;
use OpenDxp\Model\Document;
use OpenDxp\Model\Document\Service;
use OpenDxp\Tool\Authentication;
use pringuin\DataprivacyBundle\Helper\Configurationhelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{

    public function defaultAction(Request $request): Response
    {
        if(\OpenDxp\Model\Site::isSiteRequest()) {
            $site = \OpenDxp\Model\Site::getCurrentSite()->getId();
        } else {
            $site = 'default';
        }

        $configuration = Configurationhelper::getConfigurationForSite($site);

        //Make replacements for locales
        foreach($configuration as $key => $value){
            if(strpos($value,'%locale%')){
                $configuration[$key] = str_replace('%locale%',$request->getLocale(),$value);
            }
        }

        if(is_numeric($configuration['privacyUrl'])){
            $documentService = $this->get('opendxp.document_service');
            $document = Document::getById($configuration['privacyUrl']);
            $translations = $documentService->getTranslations($document);
            if(!empty($translations[$request->getLocale()])){
                $configuration['privacyUrl'] = Document::getById($translations[$request->getLocale()])->getFullPath();
            } else {
                $configuration['privacyUrl'] = $document->getFullPath();
            }
        }

        return $this->render('@pringuinDataprivacy/default/default.html.twig', ['configuration' => $configuration]);

    }
}
