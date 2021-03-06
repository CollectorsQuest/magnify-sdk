<?php
/**
 * Copyright 2012 Collectors' Quest, Inc.
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
 * Filename: User.php
 *
 *
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 3/29/12
 * Id: $Id$
 */
require_once dirname(__FILE__) . '/../Resource.php';
require_once dirname(__FILE__) . '/../Feed/User.php';

class UserResource extends MagnifyResource
{

  public function getResourceGroup()
  {
    return 'user';
  }

  public function find($vq, $page = 1, $per_page = 10, $sort = 'recent')
  {
    $xml = $this->fetch('find', array(
      'page'       => $page,
      'per_page'   => $per_page,
      'vq'         => $vq,
      'sort'       => $sort
    ));

    return $this->parse($xml);// new UserFeed($this->dispatcher, $xml, $this);
  }
}
