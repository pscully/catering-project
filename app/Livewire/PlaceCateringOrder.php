<?php

namespace App\Livewire;

use App\Forms\CateringCustomerDetailsSection;
use App\Forms\CateringMenuSection;
use App\Forms\CateringOrderDetailsSection;
use App\Models\CateringOrder;
use App\Models\CateringProduct;
use App\Models\User;
use App\Services\Catering\AttachCateringOrderProductsService;
use App\Services\Catering\CalculateCateringOrderDeliveryFeeService;
use App\Services\Catering\CalculateCateringOrderTotalService;
use App\Services\Catering\CalculateDeliveryDistanceMilesService;
use App\Services\Catering\CreateCateringOrderService;
use App\Services\User\FindOrCreateUserService;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PlaceCateringOrder extends Component implements HasForms
{
    use InteractsWithForms;

    public User $user;
    public $products = [];
    public ?array $orderProducts = [];
    public ?array $orderDetails = [];
    public ?array $customerDetails = [];

    public function mount(): void
    {
        $this->products = CateringProduct::all();

        if (Auth::check()) {
            $this->user = Auth::user();
            $this->form->fill([
                'customerDetails.first_name' => $this->user->first_name,
                'customerDetails.last_name' => $this->user->last_name,
                'customerDetails.email' => $this->user->email,
                'customerDetails.phone_number' => $this->user->phone_number,
            ]);
        } else {
            $this->form->fill();
        }

    }

    private function buildFormSchema(): array
    {
        $customerDetailsFields = new CateringCustomerDetailsSection();
        $orderDetailsFields = new CateringOrderDetailsSection();
        $productFields = new CateringMenuSection();

        return [
            Section::make('Customer Details')
                ->schema($customerDetailsFields->build())
                ->statePath('customerDetails')
                ->collapsible(),

            Section::make('Order Details')
                ->collapsible()
                ->statePath('orderDetails')
                ->schema($orderDetailsFields->build()),

            Section::make('Catering Menu')
                ->collapsible()
                ->statePath('orderProducts')
                ->schema($productFields->build($this->products)),
        ];
    }

    public function submitAndCreateOrder(FindOrCreateUserService $findOrCreateUserService, CreateCateringOrderService $createCateringOrderService, AttachCateringOrderProductsService $attacher, CalculateCateringOrderTotalService $calculator, CalculateCateringOrderDeliveryFeeService $deliveryFeeCalculator, CalculateDeliveryDistanceMilesService $milesCalculator ): null
    {
        $data = $this->form->getState();

        $isDelivery = $data['orderDetails']['delivery']?? false;

        try {
            $user = $this->findOrCreateUser($findOrCreateUserService, $data['customerDetails']);
            $order = $this->createCateringOrder($createCateringOrderService, $user, $data['orderDetails']);
            $order->attachProductsAndFinalize($data['orderProducts'], $isDelivery, $attacher, $calculator, $deliveryFeeCalculator, $milesCalculator);

            Auth::login($user);
            return $this->redirect('/dashboard');
        } catch (\Throwable $th) {
            // Handle the exception in a user-friendly way
            session()->flash('error', 'An error occurred while processing your order. Please try again.');
            return null;
        }
    }

    private function findOrCreateUser($service, $customerDetails): User
    {
        return $service->run($customerDetails);
    }

    private function createCateringOrder($service, $user, $orderDetails): CateringOrder
    {
        return $service->run($user, $orderDetails);
    }

    public function form(Form $form): Form
    {
        return $form->schema($this->buildFormSchema())->model(CateringOrder::class);
    }

    public function render()
    {
        return view('livewire.place-catering-order');
    }
}
