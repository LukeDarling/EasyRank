<?php
/*
__PocketMine Plugin__
name=EasyRank
version=1.0
author=LDX
class=EasyRank
apiversion=12,13,14
*/
class EasyRank implements Plugin {
private $api;
public function __construct(ServerAPI $api, $server = false) {
$this->api = $api;
$this->api->LoadAPI("easyrank","EasyRankAPI");
}
public function init() {
$path = $this->api->plugin->configPath($this);
if(!file_exists($path . "bin/.ignore")) {
console(FORMAT_BLUE . "[EasyRank] " . FORMAT_GREEN . "Notice: Configuration files not found.");
console(FORMAT_BLUE . "[EasyRank] " . FORMAT_GREEN . "Creating configuration files...");
$installLog = "";
@mkdir($path . "players/");
@mkdir($path . "config/");
$installLog = $installLog . file_put_contents($path . "config/prefix.ini","true");
$installLog = $installLog . "&" . file_put_contents($path . "config/opbypass.ini","false");
$installLog = $installLog . "&" . file_put_contents($path . "config/autoaddcmds.ini","true");
@mkdir($path . "ranks/");
$installLog = $installLog . "&" . file_put_contents($path . "ranks/0.ini","Blocked");
$installLog = $installLog . "&" . file_put_contents($path . "ranks/1.ini","Player");
$installLog = $installLog . "&" . file_put_contents($path . "ranks/2.ini","Trust");
$installLog = $installLog . "&" . file_put_contents($path . "ranks/3.ini","Mod");
$installLog = $installLog . "&" . file_put_contents($path . "ranks/4.ini","Admin");
$installLog = $installLog . "&" . file_put_contents($path . "ranks/5.ini","Owner");
$installLog = $installLog . "&" . file_put_contents($path . "ranks/6.ini","Debug");
@mkdir($path . "cmds/");
// Default PocketMine commands found in the PocketMine documentation.
// https://github.com/PocketMine/PocketMine-MP/wiki/Default-server-commands
$installLog = $installLog . "&" . file_put_contents($path . "cmds/ban.ini","3");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/banip.ini","4");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/defaultgamemode.ini","5");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/deop.ini","5");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/difficulty.ini","5");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/gamemode.ini","2");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/give.ini","4");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/help.ini","1");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/kick.ini","3");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/kill.ini","4");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/list.ini","1");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/me.ini","1");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/op.ini","5");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/ping.ini","1");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/save-all.ini","6");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/save-off.ini","6");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/save-on.ini","6");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/say.ini","4");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/seed.ini","1");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/spawn.ini","1");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/spawnpoint.ini","5");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/status.ini","1");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/stop.ini","6");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/sudo.ini","5");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/tell.ini","1");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/time.ini","4");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/tp.ini","2");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/whitelist.ini","6");
// Built-in commands.
$installLog = $installLog . "&" . file_put_contents($path . "cmds/setrank.ini","5");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/setcmd.ini","5");
$installLog = $installLog . "&" . file_put_contents($path . "cmds/setrankname.ini","5");
@mkdir($path . "bin/");
$installLog = $installLog . "&" . file_put_contents($path . "bin/api.ini","true");
$installLog = $installLog . "#" . file_put_contents($path . "bin/.ignore","WARNING: If you delete this file, all settings and configurations will be reset to their default values.");
$installLog = $installLog . "@" . $path;
file_put_contents($path . "bin/install.txt",$installLog);
console(FORMAT_BLUE . "[EasyRank] " . FORMAT_GREEN . "Configuration files created!");
}
// Initialize commands.
$this->api->console->register("setrank","<username> <#>",array($this,"setrank"));
$this->api->console->register("setcmd","<command> <#>",array($this,"setcmd"));
$this->api->console->register("setrankname","<#> <name>",array($this,"setrankname"));
// Initialize handlers.
$this->api->addHandler("console.command",array($this,"handleCmd"));
$this->api->addHandler("player.chat",array($this,"handleChat"));
// Tell the console that we're done.
console(FORMAT_BLUE . "[EasyRank] " . FORMAT_GREEN . "EasyRank Enabled!");
}
public function setrank($cmd,$args,$issuer) {
$path = $this->api->plugin->configPath($this);
if(isset($args[0])) {
$user = $args[0];
if(isset($args[1])) {
$rank = $args[1];
if($rank >= 0 && $rank <= 6) {
file_put_contents($path . "players/" . strtolower($user) . ".ini",$rank);
return "[EasyRank] Set " . $user . "'s rank to " . file_get_contents($path . "ranks/" . strtolower($rank) . ".ini") . "! (" . $rank . ")";
if($this->api->player->get($user) instanceof Player) {
console("yeah");
$this->api->chat->sendTo(false,"[EasyRank] Your rank has been changed to " . file_get_contents($path . "ranks/" . strtolower($rank) . ".ini") . "! (" . $rank . ")",$user);
}
} else {
return "[EasyRank] Please specify a valid rank level. (0-6)";
}
} else {
return "[EasyRank] Please specify a rank level.";
}
} else {
return "[EasyRank] Please specify a player.";
}
}
public function setcmd($cmd,$args,$issuer) {
$path = $this->api->plugin->configPath($this);
if(isset($args[0])) {
$command = $args[0];
if(isset($args[1])) {
$rank = $args[1];
if($rank >= 0 && $rank <= 6) {
file_put_contents($path . "cmds/" . strtolower(str_replace("/","%",$command)) . ".ini",$rank);
return "[EasyRank] Set " . $command . "'s minimum rank to " . file_get_contents($path . "ranks/" . strtolower($rank) . ".ini") . "! (" . $rank . ")";
} else {
return "[EasyRank] Please specify a valid rank level. (0-6)";
}
} else {
return "[EasyRank] Please specify a rank level.";
}
} else {
return "[EasyRank] Please specify a command.";
}
}
public function setrankname($cmd,$args,$issuer) {
$path = $this->api->plugin->configPath($this);
if(isset($args[0])) {
$rank = $args[0];
if(isset($args[1])) {
$rankname = $args[1];
if($rank >= 0 && $rank <= 6) {
file_put_contents($path . "ranks/" . strtolower($rank) . ".ini",$rankname);
return "[EasyRank] Set rank level " . $rank . "'s name to " . file_get_contents($path . "ranks/" . strtolower($rank) . ".ini") . "!";
} else {
return "[EasyRank] Please specify a valid rank level. (0-6)";
}
} else {
return "[EasyRank] Please specify a new rank name.";
}
} else {
return "[EasyRank] Please specify a rank.";
}
}
private function checkProfile($user) {
$path = $this->api->plugin->configPath($this);
if(!file_exists($path . "players/" . strtolower($user) . ".ini")) {
file_put_contents($path . "players/" . strtolower($user) . ".ini","1");
}
}
private function allowCmd($user,$cmd) {
$path = $this->api->plugin->configPath($this);
$this->checkProfile($user);
$rank = file_get_contents($path . "players/" . strtolower($user) . ".ini");
if(file_exists($path . "cmds/" . strtolower(str_replace("/","%",$cmd)) . ".ini")) {
$command = file_get_contents($path . "cmds/" . strtolower(str_replace("/","%",$cmd)) . ".ini");
if($rank >= $command || $this->api->ban->isOp($user)) {
return true;
} else {
return false;
}
} else {
return false;
}
}
private function getRank($user) {
$path = $this->api->plugin->configPath($this);
$this->checkProfile($user);
return file_get_contents($path . "players/" . strtolower($user) . ".ini");
}
public function handleCmd($data,$event) {
$this->api->ban->cmdWhitelist($data["cmd"]);
if($data["issuer"] instanceof Player) {
$this->checkProfile($data["issuer"]->username);
if($this->allowCmd($data["issuer"]->username,$data["cmd"])) {
} else {
return false;
}
}
}
public function handleChat($data,$event) {
$path = $this->api->plugin->configPath($this);
if(file_get_contents($path . "config/prefix.ini") == "true") {
$this->checkProfile($data["player"]->username);
$rank = file_get_contents($path . "ranks/" . $this->getRank($data["player"]->username) . ".ini");
$this->api->chat->broadcast("[" . $rank . "] <" . $data["player"]->username . "> " . $data["message"]);
return false;
}
}
public function __destruct() {
}
}
class EasyRankAPI {
public function addCommand($cmd,$rank) {
$path = "plugins/EasyRank/";
if($rank >= 0 && $rank <= 6 && !file_exists($path . "cmds/" . strtolower(str_replace("/","%",$cmd)) . ".ini")) {
file_put_contents($path . "cmds/" . strtolower(str_replace("/","%",$cmd)) . ".ini",$rank);
return true;
} else {
return false;
}
}
public function setCommand($cmd,$rank) {
$path = "plugins/EasyRank/";
if($rank >= 0 && $rank <= 6 && file_exists($path . "cmds/" . strtolower(str_replace("/","%",$cmd)) . ".ini")) {
file_put_contents($path . "cmds/" . strtolower(str_replace("/","%",$cmd)) . ".ini",$rank);
return true;
} else {
return false;
}
}
public function setRank($user,$rank) {
$path = "plugins/EasyRank/";
if($rank >= 0 && $rank <= 6) {
file_put_contents($path . "players/" . strtolower($user) . ".ini",$rank);
return true;
} else {
return false;
}
}
public function setRankName($rank,$name) {
$path = "plugins/EasyRank/";
if($rank >= 0 && $rank <= 6) {
file_put_contents($path . "ranks/" . strtolower($rank) . ".ini",$name);
return true;
} else {
return false;
}
}
}
?>
