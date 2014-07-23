<?php
namespace LDX\EasyRank;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat as Color;
use pocketmine\event\PlayerCommandPreprocessEvent as CommandEvent;
class Main extends PluginBase {
  public function onLoad() {
    $this->getLogger()->info(TextFormat::YELLOW . "Loading EasyRank v" . $this->getDescription()->getVersion() . " by LDX...");
  }
  public function onEnable() {
    if(!file_exists($this->getDataFolder() . "config.yml")) {
      @mkdir($this->getDataFolder());
      file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
    }
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    $this->getLogger()->info(TextFormat::YELLOW . "Enabling EasyRank...");
  }
  public function onCommand(CommandSender $issuer,Command $cmd,$label,array $args) {
    
  }
  /**
  * @param CommandEvent $event
  *
  * @priority HIGHEST
  * @ignoreCancelled true
  */
  public function playerCommand(CommandEvent $event) {
    $p = $event->getPlayer();
    $m = $event->getMessage();
    $p->sendMessage($m); // DEBUG
    if(substr($m,0) == "/") {
      $cmd = trim($m,"/");
      $p->sendMessage($cmd); // DEBUG
      if(!$this->hasPermission($p->getName(),$cmd)) {
        $event->setCancelled();
      }
    }
  }
  public function onDisable() {
    $this->getLogger()->info(TextFormat::YELLOW . "Disabling EasyRank...");
  }
}
?>
