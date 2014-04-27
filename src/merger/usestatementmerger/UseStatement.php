<?php


namespace merger\usestatementmerger;


class UseStatement
{
    /**
     * @var string
     */
    private $fullQualifiedName;

    /**
     * @param string $fullQualifiedName
     */
    public function setFullQualifiedName($fullQualifiedName)
    {
        $this->fullQualifiedName = $fullQualifiedName;
    }

    /**
     * @return string
     */
    public function getFullQualifiedName()
    {
        return $this->fullQualifiedName;
    }

} 