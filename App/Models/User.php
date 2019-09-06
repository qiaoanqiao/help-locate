<?php
namespace App\Models;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User extends ODM\AbstractField
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $name;

    /** @ODM\Field(type="string") */
    private $email;

    /** @ODM\ReferenceMany(targetDocument="BlogPost", cascade="all") */
    private $posts = array();

}