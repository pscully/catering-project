<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\CateringOrder;
use App\Models\CateringProduct;
use App\Models\CateringOrderProduct;
use App\Models\Location;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\Section;
use Livewire\Component;
use App\Enums\CateringOrderTimes;

class PlaceCateringOrder extends Component implements HasForms
{
    use InteractsWithForms;

    public $user;
    public $products = [];
    public ?array $orderProducts = [];
    public $first_name = "";
    public $last_name = "";
    public $email = "";
    public $phone = "";

    public function mount()
    {
        $this->products = CateringProduct::all();

        $this->user = auth()->user();

        if (!$this->user) {
            $this->form->fill();
        } else {
            $this->form->fill($this->user->toArray());
        }


    }

    public function form(Form $form): Form
    {
        $customerDetails = [];

        $orderDetails = [];

        $products = [];

        $customerDetails[] = TextInput::make('first_name')
            ->label('First Name')
            ->required();

        $customerDetails[] = TextInput::make('last_name')
            ->label('Last Name')
            ->required();

        $customerDetails[] = TextInput::make('email')
            ->label('Email')
            ->required();

        $customerDetails[] = TextInput::make('phone_number')
            ->label('Phone')
            ->required();

        $orderDetails[] = ToggleButtons::make('delivery')
            ->label('Delivery Required?')
            ->live()
            ->boolean()
            ->colors([
                false => 'warning',
                true => 'success',
            ])
            ->grouped();

        $orderDetails[] = ToggleButtons::make('setup')
            ->label('Setup Required?')
            ->boolean()
            ->grouped()
            ->hidden(fn(Get $get): bool => !$get('delivery'));

        $orderDetails[] = TextInput::make('delivery_address')
            ->label('Delivery Address')
            ->hidden(fn(Get $get): bool => !$get('delivery'));

        $orderDetails[] = Select::make('closest_location')
            ->label('Closest Location')
            ->options(Location::pluck('name', 'id')->toArray())
            ->required();

        $orderDetails[] = DatePicker::make('order_date')
            ->label('Order Date')
            ->format('m/d/Y')
            ->helperText('Pickup or Delivery Date')
            ->required();

        $orderDetails[] = Select::make('time')
            ->label('Time')
            ->options(CateringOrderTimes::class)
            ->required();

        $orderDetails[] = TextInput::make('number_people')
            ->label('Number of People')
            ->numeric()
            ->step(1);

        $orderDetails[] = TextInput::make('pickup_first_name')
            ->label('Optional Pickup First Name')
            ->helperText('Who will pickup the order?');

        $orderDetails[] = TextArea::make('notes')
            ->label('Notes')
            ->helperText('Any special instructions?');


        foreach ($this->products as $product) {
            $products[] = TextInput::make($product->sku)
                ->label($product->name)
                ->placeholder('0')
                ->numeric()
                ->step(1)
                ->live()
                ->default(0);
        }

        $formSection[] = Section::make('Customer Details')
            ->schema($customerDetails)
            ->collapsible();

        $formSection[] = Section::make('Order Details')
            ->collapsible()
            ->schema($orderDetails);

        $formSection[] = Section::make('Menu')
            ->collapsible()
            ->schema($products);


        return $form->schema($formSection)->statePath('orderProducts')->model(CateringOrder::class);
    }

    public function placeCateringOrderSubmit(): \Illuminate\Http\RedirectResponse
    {
        $userData = $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $user = User::firstOrCreate([
            'email' => $userData['email'],
        ], $userData);

        $order = new CateringOrder();

        $deliveryAddress = new DeliveryAddress();

        foreach ($this->orderProducts as $sku => $quantity) {
            if ($quantity > 0) {
                $product = CateringProduct::where('sku', $sku)->first();
                $orderProduct = new CateringOrderProduct([
                    'quantity' => $quantity,
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ]);

                $user->cateringOrders()->save($order);
            }

        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.place-catering-order');
    }
}
