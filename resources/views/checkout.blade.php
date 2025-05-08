<form method="POST" action="{{ route('mpesa.confirm') }}">
    @csrf
    <input type="text" name="phone" placeholder="Enter Phone Number" required>
    <input type="number" name="amount" placeholder="Amount" required>
    <button type="submit">Pay with M-Pesa</button>
</form>
