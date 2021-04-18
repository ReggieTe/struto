<?php 
//The App is composed of two classes 
//Flower and HoneyBird
//App
$app = new HoneyBird();
$app->onDayStart();

/**HoneyBird Class */

class HoneyBird {
    private $flowers ; //contain flower objects
    private $days;
    private $hourCounter;//represents the sun
    
    public function __construct($requiredFlowers=10)
    {
        for($i=0;$i<$requiredFlowers;$i++)
        {
            $this->flowers[$i] = new Flower();
        }
        $this->days =0;
    }

    public function onDayStart()
    {
        if($this->checkNectarAvaliability())
        {   
            $totalDayLightHours = 12;
            $this->hourCounter=1;
            echo "\n<br><b>DAY START ($this->days)</b>\n<br>";
            while ($this->hourCounter <= $totalDayLightHours) {
                $this->onHourChange($this->hourCounter);
                $this->hourCounter++;
            }
            $this->onDayEnd($this->hourCounter,$totalDayLightHours);
            $this->sleep();
        }
        echo "EXIT ($this->days) \n<br> ----------------------------------";
        exit;
    }

    public function onDayEnd($hourCounter,$totalDayLightHours)
    {
        echo ($hourCounter==$totalDayLightHours)?"\n<br><b>DAY END ($this->days)</b>\n<br>":"";
    }

    public function onHourChange($hourCounter)
    {
        echo "\n<br>HOUR CHANGE ($hourCounter)";
        $this->pickFlower();    
        echo "\n<br>";
    }

    public function sleep()
    {
       while ($this->hourCounter>=12) { 
            echo "\n<br>HOUR CHANGE(".$this->hourCounter.") \n<br> SLEEP\n<br>";
            $this->hourCounter++;
            if($this->hourCounter ==25)
            {
                break;
            }
        }
        $this->days++; //Record new day
        $this->hourCounter=1;//Reset the hours for new day
        $this->onDayStart();
    }


    public function checkNectarAvaliability()
    {
        $flowerCounter=0;
        $flowersWithNectars=array();     
        foreach ($this->flowers as $flower) {
            if ($flower->getNectar()>0) {
                array_push($flowersWithNectars,$flower);
                $flowerCounter++;
            }
        }
        $this->flowers =$flowersWithNectars; 
        return $flowerCounter>0?true:false;
    }

    public function pickFlower()
    {
        if (count($this->flowers)) {
            $pick = rand(0, count($this->flowers)-1);        
            if (array_key_exists($pick, $this->flowers)) {
                $currentFlower = $this->flowers[$pick];
                $remainingNectarCount = $currentFlower->getNectar();
                if ($remainingNectarCount>0) {                   
                    //record visit
                    if ($currentFlower->getVisit()!=$this->days) {
                        //feed
                        echo "\n<br>FLOWER-$pick ($remainingNectarCount)";
                        $this->flowers[$pick]->setNectar((int)$remainingNectarCount-1);
                        $this->flowers[$pick]->setVisit($this->days);
                    }

                } else {   
                    echo "\n\n<br>FLOWER-$pick (0)";
                    unset($this->flowers[$pick]); //removing flowers without nectar
                    $this->flowers = array_values($this->flowers);//resetting keys
                    $this->pickFlower();//pick a different flower
                }
            } else {
                $this->pickFlower();//pick a different flower
            }
        }
    }

}

/**Flower class */
class Flower{
    private $nectar; //nectar feed counter
    private $visit;

    public function __construct()
    {
        $this->nectar = 10;
        $this->visit=-1;
    }


    public function onHourChange()
    {

    }
    /**
     * Get the value of nectar
     */ 
    public function getNectar()
    {
        return $this->nectar;
    }

    /**
     * Set the value of nectar
     *
     * @return  self
     */ 
    public function setNectar($nectar)
    {
        $this->nectar = $nectar;

        return $this;
    }

    /**
     * Get the value of visit
     */ 
    public function getVisit()
    {
        return $this->visit;
    }

    /**
     * Set the value of visit
     *
     * @return  self
     */ 
    public function setVisit($visit)
    {
        $this->visit = $visit;

        return $this;
    }
}


