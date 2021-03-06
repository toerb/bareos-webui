<?php

/**
 *
 * bareos-webui - Bareos Web-Frontend
 *
 * @link      https://github.com/bareos/bareos-webui for the canonical source repository
 * @copyright Copyright (c) 2013-2016 Bareos GmbH & Co. KG (http://www.bareos.org/)
 * @license   GNU Affero General Public License (http://www.gnu.org/licenses/)
 * @author    Frank Bergkemper
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Director\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

class DirectorController extends AbstractActionController
{
   protected $directorModel;

   public function indexAction()
   {
      $this->RequestURIPlugin()->setRequestURI();

      if(!$this->SessionTimeoutPlugin()->isValid()) {
         return $this->redirect()->toRoute('auth', array('action' => 'login'), array('query' => array('req' => $this->RequestURIPlugin()->getRequestURI())));
      }

      return new ViewModel(array(
         'directorOutput' => $this->getDirectorModel()->getDirectorStatus()
      ));
   }

   public function messagesAction()
   {
      $this->RequestURIPlugin()->setRequestURI();

      if(!$this->SessionTimeoutPlugin()->isValid()) {
         return $this->redirect()->toRoute('auth', array('action' => 'login'), array('query' => array('req' => $this->RequestURIPlugin()->getRequestURI())));
      }

      return new ViewModel();
   }

   public function scheduleAction()
   {
      $this->RequestURIPlugin()->setRequestURI();

      if(!$this->SessionTimeoutPlugin()->isValid()) {
         return $this->redirect()->toRoute('auth', array('action' => 'login'), array('query' => array('req' => $this->RequestURIPlugin()->getRequestURI())));
      }

      return new ViewModel(array(
         'directorOutput' => $this->getDirectorModel()->getDirectorSchedules()
      ));
   }

   public function schedulerstatusAction()
   {
      $this->RequestURIPlugin()->setRequestURI();

      if(!$this->SessionTimeoutPlugin()->isValid()) {
         return $this->redirect()->toRoute('auth', array('action' => 'login'), array('query' => array('req' => $this->RequestURIPlugin()->getRequestURI())));
      }

      return new ViewModel(array(
         'directorOutput' => $this->getDirectorModel()->getDirectorSchedulerStatus()
      ));
   }

   public function consoleAction()
   {
      $this->RequestURIPlugin()->setRequestURI();

      if(!$this->SessionTimeoutPlugin()->isValid()) {
         return $this->redirect()->toRoute('auth', array('action' => 'login'), array('query' => array('req' => $this->RequestURIPlugin()->getRequestURI())));
      }

      return new ViewModel(array(
      ));
   }

   public function getDataAction()
   {
      $this->RequestURIPlugin()->setRequestURI();

      if(!$this->SessionTimeoutPlugin()->isValid()) {
         return $this->redirect()->toRoute('auth', array('action' => 'login'), array('query' => array('req' => $this->RequestURIPlugin()->getRequestURI())));
      }

      $data = $this->params()->fromQuery('data');
      $limit = $this->params()->fromQuery('limit');
      $offset = $this->params()->fromQuery('offset');
      $reverse = $this->params()->fromQuery('reverse');
      $command = $this->params()->fromPost('command');

      if($data == "messages") {
         $result = $this->getDirectorModel()->getDirectorMessages($limit);
      }
      elseif($data == "cli") {
         $result = $this->getDirectorModel()->sendCommand($command);
      }
      else {
         $result = null;
      }

      $response = $this->getResponse();
      $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');

      if(isset($result)) {
         $response->setContent(JSON::encode($result));
      }

      return $response;
   }

   public function getDirectorModel()
   {
      if(!$this->directorModel) {
         $sm = $this->getServiceLocator();
         $this->directorModel = $sm->get('Director\Model\DirectorModel');
      }
      return $this->directorModel;
   }
}
