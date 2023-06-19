<?php

namespace Tests\Feature;

use App\Models\User\UserModel;
use Tests\TestCase;

class UserTest extends TestCase {

    public function test_create_loan(): void {
        $userData = [
            'name' => rand(),
            'email' => rand() . '@xyz.com'
        ];

        $user = UserModel::Create($userData);

        $payload = ['loan_amount' => '100.00', 'term' => 6];
        $header = ['user_id' => $user->id];

        $this->post('user/v1/createLoan', $payload, $header)
                ->assertStatus(200)
                ->assertJsonStructure(
                        [
                            'internalStatusCode',
                            'mainMessage',
                            'data' => ['loan_id']
                        ]
        );
    }

    public function test_view_loan_list(): void {
        $userData = [
            'name' => rand(),
            'email' => rand() . '@xyz.com'
        ];

        $user = UserModel::Create($userData);

        $payload = ['loan_amount' => '100.00', 'term' => 6];
        $header = ['user_id' => $user->id];

        $this->post('user/v1/createLoan', $payload, $header);

        $this->get('user/v1/viewLoanList?page=1', $header)
                ->assertStatus(200)
                ->assertJsonStructure(
                        [
                            'internalStatusCode',
                            'mainMessage',
                            'data' => [
                                'loanList' =>
                                [
                                    '*' => [
                                        'loan_id',
                                        'loan_amount',
                                        'term',
                                        'loan_status',
                                        'created_at',
                                        'updated_at',
                                        'installment' =>
                                        [
                                            '*' => [
                                                'id',
                                                'loan_id',
                                                'loan_amount',
                                                'term_date',
                                                'paid_amount',
                                                'paid_status',
                                                'paid_datetime',
                                                'created_at',
                                                'updated_at'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
        );
    }

    public function test_loan_prepyment(): void {

        $userData = [
            'name' => rand(),
            'email' => rand() . '@xyz.com'
        ];

        $user = UserModel::Create($userData);
        $amount = '100.00';

        $Loanpayload = ['loan_amount' => $amount, 'term' => 6];
        $header = ['user_id' => $user->id];

        $response = $this->post('user/v1/createLoan', $Loanpayload, $header)->decodeResponseJson();

        $response_array = json_decode($response->json, TRUE);
        //var_dump($response_array['data']['loan_id']);die;
        $loan_id = $response_array['data']['loan_id'];

        $payload = ['loan_id' => $loan_id, 'loan_status' => 'APPROVED'];

        $this->post('admin/v1/updateLoanStatus', $payload);

        $prepaymentPayload = ['loan_id' => $loan_id, 'amount_to_be_paid' => $amount];

        $this->post('user/v1/loanPrepayment', $prepaymentPayload)
                ->assertStatus(200)
                ->assertJsonStructure(
                        [
                            'internalStatusCode',
                            'mainMessage',
                            'data' => ['message']
                        ]
        );
    }

}
