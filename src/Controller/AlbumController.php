<?php

namespace App\Controller;
use App\Entity\Album;
use App\Entity\Token;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/***
 * Controller for albums requests
 * Class AlbumController
 * @package App\Controller
 */
class AlbumController extends AbstractController
{


    /***
     * @Route("/albums", name="albums")
     * @param $sToken
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAlbumByToken($sToken)
    {
        try {

            //search the token
            $em = $this->getDoctrine()->getEntityManager();
            $oTokenRepo = $em->getRepository(Token::class);
            $oToken = $oTokenRepo->findBy(['token_value' => $sToken]);

            if (empty($oToken))
                throw new \Exception('NO token found');

            /** @var Album $oAlbum */
            $oAlbum = $oToken[0]->getAlbum(); //means the token doesn't belong to an album
            if (empty($oAlbum))
                throw new \Exception('NO album found');

            //success response
            unset($oTokenRepo);
            unset($oToken);
            $response = ['result' => self::SUCCESS_RESPONSE, 'message' => 'success', 'data' => $oAlbum->getSerializedAlbum()];
        } catch (\Exception $ex) {
            //error response
            $response = ['result' => self::NOT_SUCCESS_RESPONSE, 'message' => $ex->getMessage(), 'data' => ''];
        }

        return $this->json($response);
    }
}
