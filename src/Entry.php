<?php
/**
 * Copyright 2011 Collectors' Quest, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/**
 * Filename: Entry.php
 *
 * @package Magnify
 * @subpackage Entry
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 3/28/12
 * Id: $Id$
 */

abstract class MagnifyEntry
{

  private $_data = array();
  /**
   * @var MagnifyResource
   */
  protected $parser = null;

  public function __construct($entry, $parser)
  {
    $this->_data = null;
    $this->parser = $parser;
  }

  public function __set($name, $value)
  {
    $this->_data[$name] = $value;
  }

  public function __get($name)
  {
    return isset($this->_data[$name]) ? $this->_data[$name] : null;
  }

  public function __isset($name)
  {
    return isset($this->_data[$name]);
  }

}
