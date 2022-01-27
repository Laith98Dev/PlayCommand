<?php

namespace Laith98Dev\PlayCommand;

/*  
 *  A plugin for PocketMine-MP.
 *  
 *	 _           _ _   _    ___   ___  _____             
 *	| |         (_) | | |  / _ \ / _ \|  __ \            
 *	| |     __ _ _| |_| |_| (_) | (_) | |  | | _____   __
 *	| |    / _` | | __| '_ \__, |> _ <| |  | |/ _ \ \ / /
 *	| |___| (_| | | |_| | | |/ /| (_) | |__| |  __/\ V / 
 *	|______\__,_|_|\__|_| |_/_/  \___/|_____/ \___| \_/  
 *	
 *  Copyright (C) 2021 Laith98Dev
 *  
 *	Youtube: Laith Youtuber
 *	Discord: Laith98Dev#0695
 *	Gihhub: Laith98Dev
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 	
 */

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat as TF;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;

use Laith98Dev\PlayCommand\Main;

class PlayCommand extends Command implements PluginOwned
{
    private Main $plugin;
	
	public function init(BuycraftPlugin $plugin) : void{
		$this->plugin = $plugin;
	}
	
	public function getOwningPlugin() : Plugin{
		return $this->plugin;
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
		if(!$sender instanceof Player){
			$sender->sendMessage("run the command in-game only!");
			return false;
		}
		
		if(!$this->testPermission($sender))
			return false;
		
		if(!isset($args[0])){
			$sender->sendMessage(TF::RED . "Usage: /" . $commandLabel . " <GameName>");
			return false;
		}
		
		$game = $args[0];
		$other = isset($args[1]) ?? "";
		$gamesList = $this->plugin->getGamesList();
		
		if(in_array($game, ["add", "set", "remove"])){
			
			if(!$sender->hasPermission("play.cmd.admin")){
				$sender->sendMessage(TF::RED . "You don't have permission to use this subcommand!");
				return false;
			}
			
			if(strtolower($game) == "add"){
				if(isset($args[1])){
					$name = $args[1];
					if(in_array($name, ["add", "set", "remove", "list"])){
						$sender->sendMessage(TF::RED . "you cannot use this names <add|set|remove|list>");
						return false;
					}
					if(isset($args[2])){
						$cmd = $args[2];
						for ($i = 3; $i <= count($args); $i++){
							if(isset($args[$i])){
								$cmd .= " " . $args[$i];
							}
						}
						if($this->plugin->addGame($name, $cmd)){
							$sender->sendMessage(TF::YELLOW . "the game with name '" . $name . "' has been added!");
							return true;
						} else {
							$sender->sendMessage(TF::RED . "the game with name '" . $name . "' already exist!");
							return false;
						}
					} else {
						$sender->sendMessage(TF::RED . "please type game command!");
						return false;
					}
				} else {
					$sender->sendMessage(TF::RED . "please type game name!");
					return false;
				}
			}
			
			if(strtolower($game) == "set"){
				if(isset($args[1])){
					$name = $args[1];
					if(!isset($gamesList[$name])){
						$sender->sendMessage(TF::RED . "the game with name '" . $name . "' not exist!");
						return false;
					}
					
					if(isset($args[2])){
						$cmd = $args[2];
						if($this->plugin->editGame($name, $cmd)){
							$sender->sendMessage(TF::YELLOW . "Changes saved!");
							return true;
						}
					} else {
						$sender->sendMessage(TF::RED . "please type new command!");
						return false;
					}
				}
			}
			
			if(strtolower($game) == "remove"){
				if(isset($args[1])){
					$name = $args[1];
					if(!isset($gamesList[$name])){
						$sender->sendMessage(TF::RED . "the game with name '" . $name . "' not exist!");
						return false;
					}
					
					if($this->plugin->removeGame($name)){
						$sender->sendMessage(TF::RED . "the game with name '" . $name . "' has been removed!");
						return true;
					}
					
				} else {
					$sender->sendMessage(TF::RED . "please type game name!");
					return false;
				}
			}
		}
		
		if(strtolower($game) == "list"){
			
			$games = "Games list: ";
			
			foreach ($gamesList as $gameName => $cc){
				$games .= "\n" . TF::GREEN . "- " . TF::YELLOW . $gameName;
			}
			
			$sender->sendMessage($games);
			
			return true;
		}
		
		if(!isset($gamesList[$game])){
			$sender->sendMessage(TF::RED . "the game with name '" . $game . "' not exist!");
			return false;
		}
		
		$cmd = $gamesList[$game];
		
		$this->plugin->getServer()->dispatchCommand($sender, $cmd . " " . $other);
		return true;
	}
}
