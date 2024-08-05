<?php
// src/Service/FileUploader.php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Notefecation;
use App\Entity\User;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFalseNode;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class NotefecationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function notefy(string  $user,string $table,string $line,string $did_what): void
    {

       
        $notefecation=new Notefecation();
        $notefecation->setWho($user);
        $notefecation->setInTable($table);
        $notefecation->setDateNotefecation(new \DateTime());
        $notefecation->setSeen(false);
        $notefecation->setInLine($line);
        $notefecation->setDidWhat($did_what);
        $this->entityManager->persist($notefecation);
        $this->entityManager->flush();
            

        
    }
}
