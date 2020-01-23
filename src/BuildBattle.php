<?php

namespace The0v3rD0z\BuildBattle;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
    public $bb = array();
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->bb = array();
        $this->bb[0] = 0; // ist es am starten       
        $this->bb[1] = 0; // was gebaut wird
        
        $this->bb[2] = 0; // overall rating of 1 built
        $this->bb[3] = 0; // overall rating of 2 built
        $this->bb[4] = 0; // overall rating of 3 built
        $this->bb[5] = 0; // overall rating of 4 built
        $this->bb[6] = 0; // overall rating of 5 built
        $this->bb[7] = 0; // overall rating of 6 built
        $this->bb[8] = 0; // overall rating of 7 built
        $this->bb[9] = 0; // overall rating of 8 built
        $this->bb[10] = 0; // overall rating of 9 build
        $this->bb[11] = 0; // overall rating of 10 built
        
        $this->bb[12] = 0; // nickname builder 1 built
        $this->bb[13] = 0; // nickname builder 2 built
        $this->bb[14] = 0; // nickname builder 3 built
        $this->bb[15] = 0; // nickname builder 4 built
        $this->bb[16] = 0; // nickname builder 5 built
        $this->bb[17] = 0; // nickname builder 6 built
        $this->bb[18] = 0; // nickname builder 7 built      
        $this->bb[19] = 0; // nickname builder 8 built   
        $this->bb[20] = 0; // nickname builder 9 built
        $this->bb[21] = 0; // nickname builder 10 built
        
        $this->bb[22] = 0; // in which arena are the players
        
        $this->getScheduler()->scheduleRepeatingTask(new Task(array($this, "Popup")), 10);
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            @mkdir($this->getDataFolder());
            file_put_contents($this->getDataFolder() . "config.yml", $this->getResource("config.yml"));
        }
    }
    public function PlayerJoinEvent(PlayerJoinEvent $event){
        $p = $event->getPlayer();
        if((int)$this->bb[0] != 0){
            $p->close("", TextFormat::RED."The game has already begun!");
            return false;
        }
        $p->setNameTagVisible(false);
        $p->setGamemode(0);
        $p->teleport(new Position($this->getConfig()->get("Spawn")));
        if(count($this->getServer()->getOnlinePlayers()) >= 10){
            $this->getServer()->broadcastMessage(TextFormat::RED."Startet in weniger als 10 Sekunden!");
            $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "Start"]), 10 * 20 );
        } else {
            $p->sendMessage(TextFormat::GOLD.'Du bist der BuildBattle-Formation beigetreten.');
            $p->sendMessage(TextFormat::GOLD.'Sobald die Anzahl der Spieler 10 erreicht hat, beginnt das Spiel.');
        }
    }
    public function PlayerQuitEvent(PlayerQuitEvent $event){
        $event->getPlayer()->getInventory()->clearAll();
    }
    public function Start(){
        if(count($this->getServer()->getOnlinePlayers()) >= 10){
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Spielstart wurde abgesagt. Jemand hat den Server verlassen.");
        } else {
            $this->getServer()->broadcastMessage(TextFormat::RED."Das Spiel hat begonnen!");
            $online = $this->getServer()->getOnlinePlayers();
            $online[0]->teleport(new Position($this->getConfig()->get("1")));
            $online[1]->teleport(new Position($this->getConfig()->get("2")));
            $online[2]->teleport(new Position($this->getConfig()->get("3")));
            $online[3]->teleport(new Position($this->getConfig()->get("4")));
            $online[4]->teleport(new Position($this->getConfig()->get("5")));
            $online[5]->teleport(new Position($this->getConfig()->get("6")));
            $online[6]->teleport(new Position($this->getConfig()->get("7")));
            $online[7]->teleport(new Position($this->getConfig()->get("8")));
            $online[8]->teleport(new Position($this->getConfig()->get("9")));
            $online[9]->teleport(new Position($this->getConfig()->get("10")));
           
            $this->bb[10] = $online[0]; // nickname builder 1 built
            $this->bb[11] = $online[1]; // nickname builder 2 built
            $this->bb[12] = $online[2]; // nickname builder 3 built
            $this->bb[13] = $online[3]; // nickname builder 4 built
            $this->bb[14] = $online[4]; // nickname builder 5 built
            $this->bb[15] = $online[5]; // nickname builder 6 built
            $this->bb[16] = $online[6]; // nickname builder 7 built
            $this->bb[17] = $online[7]; // nickname builder 8 built
            $this->bb[18] = $online[8]; // nickname builder 9 built
            $this->bb[19] = $online[9]; // nickname builder 10 built
            
            foreach($this->getServer()->getOnlinePlayers() as $p){
            
                $p->setGamemode(1);
            }
            $r = mt_rand(1,10);
            if($r == 1){
                $this->bb[1] = "Brücke";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten eine Brücke");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 2){
                $this->bb[1] = "Computer";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue einen Computer innerhalb von 5 Minuten");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 3){
                $this->bb[1] = "Schloss";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten ein Schloss");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 4){
                $this->bb[1] = "Turm";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Turm.");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 5){
                $this->bb[1] = "Lampe";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten eine Lampe");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 6){
                $this->bb[1] = "Kopfhörer";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue Kopfhörer für 5 Minuten");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 7){
                $this->bb[1] = "Cartoon";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Cartoon");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 8){
                $this->bb[1] = "Auto";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue ein Auto innerhalb von 5 Minuten");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 9){
                $this->bb[1] = "Flugzeug";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten ein Flugzeug.");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 10){
                $this->bb[1] = "Hai";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Hai");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 11){
                $this->bb[1] = "Baby";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue ein Baby innerhalb von 5 Minuten.");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 12){
                $this->bb[1] = "Burger";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Burger ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 13){
                $this->bb[1] = "Drachen";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Drachen ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 14){
                $this->bb[1] = "Vulkan";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Vulkan ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 15){
                $this->bb[1] = "Baumhaus";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten ein Baumhaus ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 16){
                $this->bb[1] = "Wasserfall";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Wasserfall ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 17){
                $this->bb[1] = "Baby Drache";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Baby-Drache ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 18){
                $this->bb[1] = "Disney";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue Disney innerhalb von 5 Minuten ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 19){
                $this->bb[1] = "Superheld";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Superhelden ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 20){
                $this->bb[1] = "Bergwerk";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten ein Bergwerk ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 21){
                $this->bb[1] = "Dorf";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten ein Dorf ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 22){
                $this->bb[1] = "Schurke";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Baue innerhalb von 5 Minuten einen Schurken");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );
            } elseif($r == 23){
                $this->bb[1] = "Winter";
                $this->getServer()->broadcastMessage(TextFormat::GOLD."Erstellen Sie innerhalb von 5 Minuten ein Winterthema ");
                $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "min1"]), 4 * 60 * 20 );

            }
            $this->bb[0] = 1;
        }
    }
    public function Popup(){
        if($this->bb[0] == 1){
            $this->getServer()->broadcastPopup(TextFormat::GOLD."Du musst das ".$this->bb[1]."bauen");
        }
    }
    public function min1(){
        $this->getServer()->broadcastMessage(TextFormat::GOLD."Noch eine Minute! Beeilen Sie sich, um abzuschließen");
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "second30"]), 30 * 20 );
    }
    public function second30(){
        $this->getServer()->broadcastMessage(TextFormat::GOLD."Noch 30 Sekunden, schneller bauen!");
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "finish"]), 30 * 20 );
    }
    public function finish(){
        $this->getServer()->broadcastMessage(TextFormat::GOLD."Die Zeit ist um! Abstimmungsstunde!");
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a1"]), 20 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a2"]), 40 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a3"]), 60 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a4"]), 80 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a5"]), 100 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a6"]), 120 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a7"]), 140 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a8"]), 160 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a9"]), 180 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "a10"]), 200 * 20 );
        $this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, "stats"]), 220 * 20 );
    }
    public function a1(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[7]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diese Konstruktion.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "1";
        }
    }
    public function a2(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[8]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diese Konstruktion.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "2";
        }
    }
    public function a3(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[9]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diese Konstruktion.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "3";
        }
    }
    public function a4(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[10]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diese Konstruktion.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "4";
        }
    }
    public function a5(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[11]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diese Konstruktion.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "5";
        }
    }
    public function a6(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[12]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diese Konstruktion.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "6";
        }
   }
   public function a7(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[13]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diese Konstruktion.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "7";
        }
   }
   public function a8(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[14]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diesen Build.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "8";
       }
   }
   public function a9(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[15]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diesen Build.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "9";
       }
   }
   public function a10(){
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->setGamemode(0);
            $p->getInventory()->setItem(1, Item::get(35,5,1)); // OK
            $p->getInventory()->setItem(1, Item::get(35,4,1)); // Good
            $p->getInventory()->setItem(1, Item::get(35,14,1)); // Bad
            $p->teleport(new Position(100,100,100));
            $p->sendMessage(TextFormat::GOLD."Diese Konstruktion vom Spieler ".$this->bb[16]);
            $p->sendMessage(TextFormat::GOLD."Stimmen Sie für diesen Build.");
            $p->sendMessage(TextFormat::GREEN."- grüne wolle: Einfach fabelhaft.");
            $p->sendMessage(TextFormat::YELLOW."- gelbe Wolle: Ist oke.");
            $p->sendMessage(TextFormat::RED."- rote Wolle: Meine Augen brennen.");
            $this->bb[12] = "10";
        }
    }
    public function stats(){
        $stats = array((int)$this->bb[2], (int)$this->bb[3], (int)$this->bb[4], (int)$this->bb[5], (int)$this->bb[6]);
        $iterator = new \RecursiveArrayIterator(new \RecursiveArrayIterator($stats));
        $max = max(iterator_to_array($iterator, false));
        if((int)$this->bb[2] == $max){
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist: ".$this->bb[7]);
        } elseif((int)$this->bb[3] == $max){
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist: ".$this->bb[8]);
        } elseif((int)$this->bb[4] == $max){
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist:  ".$this->bb[9]);
        } elseif((int)$this->bb[5] == $max){
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist: ".$this->bb[10]);
        } elseif((int)$this->bb[6] == $max){
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
          $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist:  ".$this->bb[11]);
       } elseif((int)$this->bb[7] == $max){
           foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist: ".$this->bb[12]);
      } elseif((int)$this->bb[8] == $max){
           foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist ".$this->bb[13]);
      } elseif((int)$this->bb[9] == $max){
           foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist ".$this->bb[14]);
      } elseif((int)$this->bb[10] == $max){
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist: ".$this->bb[15]);
      } elseif((int)$this->bb[11] == $max){
            foreach($this->getServer()->getOnlinePlayers() as $p){
                $p->teleport(new Position($this->getConfig()->get("Spawn")));
                $p->getInventory()->addItem(Item::get(1,0,1));
                $p->getInventory()->clearAll();
            }
            $this->getServer()->broadcastMessage(TextFormat::RED."Der Gewinner ist: ".$this->bb[16]);
        }
        $this->bb = array();       
        $this->bb[0] = 0; // is the game running
        $this->bb[1] = 0; // what you need to build
        
        $this->bb[2] = 0; // overall rating of 1 built
        $this->bb[3] = 0; // overall rating of 2 built
        $this->bb[4] = 0; // overall rating of 3 built
        $this->bb[5] = 0; // overall rating of 4 built
        $this->bb[6] = 0; // overall rating of 5 built
        $this->bb[7] = 0; // overall rating of 6 built
        $this->bb[8] = 0; // overall rating of 7 built
        $this->bb[9] = 0; // overall rating of 8 built
        $this->bb[10] = 0; // overall rating of 9 built
        $this->bb[11] = 0; // overall rating of 10 built
        
        $this->bb[12] = 0; // nickname builder 1 built
        $this->bb[13] = 0; // nickname builder 2 built
        $this->bb[14] = 0; // nickname builder 3 built       
        $this->bb[15] = 0; // nickname builder 4 built
        $this->bb[16] = 0; // nickname builder 5 built
        $this->bb[17] = 0; // nickname builder 6 built
        $this->bb[18] = 0; // nickname builder 7 built
        $this->bb[19] = 0; // nickname builder 8 built
        $this->bb[20] = 0; // nickname builder 9 built
        $this->bb[21] = 0; // nickname builder 10 built
      
        $this->bb[22] = 0; // in which arena are the players
    }
    public function PlayerItemHeldEvent(PlayerItemHeldEvent $event){
        $i = $event->getItem();
        $p = $event->getPlayer();
        if($i->getId() == 35 && $i->getDamage() == 5){
            $p->sendMessage(TextFormat::GREEN."Du hast das Spiel erfolgreich verlassen!");
            $p->sendTip(TextFormat::GREEN."Du hast das Spiel erfolgreich verlassen!");
            $event->setCancelled(true);
            if($this->bb[12] == "1"){
                $this->bb[2] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "2"){
                $this->bb[3] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "3"){
                $this->bb[4] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "4"){
                $this->bb[5] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "5"){
                $this->bb[6] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "6"){
                $this->bb[7] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "7"){
                $this->bb[8] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "8"){
                $this->bb[9] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "9"){
                $this->bb[10] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "10"){
                $this->bb[11] = (int)$this->bb[2] + 3;
            }
            $event->getPlayer()->getInventory()->addItem(Item::get(1,0,1));
            $p->getInventory()->clearAll();
        } elseif($i->getId() == 35 && $i->getDamage() == 4){
            $p->sendMessage(TextFormat::YELLOW."Du hast das Spiel erfolgreich verlassen!");
            $p->sendTip(TextFormat::YELLOW."Du hast das Spiel erfolgreich verlassen!");
            $event->setCancelled(true);
            if($this->bb[12] == "1"){
                $this->bb[2] = (int)$this->bb[2] + 2;
            } elseif($this->bb[12] == "2"){
                $this->bb[3] = (int)$this->bb[2] + 2;
            } elseif($this->bb[12] == "3"){
                $this->bb[4] = (int)$this->bb[2] + 2;
            } elseif($this->bb[12] == "4"){
                $this->bb[5] = (int)$this->bb[2] + 2;
            } elseif($this->bb[12] == "5"){
                $this->bb[6] = (int)$this->bb[2] + 2;
            } elseif($this->bb[12] == "6"){
                $this->bb[7] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "7"){
                $this->bb[8] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "8"){
                $this->bb[9] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "9"){
                $this->bb[10] = (int)$this->bb[2] + 3;                
            } elseif($this->bb[12] == "10"){
                $this->bb[11] = (int)$this->bb[2] + 3;
            }
            $event->getPlayer()->getInventory()->addItem(Item::get(1,0,1));
            $p->getInventory()->clearAll();
        } elseif($i->getId() == 35 && $i->getDamage() == 14){
            $p->sendMessage(TextFormat::RED."Du hast das Spiel erfolgreich verlassen!");
            $p->sendTip(TextFormat::RED."Du hast das Spiel erfolgreich verlassen!");
            $event->setCancelled(true);
            if($this->bb[12] == "1"){
                $this->bb[2] = (int)$this->bb[2] + 1;
            } elseif($this->bb[12] == "2"){
                $this->bb[3] = (int)$this->bb[2] + 1;
            } elseif($this->bb[12] == "3"){
                $this->bb[4] = (int)$this->bb[2] + 1;
            } elseif($this->bb[12] == "4"){
                $this->bb[5] = (int)$this->bb[2] + 1;
            } elseif($this->bb[12] == "5"){
                $this->bb[6] = (int)$this->bb[2] + 1;
            } elseif($this->bb[12] == "6"){
                $this->bb[7] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "7"){
                $this->bb[8] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "8"){
                $this->bb[9] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "9"){
                $this->bb[10] = (int)$this->bb[2] + 3;
            } elseif($this->bb[12] == "10"){
                $this->bb[11] = (int)$this->bb[2] + 3;
            }
            $event->getPlayer()->getInventory()->addItem(Item::get(1,0,1));
            $p->getInventory()->clearAll();
        }
    }
    public function BlockBreakEvent(BlockBreakEvent $event){
        if($event->getPlayer()->getGamemode() != 0){
            $event->setCancelled(true);
        } elseif($event->getBlock()->getId() == 20 && !$event->getPlayer()->isOp()){
            $event->setCancelled(true);
        }
    }
    public function BlockPlaceEvent(BlockPlaceEvent $event){
        if($event->getPlayer()->getGamemode() != 0){
            $event->setCancelled(true);
        } elseif($event->getBlock()->getId() == 20 && !$event->getPlayer()->isOp()){
            $event->setCancelled(true);
        }
    }
    public function PlayerGameModeChangeEvent(PlayerGameModeChangeEvent $event){
        $event->getPlayer()->getInventory()->addItem(Item::get(1,0,1));
        $event->getPlayer()->getInventory()->clearAll();
    }
}
