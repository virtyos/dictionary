<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="translate_error", indexes={
 *   @ORM\Index(name="hash_idx", columns={"hash"}),
 *   @ORM\Index(name="count_idx", columns={"count"})
 * })
 */
class TranslateError
{
  
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=150, options={"collation":"utf8_general_ci"})
     */
    protected $word;
    
    /**
     * @ORM\Column(type="string", length=150, options={"collation":"utf8_general_ci"})
     */
    protected $translate;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $hash;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $count;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set word
     *
     * @param string $word
     *
     * @return TranslateError
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set translate
     *
     * @param string $translate
     *
     * @return TranslateError
     */
    public function setTranslate($translate)
    {
        $this->translate = $translate;

        return $this;
    }

    /**
     * Get translate
     *
     * @return string
     */
    public function getTranslate()
    {
        return $this->translate;
    }

    /**
     * Set hash
     *
     * @param integer $hash
     *
     * @return TranslateError
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return integer
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return TranslateError
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }
}
