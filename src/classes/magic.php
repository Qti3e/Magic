<?php
/*****************************************************************************
 *         In the name of God the Most Beneficent the Most Merciful          *
 *___________________________________________________________________________*
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

/**
 * Class magic
 */
class magic {
	/**
	 * @var array
	 */
	private $configs;
	/**
	 * @var array
	 */
	private $controllers;

	/**
	 * magic constructor.
	 */
	public function __construct() {
		$this->configs      = [];
		$this->controllers  = [];
	}

	/**
	 * @param Closure $closure
	 *
	 * @return $this
	 */
	public function config(Closure $closure){
		$this->configs[]    = $closure;
		return $this;
	}

	/**
	 * @param         $name
	 * @param Closure $closure
	 *
	 * @return $this
	 */
	public function controller($name,Closure $closure){
		$this->controllers[$name]   = $closure;
		return $this;
	}

	/**
	 * @param null $defaultController
	 *
	 * @return void
	 */
	public function run($defaultController = null){
		//Call config functions
		$count  = count($this->configs);
		for($i  = 0;$i < $count;$i++){
			$this->callFunction($this->configs[$i]);
		}
		//Call default controller
		if($defaultController !== null){
			if(isset($this->controllers[$defaultController])){
				$this->callFunction($this->controllers[$defaultController]);
			}
		}
	}

	/**
	 * @param $function
	 *
	 * @return void
	 */
	public function callFunction($function){
		$function   = new ReflectionFunction($function);
		$parameters = $function->getParameters();
		$count      = count($parameters)-1;
		for(;$count > -1;$count--){
			$parameter  = $parameters[$count];
			$def        = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : [];
			if(is_string($def)){
				$def    = [$def];
			}
			$class      = $parameter->getClass();
			if($class   === null){
				$class  = $parameter->getName();
			}else{
				$class  = $class->name;
			}
			if(class_exists($class)){
				$class  = new ReflectionClass($class);
				$obj    = $class->newInstanceArgs($def);
				$parameters[$count] = $obj;
			}else{
				$parameters[$count] = false;
			}
		}
		$function->invokeArgs($parameters);
	}
}