<?php
/*****************************************************************************
 *   This program is free software: you can redistribute it and/or modify    *
 *   it under the terms of the GNU General Public License as published by    *
 *   the Free Software Foundation, either version 3 of the License, or       *
 *   (at your option) any later version.                                     *
 *___________________________________________________________________________*
 *   This program is distributed in the hope that it will be useful,         *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *   GNU General Public License for more details.                            *
 *___________________________________________________________________________*
 *   You should have received a copy of the GNU General Public License       *
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *___________________________________________________________________________*
 *                             Created by  Qti3e                             *
 *        <http://Qti3e.Github.io>    LO-VE    <Qti3eQti3e@Gmail.com>        *
 *****************************************************************************/
class magic{private $configs;private $controllers;public function __construct(){$this->configs=[];$this->controllers=[];}public function config(Closure $closure){$this->configs[]=$closure;return $this;}public function controller($name,Closure $closure){$this->controllers[$name]=$closure;return $this;}public function run($defaultController=null){$count=count($this->configs);for($i=0;$i < $count;$i++){$this->callFunction($this->configs[$i]);}if($defaultController !==null){if(isset($this->controllers[$defaultController])){$this->callFunction($this->controllers[$defaultController]);}}}public function callFunction($function){$function=new ReflectionFunction($function);$parameters=$function->getParameters();$count=count($parameters)-1;for(;$count > -1;$count--){$parameter=$parameters[$count];$def=$parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : [];if(is_string($def)){$def=[$def];}$class=$parameter->getClass();if($class===null){$class=$parameter->getName();}else{$class=$class->name;}if(class_exists($class)){$class=new ReflectionClass($class);$obj=$class->newInstanceArgs($def);$parameters[$count]=$obj;}else{$parameters[$count]=false;}}$function->invokeArgs($parameters);}}spl_autoload_register(function($name){$file=__DIR__.'\\classes\\'.$name.'.php';if(file_exists($file)){include_once($file);return true;}return false;});function magic(){return new magic();}