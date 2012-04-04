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
 * Filename: Magnify.php
 *
 * This is the main magnify.net API class
 *
 * @package Magnify
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 3/28/12
 * Id: $Id$
 */
require_once dirname(__FILE__) . '/Resource.php';

class Magnify
{

  const VERSION = '1.0-RC1';

  /**
   * @var string
   */
  private $channel = null;

  /**
   * @var string
   */
  private $apiKey = null;

  /**
   * @param string $channel
   * @param string $apiKey
   */
  public function __construct($channel, $apiKey)
  {
    $this->channel = $channel;
    $this->apiKey = $apiKey;
  }

  /**
   * @return string
   */
  public function getChannel()
  {
    return $this->channel;
  }

  /**
   * @return string
   */
  public function getApiKey()
  {
    return $this->apiKey;
  }

  public function getResource($resourceName)
  {
    return MagnifyResource::factory($resourceName, $this);
  }

  /**
   * Shortcut method
   *
   * @return UserResource
   */
  public function getUser()
  {
    return MagnifyResource::factory('user', $this);
  }

  /**
   * Shortcut method
   *
   * @return ContentResource
   */
  public function getContent()
  {
    return MagnifyResource::factory('content', $this);
  }

  /**
   * Shortcut method
   *
   * @return PlaylistResource
   */
  public function getPlaylist()
  {
    return MagnifyResource::factory('playlist', $this);
  }

  /**
   * Shortcut method
   *
   * @return ActivityResource
   */
  public function getActivity()
  {
    return MagnifyResource::factory('activity', $this);
  }

  /**
   * @param $name
   * @return mixed
   */
  function __get($name)
  {
    if (in_array($name, array('user', 'content', 'activity', 'playlist')))
    {
      return MagnifyResource::factory($name, $this);
    }

    return null;
  }

}
