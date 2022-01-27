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

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {
	
	public function onEnable(): void{
		@mkdir($this->getDataFolder());
		
		$map = $this->getServer()->getCommandMap();
		
		$cmd = new PlayCommand("play", "Play Command", "play.cmd.admin", ["play"]);
		$cmd->init($this);
		
		$map->register($this->getName(), $cmd);
		
		if(!is_file($this->getDataFolder() . "config.yml")){
			(new Config($this->getDataFolder() . "config.yml", Config::YAML, ["games_list" => []]));
		}
	}
	
	public function getGamesList(){
		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		return $cfg->get("games_list", []);
	}
	
	public function addGame(string $name, string $command): bool{
		if(!is_file($this->getDataFolder() . "config.yml"))
			return false;
		
		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		
		if($command[0] == "/"){
			$command = substr($command, -(strlen($command) - 1), strlen($command) - 1);// //join => /join | /join => join 
		}
		
		// $command = str_replace("/", "", $command);
		$index = [];
		
		$list = $cfg->get("games_list", []);
		
		foreach ($list as $game => $cmd){
			$index[$game] = $cmd;
		}
		
		if(isset($index[$name]))
			return false;
		
		$index[$name] = $command;
		
		$cfg->set("games_list", $index);
		$cfg->save();
		return true;
	}
	
	public function removeGame(string $name): bool{
		if(!is_file($this->getDataFolder() . "config.yml"))
			return false;
		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$list = $cfg->get("games_list", []);
		
		$index = [];
		foreach ($list as $game => $cmd){
			$index[$game] = $cmd;
		}
		
		if(isset($index[$name])){
			unset($index[$name]);
			$cfg->set("games_list", $index);
			$cfg->save();
			return true;
		}
		
		return false;
	}
	
	public function editGame(string $name, string $newCommand): bool{
		if(!is_file($this->getDataFolder() . "config.yml"))
			return false;
		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		
		$list = $cfg->get("games_list", []);
		
		$index = [];
		foreach ($list as $game => $cmd){
			$index[$game] = $cmd;
		}
		
		if(isset($index[$name])){
			if($newCommand[0] == "/"){
				$newCommand = substr($newCommand, -(strlen($newCommand) - 1), strlen($newCommand) - 1);// //join => /join | /join => join 
			}
			// $newCommand = str_replace("/", "", $newCommand);
			$index[$name] = $newCommand;
			$cfg->set("games_list", $index);
			$cfg->save();
			return true;
		}
		
		return false;
	}
}
