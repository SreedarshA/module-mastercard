<?php
/**
 * Copyright (c) 2016-2019 Mastercard
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Mastercard\Mastercard\Observer;

use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mastercard\Mastercard\Model\FeedFactory;

class PredispatchAdminActionControllerObserver implements ObserverInterface
{
    /**
     * @var FeedFactory
     */
    protected $feedFactory;

    /**
     * @var Session
     */
    protected $backendAuthSession;

    /**
     * @param FeedFactory $feedFactory
     * @param Session $backendAuthSession
     */
    public function __construct(
        FeedFactory $feedFactory,
        Session $backendAuthSession
    ) {
        $this->feedFactory = $feedFactory;
        $this->backendAuthSession = $backendAuthSession;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->backendAuthSession->isLoggedIn()) {
            $feedModel = $this->feedFactory->create();
            /** @var $feedModel \Mastercard\Mastercard\Model\Feed */
            $feedModel->checkUpdate();
        }
    }
}
