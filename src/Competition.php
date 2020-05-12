<?php
/**
 * This file prints out the comments
 * 
 * PHP version 7
 *
 * @category Golf
 * @package  Competition
 * @author   Petter <petter@lerenius.se>
 * @license  xxx http://
 * @version  GIT: :git_id:
 * @link     http://obantour.lerenius.se
 */
class Competition
{
    protected $id;
    protected $name;
    protected $doublePoints;
    protected $result;
    protected $rank;
    protected $closestFlag;
    protected $longestDrive;
    protected $numOfPlayers;
    
    function __construct(
        $id, $name, $numOfPlayers, $doublePoints,
        $result, $rank, $closestFlag, $longestDrive
    ) {
        $this->id=$id;
        $this->name=$name;
        $this->numOfPlayers=$numOfPlayers;
        $this->doublePoints=$doublePoints;
        $this->result=$result;
        $this->rank=$rank;
        $this->closestFlag=$closestFlag;
        $this->longestDrive=$longestDrive;
    }
    
    function getId()
    {
        return $this->id;
    }
    
    function getName()
    {
        return $this->name;
    }
    
    function getRank()
    {
        return $this->rank;
    }
    
    function getClosestFlag()
    {
        return $this->closestFlag;
    }
    
    function getLongestDrive()
    {
        return $this->longestDrive;
    }
    
    function getDoublePoints()
    {
        return $this->doublePoints;
    }
    
    function getRankPoints()
    {
        $points = ($this->rank==0) ?
            0 : 
            (10 * ($this->numOfPlayers - $this->rank)/($this->numOfPlayers - 1) + 1);
        return $this->doublePoints * $points;
    }
    
    function getTotalPoints()
    {
        return $this->getRankPoints() +
            (($this->closestFlag != 0)?2*$this->doublePoints:0);
    }
}
