<?php
/*
__PocketMine Plugin__
name=EasyRank
version=2.0
author=LDX
class=EasyRank
apiversion=12
*/
class EasyRank implements Plugin {
  private $api;
  public function __construct(ServerAPI $api,$server = false) {
    $this->api = $api;
  }
  public function init() {
    console("[*] Loading EasyRank...");
    $this->path = $this->api->plugin->configPath($this);
    @mkdir($this->path . "players/");
    if(!file_exists($this->path . "prefix.yml")) {
      file_put_contents($this->path . "prefix.yml",yaml_emit(array("Prefix" => true,"Format" => "")));
    }
    $this->prefixConfig = yaml_parse(file_get_contents($this->path . "prefix.yml"));
    $this->prefix = $this->prefixConfig["Prefix"];
    $this->api->console->register("setrank","<username> <#>",array($this,"setrank"));
    $this->api->console->register("setcmd","<command> <#>",array($this,"setcmd"));
    $this->api->addHandler("console.command",array($this,"handleCmd"));
    $this->api->addHandler("player.chat",array($this,"handleChat"));
    $this->refresh();
    console("[*] EasyRank Enabled!");
  }
  private function refresh() {
    if(!file_exists($this->path . "commands.yml")) {
      console("[*] Commands configuration not found!");
      console("[*] Creating commands configuration...");
      if(file_put_contents($this->path . "commands.yml",yaml_emit(array("ban" => 3,"banip" => 4,"defaultgamemode" => 6,"deop" => 5,"difficulty" => 6,"gamemode" => 3,"give" => 4,"help" => 1,"kick" => 3,"kill" => 4,"list" => 1,"me" => 1,"op" => 5,"ping" => 1,"save-all" => 6,"save-off" => 6,"save-on" => 6,"say" => 4,"seed" => 1,"spawn" => 1,"spawnpoint" => 6,"status" => 1,"stop" => 6,"sudo" => 5,"tell" => 1,"time" => 4,"tp" => 3,"whitelist" => 6,"setrank" => 5,"setcmd" => 5))) == false) {
        console("[*] Error creating commands configuration!");
        $this->api->console->run("stop");
      } else {
        console("[*] Commands configuration created!");
      }
    }
    if(!file_exists($this->path . "ranks.yml")) {
      console("[*] Ranks configuration not found!");
      console("[*] Creating ranks configuration...");
      if(file_put_contents($this->path . "ranks.yml",yaml_emit(array("Blocked","Player","Trust","Mod","Admin","Owner","Debug"))) == false) {
        console("[*] Error creating ranks configuration!");
        $this->api->console->run("stop");
      } else {
        console("[*] Ranks configuration created!");
      }
    }
    $this->command = yaml_parse(file_get_contents($this->path . "commands.yml"));
    $this->rank = yaml_parse(file_get_contents($this->path . "ranks.yml"));
    $this->maxrank = count($this->rank) - 1;
  }
  private function player($name) {
    if(!isset($this->player[$name])) {
      if(!file_exists($this->path . "players/" . strtolower($name) . ".dat")) {
        file_put_contents($this->path . "players/" . strtolower($name) . ".dat","1");
      }
      $this->player[$name] = file_get_contents($this->path . "players/" . strtolower($name) . ".dat");
    }
  }
  private function getRank($name) {
    $this->player($name);
    return $this->player[$name];
  }
  private function getCommand($cmd) {
    if(!isset($this->command[$cmd])) {
      return $this->maxrank;
    } else {
      return $this->command[$cmd];
    }
  }
  private function checkPerm($name,$cmd) {
    if($this->getRank($name) >= $this->getCommand($cmd)) {
      return true;
    } else {
      return false;
    }
  }
  public function setrank($cmd,$args,$issuer) {
    if(isset($args[0]) && $args[0] != "") {
      if($this->api->player->get($args[0]) != false) {
        $user = $this->api->player->get($args[0])->username;
        $send = true;
      } else {
        $user = $args[0];
        $send = false;
      }
      if(isset($args[1]) && $args[1] != "" && is_numeric($args[1]) && $args[1] <= $this->maxrank && $args[1] >= 0) {
        $rank = $args[1];
        file_put_contents($this->path . "players/" . strtolower($user) . ".dat",$rank);
        $this->player[$user] = $rank;
        $this->refresh();
        if($send) {
          if($issuer instanceof Player) {
            $n = $issuer->username;
          } else {
            $n = "Console";
          }
          $this->api->player->get($user)->sendChat("[EasyRank] " . $n . " set your rank to " . $this->rank[$rank] . "! (" . $rank . ")");
        }
        return "[EasyRank] Set " . $user . "'s rank to " . $this->rank[$rank] . "! (" . $rank . ")";
      } else {
        return "[EasyRank] Please specify a rank level.";
      }
    } else {
      return "[EasyRank] Please specify a player.";
    }
  }
  public function setcmd($cmd,$args,$issuer) {
    if(isset($args[0]) && $args[0] != "") {
      $command = $args[0];
      if(isset($args[1]) && $args[1] != "" && is_numeric($args[1]) && $args[1] <= $this->maxrank && $args[1] >= 0) {
        $rank = $args[1];
        $cmds = yaml_parse(file_get_contents($this->path . "commands.yml"));
        $cmds[$command] = $rank;
        file_put_contents($this->path . "commands.yml",yaml_emit($cmds));
        $this->refresh();
        return "[EasyRank] Set " . $command . "'s minimum rank to " . $this->rank[$rank] . "! (" . $rank . ")";
      } else {
        return "[EasyRank] Please specify a rank level.";
      }
    } else {
      return "[EasyRank] Please specify a command.";
    }
  }
  public function handleCmd($data,$event) {
    $this->api->ban->cmdWhitelist($data["cmd"]);
    if($data["issuer"] instanceof Player) {
      if(!$this->checkPerm($data["issuer"]->username,$data["cmd"])) {
        return false;
      }
    }
  }
  public function handleChat($data,$event) {
    if($this->prefix == "true") {
      $this->player($data["player"]->username);
      $rank = $this->player[$data["player"]->username];
      if($rank > 1) {
        $char = "/";
      } else {
        $char = "#";
      }
      $this->api->chat->broadcast($char . " [" . $this->rank[$this->player[$data["player"]->username]] . "] <" . $data["player"]->username . "> " . $data["message"]);
      return false;
    }
  }
  public function __destruct() { }
}
?>
