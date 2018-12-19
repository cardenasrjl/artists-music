<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Song;
use App\Entity\Artist;
use App\Entity\Token;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData extends Fixture
{
    public function load(ObjectManager $manager)
    {

        try {

            //repo to create tokens
            $oTokenRepo = $manager
                ->getRepository(Token::class);

            $sJsonData = file_get_contents(__DIR__ . '/data.json');
            if (!empty($sJsonData))
                $arrArtist = json_decode($sJsonData);

            //artists
            foreach ($arrArtist as $sArtist) {
                $oArtist = (new Artist())
                    ->setName($sArtist->name)
                    ->setToken($oTokenRepo->generateToken($manager));

                $manager->persist($oArtist);

                //albums
                foreach ($sArtist->albums as $sAlbum) {
                    $oAlbum = (new Album())
                        ->setToken($oTokenRepo->generateToken($manager))
                        ->setArtist($oArtist)
                        ->setTitle($sAlbum->title)
                        ->setCover($sAlbum->cover)
                        ->setDescription($sAlbum->description);

                    $manager->persist($oAlbum);

                    //songs
                    foreach ($sAlbum->songs as $sSong) {

                        $oSong = (new Song())
                            ->setAlbum($oAlbum)
                            ->setTitle($sSong->title)
                            ->setLength((int)$sSong->length * 60 + floor($sSong->length) * 60);

                        $manager->persist($oSong);
                    }//foreach songs
                } //foreach albums
            } //foreach artists

            $manager->flush();
            unset($oTokenRepo);
        }
        catch (\Exception $ex)
        {
            echo "ERROR:" .$ex->getMessage();
        }

    }
}
