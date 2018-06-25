<?php

namespace Fx\SchoolBundle\Manager;

use Doctrine\ORM\EntityManager;

use Fx\SchoolBundle\Entity\Image;
use Symfony\Bridge\Monolog\Logger;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Translate\TranslateClient;

/**
 * @Service(id="fx_school.visio_manager")
 */
class visioManager
{
    private $em;


    /**
     * @InjectParams({
     *     "em"     = @Inject("doctrine.orm.entity_manager"),
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em     = $em;
    }


    public function traducir($text,$target){
        $projectId = 'turism-oven-204915';
        $translator = new TranslateClient(array(
            'projectId' => $projectId
        ));

        $translation = $translator->translate($text, array(
            'target'=> $target
        ));

        return $translation['text'];
    }
    public function getDatos(Image $image){
        $imageAnnotator = new ImageAnnotatorClient();

        $image = file_get_contents($image->getAbsolutePath());

        $response  = $imageAnnotator->landmarkDetection($image);
        $landmarks = $response->getLandmarkAnnotations();
        if(count($landmarks)>0){

            $site = $landmarks[0]->getDescription();
            $localitions=$landmarks[0]->getLocations();
            $coordenadas=$localitions[0]->getLatLng();

            $site = urlencode($this->traducir($site, 'es'));

            $wikipedia = "https://es.wikipedia.org/w/api.php?";
            $arguments = "action=query&format=json&prop=extracts&indexpageids=1&titles=$site&redirects=1&exintro=1";
            $wikiResponse = file_get_contents($wikipedia . $arguments);
            $wikiResponse = json_decode($wikiResponse, true);

            $pageId = $wikiResponse['query']['pageids']['0'];

            if ($pageId == '-1') {
                return  array(
                    'informacion' => "NO SE ENCONTRO INFORMACIÓN",
                    'lugar'      => urldecode($site),
                    'query'      => $landmarks[0]->getDescription(),
                    'location'   => $coordenadas
                );
            } else {
                $content = $wikiResponse['query']['pages']["$pageId"]['extract'];
                return  array(
                    'informacion' =>  $content,
                    'lugar'      => urldecode($site),
                    'query'      => $landmarks[0]->getDescription(),
                    'location'   => $coordenadas
                );
            }
        }
        return  array(
            'informacion' => "NO SE ENCONTRO INFORMACIÓN",
            'lugar'      =>  ":(",
            'query'      => null,
            'location'  => null
        );

    }

    //endregion
}