<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTO\AddressDTO;
use App\Services\LogisticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CalculatePriceRequest extends Request
{
    public function __construct(
        private readonly LogisticService $logisticService
    ) {
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            'addresses' => 'required|array|min:2',
            'addresses.*.country' => 'required|string',
            'addresses.*.zip' => 'required|string',
            'addresses.*.city' => 'required|string',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function validateResolved(): array
    {
        $data = $this->json()->all();
        $validator = Validator::make($data, $this->rules());

        $validator->after(function ($validator) use ($data) {
            foreach ($data['addresses'] as $address) {
                $addressDTO = new AddressDTO($address['country'], $address['zip'], $address['city']);

                if (!$this->logisticService->getCity($addressDTO)) {
                    $validator->errors()->add('addresses', 'The city ' . $addressDTO->getCity() . ' does not exist in the database.');
                }
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
