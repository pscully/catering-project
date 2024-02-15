<?php

namespace App\Livewire;

use App\Mail\CateringOrderPlacedInt;
use App\Models\User;
use App\Models\CateringOrder;
use App\Models\CateringProduct;
use App\Models\CateringOrderProduct;
use App\Models\Location;
use App\Rules\CateringOrderValidDateTime;
use Carbon\Carbon;
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
use Illuminate\Support\Str;
use Livewire\Component;
use App\Enums\CateringOrderTimes;
use Closure;
use Illuminate\Support\Facades\Mail;

class PlaceCateringOrder extends Component implements HasForms
{
    use InteractsWithForms;

    public $user;
    public $products = [];
    public ?array $orderProducts = [];
    public ?array $orderDetails = [];
    public ?array $customerDetails = [];

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

    private function customerDetailsSection(): Section
    {
        $customerDetailsFields = [];

        $customerDetailsFields[] = TextInput::make('first_name')
            ->label('First Name')
            ->required();

        $customerDetailsFields[] = TextInput::make('last_name')
            ->label('Last Name')
            ->required();

        $customerDetailsFields[] = TextInput::make('email')
            ->label('Email')
            ->required();

        $customerDetailsFields[] = TextInput::make('phone_number')
            ->label('Phone')
            ->required();

        return Section::make('Customer Details')
            ->schema($customerDetailsFields)
            ->statePath('customerDetails')
            ->collapsible();
    }

    private function orderDetailsSection(): Section
    {
        $orderDetailsFields = [];

        $orderDetailsFields[] = ToggleButtons::make('delivery')
            ->label('Delivery Required?')
            ->live()
            ->required()
            ->boolean()
            ->colors([
                false => 'warning',
                true => 'success',
            ])
            ->grouped();

        $orderDetailsFields[] = ToggleButtons::make('setup')
            ->label('Setup Required?')
            ->boolean()
            ->colors([
                '0' => 'warning',
                '1' => 'success',
            ])
            ->grouped()
            ->hidden(fn(Get $get): bool => !$get('delivery'));

        $orderDetailsFields[] = TextInput::make('delivery_address')
            ->label('Delivery Address')
            ->hidden(fn(Get $get): bool => !$get('delivery'));

        $orderDetailsFields[] = Select::make('closest_location')
            ->label('Closest Location')
            ->options(Location::pluck('name', 'id')->toArray())
            ->required();

        $orderDetailsFields[] = DatePicker::make('order_date')
            ->label('Order Date')
            ->format('m/d/Y')
            ->helperText('Pickup or Delivery Date')
            ->after('today')
            ->required();

        $orderDetailsFields[] = Select::make('order_time')
            ->label('Time')
            ->options(CateringOrderTimes::class)
            ->required()
            ->rules([fn(Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                $tomorrow = Carbon::tomorrow()->startOfDay(); // Get tomorrow's date at 00:00 hours
                $selectedDate = Carbon::parse($get('order_date'))->startOfDay(); // Parse the selected date and reset time to 00:00 hours

                if ($selectedDate->equalTo($tomorrow) && strtotime($value) < strtotime('12:00 PM')) {
                    $fail('When placing a next day order, pickup time must be at 12:00 PM or later.');
                }

            }]);

        $orderDetailsFields[] = TextInput::make('number_people')
            ->label('Number of People')
            ->numeric()
            ->step(1);

        $orderDetailsFields[] = TextInput::make('pickup_first_name')
            ->label('Optional Pickup First Name')
            ->helperText('Who will pickup the order?');

        $orderDetailsFields[] = TextArea::make('notes')
            ->label('Notes')
            ->helperText('Any special instructions?');

        return Section::make('Order Details')
            ->collapsible()
            ->statePath('orderDetails')
            ->schema($orderDetailsFields);

    }

    private function menuSection(): Section
    {
        $products = [];

        foreach ($this->products as $product) {
            $products[] = TextInput::make($product->sku)
                ->label($product->name)
                ->placeholder('0')
                ->numeric()
                ->step(1)
                ->live()
                ->default(0);
        }

        return Section::make('Menu')
            ->collapsible()
            ->statePath('orderProducts')
            ->schema($products);
    }

    public function form(Form $form): Form
    {
        if ($this->user) {
            return $form->schema([
                $this->orderDetailsSection(),
                $this->menuSection(),
            ])->model(CateringOrder::class);
        } else {
            return $form->schema([
                $this->customerDetailsSection(),
                $this->orderDetailsSection(),
                $this->menuSection(),
            ])->model(CateringOrder::class);
        }


    }

    public function placeCateringOrderSubmit(): null
    {
        $formData = $this->form->getState();

        $user = $this->createOrUpdateUser($formData['customerDetails'] ?? []);
        $order = $this->createOrder($user, $formData['orderDetails']);
        $this->createOrderProducts($order, $formData['orderProducts']);

        $order->total = $this->calculateGrandTotal($formData['orderProducts']);

        $order->save();

        Mail::to($user->email)->send(new CateringOrderPlacedInt($order));

        return $this->redirect('/dashboard');
    }

    private function createOrUpdateUser(array $customerDetails): User
    {
        if (auth()->user()) {
            return auth()->user();
        } else {
            return User::firstOrCreate([
                'email' => $customerDetails['email'],
            ], [
                'first_name' => $customerDetails['first_name'],
                'last_name' => $customerDetails['last_name'],
                'phone_number' => $customerDetails['phone_number'],
                'password' => bcrypt(Str::random(10)),
            ]);
        }

    }

    private function createOrder(User $user, array $orderDetails): \Illuminate\Database\Eloquent\Model|bool
    {
        $order = new CateringOrder([
            'delivery' => $orderDetails['delivery'],
            'setup' => $orderDetails['setup'] ?? false,
            'order_date' => $orderDetails['order_date'],
            'closest_location' => $orderDetails['closest_location'],
            'pickup_first_name' => $orderDetails['pickup_first_name'],
            'notes' => $orderDetails['notes'],
            'number_people' => $orderDetails['number_people'],
            'order_time' => $orderDetails['order_time']]);

        return $user->cateringOrders()->save($order);
    }

    private function createOrderProducts(CateringOrder $order, array $orderProducts): void
    {
        foreach ($orderProducts as $sku => $quantity) {
            if ($quantity > 0) {
                $product = CateringProduct::where('sku', $sku)->first();
                $orderProduct = new CateringOrderProduct([
                    'quantity' => $quantity,
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ]);

                $orderProduct->save();
            }
        }
    }

    private function calculateGrandTotal(array $orderProducts): float|int
    {
        $grandTotal = 0;

        foreach ($orderProducts as $sku => $quantity) {
            if ($quantity > 0) {
                $product = CateringProduct::where('sku', $sku)->first();
                $productPrice = optional($product)->price;
                $quantity = (int)$quantity;
                $productTotalPrice = $productPrice * $quantity;
                $grandTotal += $productTotalPrice;
            }
        }

        return $grandTotal;
    }

    public function render()
    {
        return view('livewire.place-catering-order');
    }
}
