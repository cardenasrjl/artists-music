<?php

namespace App\Repository;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Utils\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @method Token|null find($id, $lockMode = null, $lockVersion = null)
 * @method Token|null findOneBy(array $criteria, array $orderBy = null)
 * @method Token[]    findAll()
 * @method Token[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRepository extends ServiceEntityRepository
{

    CONST TOKEN_LENGTH = 6; //current token length

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Token::class);
    }

    /***
     *  Generates a new token to be used by an artist or an album, code and db guarantees unique value
     * @param ObjectManager $manager
     * @return Token
     */
    public  function generateToken(ObjectManager $manager) : Token
    {

        $sToken = TokenGenerator::generate(self::TOKEN_LENGTH);
        $oToken = $this->findBy(['token_value'=>$sToken]);
        while (!empty($oToken)) {
            $sToken = TokenGenerator::generate(self::TOKEN_LENGTH);
            $oToken = $this->findBy(['token_value'=>$sToken]);
        }
        $oToken = (new Token())->setTokenValue($sToken);
        $manager->persist($oToken);

        $manager->flush();

        return $oToken;
    }


}
