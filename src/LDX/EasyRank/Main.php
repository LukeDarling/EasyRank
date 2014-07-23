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
    $this->getLogger()->info(TextFormat::YELLOW . "Enabling EasyRank...");
  }
  public function onCommand(CommandSender $issuer,Command $cmd,$label,array $args) {
    
  }
  public function onDisable() {
    $this->getLogger()->info(TextFormat::YELLOW . "Disabling EasyRank...");
  }
}
?>
