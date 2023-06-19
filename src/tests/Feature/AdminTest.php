<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User\UserModel;

class AdminTest extends TestCase {

    /**
     * Test case to view Admin loan list
     * @return void
     */
    public function test_view_loan_list(): void {
        $this->get('admin/v1/viewLoanList?page=1')
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
                                        'user' =>
                                        [
                                            'name',
                                            'email'
                                        ],
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

    /**
     * Admin update loan status
     * @return void
     */
    public function test_update_loan_status(): void {

        $userData = [
            'name' => rand(),
            'email' => rand() . '@xyz.com'
        ];

        $user = UserModel::Create($userData);

        $Loanpayload = ['loan_amount' => '100.00', 'term' => 6];
        $header = ['user_id' => $user->id];

        $response = $this->post('user/v1/createLoan', $Loanpayload, $header)->decodeResponseJson();

        $response_array = json_decode($response->json, TRUE);
        //var_dump($response_array['data']['loan_id']);die;
        $loan_id = $response_array['data']['loan_id'];

        $payload = ['loan_id' => $loan_id, 'loan_status' => 'APPROVED'];

        $this->post('admin/v1/updateLoanStatus', $payload)
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
