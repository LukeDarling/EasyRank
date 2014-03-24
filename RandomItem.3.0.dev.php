<?php
/*
__PocketMine Plugin__
name=RandomItem
version=3.0
author=LDX
class=RandomItem
apiversion=1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25
*/
class RandomItem implements Plugin {
private $api;
public function __construct(ServerAPI $api, $server = false) {
$this->api = $api;
}
public function init() {
$currentVersion = "3.0";
$this->api->console->register("gift","Gives a random item. Usage: /gift [player]",array($this, "giveRI"));
$path = $this->api->plugin->configPath($this);
if(file_exists($path . "items.yml")) {
console("[INFO] [RandomItem] Converting items.yml...");
$this->items = new Config($path . "items.yml",CONFIG_YAML);
$itemsYAML = array($this->items->get("0"),$this->items->get("1"),$this->items->get("2"),$this->items->get("3"),$this->items->get("4"));
unlink($path . "items.yml");
$items = implode("*",$itemsYAML);
file_put_contents($path . "items.txt",$items);
console("[INFO] [RandomItem] items.yml converted!");
}
if(file_exists($path . "interval.yml")) {
console("[INFO] [RandomItem] Converting interval.yml...");
$this->time = new Config($path . "interval.yml",CONFIG_YAML);
$intervalYAML = $this->time->get("seconds") * 20;
unlink($path . "interval.yml");
file_put_contents($path . "interval.txt",$intervalYAML);
console("[INFO] [RandomItem] interval.yml converted!");
}
if(!file_exists($path . "mode.txt")) {
file_put_contents($path . "mode.txt","1");
}
if(!file_exists($path . "items.txt")) {
file_put_contents($path . "items.txt","295*458*364*265*391*392*360*263*6*297*352*35");
}
if(!file_exists($path . "interval.txt")) {
file_put_contents($path . "interval.txt","600");
}
$timeInterval = file_get_contents($path . "interval.txt") * 20;
$this->api->schedule($timeInterval,array($this,"giveTime"),array($timeInterval),true,"server.schedule");
if(file_exists($path . "version.txt") && file_get_contents($path . "version.txt") == $currentVersion) {
$version = file_get_contents($path . "version.txt");
} else {
if(file_exists($path . "version.txt")) {
$version = file_get_contents($path . "version.txt");
file_put_contents($path . "version.txt",$currentVersion);
console("[INFO] [RandomItem] Updated from " . $version . " to " . $currentVersion . "!");
} else {
console("[INFO] [RandomItem] Thank you for installing RandomItem!");
console("[INFO] [RandomItem] Setting stuff up...");
console("[INFO] [RandomItem] Done!");
console("[INFO] [RandomItem] The configuration files can be found in \"" . $path . "\".");
file_put_contents($path . "version.txt",$currentVersion);
}
}
private function getItemName($id) {
switch ($id) {
case "1":
$name = "Stone";
break;
case "2":
$name = "Grass block";
break;
case "3":
$name = "Dirt";
break;
case "4":
$name = "Cobblestone";
break;
case "5":
$name = "Wood planks";
break;
case "5:0":
$name = "Oak wood planks";
break;
case "5:1":
$name = "Spruce wood planks";
break;
case "5:2":
$name = "Birch wood planks";
break;
case "5:3":
$name = "Jungle wood planks";
break;
case "6":
$name = "Sapling";
break;
case "6:0":
$name = "Oak sapling";
break;
case "6:1":
$name = "Spruce sapling";
break;
case "6:2":
$name = "Birch sapling";
break;
case "6:3":
$name = "Jungle sapling";
break;
case "7":
$name = "Bedrock";
break;
case "8":
$name = "Water";
break;
case "9":
$name = "Water";
break;
case "10":
$name = "Lava";
break;
case "11":
$name = "Lava";
break;
case "12":
$name = "Sand";
break;
case "13":
$name = "Gravel";
break;
case "14":
$name = "Gold ore";
break;
case "15":
$name = "Iron ore";
break;
case "16":
$name = "Coal ore";
break;
case "17":
$name = "Wood";
break;
case "17:0":
$name = "Oak wood";
break;
case "17:1":
$name = "Spruce wood";
break;
case "17:2":
$name = "Birch wood";
break;
case "17:3":
$name = "Jungle wood";
break;
case "18":
$name = "Leaves";
break;
case "19":
$name = "Sponge";
break;
case "20":
$name = "Glass";
break;
case "21":
$name = "Lapis lazuli ore";
break;
case "22":
$name = "Lapis lazuli block";
break;
case "24":
$name = "Sandstone";
break;
case "24:0":
$name = "Sandstone";
break;
case "24:1":
$name = "Chiseled sandstone";
break;
case "24:2":
$name = "Smooth sandstone";
break;
case "27":
$name = "Powered rail";
break;
case "30":
$name = "Cobweb";
break;
case "35":
$name = "Wool";
break;
case "35:0":
$name = "White wool";
break;
case "35:1":
$name = "Orange wool";
break;
case "35:2":
$name = "Magenta wool";
break;
case "35:3":
$name = "Light blue wool";
break;
case "35:4":
$name = "Yellow wool";
break;
case "35:5":
$name = "Lime wool";
break;
case "35:6":
$name = "Pink wool";
break;
case "35:7":
$name = "Dark gray wool";
break;
case "35:8":
$name = "Light gray wool";
break;
case "35:9":
$name = "Cyan wool";
break;
case "35:10":
$name = "Purple wool";
break;
case "35:11":
$name = "Blue wool";
break;
case "35:12":
$name = "Brown wool";
break;
case "35:13":
$name = "Green wool";
break;
case "35:14":
$name = "Red wool";
break;
case "35:15":
$name = "Black wool";
break;
case "37":
$name = "Yellow flower";
break;
case "38":
$name = "Blue flower";
break;
case "39":
$name = "Brown mushroom";
break;
case "40":
$name = "Red mushroom";
break;
case "41":
$name = "Gold block";
break;
case "42":
$name = "Iron block";
break;
case "44":
$name = "Slab";
break;
case "44:0":
$name = "Stone slab";
break;
case "44:1":
$name = "Sandstone slab";
break;
case "44:2":
$name = "Wood slab";
break;
case "44:3":
$name = "Cobblestone slab";
break;
case "44:4":
$name = "Brick slab";
break;
case "44:5":
$name = "Stone brick slab";
break;
case "44:6":
$name = "Quartz slab";
break;
case "45":
$name = "Bricks";
break;
case "46":
$name = "TNT";
break;
case "47":
$name = "Bookcase";
break;
case "48":
$name = "Mossy cobblestone";
break;
case "49":
$name = "Obsidian";
break;
case "50":
$name = "Torch";
break;
case "51":
$name = "Fire";
break;
case "53":
$name = "Wood stairs";
break;
case "54":
$name = "Chest";
break;
case "56":
$name = "Diamond ore";
break;
case "57":
$name = "Diamond block";
break;
case "58":
$name = "Crafting table";
break;
case "60":
$name = "Farmland";
break;
case "61":
$name = "Furnace";
break;
case "65":
$name = "Ladder";
break;
case "66":
$name = "Rails";
break;
case "67":
$name = "Cobblestone stairs";
break;
case "73":
$name = "Redstone ore";
break;
case "78":
$name = "Snow";
break;
case "79":
$name = "Ice";
break;
case "80":
$name = "Snow block";
break;
case "81":
$name = "Cactus";
break;
case "82":
$name = "Clay block";
break;
case "85":
$name = "Fence";
break;
case "86":
$name = "Pumpkin";
break;
case "87":
$name = "Netherrack";
break;
case "89":
$name = "Glowstone";
break;
case "91":
$name = "Jack-o-lantern";
break;
case "96":
$name = "Trapdoor";
break;
case "98":
$name = "Stone bricks";
break;
case "98:0":
$name = "Stone bricks";
break;
case "98:1":
$name = "Mossy stone bricks";
break;
case "98:2":
$name = "Cracked stone bricks";
break;
case "101":
$name = "Iron bars";
break;
case "102":
$name = "Glass pane";
break;
case "103":
$name = "Melon";
break;
case "107":
$name = "Fence gate";
break;
case "108":
$name = "Brick stairs";
break;
case "109":
$name = "Stone brick stairs";
break;
case "112":
$name = "Nether brick";
break;
case "114":
$name = "Nether brick stairs";
break;
case "128":
$name = "Sandstone stairs";
break;
case "134":
$name = "Spruce wood stairs";
break;
case "135":
$name = "Birch wood stairs";
break;
case "136":
$name = "Jungle wood stairs";
break;
case "139":
$name = "Cobblestone wall";
break;
case "139:0":
$name = "Cobblestone wall";
break;
case "139:1":
$name = "Mossy cobblestone wall";
break;
case "170":
$name = "Hay bale";
break;
case "173":
$name = "Coal block";
break;
case "246":
$name = "Glowing obsidian";
break;
case "256":
$name = "Iron shovel";
break;
case "257":
$name = "Iron pickaxe";
break;
case "258":
$name = "Iron axe";
break;
case "259":
$name = "Flint and steel";
break;
case "260":
$name = "Apple";
break;
case "261":
$name = "Bow";
break;
case "262":
$name = "Arrow";
break;
case "263":
$name = "Coal";
break;
case "263:0":
$name = "Coal";
break;
case "263:1":
$name = "Charcoal";
break;
case "264":
$name = "Diamond";
break;
case "265":
$name = "Iron ingot";
break;
case "266":
$name = "Gold ingot";
break;
case "267":
$name = "Iron sword";
break;
case "268":
$name = "Wooden sword";
break;
case "269":
$name = "Wooden shovel";
break;
case "270":
$name = "Wooden pickaxe";
break;
case "271":
$name = "Wooden axe";
break;
case "272":
$name = "Stone sword";
break;
case "273":
$name = "Stone shovel";
break;
case "274":
$name = "Stone pickaxe";
break;
case "275":
$name = "Stone axe";
break;
case "276":
$name = "Diamond sword";
break;
case "277":
$name = "Diamond shovel";
break;
case "278":
$name = "Diamond pickaxe";
break;
case "279":
$name = "Diamond axe";
break;
case "280":
$name = "Stick";
break;
case "281":
$name = "Bowl";
break;
case "282":
$name = "Mushroom stew";
break;
case "283":
$name = "Gold sword";
break;
case "284":
$name = "Gold shovel";
break;
case "285":
$name = "Gold pickaxe";
break;
case "286":
$name = "Gold axe";
break;
case "287":
$name = "String";
break;
case "288":
$name = "Feather";
break;
case "289":
$name = "Gunpowder";
break;
case "290":
$name = "Wooden hoe";
break;
case "291":
$name = "Stone hoe";
break;
case "292":
$name = "Iron hoe";
break;
case "293":
$name = "Diamond hoe";
break;
case "294":
$name = "Gold hoe";
break;
case "295":
$name = "Seeds";
break;
case "296":
$name = "Wheat";
break;
case "297":
$name = "Bread";
break;
case "318":
$name = "Flint";
break;
case "319":
$name = "Raw porkchop";
break;
case "320":
$name = "Cooked porkchop";
break;
case "321":
$name = "Painting";
break;
case "323":
$name = "Sign";
break;
case "324":
$name = "Door";
break;
case "325":
$name = "Bucket"; // CHECK IDS
break;
case "325:0":
$name = "Bucket";
break;
case "325:1":
$name = "Milk bucket";
break;
case "325:2":
$name = "Water bucket";
break;
case "325:3":
$name = "Lava bucket";
break;
case "328":
$name = "Minecart";
break;
case "331":
$name = "Redstone";
break;
case "332":
$name = "Snowball";
break;
case "334":
$name = "Leather";
break;
case "336":
$name = "Brick";
break;
case "337":
$name = "Clay";
break;
case "338":
$name = "Sugar cane";
break;
case "339":
$name = "Paper";
break;
case "340":
$name = "Book";
break;
case "341":
$name = "Slimeball";
break;
case "344":
$name = "Egg";
break;
case "345":
$name = "Compass";
break;
case "347":
$name = "Clock";
break;
case "348":
$name = "Glowstone dust";
break;
case "351":
$name = "Dye"; // Add more dyes when I'm not rushing.
break;
case "352":
$name = "Bone";
break;
case "353":
$name = "Sugar";
break;
case "354":
$name = "Cake";
break;
case "355":
$name = "Bed";
break;
case "359":
$name = "Shears";
break;
case "360":
$name = "Melon slice";
break;
case "361":
$name = "Pumpkin seeds";
break;
case "362":
$name = "Melon seeds";
break;
case "363":
$name = "Raw steak";
break;
case "364":
$name = "Cooked steak";
break;
case "365":
$name = "Raw chicken";
break;
case "366":
$name = "Cooked chicken";
break;
default:
$name = "ID: " . $id;
}
return $name;
}
console("[INFO] [RandomItem] RandomItem Enabled!");
}
function giveRI($cmd,$args,$issuer) {
$path = $this->api->plugin->configPath($this);
$mode = file_get_contents($path . "mode.txt");
$RIA = explode("*",file_get_contents($path . "items.txt"));
if($mode == "1") {
$RIN = rand(1,count($RIA)) - 1;
$randItemF = $RIA[$RIN];
$randItemU = explode("x",strtolower($randItemF));
$randItem = $randItemU[0];
if(isset($randItemU[1])) {
$amount = $randItemU[1];
} else {
$amount = "1";
}
$itemName = $this->getItemName($randItem);
if(isset($args[0])) {
foreach($args as $arg) {
if(strtolower($arg) == "console") {
return "[RandomItem] You can't give a random item to the console!";
} else {
$this->api->console->run("give " . $arg . " " . $randItem . " " . $amount);
$this->api->console->run("tell " . $arg . " Random item given! (" . $itemName . ")","RandomItem");
}
}
} else {
$this->api->console->run("give @all " . $randItem . " " . $amount);
$this->api->chat->broadcast("[RandomItem] Random item given! (" . $itemName . ")");
}
} else {
if(isset($args[0])) {
$players = $args;
} else {
$players = $this->api->player->online();
}
foreach($players as $player) {
$RIN = rand(1,count($RIA)) - 1;
$randItemF = $RIA[$RIN];
$randItemU = explode("x",strtolower($randItemF));
$randItem = $randItemU[0];
if(isset($randItemU[1])) {
$amount = $randItemU[1];
} else {
$amount = "1";
}
$itemName = $this->getItemName($randItem);
$this->api->console->run("give " . $player . " " . $randItem . " " . $amount);
}
if(isset($args[0])) {
foreach($args as $arg) {
if(strtolower($arg) == "console") {
return "[RandomItem] You can't give a random item to the console!";
} else {
$this->api->console->run("tell " . $arg . " Random item given! (" . $itemName . ")","RandomItem");
}
}
} else {
$this->api->chat->broadcast("[RandomItem] Random item given! (" . $itemName . ")");
}
}
}
public function giveTime() {
$this->api->console->run("gift");
}
public function __destruct(){}
}
?>