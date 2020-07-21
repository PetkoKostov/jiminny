<?php

namespace App\Models;

use App\Models\BaseModel;
use Storage;

class Conversation extends BaseModel
{
    public $customerPoints;
    public $userPoints;
    public $longestCustomerMonologue;
    public $longestUserMonologue;
    public $totalDuration;
    public $userTotalTalkDuration;
    public $userTalkPercentage;

    public function __construct($customerFilePath, $userFilePath)
    {
        parent::__construct();

        $this->customerPoints = $this->generateTalkPoints($customerFilePath);
        $this->userPoints = $this->generateTalkPoints($userFilePath);

        $this->longestCustomerMonologue = $this->getLongestMonologue($this->customerPoints);
        $this->longestUserMonologue = $this->getLongestMonologue($this->userPoints);

        $this->setTotalDuration();
        $this->setUserTotalTalkDuration();
        $this->setUserTalkPercentage();
    }

     /**
     * Make array of talking points
     *
     * @param  string  $filePath
     * @return array
     */
    private function generateTalkPoints($filePath)
    {
        $conversationPointsArr = [];
        $start = 0;
        $file = fopen(storage_path("app/{$filePath}"), "r");

        while(!feof($file)) {
            $line = fgets($file);
            if($start !== NULL) {
                $end = $this->getTime($line); 
                $conversationPointsArr[] = [$start, $end]; 
                $start = NULL; 
            } else {
                $start = $this->getTime($line);
            }
        }

        fclose($file);

        return $conversationPointsArr;
    }

     /**
     * Return the time from the line as given by ffmpeg`s filter function
     *
     * @param  string  $filePath
     * @return float
     */
    private function getTime($line)
    {
        preg_match('#:\s(.+)\s#U', $line, $matches);

        return $matches[1];
    }

    /**
     * Return the longest monologue by calculation the difference between start and end of each
     * talking period
     * 
     * @param  array  $talkPoints
     * @return float
     */
    private function getLongestMonologue($talkPoints)
    {
        $longestMonologue = 0;

        foreach($talkPoints as $point) {
            $diff = $point[1] - $point[0];
            if($diff > $longestMonologue) {
                $longestMonologue = $diff;
            }
        }

        return $longestMonologue;
    }

     /**
     * Set call total duration.
     * !!! "For simplicity, the largest point in the dataset represents the total duration of the call."
     * 
     * @return void
     */
    private function setTotalDuration()
    {
        $customerLastTalkingPoint = $this->customerPoints[count($this->customerPoints)-1][1];
        $userLastTalkingPoint = $this->userPoints[count($this->userPoints)-1][1];
        
        $this->totalDuration = max($customerLastTalkingPoint, $userLastTalkingPoint);
    }

     /**
     * Set the total seconds that the user was talking
     * 
     * @return void
     */
    private function setUserTotalTalkDuration()
    {
        $total = 0;

        foreach($this->userPoints as $point) {
            $total += $point[1] - $point[0];
        }

        $this->userTotalTalkDuration = $total;
    }

    /**
     * Set the percentage of time the user talked over the entire call duration.
     * 
     * @return void
     */
    private function setUserTalkPercentage()
    {
        $percentage = ( $this->userTotalTalkDuration / $this->totalDuration ) * 100;

        $this->userTalkPercentage = $percentage;
    }
}
