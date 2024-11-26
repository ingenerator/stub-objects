<?php
namespace Doctrine\Common\Collections;


interface Collection
{

}

class ArrayCollection implements Collection
{
    public function __construct(public readonly array $items)
    {

    }
}
