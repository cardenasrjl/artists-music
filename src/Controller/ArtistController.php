<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Token;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/***
 * Controller for Artists requests
 * Class ArtistController
 * @package App\Controller
 */
class ArtistController extends AbstractController
{

    /***
     * Return all artist from db
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getArtists()
    {

        try {

            //search the token
            $em = $this->getDoctrine()->getEntityManager();
            $oArtistRepo = $em->getRepository(Artist::class);

            $oArtists = $oArtistRepo->findAll();

            if (empty($oArtistRepo))
                throw new \Exception('No artists found');

            //package to return
            $arrArtist = array_map(function($oArtist) {
                return $oArtist->getSerializedArtist();
            }, $oArtists );

            $response = ['result' => self::SUCCESS_RESPONSE, 'message' => 'success', 'data' => $arrArtist];
        } catch (\Exception $ex) {
            $response = ['result' => self::NOT_SUCCESS_RESPONSE, 'message' => $ex->getMessage(), 'data' => ''];
        }

        return $this->json($response);
    }


    /***
     * @param $sToken
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getArtistByToken($sToken)
    {
        try {

            //search the token
            $em = $this->getDoctrine()->getEntityManager();
            $oTokenRepo = $em->getRepository(Token::class);
            $oToken = $oTokenRepo->findBy(['token_value' => $sToken]);

            if (empty($oToken))
                throw new \Exception('No data found with the provided token');

            $oArtist = $oToken[0]->getArtist();
            if (empty($oArtist))
                throw new \Exception('The provided token does not belong to an artist');

            $response = ['result' => self::SUCCESS_RESPONSE, 'message' => 'success', 'data' =>  $oArtist->getSerializedArtist()];
        } catch (\Exception $ex) {
            $response = ['result' => self::NOT_SUCCESS_RESPONSE, 'message' => $ex->getMessage(), 'data' => ''];
        }

        return $this->json($response);
    }


}
