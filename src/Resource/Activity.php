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
 * File: Activity.php
 *
 * @package Magnify
 * @subpackage Resource
 * @author zecho
 * @version $Id$
 *
 */
require_once dirname(__FILE__) . '/../Resource.php';

class ActivityResource extends MagnifyResource
{

  public function getResourceGroup()
  {
    return 'activity';
  }

  public function content($id, $page = 1, $perPage = 10)
  {
    $xml = $this->fetch('content', array(
      'id'       => $id,
      'page'     => $page,
      'per_page' => $perPage,
    ));

    return new ActivityFeed($this->dispatcher, $xml, $this->parser);
  }
}
