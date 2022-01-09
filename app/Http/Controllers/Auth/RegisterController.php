<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\Stripe;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric', 'digits:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company' => ['required', 'string', 'max:255'],
            'terms' => ['required', 'accepted'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function verify(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric', 'digits:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company' => ['required', 'string', 'max:255'],
            'terms' => ['required', 'accepted'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);


        $intent = null;
        $customer = null;
        if (!$validation->fails()) {
            Stripe::setApiKey('sk_test_QRlgi66jX7UyI2ZABx7tX96s00mVjwISwc');
            $customer = Customer::create([
                "email" => "pending.registeration@genesis.io"
            ]);
            $intent = SetupIntent::create([
                'customer' => $customer->id
            ]);
        }

        return ["errors" => $validation->getMessageBag(), "failed" => $validation->fails(), "stripe" => ["key" => ($intent) ? $intent->client_secret : null, "customer" => ($customer) ? $customer->id : null]];

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $company = new Company();
        $company->name = $data['company'];
        $company->save();

        Stripe::setApiKey('sk_test_QRlgi66jX7UyI2ZABx7tX96s00mVjwISwc');
        Customer::update(
            $data['stripe_id'],
            [
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'phone' => $data['phone'],
                "email" => $data['email'],
                "invoice_settings" => [
                    "default_payment_method" => $data['payment_id']]
            ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'company' => $data['company'],
            'stripe_id' => $data['stripe_id'],
            'default_payment_method' => $data['payment_id'],
            'company_id' => $company->id,
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('customer');
        return $user;
    }

    public function register(Request $request)
    {
        // return "Hello";
        // $this->validator($request->all())->validate();

        $userData = $request->all();
        $userData['password'] = Hash::make($userData['password']);
        $user = User::create($userData);
        $user->assignRole('customer');
        event(new Registered($user));

        return new Response(["status" => "registered"], 201);

    }
}
