<?php
/**
 * Copyright (c) 2016-2021 Mastercard
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

namespace Mastercard\Mastercard\Gateway\Validator;

use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Validator\ResultInterface;

class AchValidator extends ResponseValidator
{
    /**
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject): ResultInterface
    {
        $response = SubjectReader::readResponse($validationSubject);

        $result = null;

        if (!isset($response['result'])) {
            $result = $this->createResult(false, [__("Response does not contain a body.")]);
        } elseif (isset($response['error']) && $response['error']['validationType'] === 'MISSING') {
            $result = $this->createResult(false, [$response['error']['field']]);
        } else {
            $allowStatus = [
                'APPROVED_PENDING_SETTLEMENT',
                'APPROVED'
            ];

            if (!in_array($response['response']['gatewayCode'], $allowStatus)) {
                $result = $this->createResult(false, [
                    __('Unexpected gateway code "%1"', $response['response']['gatewayCode'])
                ]);
            }
        }

        // If no specific result was set, proceed with parent validation
        return $result ?: parent::validate($validationSubject);
    }
}
